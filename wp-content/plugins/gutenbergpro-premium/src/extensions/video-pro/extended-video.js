import "./style.scss";

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
	__experimentalRadio as Radio,
	__experimentalRadioGroup as RadioGroup,
	__experimentalUnitControl as UnitControl,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";

/**
 * Lodash ( pre-installed with wordpress )
 */

import { isEqual, get, isEmpty, attempt, isError, filter } from "lodash";

/**
 * Custom imports and dependencies
 */

import styleToCss from "style-object-to-css-string";
import classnames from "classnames";
import {
	removeCustomClass,
	excludeEmpties,
	removeBlockMeta,
	testAndRemoveUnusedMeta,
} from "../../functions";
import { TEXT_DOMAIN } from "../../globals";
import { createCustomEnhancer } from "../../utils/extension-enhancer";

const META_KEY = "gtp_video_styling";

/**
 * Main Extended Block Component
 */

function ExtendedVideo({
	BlockEdit,
	blockProps,
	isBlockSupported,
	addCustomClass,
	updateMeta,
}) {
	if (!isBlockSupported()) return BlockEdit;

	const { clientId, attributes, setAttributes, name } = blockProps;

	// custom attributes
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
		let generatedStyles = excludeEmpties({
			// height: attributes.gutenbergProParagraphHeight.toString().concat('px')
		});

		const styledCSS = styleToCss(generatedStyles);
		const videoStyling = `.${customClass} { ${styledCSS} }`;

		return !isEmpty(styledCSS) ? videoStyling : "";
	};

	return (
		<Fragment>
			{attributes.gutenbergProVideoFrame ? (
				<div className={attributes.gutenbergProVideoFrameChoose}>
					{BlockEdit}
				</div>
			) : (
				BlockEdit
			)}
			<style
				dangerouslySetInnerHTML={{
					__html: getStyledCSS(),
				}}
			></style>
			<InspectorControls>
				<PanelBody
					title={__("Video Block Pro Settings", TEXT_DOMAIN)}
					initialOpen={false}
				>
					<PanelRow className="videopro-option">
						<span>{__("Display Frame", TEXT_DOMAIN)}</span>
						<FormToggle
							checked={attributes.gutenbergProVideoFrame}
							onChange={() =>
								setAttributes({
									gutenbergProVideoFrame: !attributes.gutenbergProVideoFrame,
								})
							}
						/>
					</PanelRow>
					{attributes.gutenbergProVideoFrame ? (
						<RadioGroup
							label="Choose Frame"
							onChange={(newFrame) =>
								setAttributes({ gutenbergProVideoFrameChoose: newFrame })
							}
							checked={attributes.gutenbergProVideoFrameChoose}
						>
							<Radio value="gtp_video_frame_iphone">
								<span class="dashicons dashicons-smartphone"></span>
							</Radio>
							<Radio value="gtp_video_frame_laptop">
								<span class="dashicons dashicons-laptop"></span>
							</Radio>
						</RadioGroup>
					) : (
						""
					)}
				</PanelBody>
			</InspectorControls>
		</Fragment>
	);
}

const videoBlockEnhancer = createCustomEnhancer("core/video", META_KEY);

export default compose(videoBlockEnhancer)(ExtendedVideo);
