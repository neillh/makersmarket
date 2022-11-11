import { TEXT_DOMAIN } from "../../globals";
import { __ } from "@wordpress/i18n";
import { Component } from "@wordpress/element";
import { clone, set, maxBy, isEmpty, isEqual } from "lodash";
import {
	TextControl,
	Button,
	IconButton,
	CheckboxControl,
	SelectControl,
} from "@wordpress/components";

export function convertDimensions(dimension) {
	const getNum = (n) => (isEmpty(n) ? 0 : n);

	if (isEmpty(dimension)) {
		return "0px 0px 0px 0px";
	} // null exception

	const {
		value: { top, right, bottom, left },
		important,
		unit,
	} = dimension;

	const isImportant = important ? "!important" : "";

	const isEveryEmpty = [top, right, bottom, left].every((p) => isEmpty(p));
	const isUnit = isEmpty(unit) ? "px" : unit;

	if (isEveryEmpty) {
		return "";
	} else {
		const topUnit = isNaN(Number(top)) ? "" : isUnit;
		const rightUnit = isNaN(Number(right)) ? "" : isUnit;
		const bottomUnit = isNaN(Number(bottom)) ? "" : isUnit;
		const leftUnit = isNaN(Number(left)) ? "" : isUnit;

		return `${getNum(top)}${topUnit} ${getNum(right)}${rightUnit} ${getNum(
			bottom
		)}${bottomUnit} ${getNum(left)}${leftUnit} ${isImportant}`;
	}
}

export const dimensionSchema = {
	type: "object",
	default: {
		value: {
			top: "",
			right: "",
			bottom: "",
			left: "",
		},
		unit: "%",
		important: true,
	},
};

const emptyState = {
	top: "",
	right: "",
	bottom: "",
	left: "",
	lock: false,
	important: true,
};
class Dimensions extends Component {
	constructor() {
		super();
		this.state = {
			top: "",
			right: "",
			bottom: "",
			left: "",
			lock: false,
			unit: "%",
			important: false,
		};

		this.lock = this.lock.bind(this);
		this.handleChange = this.handleChange.bind(this);
		this.setAll = this.setAll.bind(this);
		this.clear = this.clear.bind(this);
		this.handleUnitChange = this.handleUnitChange.bind(this);
		this.isValid = this.isValid.bind(this);
	}

	componentWillMount() {
		if (isEmpty(this.props.value)) return;

		const value = this.props.value;

		const { top, right, bottom, left } = value.value;
		const { important, unit } = value;

		const topValue = this.isValid(top) ? top : "";
		const rightValue = this.isValid(right) ? right : "";
		const bottomValue = this.isValid(bottom) ? bottom : "";
		const leftValue = this.isValid(left) ? left : "";

		this.setState({
			top: topValue,
			right: rightValue,
			bottom: bottomValue,
			left: leftValue,
			important,
			unit,
		});
	}

	handleUnitChange(newUnit) {
		this.setState({ unit: newUnit }, () => {
			const { top, right, bottom, left } = this.state;

			this.props.onChange({ top, right, bottom, left }, newUnit);
		});
	}

	isValid(value) {
		const isNumeric = !isNaN(Number(value));
		const isAuto = isEqual(value, "auto");

		return isNumeric || isAuto;
	}

	handleChange(e, type) {
		const { lock } = this.state;
		const { value } = e.target;

		let newState;

		if (!lock) {
			newState = clone(this.state);
			set(newState, type, value);
		} else {
			newState = this.setAll(value);
		}

		const { top, right, bottom, left, unit } = newState;

		this.setState({ ...newState });

		this.props.onChange({ top, right, bottom, left }, unit);
	}

	setAll(v) {
		let updatedValue = {
			top: v,
			right: v,
			bottom: v,
			left: v,
			unit: this.state.unit,
		};

		this.setState(updatedValue);

		return updatedValue;
	}

	lock() {
		const { top, right, bottom, left, lock } = this.state; // some destructuring...

		if (lock) {
			this.setState({ lock: false }); // if the control inputs are locked then unlocking them and stops executing the function...
			return;
		}

		const maxValue = maxBy([top, right, bottom, left], (p) => ~~p); // getting the maximum padding ( where ~~[VAR] is used to convert string into number ;-D )

		this.setAll(maxValue.toString()); // setting all input fields to the maximum one
		this.setState({ lock: true }); // finally locking the input
	}

	clear() {
		this.setState(emptyState, () => {
			const { top, right, bottom, left, unit } = this.state;

			this.props.onChange({ top, right, bottom, left }, unit);
		});
	}

	render() {
		const { top, right, bottom, left, lock, important, unit } = this.state;
		const supportedUnits = ["%", "px", "vw", "vh", "rem", "em"];
		const options = supportedUnits.map((u) => {
			return { value: u, label: u };
		});

		return (
			<div className="ep-dimension-control">
				<div className="ep-dimensions">
					<div className="ep-dimension">
						<SelectControl
							value={unit}
							options={options}
							onChange={this.handleUnitChange}
						/>
					</div>
					<div className="ep-dimension">
						<input
							value={top}
							onChange={(e) => this.handleChange(e, "top")}
							type="number"
						/>
						<span>{__("Top", TEXT_DOMAIN)}</span>
					</div>
					<div className="ep-dimension">
						<input
							value={right}
							onChange={(e) => this.handleChange(e, "right")}
							type="number"
						/>
						<span>{__("Right", TEXT_DOMAIN)}</span>
					</div>
					<div className="ep-dimension">
						<input
							value={bottom}
							onChange={(e) => this.handleChange(e, "bottom")}
							type="number"
						/>
						<span>{__("Bottom", TEXT_DOMAIN)}</span>
					</div>
					<div className="ep-dimension">
						<input
							value={left}
							onChange={(e) => this.handleChange(e, "left")}
							type="number"
						/>
						<span>{__("Left", TEXT_DOMAIN)}</span>
					</div>
					<div className="ep-dimension">
						<IconButton
							onClick={this.lock}
							isSmall
							icon={lock ? "lock" : "unlock"}
							isDefault
							label={__(!lock ? "Lock" : "Unlock", TEXT_DOMAIN)}
						/>
					</div>
				</div>
				<div className="ep-important">
					<div className="ep-clear">
						<Button isSmall isDefault onClick={this.clear}>
							Clear
						</Button>
					</div>
				</div>
			</div>
		);
	}
}

export default Dimensions;
