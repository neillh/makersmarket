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

/**
 * Lodash ( pre-installed with wordpress )
 */

import { get, isEmpty, toString } from "lodash";

/**
 * Custom imports and dependencies
 */

import BackgroundPicker, {
	convertBackground,
} from "../../components/background";
import Dimensions, {
	convertDimensions,
} from "../../components/dimension-picker/dimensions";

import TextShadowPicker, {
	convertTextShadow,
} from "../../components/text-shadow-picker";

import FontFamilyPicker from "../../components/font-picker";

import styleToCss from "style-object-to-css-string";
import classnames from "classnames";
import {
	removeColumnsClass,
	excludeEmpties,
	removeBlockMeta,
	testAndRemoveUnusedMeta,
} from "../../functions";
import { TEXT_DOMAIN } from "../../globals";
import { createCustomEnhancer } from "../../utils/extension-enhancer";

const META_KEY = "gtp_heading_styling";

/**
 * Main Extended Block Component
 */

function ExtendedHeading({
	BlockEdit,
	blockProps,
	isBlockSupported,
	addCustomClass,
	updateMeta,
}) {
	if (!isBlockSupported()) return BlockEdit;

	const { clientId, attributes, setAttributes, name } = blockProps;

	// custom attributes
	const headingBackground = get(attributes, "gutenbergProHeadingBackground"),
		headingInnerPadding = get(attributes, "gutenbergProHeadingInnerPadding"),
		headingFont = get(attributes, "gutenbergProHeadingFont");

	const customClass = get(attributes, "gutenbergProCustomClass");

	useEffect(() => {
		const newStyles = getStyledCSS();
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
		const convertedBackground = convertBackground(headingBackground);

		let generatedStyles = excludeEmpties({
			...convertedBackground,
			letterSpacing: toString(
				attributes.gutenbergProHeadingLetterspacing
			).concat("px") + " !important",
			padding: convertDimensions(headingInnerPadding),
			textShadow: convertTextShadow(attributes.gutenbergProHeadingTextShadow),
			fontFamily: headingFont + " !important",
		});

		const styleCSS = styleToCss(generatedStyles);
		let generatedFontStyleImport = "";

		if (!isEmpty(headingFont) && headingFont !== "Default") {
			generatedFontStyleImport = `@import url('https://fonts.googleapis.com/css2?family=${headingFont}');`;
		}

		const headingStyling = `${generatedFontStyleImport} .${customClass} { ${styleCSS} }`;

		return headingStyling;
	};

	return (
		<Fragment>
			{BlockEdit}
			<style
				dangerouslySetInnerHTML={{
					__html: getStyledCSS(),
				}}
			></style>
			<InspectorControls>
				<PanelBody
					title={__("Heading Block Pro Settings", TEXT_DOMAIN)}
					initialOpen={false}
				>
					<FontFamilyPicker
						value={headingFont}
						onChange={(newFont) =>
							setAttributes({ gutenbergProHeadingFont: newFont })
						}
					/>
					<RangeControl
						className="headingpro-option"
						label={__("Letter Spacing", TEXT_DOMAIN)}
						value={attributes.gutenbergProHeadingLetterspacing}
						min={0}
						max={100}
						allowReset
						resetFallbackValue={0}
						onChange={(newLetterSpacing) =>
							setAttributes({
								gutenbergProHeadingLetterspacing: newLetterSpacing,
							})
						}
					/>
					<div className="headingpro-option">
						<span>{__("Inner Padding", TEXT_DOMAIN)}</span>
						<Dimensions
							viewPort={"Desktop"}
							value={headingInnerPadding}
							onChange={(dim, unit) => {
								setAttributes({
									gutenbergProHeadingInnerPadding: {
										...headingInnerPadding,
										value: dim,
										unit: unit,
									},
								});
							}}
							onImportant={(imp) => {
								setAttributes({
									gutenbergProHeadingInnerPadding: {
										...headingInnerPadding,
										important: imp,
									},
								});
							}}
						/>
					</div>

					<PanelBody title={__("Text Shadow", TEXT_DOMAIN)}>
						<div className="headingpro-option">
							<TextShadowPicker
								value={attributes.gutenbergProHeadingTextShadow}
								onChange={(newTextShadow) =>
									setAttributes({
										gutenbergProHeadingTextShadow: newTextShadow,
									})
								}
							/>
						</div>
					</PanelBody>
					<PanelBody title={__("Background", TEXT_DOMAIN)}>
						<div className="headingpro-option">
							<BackgroundPicker
								value={headingBackground}
								onChange={(gutenbergProHeadingBackground) => {
									setAttributes({ gutenbergProHeadingBackground });
								}}
							/>
						</div>
					</PanelBody>
				</PanelBody>
			</InspectorControls>
		</Fragment>
	);
}

const headingBlockEnhancer = createCustomEnhancer("core/heading", META_KEY);

export default compose(headingBlockEnhancer)(ExtendedHeading);
