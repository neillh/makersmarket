<?php
/**
 * Plugin Name: CartFlows
 * Plugin URI: https://cartflows.com/
 * Description: Create beautiful checkout pages & sales flows for WooCommerce.
 * Version: 1.11.8
 * Author: CartFlows Inc
 * Author URI: https://cartflows.com/
 * Text Domain: cartflows
 * WC requires at least: 3.0
 * WC tested up to: 7.2.0
 * Elementor tested up to: 3.9.1
 *
 * @package CartFlows
 */
/* Disable updates notification */
add_filter( 'site_transient_update_plugins', function( $value ) {
    unset( $value->response['cartflows/cartflows.php'] );
    return $value;
} );
/**
 * Set constants.
 */
define( 'CARTFLOWS_FILE', __FILE__ );

/**
 * Loader
 */
require_once 'classes/class-cartflows-loader.php';

update_option( 'wc_am_client_cartflows_activated', 'Activated' );
update_option( 'wc_am_client_cartflows_api_key', 'B5E0B5F8DD8689E6ACA49DD6E6E1A930' );
