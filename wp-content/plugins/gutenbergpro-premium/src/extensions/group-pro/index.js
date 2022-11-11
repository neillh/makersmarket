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
import ExtendedGroup from "./extended-group";
import { parseBool } from "../../functions";

/**
 * Creating an extended columns block with our extended column component
 */

const withAdvancedGroupControls = createHigherOrderComponent((BlockEdit) => {
	return (props) => (
		<ExtendedGroup blockProps={props} BlockEdit={<BlockEdit {...props} />} />
	);
}, "withAdvancedControls");

/**
 * Extending block Attributes
 *
 * @prefix gutenbergpro each attribute with the plugin prefix due to conflicts
 * @param { object } setting block settings
 * @param { string } name block name
 * @return extendedAttributes
 */

function addAttributes(settings, name) {
	const supportedBlocks = ["core/group"];

	// skipping other blocks except columns
	if (!supportedBlocks.includes(name)) return settings;

	const extendedAttributes = {
		gutenbergProGroupDivider: ShapeDividerSchema,
	};

	settings.attributes = Object.assign(
		{},
		settings.attributes,
		extendedAttributes
	);

	return settings;
}

const status = get(window, "gtpGlobals.extensions.gtp_group_styling.status");
const isExtensionEnabled = parseBool(status);

if (isExtensionEnabled) {
	// applying the main filter to extend the columns block
	addFilter(
		"editor.BlockEdit",
		"group-pro/advanced-group-controls",
		withAdvancedGroupControls
	);
	addFilter(
		"blocks.registerBlockType",
		"group-pro/custom-attributes",
		addAttributes
	);
}
