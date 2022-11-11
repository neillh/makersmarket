/**
 * Wordpress Dependencies
 */

import { addFilter } from "@wordpress/hooks";

/***
 * Generating a single custom class which will be used in all gutenberg-pro extensions
 */

function addAttributes(settings, name) {
	// all extensions block
	const supportedBlocks = [
		"core/paragraph",
		"core/columns",
		"core/column",
		"core/heading",
		"core/spacer",
		"core/group",
	];

	if (!supportedBlocks.includes(name)) return settings;

	const extendedAttributes = {
		// for saving custom class
		gutenbergProCustomClass: {
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

addFilter(
	"blocks.registerBlockType",
	"gutenberg-pro/video-pro/customAttributes",
	addAttributes
);
