import React, { Component } from "react";

const { isEmpty, isEqual } = lodash;
const { Button, Dropdown } = wp.components;
const { __ } = wp.i18n;

class SvgPicker extends Component {
	constructor() {
		super();
		this.state = {
			currentSvg: null,
		};
		this.handleSelect = this.handleSelect.bind(this);
	}

	componentWillMount() {
		const { value } = this.props;

		this.setState({
			currentSvg: value,
		});
	}

	handleSelect(choice) {
		this.setState({ currentSvg: choice }, () => this.props.onSelect(choice));
	}

	render() {
		const { props, state } = this;
		const { choices } = props;
		const { currentSvg } = state;

		return (
			<div className="ep-svg-picker">
				<div className="ep-svg-picker-root">
					<Dropdown
						className="ep-svg-picker-dropdown"
						contentClassName="ep-svg-picker-dropdown-content"
						position="top center"
						renderToggle={({ isOpen, onToggle }) => {
							return !isEmpty(currentSvg) ? (
								<div
									className="ep-current-svg"
									onClick={onToggle}
									dangerouslySetInnerHTML={{ __html: currentSvg }}
								></div>
							) : (
								<div className="ep-current-svg" onClick={onToggle}>
									<p>Select Style</p>
								</div>
							);
						}}
						renderContent={({ onClose }) => (
							<div className="ep-svg-choices">
								<div
									className="ep-svg-select ep-none"
									onClick={() => {
										this.handleSelect(null); // de-selecting the style
										onClose();
									}}
								>
									<p>None</p>
								</div>

								{choices.map((choice, index) => {
									const isActive = isEqual(choice, currentSvg);
									const activeClass = isActive ? "current-svg" : "";

									return (
										<div
											className={`ep-svg-select ${activeClass}`}
											key={index}
											onClick={() => {
												this.handleSelect(choice);
												onClose();
											}}
											dangerouslySetInnerHTML={{ __html: choice }}
										></div>
									);
								})}
							</div>
						)}
					/>
				</div>
			</div>
		);
	}
}

export default SvgPicker;
