/**
 * Wordpress external dependencies
 */

import { Fragment, useEffect } from "@wordpress/element";
import { compose } from "@wordpress/compose";
import { InspectorControls } from "@wordpress/block-editor";
import { PanelBody, RadioControl } from "@wordpress/components";
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

const META_KEY = "gtp_cover_styling";
const $ = jQuery;

/**
 * Main Extended Block Component
 */

function ExtendedCover({
	BlockEdit,
	blockProps,
	isBlockSupported,
	addCustomClass,
	updateMeta,
}) {
	if (!isBlockSupported()) return BlockEdit;

	const { clientId, attributes, setAttributes, name } = blockProps;
	const handle = useRef();

	const shapeDivider = get(attributes, "gutenbergProCoverDivider"),
		customClass = get(attributes, "gutenbergProCustomClass"),
		bgEffect = get(attributes, "gutenbergProCoverBgEffect");

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
		const pseudoCSS = shapeDividersToCSS(
			shapeDivider,
			`${customClass} .wp-block-cover__inner-container`
		);

		const styledCSS = excludeEmpties({
			overflow: "hidden",
		});

		const innerImgCSS = excludeEmpties({
			animation:
			bgEffect === "zoom-pan"
				? "coverpro-kenburns 35s ease infinite alternate"
				: "",
		})

		let coverStyling =
			!isEmpty(pseudoCSS) || !isEmpty(styledCSS)
				? `.${customClass} { position:relative; ${styleToCss(
						styledCSS
				  )} }  .${customClass} .wp-block-cover__inner-container { position: initial !important; } ${pseudoCSS}`
				: ``;

		if (!isEmpty(innerImgCSS)) {
			coverStyling += `.${customClass} img { ${styleToCss(innerImgCSS)} } `
		}

		return coverStyling;
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
				<PanelBody title={__("Cover Block Pro", TEXT_DOMAIN)}>
					<RadioControl
						label={__("Background Effect", TEXT_DOMAIN)}
						selected={bgEffect}
						options={[
							{ label: "None", value: "none" },
							{ label: "Zoom and Pan", value: "zoom-pan" },
						]}
						help={__("Adds background effects on images.", TEXT_DOMAIN)}
						onChange={(newEffect) =>
							setAttributes({ gutenbergProCoverBgEffect: newEffect })
						}
					/>
				</PanelBody>
				<PanelBody title={__("Shapes", TEXT_DOMAIN)}>
					<ShapeDivider
						attributes={attributes}
						setAttributes={setAttributes}
						attr="gutenbergProCoverDivider"
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

const coverBlockEnhancer = createCustomEnhancer("core/cover", META_KEY);

export default compose(coverBlockEnhancer)(ExtendedCover);
