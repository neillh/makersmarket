/**
 * Wordpress Dependencies
 */

import { SelectControl } from "@wordpress/components";
import { map } from "lodash";
import { __ } from "@wordpress/i18n";

/**
 * Custom Imports
 **/

import fonts from "./fonts.json";
import { TEXT_DOMAIN } from "../../globals";

/**
 * Custom Select box used to pick fonts
 */

function FontFamilyPicker({ value, onChange }) {
	const fontOptions = map(fonts, (fontName, fontID) => {
		return {
			value: fontID,
			label: fontName,
		};
	});

	return (
		<SelectControl
			label={__("Font Family", TEXT_DOMAIN)}
			options={fontOptions}
			value={value}
			onChange={onChange}
		/>
	);
}

export default FontFamilyPicker;
