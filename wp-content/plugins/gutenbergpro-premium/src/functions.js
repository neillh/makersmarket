import {
	get,
	isEqual,
	isEmpty,
	split,
	filter,
	startsWith,
	omitBy,
	each,
	omit,
	clone,
	toString,
	set,
	has,
} from "lodash";
import { select, dispatch } from "@wordpress/data";
import styleToCss from "style-object-to-css-string";

const { getEditedPostAttribute, getBlock } = select("core/editor");
const { editPost } = dispatch("core/editor");
import { parse as gradientParser } from "gradient-parser";

import {
	convertMultiPositionShapeDivider,
	convertShapeDivider,
} from "./components/shape-divider/index";

export function buildActiveContext(
	initialValue,
	matchValue,
	returnValue,
	defaultValue
) {
	if (isEqual(initialValue, matchValue)) {
		return returnValue;
	}

	return defaultValue;
}

export function rgba(color) {
	const rgb = get(color, "rgb");

	if (isEmpty(rgb)) return;

	const { r, g, b, a } = rgb;

	return `rgba(${r}, ${g}, ${b}, ${a})`;
}

/**
 * Will remove the custom columns class that starts with columnspro-
 * @param {string} className
 */

export function removeCustomClass(className) {
	if (typeof className !== "string") return className;

	let classList = split(className, " ");

	// removing custom class
	classList = filter(
		classList,
		(className) => !startsWith(className, "gp-gutenbergpro-")
	);

	return classList.join(" ");
}

/**
 * Will test if the custom class exists
 * @param {string} className
 */

export function hasCustomClass(className, testClass) {
	if (typeof className !== "string") return className;

	let classList = split(className, " ");

	let result = false;

	classList.forEach((c) => {
		if (c === testClass) {
			result = true;
		}
	});

	return result;
}

export function excludeEmpties(object) {
	return omitBy(object, isEmpty);
}

export function removeBlockMeta(id, META_KEY) {
	const metaData = getEditedPostAttribute("meta"),
		currentStyling = get(metaData, META_KEY);

	let parsedCurrentStyling = JSON.parse(
		isEmpty(currentStyling) ? "{}" : currentStyling
	);

	if (isEmpty(currentStyling)) return;

	const newParsedStyling = omit(parsedCurrentStyling, [id]);

	editPost({ meta: { [META_KEY]: JSON.stringify(newParsedStyling) } });
}

export function testAndRemoveUnusedMeta(key) {
	const meta = getEditedPostAttribute("meta");
	const value = get(meta, key);

	if (isEmpty(value)) return;

	const cssObject = JSON.parse(value);

	each(cssObject, (_, clientId) => {
		const block = getBlock(clientId);

		if (isEmpty(block)) {
			removeBlockMeta(clientId, key);
		}
	});
}

export const convertHexToRGBA = (hexCode, opacity) => {
	if (startsWith(hexCode, "rgb") || isEmpty(hexCode)) return hexCode;

	let hex = hexCode.replace("#", "");

	if (hex.length === 3) {
		hex = `${hex[0]}${hex[0]}${hex[1]}${hex[1]}${hex[2]}${hex[2]}`;
	}

	const r = parseInt(hex.substring(0, 2), 16);
	const g = parseInt(hex.substring(2, 4), 16);
	const b = parseInt(hex.substring(4, 6), 16);

	return `rgba(${r},${g},${b},${opacity})`;
};

/**
 * Will add the given opacity to all rgba colors used in the gradient
 * @param {string} gradient
 * @param {number} opacity
 */

export function applyOpacityToGradient(gradient, opacity = 1) {
	if (typeof gradient !== "string") return gradient;

	const [parsedGradient = {}] = gradientParser(gradient);
	let newGradient = clone(parsedGradient);
	let newOpacity = toString(opacity);

	// applying opacity to each gradient color
	each(get(newGradient, "colorStops"), (gradientColor, index) => {
		const type = get(gradientColor, "type");
		const value = get(gradientColor, "value");

		if (isEqual(type, "rgba")) {
			// this means the opacity is present so we need to update the existing opacity
			set(value, 3, newOpacity);
		}

		if (isEqual(type, "rgb")) {
			// this means the opacity is not present currently so we need to add it manually
			// and also update the type to rgba
			value.push(newOpacity);
			newGradient["colorStops"][index]["type"] = "rgba";
		}
	});

	// serializing gradient back to it's original form
	const serializedGradient = serializeGradient(newGradient);

	return serializedGradient;
}

export const basicColorScheme = [
	{
		color: "#f78da7",
		name: "Pale Pink",
	},
	{
		name: "Vivid red",
		color: "#cf2e2e",
	},
	{
		name: "Luminous vivid orange",
		color: "#ff6900",
	},
	{
		color: "#fcb900",
		name: "Luminous vivid amber",
	},
	{
		color: "#7bdcb5",
		name: "Light green cyan",
	},
	{
		color: "#00d084",
		name: "Vivid green cyan",
	},
	{
		color: "#8ed1fc",
		name: "Pale cyan blue",
	},
	{
		color: "#0693e3",
		name: "Vivid cyan blue",
	},
	{
		color: "#9b51e0",
		name: "Vivid purple",
	},
	{
		color: "#eee",
		name: "Very light gray",
	},
	{
		color: "#abb8c3",
		name: "Cyan bluish gray",
	},
	{
		color: "#313131",
		name: "Very dark gray",
	},
];

// for serializing gradient

export function serializeGradientColor({ type, value }) {
	if (type === "literal") {
		return value;
	}
	if (type === "hex") {
		return `#${value}`;
	}
	return `${type}(${value.join(",")})`;
}

export function serializeGradientPosition({ type, value }) {
	return `${value}${type}`;
}

export function serializeGradientColorStop({ type, value, length }) {
	return `${serializeGradientColor({
		type,
		value,
	})} ${serializeGradientPosition(length)}`;
}

export function serializeGradientOrientation(orientation) {
	if (!orientation || orientation.type !== "angular") {
		return;
	}
	return `${orientation.value}deg`;
}

export function serializeGradient({ type, orientation, colorStops }) {
	const serializedOrientation = serializeGradientOrientation(orientation);
	const serializedColorStops = colorStops
		.sort((colorStop1, colorStop2) => {
			return (
				lodash.get(colorStop1, ["length", "value"], 0) -
				lodash.get(colorStop2, ["length", "value"], 0)
			);
		})
		.map(serializeGradientColorStop);
	return `${type}(${lodash
		.compact([serializedOrientation, ...serializedColorStops])
		.join(",")})`;
}

export function parseBool(str) {
	if (str == null) return false;

	if (typeof str === "boolean") {
		return str;
	}

	if (typeof str === "string") {
		if (str == "") return false;

		str = str.replace(/^\s+|\s+$/g, "");
		if (str.toLowerCase() == "true" || str.toLowerCase() == "yes") return true;

		str = str.replace(/,/g, ".");
		str = str.replace(/^\s*\-\s*/g, "-");
	}

	if (!isNaN(str)) return parseFloat(str) != 0;

	return false;
}

export const dividers = [
	'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"></path></svg>',
	'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path style="transform:rotate(180deg);  transform-origin: 50%;" d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z"></path></svg>',
	'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25"></path><path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5"></path><path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"></path></svg>',
	'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M0,0V7.23C0,65.52,268.63,112.77,600,112.77S1200,65.52,1200,7.23V0Z"></path></svg>',
	'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path style="transform:rotate(180deg); transform-origin: 50%;" d="M600,112.77C268.63,112.77,0,65.52,0,7.23V120H1200V7.23C1200,65.52,931.37,112.77,600,112.77Z"></path></svg>',
	'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M0,0V6c0,21.6,291,111.46,741,110.26,445.39,3.6,459-88.3,459-110.26V0Z"></path></svg>',
	'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path style="transform:rotate(180deg); transform-origin: 50%;" d="M741,116.23C291,117.43,0,27.57,0,6V120H1200V6C1200,27.93,1186.4,119.83,741,116.23Z"></path></svg>',
	'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M1200 0L0 0 598.97 114.72 1200 0z"></path></svg>',
	'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path style="transform:rotate(180deg); transform-origin: 50%;" d="M598.97 114.72L0 0 0 120 1200 120 1200 0 598.97 114.72z"></path></svg>',
	'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M1200 0L0 0 892.25 114.72 1200 0z"></path></svg>',
	'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path style="transform:rotate(180deg); transform-origin: 50%;" d="M892.25 114.72L0 0 0 120 1200 120 1200 0 892.25 114.72z"></path></svg>',
	'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M1200 120L0 16.48 0 0 1200 0 1200 120z"></path></svg>',
	'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M649.97 0L550.03 0 599.91 54.12 649.97 0z"></path></svg>',
	'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path style="transform:rotate(180deg); transform-origin: 50%;" d="M649.97 0L599.91 54.12 550.03 0 0 0 0 120 1200 120 1200 0 649.97 0z"></path></svg>',
	'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M1200,0H0V120H281.94C572.9,116.24,602.45,3.86,602.45,3.86h0S632,116.24,923,120h277Z"></path></svg>',
	'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path style="transform:rotate(180deg); transform-origin: 50%;" d="M602.45,3.86h0S572.9,116.24,281.94,120H923C632,116.24,602.45,3.86,602.45,3.86Z"></path></svg>',
];

export function shapeDividersToCSS(dividers, className) {
	let convertedShapeDivider = convertMultiPositionShapeDivider(dividers);
	let pseudoCSS = ``;

	if (has(convertedShapeDivider, "topDivider")) {
		const topShapeDivider = styleToCss(convertedShapeDivider.topDivider);
		const bottomShapeDivider = styleToCss(convertedShapeDivider.bottomDivider);

		pseudoCSS =
			!isEmpty(topShapeDivider) && !isEmpty(topShapeDivider)
				? `
		.${className}::before {
			content: "";
			width: 100% !important;
			${isEmpty(topShapeDivider) ? "" : topShapeDivider}
		}

		.${className}::after {
			content: "";
			width: 100% !important;
			${isEmpty(bottomShapeDivider) ? "" : bottomShapeDivider}
		}
	`
				: ``;
	} else {
		pseudoCSS = !isEmpty(convertedShapeDivider)
			? `
	.${className}::before {
		content: "";
		width: 100% !important;
		${styleToCss(convertedShapeDivider)}
	}

`
			: ``;
	}

	return pseudoCSS;
}
