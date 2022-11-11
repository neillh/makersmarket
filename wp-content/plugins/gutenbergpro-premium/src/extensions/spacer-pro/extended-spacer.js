/**
 * Wordpress external dependencies
 */

import { Fragment, useEffect, useRef } from "@wordpress/element";
import { compose } from "@wordpress/compose";
import { InspectorControls } from "@wordpress/block-editor";
import { PanelBody, FormToggle, PanelRow } from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import ShapeDivider from "../../components/shape-divider/index";

/**
 * Lodash ( pre-installed with wordpress )
 */

import { get, isEmpty, has } from "lodash";

/**
 * Custom imports and dependencies
 */

import BackgroundPicker, {
	convertBackground,
} from "../../components/background";

import styleToCss from "style-object-to-css-string";
import {
	dividers,
	excludeEmpties,
	removeBlockMeta,
	shapeDividersToCSS,
	testAndRemoveUnusedMeta,
} from "../../functions";
import { TEXT_DOMAIN } from "../../globals";

// enhancer
import { createCustomEnhancer } from "../../utils/extension-enhancer";

const META_KEY = "gtp_spacer_styling";
const $ = jQuery;

/**
 * Main Extended Block Component
 */

function ExtendedSpacer({
	BlockEdit,
	blockProps,
	isBlockSupported,
	addCustomClass,
	updateMeta,
}) {
	if (!isBlockSupported()) return BlockEdit;

	const { clientId, attributes, setAttributes, name } = blockProps;

	// custom attributes
	const background = get(attributes, "gutenbergProSpacerBackground");
	const customClass = get(attributes, "gutenbergProCustomClass");
	const isFullWidth = get(attributes, "gutenbergProSpacerFullWidth") ?? false;
	const shape = get(attributes, "gutenbergProShapeDivider");

	// dom references
	const handle = useRef();

	useEffect(() => {
		if (isFullWidth) {
			$(handle.current)
				.parents(".coverpro-block-admin")
				.addClass("coverpro-image_full_screen");
		} else {
			$(handle.current)
				.parents(".coverpro-block-admin")
				.removeClass("coverpro-image_full_screen");
		}
	}, [isFullWidth]);

	const getStyledCSS = (isEditor = true) => {
		const convertedBackground = convertBackground(background);
		const convertedBackgroundImage =
			get(convertedBackground, "backgroundImage") ?? "";

		const bgImageRes = !isEmpty(convertedBackgroundImage)
			? {
					backgroundImage: convertedBackgroundImage + " !important",
			  }
			: {};

		let generatedStyles = excludeEmpties({
			...convertedBackground,
			...bgImageRes,
		});

		if (isFullWidth) {
			generatedStyles = Object.assign(
				generatedStyles,
				!isEditor
					? {
							marginLeft: "calc(-50vw + 50%)",
							marginRight: "calc(-50vw + 50%)",
							maxWidth: "100vw",
							width: "100vw",
					  }
					: {}
			);
		}

		const className = isEditor
			? customClass + ` .components-resizable-box__container`
			: customClass;
		const styledCSS = styleToCss(generatedStyles);
		const pseudoCSS = shapeDividersToCSS(shape, className);

		let spacerStyling = !isEmpty(styledCSS)
			? `.${className} { position:relative; ${styledCSS} }`
			: ``;

		spacerStyling += pseudoCSS;

		return spacerStyling;
	};

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

	useEffect(() => {
		addCustomClass(true);
	}, []);

	const generatedCSS = getStyledCSS();

	return (
		<Fragment>
			{BlockEdit}
			<div className="gtp-handle" ref={handle}></div>
			{!isEmpty(generatedCSS) && (
				<style
					dangerouslySetInnerHTML={{
						__html: generatedCSS,
					}}
				></style>
			)}
			<InspectorControls>
				<PanelBody
					title={__("Spacer Block Pro Settings", TEXT_DOMAIN)}
					initialOpen={false}
				>
					<PanelRow className="gutenbergpro-option">
						<span>{__("Full Width", TEXT_DOMAIN)}</span>
						<FormToggle
							checked={attributes.gutenbergProSpacerFullWidth}
							onChange={() =>
								setAttributes({
									gutenbergProSpacerFullWidth: !attributes.gutenbergProSpacerFullWidth,
								})
							}
						/>
					</PanelRow>

					<div className="gutenbergpro-option">
						<BackgroundPicker
							value={background}
							onChange={(gutenbergProSpacerBackground) => {
								setAttributes({ gutenbergProSpacerBackground });
							}}
						/>
					</div>
				</PanelBody>
				<PanelBody title={__("Shapes")}>
					<ShapeDivider
						attributes={attributes}
						setAttributes={setAttributes}
						attr={"gutenbergProShapeDivider"}
						value={shape}
						onChange={(newShape, attr) => {
							setAttributes({ [attr]: newShape });
						}}
					/>
				</PanelBody>
			</InspectorControls>
		</Fragment>
	);
}

const SpacerBlockCustomEnhancer = createCustomEnhancer("core/spacer", META_KEY);

export default compose(SpacerBlockCustomEnhancer)(ExtendedSpacer);
