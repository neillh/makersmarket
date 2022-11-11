/**
 * Wordpress Dependencies
 */

import { addFilter } from "@wordpress/hooks";
import { createHigherOrderComponent } from "@wordpress/compose";
import { get } from "lodash";

/**
 * Custom imports
 */

import ExtendedHeading from "./extended-heading";
import { backgroundSchema } from "../../components/background";
import { dimensionSchema } from "../../components/dimension-picker/dimensions";
import { textShadowSchema } from "../../components/text-shadow-picker";
import { parseBool } from "../../functions";

/**
 * Creating an extended heading block with our extended column component
 */

const withAdvancedHeadingControls = createHigherOrderComponent((BlockEdit) => {
	return (props) => (
		<ExtendedHeading blockProps={props} BlockEdit={<BlockEdit {...props} />} />
	);
}, "withAdvancedControls");

function addAttributes(settings, name) {
	const supportedBlocks = ["core/heading"];

	// skipping other blocks except columns
	if (!supportedBlocks.includes(name)) return settings;

	const extendedAttributes = {
		gutenbergProHeadingInnerPadding: dimensionSchema,
		gutenbergProHeadingBackground: backgroundSchema,
		gutenbergProHeadingTextShadow: textShadowSchema,
		gutenbergProHeadingFont: {
			type: "string",
			default: "",
		},
		gutenbergProHeadingLetterspacing: {
			type: "number",
			default: 0
		}
	};

	settings.attributes = Object.assign(
		{},
		settings.attributes,
		extendedAttributes
	);

	return settings;
}

// applying the main filter to extend the heading block
const status = get(window, "gtpGlobals.extensions.gtp_heading_styling.status");
const isExtensionEnabled = parseBool(status);

if (isExtensionEnabled) {
	addFilter(
		"editor.BlockEdit",
		"gutenberg-pro/heading-pro/advanced-heading-controls",
		withAdvancedHeadingControls
	);

	addFilter(
		"blocks.registerBlockType",
		"gutenberg-pro/heading-pro/customAttributes",
		addAttributes
	);
}
