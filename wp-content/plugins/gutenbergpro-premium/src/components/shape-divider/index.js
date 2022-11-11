import { basicColorScheme, dividers as svgDividers } from "../../functions";
import SvgPicker from "./components/svg-picker";
import { Component } from "@wordpress/element";
import { TEXT_DOMAIN } from "../../globals";

const jQuery = window.jQuery;

const { clone, set, isEmpty, get, isEqual } = lodash;
const {
	SelectControl,
	ColorPalette,
	PanelRow,
	FormToggle,
	RangeControl,
} = wp.components;
const { __ } = wp.i18n;

export const ShapeDividerSchema = {
	type: "object",
	default: {
		height: {
			value: 30,
		},
		color: "#fff",
		style: "",
		arrangement: "top",
		position: "top",
		flip: false,
		mobileHeight: "gtp-sm-height-sameAsDesktop",
	},
};

export function convertMultiPositionShapeDivider(divider) {
	const { position } = divider;

	if (position === "topBottom") {
		return {
			topDivider: convertShapeDivider({ ...divider, position: "top" }),
			bottomDivider: convertShapeDivider({ ...divider, position: "bottom" }),
		};
	} else {
		return convertShapeDivider(divider);
	}
}

export function convertShapeDivider(divider) {
	const res = {};

	const { height, color, style, arrangement, position, flip } = divider;

	if (isEmpty(style)) return {};

	const newHeight = get(height, "value") + "%";

	const newStyle = jQuery(style).attr("fill", color);

	let svgDataUri;

	if (!isEmpty(style)) {
		const selectedSvg = newStyle[0].outerHTML;
		svgDataUri = "data:image/svg+xml;base64," + window.btoa(selectedSvg);
	}

	let transforms = [];

	res["height"] = newHeight;
	res["minHeight"] = `${newHeight} !important`;
	res["position"] = "absolute";
	res["background-color"] = "transparent !important";
	res["display"] = "block";
	res["opacity"] = "1 !important";
	res["background-size"] = "101%";
	res["background-repeat"] = "no-repeat";
	if (position === "top") {
		res["top"] = "-1px !important";
	} else {
		res["top"] = "auto !important";
		res["bottom"] = "-1px";

		transforms.push("rotateX(180deg)");
	}

	if (flip) {
		transforms.push("rotateY(180deg)");
	}

	res["left"] = "0 !important";

	if (!isEmpty(transforms)) {
		res["transform"] = transforms.join(" ");
	}

	if (arrangement === "underneath") {
		res["z-index"] = "-1";
	} else {
		res["z-index"] = "100";
	}

	if (!isEmpty(style)) {
		res["background-image"] = `url(${svgDataUri})`;
	}

	if (isEmpty(style)) {
		return "";
	}

	return res; // exception
}

// mobile heights options
const supportedMobileHeights = [
	{
		label: __("Same as desktop", TEXT_DOMAIN),
		value: "gtp-sm-height-sameAsDesktop",
	},
	{
		label: __("HD - Good for 16:9 images", TEXT_DOMAIN),
		value: "gtp-sm-height-hd",
	},
	{
		label: __("SD - Good for 4:3 images", TEXT_DOMAIN),
		value: "gtp-sm-height-sd",
	},
	{
		label: __("Stretch Full Height", TEXT_DOMAIN),
		value: "gtp-sm-height-stretch",
	},
];

class ShapeDivider extends Component {
	constructor() {
		super();
		this.state = {
			divider: {
				...ShapeDividerSchema.default,
			},
		};
		this.handleChange = this.handleChange.bind(this);
		this.handleClass = this.handleClass.bind(this);
	}

	componentWillMount() {
		const { value } = this.props;

		this.setState({
			divider: value,
		});
	}

	handleChange(value, type) {
		const { attr } = this.props;

		const newDivider = clone(this.state.divider); // creating a copy of original divider
		set(newDivider, type, value); // mutating...

		this.setState({ divider: newDivider }, () =>
			this.props.onChange(newDivider, attr)
		); // updating...
	}

	/**
	 * Will test if the given class is already applied to the className parameter
	 *
	 * @param { string } className
	 * @param { string } testClass
	 *
	 * @return { boolean }
	 */

	hasClass(className, testClass) {
		// some safety checks
		if (typeof className !== "string") return false;

		// creating a classList from the className
		const classList = className.split(" ");
		let hasGivenClassName = false;

		classList.forEach((c) => {
			if (isEqual(c, testClass)) {
				hasGivenClassName = true;
			}
		});

		return hasGivenClassName;
	}

	/**
	 * Will add the given class to the block
	 */

	handleClass(newClass, isMobileHeight = false) {
		const { attributes, attr, setAttributes } = this.props;

		// current className
		const { className } = attributes;

		const mobileHeightClassList = supportedMobileHeights.map((c) => c.value);

		if (isEmpty(className) || isEmpty(attributes)) return;

		// checking if it already has the given class applied
		const hasGivenClass = this.hasClass(className, newClass);

		if (!hasGivenClass && typeof className === "string" && !isMobileHeight) {
			let currentClassList = className.split(" ");

			currentClassList.push(newClass);

			setAttributes({ className: currentClassList.join(" ") });
		} else if (
			hasGivenClass &&
			typeof className === "string" &&
			!isMobileHeight
		) {
			let currentClassList = className.split(" ");

			currentClassList = currentClassList.filter((c) => {
				if (!isEqual(c, newClass)) return c;
			});

			setAttributes({ className: currentClassList.join(" ") });
		}

		if (isMobileHeight && typeof className === "string") {
			let currentClassList = className.split(" ");

			currentClassList.push(newClass);

			currentClassList = currentClassList.filter((classN) => {
				if (mobileHeightClassList.includes(classN) && classN !== newClass) {
					return false;
				}

				return classN;
			});

			setAttributes({ className: currentClassList.join(" ") });
		}
	}

	render() {
		const {
			color,
			height,
			style,
			position,
			flip,
			mobileHeight,
		} = this.state.divider;

		const { className = "" } = this.props.attributes;

		return (
			<div className="ep-shape-divider-control">
				<span style={{ marginBottom: 10, display: "block" }}>
					{__("Shape Style")}
				</span>
				<SvgPicker
					choices={svgDividers}
					value={style}
					onSelect={(svg) => this.handleChange(svg, "style")}
				/>

				<SelectControl
					label={__("Shape Position", TEXT_DOMAIN)}
					value={position}
					options={[
						{ label: "Top", value: "top" },
						{ label: "Bottom", value: "bottom" },
						{ label: "Top And Bottom", value: "topBottom" },
					]}
					onChange={(position) => {
						this.handleChange(position, "position");
					}}
				/>

				<PanelRow>
					<span>{__("Shape Flip", TEXT_DOMAIN)}</span>
					<FormToggle
						checked={flip}
						onChange={() => this.handleChange(!flip, "flip")}
					/>
				</PanelRow>

				<RangeControl
					label={__("Shape Height", TEXT_DOMAIN)}
					value={get(height, "value")}
					onChange={(newH) => this.handleChange({ value: newH }, "height")}
				/>

				<div>
					<h3>{__("Shape Color", TEXT_DOMAIN)}</h3>
					<ColorPalette
						colors={basicColorScheme}
						value={color}
						onChange={(color) => this.handleChange(color, "color")}
					/>
				</div>

				<PanelRow>
					<span>{__("Hide On Desktop", TEXT_DOMAIN)}</span>
					<FormToggle
						checked={this.hasClass(className, "gtp-hide-desktop")}
						onChange={() => this.handleClass("gtp-hide-desktop")}
					/>
				</PanelRow>

				<PanelRow>
					<span>{__("Hide On Tablet", TEXT_DOMAIN)}</span>
					<FormToggle
						checked={this.hasClass(className, "gtp-hide-tablet")}
						onChange={() => this.handleClass("gtp-hide-tablet")}
					/>
				</PanelRow>

				<PanelRow>
					<span>{__("Hide On Mobile", TEXT_DOMAIN)}</span>
					<FormToggle
						checked={this.hasClass(className, "gtp-hide-mobile")}
						onChange={() => this.handleClass("gtp-hide-mobile")}
					/>
				</PanelRow>
				<div style={{ margin: "10px 0px" }}>
					<SelectControl
						label={__("Mobile Height", TEXT_DOMAIN)}
						value={mobileHeight}
						options={supportedMobileHeights}
						onChange={(newMobileHeight) => {
							this.handleChange(newMobileHeight, "mobileHeight");

							this.handleClass(newMobileHeight, true);
						}}
					/>
				</div>
			</div>
		);
	}
}

export default ShapeDivider;
