/**
 * Wordpress Dependencies
 */

import { addFilter } from "@wordpress/hooks";
import { createHigherOrderComponent } from "@wordpress/compose";
import { get } from "lodash";

/**
 * Custom imports
 */

import ExtendedSpacer from "./extended-spacer";
import { backgroundSchema } from "../../components/background";
import { parseBool } from "../../functions";
import { ShapeDividerSchema } from "../../components/shape-divider/index";

/**
 * Creating an extended spacer block with our extended column component
 */

const withAdvancedSpacerControls = createHigherOrderComponent((BlockEdit) => {
	return (props) => (
		<ExtendedSpacer blockProps={props} BlockEdit={<BlockEdit {...props} />} />
	);
}, "withAdvancedControls");

function addAttributes(settings, name) {
	const supportedBlocks = ["core/spacer"];

	// skipping other blocks except spacer
	if (!supportedBlocks.includes(name)) return settings;

	const extendedAttributes = {
		gutenbergProSpacerFullWidth: {
			type: "boolean",
			default: false,
		},
		gutenbergProSpacerBackground: backgroundSchema,
		gutenbergProShapeDivider: ShapeDividerSchema,
	};

	settings.attributes = Object.assign(
		{},
		settings.attributes,
		extendedAttributes
	);

	return settings;
}

// applying the main filter to extend the spacer block

const status = get(window, "gtpGlobals.extensions.gtp_spacer_styling.status");
const isExtensionEnabled = parseBool(status);

if (isExtensionEnabled) {
	addFilter(
		"editor.BlockEdit",
		"gutenberg-pro/spacer-pro/advanced-spacer-controls",
		withAdvancedSpacerControls
	);

	addFilter(
		"blocks.registerBlockType",
		"gutenberg-pro/spacer-pro/customAttributes",
		addAttributes
	);
}
