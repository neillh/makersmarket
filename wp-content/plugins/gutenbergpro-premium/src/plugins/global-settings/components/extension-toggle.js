/**
 * Wordpress Dependencies
 */

import { FormToggle, PanelRow, PanelBody } from "@wordpress/components";
import { compose, withState } from "@wordpress/compose";
import { withSelect, withDispatch } from "@wordpress/data";
import { __ } from "@wordpress/i18n";
import { get, isObject, toString } from "lodash";
import { useEffect } from "@wordpress/element";

/**
 * Custom Imports
 */

import { TEXT_DOMAIN } from "../../../globals";
import classnames from "classnames";
import { parseBool } from "../../../functions";

function extensionToggle({
	name,
	data,
	icon,
	loading,
	error,
	setState,
	onToggle,
	currentStatus,
}) {
	const currentSavedStatus = get(data, "status") ?? true,
		label = get(data, "label");

	useEffect(() => {
		setState({ currentStatus: currentSavedStatus });
	}, []);

	return (
		<PanelBody>
			<PanelRow
				className={classnames({
					"gtp-toggle-extension": true,
					"gtp-loading-extension-status": loading,
					"gtp-is-disabled": !parseBool(currentStatus),
				})}
			>
				<div className="gtp-toggle-label">
					{isObject(icon) && icon}
					<span>{__(label, TEXT_DOMAIN)}</span>
				</div>
				<FormToggle onChange={onToggle} checked={parseBool(currentStatus)} />
			</PanelRow>
		</PanelBody>
	);
}

export default compose([
	withState({
		loading: false,
		error: false,
		currentStatus: true,
	}),
	withSelect((select, { data }) => {
		const { getBlockType } = select("core/blocks");

		const currentBlockToggle = get(data, "block");
		const blockDetails = getBlockType(currentBlockToggle);
		const blockIcon = get(blockDetails, "icon.src");

		// getting the block icon
		return {
			icon: blockIcon,
		};
	}),
	withDispatch((dispatch, { name, data, setState, currentStatus }) => {
		const { createNotice } = dispatch("core/notices");

		return {
			/**
			 * When the extension toggles
			 */

			async onToggle() {
				// retrieving the settings model to manipulate wordpress settings api
				const { Settings } = wp.api.models;

				// toggling the currentStatus
				const newStatus = !parseBool(currentStatus);

				// setting the ui status
				setState({
					loading: true,
					error: false,
				});

				try {
					const updatedStatus = new Settings({
						[name]: toString(newStatus),
					});

					const response = await updatedStatus.save();

					setState({
						loading: false,
						error: false,
						currentStatus: newStatus,
					});

					createNotice(
						"info",
						__(
							"Extension Status Updated, Please refresh the page.",
							TEXT_DOMAIN
						),
						{
							isDismissible: true,
							type: "snackbar",
						}
					);
				} catch (error) {
					setState({
						loading: false,
						error: true,
					});
				}
			},
		};
	}),
])(extensionToggle);
