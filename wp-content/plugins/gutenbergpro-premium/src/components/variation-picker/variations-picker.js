import { PanelBody, Button, PanelRow, Tip } from "@wordpress/components";
import { compose } from "@wordpress/compose";
import { withSelect, withDispatch } from "@wordpress/data";
import { __ } from "@wordpress/i18n";
import { TEXT_DOMAIN } from "../../globals";
import {
	isEqual,
	isEmpty,
	get,
	clone,
	filter,
	map,
	each,
	keys,
	pick,
} from "lodash";

/**
 * Custom Variations Picker
 */

function VariationsPicker({
	variations,
	applyBlockVariation,
	isActiveVariation,
}) {
	return (
		!isEmpty(variations) && (
			<PanelBody className="cp-columnspro-variations-picker">
				<PanelRow>
					<span>{__("Column Layout", TEXT_DOMAIN)}</span>
				</PanelRow>
				<div className="cp-columnspro-variations">
					{map(variations, (variation, idx) => {
						const { icon, description, title } = variation;
						const buttonProperties = isActiveVariation(variation)
							? { isPrimary: true }
							: { isDefault: true };

						return (
							<div className="cp-columnspro-variation">
								<Button
									showTooltip
									isLarge
									onClick={() => applyBlockVariation(variation)}
									label={description}
									{...buttonProperties}
								>
									{icon}
								</Button>
								<span>{title}</span>
							</div>
						);
					})}
				</div>

				<PanelRow>
					<Tip>{__("Change the layout of your columns.", TEXT_DOMAIN)}</Tip>
				</PanelRow>
			</PanelBody>
		)
	);
}

export default compose([
	withSelect((select, { name, clientId }) => {
		const { getBlockVariations } = select("core/blocks");
		const { getBlock } = select("core/block-editor");

		const currentBlock = getBlock(clientId),
			currentInnerBlocks = get(currentBlock, "innerBlocks"),
			currentBlockVariations = getBlockVariations(name);

		/**
		 * Will filter variations based on the current columns layout
		 * @return {array} variations
		 */

		const getSupportedVariations = () => {
			// cloning due to mutation issues
			let registeredVariations = clone(currentBlockVariations);

			// checking each variation weather it's safe to apply for current columns layout
			registeredVariations = filter(registeredVariations, (variation) => {
				const innerBlocksToApply = get(variation, "innerBlocks") ?? [];
				const isVariationSafeToApply = isEqual(
					currentInnerBlocks.length,
					innerBlocksToApply.length
				);

				return isVariationSafeToApply;
			});

			return registeredVariations;
		};

		return {
			variations: getSupportedVariations(name),
			currentInnerBlocks,
			currentBlock,

			/**
			 * Checking for the supported block i.e => core/columns
			 * @return {boolean} block supported
			 */

			isBlockSupported() {
				return isEqual(name, "core/columns");
			},

			/**
			 * Check if the given variation is currently active on the block
			 * @param {object} variation
			 * @return {boolean} active
			 */

			isActiveVariation(variation) {
				const innerBlocksToUpdate = get(variation, "innerBlocks");
				let isActive = false;

				// looping and checking each child/inner block
				each(currentInnerBlocks, (childBlock, idx) => {
					const attributeToUpdate = get(innerBlocksToUpdate, `[${idx}][1]`) ?? {
						width: "50%",
					};
					const attributesToTest = keys(attributeToUpdate);
					const childAttributes = get(childBlock, "attributes");
					const childAttributesToTest = pick(childAttributes, attributesToTest);

					isActive = isEqual(attributeToUpdate, childAttributesToTest);
				});

				return isActive;
			},
		};
	}),
	withDispatch((dispatch, { currentBlock, currentInnerBlocks }) => {
		const { updateBlockAttributes } = dispatch("core/block-editor");

		return {
			/**
			 * Will apply the selected variation on the
			 * block that corresponds to the clientId
			 * @param {object} variation
			 */

			applyBlockVariation(variation) {
				const innerBlocksToUpdate = get(variation, "innerBlocks");

				// looping and updating each child/inner block
				each(currentInnerBlocks, (childBlock, idx) => {
					const attributeToUpdate = get(innerBlocksToUpdate, `[${idx}][1]`) ?? {
						width: "50%",
					};
					const childClientId = get(childBlock, "clientId");

					// updating the child attribute
					updateBlockAttributes(childClientId, attributeToUpdate);
				});
			},
		};
	}),
])(VariationsPicker);
