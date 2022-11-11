/**
 * Wordpress external dependencies
 */

import { Fragment, useEffect } from "@wordpress/element";
import { compose } from "@wordpress/compose";
import { withDispatch, withSelect } from "@wordpress/data";
import { InspectorControls } from "@wordpress/block-editor";
import {
	PanelBody,
	RangeControl,
	FormToggle,
	PanelRow,
	__experimentalUnitControl as UnitControl,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import { addFilter } from "@wordpress/hooks";

/**
 * Lodash ( pre-installed with wordpress )
 */

import { isEqual, get, isEmpty, attempt, isError, has } from "lodash";

/**
 * Custom imports and dependencies
 */

import VariationsPicker from "../../components/variation-picker/variations-picker";
import BackgroundPicker, {
	convertBackground,
} from "../../components/background";
import Dimensions, {
	convertDimensions,
} from "../../components/dimension-picker/dimensions";
import styleToCss from "style-object-to-css-string";
import classnames from "classnames";
import {
	removeCustomClass,
	excludeEmpties,
	removeBlockMeta,
	testAndRemoveUnusedMeta,
	shapeDividersToCSS,
} from "../../functions";
import { TEXT_DOMAIN } from "../../globals";
import ShapeDivider from "../../components/shape-divider";

const META_KEY = "gtp_columnspro_styling";

/**
 * Main Extended Block Component
 */

function ExtendedColumn({
	BlockEdit,
	blockProps,
	isBlockSupported,
	addCustomClass,
	updateMeta,
	isShapesSupported,
}) {
	// skipping blocks other than core/columns
	if (!isBlockSupported()) return <BlockEdit {...blockProps} />;

	const {
		clientId,
		attributes,
		setAttributes,
		name,
	} = blockProps;

	// custom attributes
	const background = get(attributes, "columnsproBackground"),
		columnGap = get(attributes, "columnsproColumnGap"),
		fullScreen = get(attributes, "columnsproFullScreen"),
		innerPadding = get(attributes, "columnsproInnerPadding"),
		removeBottomMargin = get(attributes, "columnsproRemoveBottomMargin"),
		minHeight = get(attributes, "columnsProMinHeight"),
		reverseColumnsOnMobile = get(
			attributes,
			"columnsProReverseColumnsOnMobile"
		),
		shapeDivider = get(attributes, "columnsProShapeDivider");

	const customClass = get(attributes, "gutenbergProCustomClass");

	const updateStyles = () => {
		addFilter('gutenbergPro.columns.css', 'css', (css) => {
			css[clientId] = getStyledCSS();
			return css;
		});
	}

	const getStyledCSS = () => {
		const convertedBackground = convertBackground(background);

		let generatedStyles = excludeEmpties({
			...convertedBackground,
			gap: columnGap + "%",
			height: fullScreen ? "100vh" : "",
			marginBottom: removeBottomMargin ? "0px !important" : "",
			minHeight: !fullScreen ? minHeight : "",
		});

		let generatedColumnStyling = excludeEmpties({
			padding: convertDimensions(innerPadding),
			marginLeft: "0 !important",
		});
		let columnsMobileStyling = "";

		if (reverseColumnsOnMobile) {
			columnsMobileStyling = `
				@media ( max-width: 600px ) {
					.${customClass} {
    					flex-direction: column-reverse;
					}
				}
			`;
		}

		const columnsStyling = `.${customClass} { position:relative; ${styleToCss(
			generatedStyles
		)} } ${columnsMobileStyling}`;

		const columnStyling = `.${customClass} .wp-block-column { ${styleToCss(
			generatedColumnStyling
		)} }`;

		const styles = [columnsStyling, columnStyling];
		let pseudoCSS = shapeDividersToCSS(shapeDivider, customClass);

		if (name === "core/column") {
			delete styles[1];
		}

		let newStyles = styles.join(" ");

		if (isShapesSupported) {
			newStyles += pseudoCSS;
		}

		return newStyles;
	};

	useEffect(() => {
		updateStyles();
		return () => removeStyles();
	}, []);

	useEffect(updateStyles, [attributes]);

	useEffect(() => {
		addCustomClass(true);
	}, []);

	return (
		<Fragment>
			<BlockEdit {...blockProps} />
			<style
				dangerouslySetInnerHTML={{
					__html: getStyledCSS(),
				}}
			></style>
			<InspectorControls>
				<VariationsPicker {...blockProps} clientId={clientId} />
				<PanelBody
					title={__("Columns Block Pro Settings", TEXT_DOMAIN)}
					initialOpen={false}
				>
					{name === "core/columns" && (
						<Fragment>
							<PanelRow className="columnspro-option">
								<span>{__("Full Screen", TEXT_DOMAIN)}</span>
								<FormToggle
									help
									checked={fullScreen}
									onChange={() =>
										setAttributes({ columnsproFullScreen: !fullScreen })
									}
								/>
							</PanelRow>
							{!fullScreen && (
								<PanelRow className="columnspro-option">
									<span style={{ flex: 5 }}>
										{__("Min Height", TEXT_DOMAIN)}
									</span>
									<UnitControl
										style={{ flex: 5 }}
										value={minHeight}
										onChange={(newMinHeight) =>
											setAttributes({ columnsProMinHeight: newMinHeight })
										}
									/>
								</PanelRow>
							)}
							<PanelRow className="columnspro-option">
								<span>{__("Remove Bottom Margin", TEXT_DOMAIN)}</span>
								<FormToggle
									help
									checked={removeBottomMargin}
									onChange={() =>
										setAttributes({
											columnsproRemoveBottomMargin: !removeBottomMargin,
										})
									}
								/>
							</PanelRow>
							<PanelRow className="columnspro-option">
								<span>{__("Reverse Columns On Mobile", TEXT_DOMAIN)}</span>
								<FormToggle
									help
									checked={reverseColumnsOnMobile}
									onChange={() =>
										setAttributes({
											columnsProReverseColumnsOnMobile: !reverseColumnsOnMobile,
										})
									}
								/>
							</PanelRow>

							<RangeControl
								className="columnspro-option"
								label={__("Column Gap", TEXT_DOMAIN)}
								value={columnGap}
								min={0}
								max={10}
								allowReset
								resetFallbackValue={0}
								onChange={(columnsproColumnGap) =>
									setAttributes({ columnsproColumnGap })
								}
								help={__("Add gap between columns.", TEXT_DOMAIN)}
							/>
							<div className="columnspro-option">
								<span>{__("Inner Padding", TEXT_DOMAIN)}</span>
								<Dimensions
									viewPort={"Desktop"}
									value={innerPadding}
									onChange={(dim, unit) => {
										setAttributes({
											columnsproInnerPadding: {
												...innerPadding,
												value: dim,
												unit: unit,
											},
										});
									}}
									onImportant={(imp) => {
										setAttributes({
											columnsproInnerPadding: {
												...innerPadding,
												important: imp,
											},
										});
									}}
								/>
							</div>
						</Fragment>
					)}
					<div className="columnspro-option">
						<BackgroundPicker
							value={background}
							onChange={(columnsproBackground) => {
								setAttributes({ columnsproBackground });
							}}
						/>
					</div>
				</PanelBody>
				{isShapesSupported && (
					<PanelBody title={__("Shapes", TEXT_DOMAIN)}>
						<ShapeDivider
							attributes={attributes}
							setAttributes={setAttributes}
							attr={"columnsProShapeDivider"}
							value={shapeDivider}
							onChange={(newShape, attr) => setAttributes({ [attr]: newShape })}
						/>
					</PanelBody>
				)}
			</InspectorControls>
		</Fragment>
	);
}

export default compose([
	withSelect((select, { blockProps: { name, attributes, clientId } }) => {
		const { getEditedPostAttribute } = select("core/editor");
		const { getPreviousBlockClientId, getBlock, getBlocks } =
			select("core/block-editor");

		// current post meta value
		const currentPostMeta = getEditedPostAttribute("meta");

		/**
		 * Check if the current block is duplicated from the block above
		 */

		const isBlockDuplicated = () => {
			const aboveBlockClientId = getPreviousBlockClientId(clientId);
			const aboveBlock = getBlock(aboveBlockClientId);

			if (isEmpty(aboveBlock)) return false;

			// if both generated classNames match this means
			// the current block is duplicated from the above block
			// therefore we need to re-generate a new className for current block

			const aboveBlockGeneratedClassName = get(
				aboveBlock,
				"attributes.gutenbergProCustomClass"
			);

			const currentBlockGeneratedClassName = get(
				attributes,
				"gutenbergProCustomClass"
			);

			return isEqual(
				aboveBlockGeneratedClassName,
				currentBlockGeneratedClassName
			);
		};

		return {
			currentPostMeta,
			currentScopedMeta: get(currentPostMeta, META_KEY),
			isDuplicated: isBlockDuplicated(),

			/***
			 * Will check if the current block supports shapes
			 *
			 * @return { boolean } support
			 */

			get isShapesSupported() {
				// currently only adding shapes to the columns main block
				return isEqual(name, "core/columns");
			},

			/**
			 * Checking for the supported block i.e => core/columns
			 * @return {boolean} block supported
			 */

			isBlockSupported() {
				return ["core/columns", "core/column"].includes(name);
			},
		};
	}),
	withDispatch(
		(
			dispatch,
			{
				blockProps: { clientId, setAttributes, attributes },
				currentScopedMeta,
				isDuplicated,
			}
		) => {
			const { editPost } = dispatch("core/editor");

			const { className = "", gutenbergProCustomClass = "" } = attributes;
			const uniqueId =
				typeof clientId === "string" ? clientId.substring(0, 5) : "";

			return {
				/**
				 * Will test and add custom class on columns block
				 * @param {boolean} onLoad initial render
				 */

				addCustomClass(onLoad = false) {

					const randomClass = "gp-gutenbergpro-" + uniqueId;

					if ( attributes?.className?.indexOf?.("gp-gutenbergpro-") !== -1 && typeof attributes.className !== 'undefined' && !isDuplicated  ) {
						return;
					}

					if (isEmpty(gutenbergProCustomClass) || isDuplicated || onLoad) {
						setAttributes({
							gutenbergProCustomClass: randomClass,
						});
					}


					// updating the class
					setAttributes({
						className: classnames(
							removeCustomClass(className),
							randomClass
						),
					});


				},

				/**
				 * Update the meta field to render styles on frontend
				 * @param {string} newGeneratedStyle updated style
				 */

				updateMeta(newGeneratedStyle) {
					// checking if the meta value can be parsed into json
					const canBeParsed = !isError(attempt(JSON.parse, currentScopedMeta));

					//! breaking if it's un-parsable
					if (!canBeParsed) return;

					const parsedStyles = JSON.parse(currentScopedMeta);

					parsedStyles[clientId] = newGeneratedStyle;

					editPost({ meta: { [META_KEY]: JSON.stringify(parsedStyles) } });
				},
			};
		}
	),
])(ExtendedColumn);
