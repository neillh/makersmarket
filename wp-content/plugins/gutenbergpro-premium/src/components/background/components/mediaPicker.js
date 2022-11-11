import LibraryPicker from "./libraryPicker";
import { buildActiveContext } from "../../../functions";
import { isEqual, get, isEmpty } from "lodash";
import { Component } from "@wordpress/element";
import { FocalPointPicker, Button, ButtonGroup } from "@wordpress/components";

class MediaPicker extends Component {
	constructor() {
		super();
		this.state = {
			media: {
				backgroundPositionX: 0.5,
				backgroundPositionY: 0.5,
				background: {},
				position: "relative",
				backgroundSize: "",
				backgroundRepeat: "",
			},
		};

		this.handleSelect = this.handleSelect.bind(this);
		this.handleClear = this.handleClear.bind(this);
		this.handleChange = this.handleChange.bind(this);
	}

	handleChange(type, value) {
		const { media } = this.state;

		const shouldApplyProperty = !isEqual(media[type], value);

		this.setState({
			media: {
				...this.state.media,
				[type]: shouldApplyProperty ? value : "",
			},
		});
	}

	componentDidMount() {
		this.setState({ media: this.props.value });
	}

	componentWillUpdate(newProps, newState) {
		if (isEqual(this.state.media, newState.media)) return;

		const { media } = newState;

		this.props.onChange(media);
	}

	handleSelect(newMedia) {
		if (isEmpty(newMedia)) return;

		this.setState({
			media: {
				...this.state.media,
				background: newMedia,
			},
		});
	}

	handleClear() {
		this.setState({
			media: {
				backgroundPositionX: 0.5,
				backgroundPositionY: 0.5,
				position: "relative",
				background: {},
				backgroundSize: "",
				backgroundRepeat: "",
			},
		});
	}

	render() {
		const {
			background,
			backgroundPositionX,
			backgroundPositionY,
			backgroundSize,
			backgroundRepeat,
		} = this.state.media;
		const ALLOWED_TYPES = ["image"];

		const dimensions = {
			width: get(background, "width"),
			height: get(background, "height"),
		};

		const url = get(background, "url");
		const type = get(background, "type");

		const focalValue = {
			x: backgroundPositionX,
			y: backgroundPositionY,
		};

		if (isEmpty(background)) {
			return (
				<LibraryPicker
					allowed={ALLOWED_TYPES}
					value={background}
					onSelect={this.handleSelect}
					title="Background Image"
				/>
			);
		} else {
			return (
				<div>
					<FocalPointPicker
						url={url}
						dimensions={dimensions}
						value={focalValue}
						onChange={(focalPoint) => {
							this.setState({
								media: {
									...this.state.media,
									backgroundPositionX: focalPoint.x,
									backgroundPositionY: focalPoint.y,
								},
							});
						}}
					/>

					<div className="ep-btn-group">
						<h3>Size</h3>
						<div>
							<Button
								isSmall
								{...buildActiveContext(
									backgroundSize,
									"auto",
									{ isPrimary: true },
									{ isDefault: true }
								)}
								onClick={() => this.handleChange("backgroundSize", "auto")}
							>
								Auto
							</Button>
							<Button
								isSmall
								{...buildActiveContext(
									backgroundSize,
									"cover",
									{ isPrimary: true },
									{ isDefault: true }
								)}
								onClick={() => this.handleChange("backgroundSize", "cover")}
							>
								Cover
							</Button>
							<Button
								isSmall
								{...buildActiveContext(
									backgroundSize,
									"contain",
									{ isPrimary: true },
									{ isDefault: true }
								)}
								onClick={() => this.handleChange("backgroundSize", "contain")}
							>
								Contain
							</Button>
						</div>
					</div>

					<div style={{ marginTop: "15px" }} className="ep-btn-group">
						<h3>Repeat</h3>
						<div>
							<Button
								isSmall
								{...buildActiveContext(
									backgroundRepeat,
									"repeat",
									{ isPrimary: true },
									{ isDefault: true }
								)}
								onClick={() => this.handleChange("backgroundRepeat", "repeat")}
							>
								All
							</Button>
							<Button
								isSmall
								{...buildActiveContext(
									backgroundRepeat,
									"no-repeat",
									{ isPrimary: true },
									{ isDefault: true }
								)}
								onClick={() =>
									this.handleChange("backgroundRepeat", "no-repeat")
								}
							>
								None
							</Button>
							<Button
								isSmall
								{...buildActiveContext(
									backgroundRepeat,
									"repeat-x",
									{ isPrimary: true },
									{ isDefault: true }
								)}
								onClick={() =>
									this.handleChange("backgroundRepeat", "repeat-x")
								}
							>
								X
							</Button>
							<Button
								isSmall
								{...buildActiveContext(
									backgroundRepeat,
									"repeat-y",
									{ isPrimary: true },
									{ isDefault: true }
								)}
								onClick={() =>
									this.handleChange("backgroundRepeat", "repeat-y")
								}
							>
								Y
							</Button>
						</div>
					</div>

					<div
						style={{ marginTop: 10, textAlign: "right" }}
						className="ep-clear"
						onClick={this.handleClear}
					>
						<Button isSmall isDefault>
							Clear Media
						</Button>
					</div>
				</div>
			);
		}
	}
}

export default MediaPicker;
