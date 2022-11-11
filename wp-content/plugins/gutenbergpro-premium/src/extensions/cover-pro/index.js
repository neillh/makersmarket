/**
 * Wordpress Dependencies
 */

import { addFilter } from "@wordpress/hooks";
import { createHigherOrderComponent } from "@wordpress/compose";
import { get } from "lodash";

/**
 * Custom imports
 */

import { ShapeDividerSchema } from "../../components/shape-divider";
import ExtendedCover from "./extended-cover";
import { parseBool } from "../../functions";

const withAdvancedCoverControls = createHigherOrderComponent((BlockEdit) => {
	return (props) => (
		<ExtendedCover blockProps={props} BlockEdit={<BlockEdit {...props} />} />
	);
}, "withAdvancedControls");

function addAttributes(settings, name) {
	const supportedBlocks = ["core/cover"];

	// skipping other blocks except columns
	if (!supportedBlocks.includes(name)) return settings;

	const extendedAttributes = {
		gutenbergProCoverDivider: ShapeDividerSchema,
		gutenbergProCoverBgEffect: {
			type: "string",
			default: "none",
		},
	};

	settings.attributes = Object.assign(
		{},
		settings.attributes,
		extendedAttributes
	);

	return settings;
}

const status = get(window, "gtpGlobals.extensions.gtp_cover_styling.status");
const isExtensionEnabled = parseBool(status);

if (isExtensionEnabled) {
	addFilter(
		"editor.BlockEdit",
		"cover-pro/advanced-cover-controls",
		withAdvancedCoverControls
	);
	addFilter(
		"blocks.registerBlockType",
		"cover-pro/custom-attributes",
		addAttributes
	);
}
