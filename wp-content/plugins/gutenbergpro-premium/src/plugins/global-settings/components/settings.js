/**
 * Wordpress Dependencies
 */

import { get, map } from "lodash";

/**
 * Custom Imports
 */

import ExtensionToggle from "./extension-toggle";

function Settings() {
	const globals = get(window, "gtpGlobals");
	const extensions = get(globals, "extensions");

	return (
		<div className="gtp-global-settings">
			{map(extensions, (data, name) => {
				return <ExtensionToggle name={name} data={data} />;
			})}
		</div>
	);
}

export default Settings;
