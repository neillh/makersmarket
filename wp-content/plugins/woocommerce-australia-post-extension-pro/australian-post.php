<?php
/* @wordpress-plugin
 * Plugin Name:       Australia Post WooCommerce Extension PRO
 * Plugin URI:        https://wpruby.com/plugin/australia-post-woocommerce-extension-pro/
 * Description:       The PRO version of WooCommerce Australian Post Shipping Method.
 * Version:           4.9.2
 * Requires at least: 4.0
 * Requires PHP: 7.0
 * WC requires at least: 3.0
 * WC tested up to: 7.1
 * Author:            WPRuby
 * Author URI:        https://wpruby.com
 * Text Domain:       woocommerce-australia-post-pro
 * license:           GPL-2.0+
 * license URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

namespace AustraliaPost;


use AustraliaPost\Core\Bootstrap;

require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );
require_once( dirname( __FILE__ ) . '/includes/autoload.php' );


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AUSPOSTPRO_URL', plugin_dir_url( __FILE__ ) );
define( 'AUSPOST_CURRENT_VERSION',              '4.9.2' );
define( 'WPRUBY_AUPOST_STORE_URL',              'https://wpruby.com/edd_sl/woocommerce-australia-post' );
define( 'WPRUBY_AUPOST_ITEM_NAME',              'Australia Post WooCommerce Extension PRO' );
define( 'WPRUBY_AUPOST_ITEM_ID',                9862);
define( 'WPRUBY_PRICE_PAYLOAD_KEY',             '_australia_post_price_payload');
define( 'WPRUBY_CREATE_SHIPMENT_PAYLOAD_KEY',   '_australia_post_create_shipment_payload');
define( 'WPRUBY_GENERATE_LABEL_PAYLOAD_KEY',    '_australia_post_generate_label_payload');
define( 'WPRUBY_LABEL_STATUS_KEY',              '_australia_post_order_status');
define( 'WPRUBY_TRACKING_IDS',                  '_australia_post_tracking_ids');
define( 'WPRUBY_PACKAGING_DETAILS_KEY',         '_australia_post_packaging_details');


/** initiate the plugin */
Bootstrap::get_instance();
