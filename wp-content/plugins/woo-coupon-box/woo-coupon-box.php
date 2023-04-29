<?php
/**
 * Plugin Name: Coupon Box for WooCommerce
 * Plugin URI: https://villatheme.com/extensions/woo-coupon-box/
 * Description: The easiest way to share your coupon code and grow your social followers at the same time. With Coupon Box for WooCommerce, your customers will see a popup that motivates them to follow your social profiles to get coupon code.
 * Version: 2.0.7
 * Author: VillaTheme
 * Author URI: http://villatheme.com
 * Text Domain: woo-coupon-box
 * Domain Path: /languages
 * Copyright 2017-2022 VillaTheme.com. All rights reserved.
 * Requires at least: 5.0
 * Tested up to: 6.1
 * WC requires at least: 4.0
 * WC tested up to: 7.1
 * Requires PHP: 7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VI_WOO_COUPON_BOX_VERSION', '2.0.7' );
/**
 * Detect plugin. For use on Front End only.
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'woocommerce-coupon-box/woocommerce-coupon-box.php' ) ) {
	return;
}
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
	$init_file = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "woo-coupon-box" . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "define.php";
	require_once $init_file;
} else {
	if ( ! function_exists( 'wcb_notification' ) ) {
		function wcb_notification() {
			?>
            <div id="message" class="error">
                <p><?php esc_html_e( 'Please install and activate WooCommerce to use Coupon Box for WooCommerce.', 'woo-coupon-box' ); ?></p>
            </div>
			<?php
		}
	}
	add_action( 'admin_notices', 'wcb_notification' );
}