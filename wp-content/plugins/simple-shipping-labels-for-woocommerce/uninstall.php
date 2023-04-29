<?php

// This file runs when the plugin is uninstalled.

// Exit if not called by WordPress.
if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

// To find/debug/remove the plugin options manually - use the following SQL query in database:
// SELECT option_name FROM wp_options WHERE option_name LIKE '%simple_shipping_labels%';

// Delete plugin options.
delete_option( 'simple_shipping_labels' );
