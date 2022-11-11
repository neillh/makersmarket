/**
 * Wordpress external dependencies
 */

import { Fragment, useEffect } from "@wordpress/element";
import { compose } from "@wordpress/compose";
import { InspectorControls } from "@wordpress/block-editor";
import { PanelBody } from "@wordpress/components";
import { __ } from "@wordpress/i18n";

/**
 * Lodash ( pre-installed with wordpress )
 */

import { get, toString, isEmpty } from "lodash";

/**
 * Custom imports and dependencies
 */

import ShapeDivider from "../../components/shape-divider";
import styleToCss from "style-object-to-css-string";
import classnames from "classnames";
import {
	excludeEmpties,
	removeBlockMeta,
	shapeDividersToCSS,
	testAndRemoveUnusedMeta,
} from "../../functions";

import { TEXT_DOMAIN } from "../../globals";
import { useRef } from "@wordpress/element";

// enhancer

import { createCustomEnhancer } from "../../utils/extension-enhancer";

const META_KEY = "gtp_group_styling";
const $ = jQuery;

/**
 * Main Extended Block Component
 */

function ExtendedGroup({
	BlockEdit,
	blockProps,
	isBlockSupported,
	addCustomClass,
	updateMeta,
}) {
	if (!isBlockSupported()) return BlockEdit;

	const { clientId, attributes, setAttributes, name } = blockProps;
	const handle = useRef();

	const shapeDivider = get(attributes, "gutenbergProGroupDivider"),
		customClass = get(attributes, "gutenbergProCustomClass");

	const { className } = attributes;

	useEffect(() => {
		const newStyles = getStyledCSS(false);
		updateMeta(newStyles); // updating meta value
		addCustomClass();
		testAndRemoveUnusedMeta(META_KEY);
	}, [attributes]);

	useEffect(() => {
		addCustomClass(true);

		return () => {
			testAndRemoveUnusedMeta(META_KEY);
			removeBlockMeta(clientId, META_KEY);
		};
	}, []);

	const getStyledCSS = () => {
		const pseudoCSS = shapeDividersToCSS(shapeDivider, customClass);

		let groupStyling = !isEmpty(pseudoCSS)
			? `.${customClass} { position:relative; } ${pseudoCSS}`
			: ``;

		return groupStyling;
	};

	return (
		<Fragment>
			{BlockEdit}
			<style
				dangerouslySetInnerHTML={{
					__html: getStyledCSS(),
				}}
			></style>
			<div ref={handle}></div>
			<InspectorControls>
				<PanelBody title={__("Shapes", TEXT_DOMAIN)}>
					<ShapeDivider
						attributes={attributes}
						setAttributes={setAttributes}
						attr="gutenbergProGroupDivider"
						value={shapeDivider}
						onChange={(newShape, attr) => {
							setAttributes({ [attr]: newShape });
						}}
					/>
				</PanelBody>
			</InspectorControls>
		</Fragment>
	);
}

const groupBlockEnhancer = createCustomEnhancer("core/group", META_KEY);

export default compose(groupBlockEnhancer)(ExtendedGroup);
