import {
	buildActiveContext,
	rgba,
	basicColorScheme,
	convertHexToRGBA,
	applyOpacityToGradient,
} from "../../functions";

import MediaPicker from "./components/mediaPicker";
import { Component } from "@wordpress/element";
import {
	capitalize,
	isEqual,
	get,
	isEmpty,
	has,
	pick,
	keys,
	clone,
	assign,
	map,
	omit,
} from "lodash";
import {
	ButtonGroup,
	Button,
	ColorPalette,
	RangeControl,
	SelectControl,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import { TEXT_DOMAIN } from "../../globals";

// Using experimental picker if found, otherwise using the native one.
const GradientPicker = wp.components.__experimentalGradientPicker || wp.components.GradientPicker;

// Attribute schema in order to use this attribute
export const backgroundSchema = {
	type: "object",
	default: {
		solid: "",
		gradient: "",
		opacity: 1,
		media: {
			backgroundPositionX: "0.50",
			backgroundPositionY: "0.50",
			background: {},
			backgroundSize: "cover",
			backgroundRepeat: "",
		},
	},
};

export function convertBackground(bg) {
	if (!bg) return {};
	if (!has(bg, "solid") && !has(bg, "gradient") && !has(bg, "media")) return {};

	const {
		solid,
		gradient,
		media: {
			background,
			backgroundPositionX,
			backgroundPositionY,
			backgroundSize,
			backgroundRepeat,
		},
		opacity,
	} = bg;

	const PERCENTAGE_UNIT = 100;

	let res = {};

	const bgImage = !isEmpty(background)
		? `,url("${get(background, "url")}")`
		: "";

	const convertedColor = convertHexToRGBA(solid, opacity);

	const solidBg =
		`linear-gradient(${convertedColor}, ${convertedColor})` + bgImage;

	const gradientBg = gradient + bgImage;

	const normalBg = `url("${get(background, "url")}")`;

	if (isEmpty(solid) && isEmpty(gradient)) {
		res["backgroundImage"] = normalBg;
	} else if (!isEmpty(solid) && isEmpty(gradient)) {
		res["backgroundImage"] = solidBg;
	} else if (isEmpty(solid) && !isEmpty(gradient)) {
		res["backgroundImage"] = gradientBg;
	}

	if (isEmpty(solid) && isEmpty(gradient) && isEmpty(background)) {
		res["backgroundImage"] = "";
	}

	if (
		!isEmpty(bgImage) ||
		(!isEmpty(backgroundPositionX) && !isEmpty(backgroundPositionY))
	) {
		res["backgroundPositionX"] = `${backgroundPositionX * PERCENTAGE_UNIT}%`;
		res["backgroundPositionY"] = `${backgroundPositionY * PERCENTAGE_UNIT}%`;
	}

	res["backgroundSize"] = backgroundSize;
	res["background-repeat"] = backgroundRepeat;

	if (get(background, "url") === "") {
		res = omit(res, [
			"backgroundPositionX",
			"backgroundPositionY",
			"backgroundSize",
		]);
	}

	return res;
}

class Background extends Component {
	constructor() {
		super();
		this.state = {
			currentPicker: "solid",
			background: backgroundSchema.default,
		};

		// default control config
		this.config = {
			// default controls to render
			controls: ["solid", "gradient", "media"],
		};

		this.handleNav = this.handleNav.bind(this);
		this.getActiveStatus = this.getActiveStatus.bind(this);
		this.handleChange = this.handleChange.bind(this);
		this.getConfig = this.getConfig.bind(this);
		this.handleOpacityChange = this.handleOpacityChange.bind(this);
	}

	componentWillMount() {
		const { value } = this.props;

		this.setState({
			background: value,
		});
	}

	handleNav(picker) {
		// testing if the current tab is none and removing each background css

		if (isEqual(picker, "none")) {
			this.clear(); // removing background
		}

		this.setState({ currentPicker: picker });
	}

	clear() {
		const clearState = {
			...this.state,
			background: backgroundSchema.default,
		};

		this.setState(clearState, () => this.props.onChange(clearState.background));
	}

	getActiveStatus(t) {
		const { currentPicker } = this.state;

		return buildActiveContext(
			t,
			currentPicker,
			{ isPrimary: true },
			{ isSecondary: true }
		);
	}

	handleChange(type, value) {
		const newBackground = {
			...this.state.background,
			[type]: value,
		};

		switch (type) {
			case "solid":
				{
					newBackground["gradient"] = "";
				}
				break;
			case "gradient": {
				newBackground["solid"] = "";
			}
		}

		this.setState(
			{
				background: newBackground,
			},
			() => {
				this.props.onChange(newBackground);
			}
		);
	}

	/**
	 * Will provide control configuration assigning some default values
	 * @return {object} config
	 */

	getConfig() {
		// default configuration
		const { config } = this;
		// picking user config
		const userConfig = pick(this.props, keys(config));
		const finalConfig = clone(config);

		// merging user config
		assign(finalConfig, userConfig);

		return finalConfig;
	}

	handleOpacityChange(newOpacity) {
		const { background } = this.state;
		const newBackground = {
			...background,
			opacity: newOpacity,
		};
		const currentGradient = get(background, "gradient");

		if (!isEmpty(currentGradient)) {
			const currentGradientWithAppliedOpacity = applyOpacityToGradient(
				currentGradient,
				newOpacity
			);

			newBackground["gradient"] = currentGradientWithAppliedOpacity;
		}

		this.setState(
			{
				background: newBackground,
			},
			() => {
				this.props.onChange(newBackground);
			}
		);
	}

	render() {
		const { currentPicker, background } = this.state;
		const media = get(background, "media");
		const gradient = get(background, "gradient");
		const solid = get(background, "solid");
		const opacity = get(background, "opacity");

		const mediaBackground = get(media, "background");

		const { controls = this.config.controls } = this.getConfig();

		const navs = [
			{
				label: "solid",
				key: "solid",
				component: (
					<div>
						<ColorPalette
							colors={basicColorScheme}
							value={solid}
							onChange={(color) => {
								this.handleChange("solid", color);
							}}
						/>
					</div>
				),
			},
			{
				label: "gradient",
				key: "gradient",

				component: (
					<GradientPicker
						value={gradient}
						onChange={(gradient) => {
							this.handleChange("gradient", gradient);
						}}
					/>
				),
			},
		];

		// no need to render tabs for only one background control
		const shouldRenderNavigation = controls.length > 1;

		return (
			<div className="ep-background">
				<div className="ep-background-img-pick">
					<MediaPicker
						value={media}
						onChange={(media) => this.handleChange("media", media)}
					/>
				</div>
				<div style={{ marginBottom: 10 }}>
					<span>{__("Background Colour", TEXT_DOMAIN)}</span>
				</div>
				{shouldRenderNavigation && (
					<ButtonGroup className="ep-navigation">
						{navs.map((nav, index) => {
							const { label, key } = nav;
							const shouldRenderNav = controls.includes(key);

							return (
								shouldRenderNav && (
									<Button
										key={index}
										onClick={() => this.handleNav(label)}
										{...this.getActiveStatus(label)}
									>
										{capitalize(label)}
									</Button>
								)
							);
						})}
					</ButtonGroup>
				)}

				{navs.map((nav, index) => {
					const { label, component, key } = nav;

					const isActiveNav = isEqual(controls.length, 1)
						? true
						: isEqual(label, currentPicker);

					// if there is only one bg control to render then making it active by default
					const isActiveControl =
						isEqual(controls.length, 1) &&
						this.config.controls.includes(get(controls, 0)) &&
						key === get(controls, 0)
							? true
							: false;

					if (isEqual(controls.length, 1)) {
						return (
							isActiveNav &&
							isActiveControl && (
								<div key={index} className="ep-bg-component">
									{component}
								</div>
							)
						);
					} else {
						return (
							isActiveNav && (
								<div key={index} className="ep-bg-component">
									{component}
								</div>
							)
						);
					}
				})}
				{!isEmpty(mediaBackground) && (
					<RangeControl
						label={__("Opacity", TEXT_DOMAIN)}
						value={opacity}
						onChange={this.handleOpacityChange}
						allowReset
						resetFallbackValue={""}
						min={0}
						max={1}
						step={0.1}
					/>
				)}
			</div>
		);
	}
}

export default Background;
