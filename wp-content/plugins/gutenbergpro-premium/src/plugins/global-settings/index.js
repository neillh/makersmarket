/**
 * Wordpress Dependencies
 */

import { PluginSidebar } from "@wordpress/edit-post";

/**
 * Custom Imports
 */

import Settings from "./components/settings";

/**
 * This plugin creates a sidebar to toggle Gutenberg Pro Global settings
 */

wp.domReady(() => {
	const { registerPlugin } = wp.plugins;

	registerPlugin("gtp-global-settings", {
		render: () => (
			<PluginSidebar name="gtp-global-settings" title="Gutenberg Pro Settings">
				<Settings />
			</PluginSidebar>
		),
	});
});
