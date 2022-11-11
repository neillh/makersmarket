/**
 * Wordpress Dependencies
 */

import { addFilter } from "@wordpress/hooks";
import { createHigherOrderComponent } from "@wordpress/compose";
import { get } from "lodash";

/**
 * Custom imports
 */

import ExtendedParagraph from "./extended-paragraph";
import { backgroundSchema } from "../../components/background";
import { dimensionSchema } from "../../components/dimension-picker/dimensions";
import { parseBool } from "../../functions";

/**
 * Creating an extended paragraph block with our extended column component
 */

const withAdvancedParagraphControls = createHigherOrderComponent(
	(BlockEdit) => {
		return (props) => (
			<ExtendedParagraph
				blockProps={props}
				BlockEdit={<BlockEdit {...props} />}
			/>
		);
	},
	"withAdvancedControls"
);

function addAttributes(settings, name) {
	const supportedBlocks = ["core/paragraph"];

	// skipping other blocks except columns
	if (!supportedBlocks.includes(name)) return settings;

	const extendedAttributes = {
		gutenbergProParagraphFullWidth: {
			type: "boolean",
			default: false,
		},

		gutenbergProParagraphHeight: {
			type: "number",
			default: "",
		},

		gutenbergProInnerPadding: dimensionSchema,
		// for columns background
		gutenbergProBackground: backgroundSchema,
		gutenbergProFontFamily: {
			type: "string",
			default: "",
		},
	};

	settings.attributes = Object.assign(
		{},
		settings.attributes,
		extendedAttributes
	);

	return settings;
}

// applying the main filter to extend the columns block
const status = get(
	window,
	"gtpGlobals.extensions.gtp_paragraph_styling.status"
);
const isExtensionEnabled = parseBool(status);

if (isExtensionEnabled) {
	addFilter(
		"editor.BlockEdit",
		"gutenberg-pro/pargraph-pro/advanced-paragraph-controls",
		withAdvancedParagraphControls
	);

	addFilter(
		"blocks.registerBlockType",
		"gutenberg-pro/pargraph-pro/customAttributes",
		addAttributes
	);
}
