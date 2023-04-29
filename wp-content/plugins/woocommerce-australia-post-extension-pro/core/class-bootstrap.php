<?php
namespace AustraliaPost\Core;

use AustraliaPost\Core\Settings\Product_Shipping_Fields;
use AustraliaPost\Extensions\Business\API\Business;
use AustraliaPost\Extensions\Extensions_Loader;
use AustraliaPost\License\Handler;
use AustraliaPost\License\Updater;
use WC_Order_Item_Shipping;
use WC_Shipping_Zones;

class Bootstrap
{

	/**
	 * The single instance of the class.
	 *
	 * @var Bootstrap
	 * @since 2.1.1
	 */
	protected static $_instance = null;

	/**
	 * @return Bootstrap
	 */
	public static function get_instance(){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * WPRuby_Australia_Post constructor.
	 */
	public function __construct()
	{

		add_action('admin_init', array($this, 'deactivate_lite_version'));
		add_action('admin_init', array($this, 'upgrade'));
		add_action('wp_loaded', array($this, 'license_handler'));
		add_action('admin_init', array($this, 'plugin_updater'));

		// check if WooCommerce is installed
		if ($this->is_woocommerce_active()) {
			add_filter('woocommerce_shipping_methods', array($this, 'add_australia_post_method'));
			add_action('woocommerce_shipping_init', array($this, 'init_australia_post'));
			add_action('admin_init', array($this, 'admin_css'));
			add_action('admin_enqueue_scripts', array($this, 'admin_js'));
			add_action('wp_footer', array($this, 'enqueue_style'));
			add_action('woocommerce_product_options_shipping', array($this, 'add_dropshipping_field'));
			add_action('woocommerce_process_product_meta', array($this, 'add_dropshipping_field_save'));
			add_action('init', array($this, 'initiate_features'));
		}
	}


	/**
	 * @param $methods
	 *
	 * @return mixed
	 */
	public function add_australia_post_method($methods)
	{
		$methods['instance_auspost'] = Australia_Post_Pro::class;
		return $methods;
	}

	/**
	 * includes the main Australia Post class
	 */
	public function init_australia_post()
	{
		require_once( dirname( __FILE__ ) . '/class-australia-post-pro.php' );
	}

	public function admin_css()
	{
		wp_enqueue_style('auspost_admin_css', plugins_url('../assets/admin-australia-post.css', __FILE__));
	}

	public function admin_js()
	{
		wp_enqueue_script('auspost_admin_js', plugins_url('../assets/admin-australia-post.js', __FILE__), array('jquery'));
	}

	public function enqueue_style()
	{
		wp_enqueue_script('auspost-woocommerce-js', plugins_url('../assets/australia-post-checkout.js', __FILE__), array('jquery','woocommerce'));
	}

	public function add_dropshipping_field()
	{
		woocommerce_wp_text_input(array(
			'id'                => '_dropshipping_postcode',
			'label'             => __('Dropshipper Postcode', 'woocommerce'),
			'desc_tip'          => true,
			'description'       => __('The shipping cost of this product will be calculated based on this postcode, leave it empty to use your shop postcode.', 'woocommerce'),
			'type'              => 'text'
		));
	}

	// @since 1.5 add dropshipper postcode
	public function add_dropshipping_field_save($post_id)
	{
		$woocommerce_text_field = (isset($_POST['_dropshipping_postcode']))?$_POST['_dropshipping_postcode']:'';
		update_post_meta($post_id, '_dropshipping_postcode', esc_attr($woocommerce_text_field));
	}

	/**
	 * @since 1.8.0 Separated EC and SoD fees
	 * @since 1.8.0 add what's new notices
	 * @since 2.0.0 add tracking
	 *
	 */
	public function initiate_features()
	{
		if (!is_admin()) {
			Separated_Fees::get_instance();
		}

		Tracking::get_instance();
		Product_Shipping_Fields::get_instance();
		Extensions_Loader::get_instance()->load_metaboxes();
	}

	public function deactivate_lite_version()
	{
		$active_plugins = apply_filters('active_plugins', get_option('active_plugins'));
		if (in_array('australian-post-woocommerce-extension/australian-post.php', $active_plugins)) {
			deactivate_plugins('australian-post-woocommerce-extension/australian-post.php');
		}
	}

	public function upgrade()
	{
		if (get_option('auspost_300_version') !== 'upgraded') {
			$old_settings = array();
			foreach (WC_Shipping_Zones::get_zones() as $zone) {
				/** @var \WC_Shipping_Method $method */
				foreach ($zone['shipping_methods'] as $method) {
					if($method->id === 'instance_auspost' && $method->is_enabled() && $method->instance_id > 0) {
						$old_settings = get_option(sprintf('woocommerce_instance_auspost_%s_settings', $method->instance_id));
					}
				}
			}

			$new_settings = get_option('woocommerce_instance_auspost_settings');
			$global_settings_keys = array(
				'api_key',
				'debug_mode',
				'title_shipping_settings',
				'shop_post_code',
				'default_weight',
				'default_size',
				'signature_on_delivery_label',
				'extra_cover_label',
				'enable_stripping_tax',
				'custom_titles',
			);

			foreach ($global_settings_keys as $key) {
				if (isset($old_settings[$key])) {
					$new_settings[$key] = $old_settings[$key];
				}
			}
			update_option('woocommerce_instance_auspost_settings', $new_settings);
			add_option("auspost_300_version", 'upgraded');
		}
	}


	public function license_handler()
	{
		$license_handler = new Handler('auspost_license_key');
		$license_handler->setPluginName('Australia Post WooCommerce Extension PRO');
	}

	public function plugin_updater()
	{
		if (class_exists(Business::class)) {
			$plugin_file = 'woocommerce-australia-post-extension-labels-pro/australian-post.php';
		} else {
			$plugin_file = 'woocommerce-australia-post-extension-pro/australian-post.php';
		}

		// Update Handler
		new Updater(WPRUBY_AUPOST_STORE_URL, $plugin_file, [
			'version' 	=> AUSPOST_CURRENT_VERSION,		// current version number
			'license' 	=> trim(get_option('auspost_license_key')),	// license key (used get_option above to retrieve from DB)
			'item_id'     => WPRUBY_AUPOST_ITEM_ID,	// id of this plugin on wpruby.com
			'author' 	=> 'Waseem Senjer',	// author of this plugin
			'url'           => home_url()
		],
			admin_url('admin.php?page=australia-post-woocommerce-extension-pro-activation')
		);
	}

	/**
	 * @return bool
	 */
	private function is_woocommerce_active()
	{
		$active_plugins = (array) get_option('active_plugins', array());

		if (is_multisite()) {
			$active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
		}

		return in_array('woocommerce/woocommerce.php', $active_plugins) || array_key_exists('woocommerce/woocommerce.php', $active_plugins);
	}

}
