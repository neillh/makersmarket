/**
 * Wordpress Dependencies
 */

import { addFilter } from "@wordpress/hooks";
import { createHigherOrderComponent } from "@wordpress/compose";
import { get } from "lodash";

/**
 * Custom imports
 */

import ExtendedVideo from "./extended-video";
import { parseBool } from "../../functions";

/***
 * Adding Custom attributes
 */

function addAttributes(settings, name) {
	const supportedBlocks = ["core/video"];

	// skipping other blocks except columns
	if (!supportedBlocks.includes(name)) return settings;

	const extendedAttributes = {
		gutenbergProVideoFrame: {
			type: "boolean",
			default: false,
		},

		gutenbergProVideoFrameChoose: {
			type: "string",
			default: "gtp_video_frame_iphone",
		},
	};

	settings.attributes = Object.assign(
		{},
		settings.attributes,
		extendedAttributes
	);

	return settings;
}

/**
 * Creating an extended video block with our extended column component
 */

const withAdvancedVideoControls = createHigherOrderComponent((BlockEdit) => {
	return (props) => (
		<ExtendedVideo blockProps={props} BlockEdit={<BlockEdit {...props} />} />
	);
}, "withAdvancedControls");

const status = get(window, "gtpGlobals.extensions.gtp_video_styling.status");
const isExtensionEnabled = parseBool(status);

if (isExtensionEnabled) {
	// applying the main filter to extend the video block
	addFilter(
		"editor.BlockEdit",
		"gutenberg-pro/video-pro/advanced-video-controls",
		withAdvancedVideoControls
	);

	addFilter(
		"blocks.registerBlockType",
		"gutenberg-pro/video-pro/customAttributes",
		addAttributes
	);
}
