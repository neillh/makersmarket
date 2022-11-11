/**
 * Wordpress Dependencies
 */

import { addFilter } from "@wordpress/hooks";
import { createHigherOrderComponent } from "@wordpress/compose";
import { get } from "lodash";

/**
 * Custom imports
 */

import ExtendedColumn from "./extended-column";
import { backgroundSchema } from "../../components/background";
import { dimensionSchema } from "../../components/dimension-picker/dimensions";
import { parseBool } from "../../functions";
import { ShapeDividerSchema } from "../../components/shape-divider";

/**
 * Creating an extended columns block with our extended column component
 */

const withAdvancedColumnControls = createHigherOrderComponent((BlockEdit) => {
	return (props) => <ExtendedColumn blockProps={props} BlockEdit={BlockEdit} />;
}, "withAdvancedControls");

/**
 * Extending block Attributes
 *
 * @prefix columnspro each attribute with the plugin prefix due to conflicts
 * @param { object } setting block settings
 * @param { string } name block name
 * @return extendedAttributes
 */

function addAttributes(settings, name) {
	const supportedBlocks = ["core/columns", "core/column"];

	// skipping other blocks except columns
	if (!supportedBlocks.includes(name)) return settings;

	const extendedAttributes = {
		// saving column gap
		columnsproColumnGap: {
			type: "number",
			default: 3,
		},
		// for making columns full screen
		columnsproFullScreen: {
			type: "boolean",
			default: false,
		},
		// for saving inner columns padding
		columnsproInnerPadding: dimensionSchema,
		// for columns background
		columnsproBackground: backgroundSchema,
		// for removing bottom margin
		columnsproRemoveBottomMargin: {
			type: "boolean",
			default: false,
		},
		// for adding minimum height control
		columnsProMinHeight: {
			type: "string",
			default: "0px",
		},
		//for reversing columns in mobile
		columnsProReverseColumnsOnMobile: {
			type: "boolean",
			default: false,
		},

		columnsProShapeDivider: ShapeDividerSchema,
	};

	settings.attributes = Object.assign(
		{},
		settings.attributes,
		extendedAttributes
	);

	return settings;
}

const status = get(
	window,
	"gtpGlobals.extensions.gtp_columnspro_styling.status"
);
const isExtensionEnabled = parseBool(status);

if (isExtensionEnabled) {
	// applying the main filter to extend the columns block
	addFilter(
		"editor.BlockEdit",
		"columns-pro/advanced-column-controls",
		withAdvancedColumnControls
	);

	addFilter(
		"blocks.registerBlockType",
		"columns-pro/custom-attributes",
		addAttributes
	);
}
