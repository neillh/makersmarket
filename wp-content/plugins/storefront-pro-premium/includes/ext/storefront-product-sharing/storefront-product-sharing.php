<?php
/**
 * Plugin Name:			Storefront Product Sharing
 * Plugin URI:			http://woothemes.com/products/storefront-product-sharing/
 * Description:			Add attractive social sharing icons for Facebook, Twitter, Pinterest and Email to your product pages.
 * Version:				1.0.2
 * Author:				WooThemes
 * Author URI:			http://woothemes.com/
 * Requires at least:	4.0.0
 * Tested up to:		4.2.2
 *
 * Text Domain: storefront-product-sharing
 * Domain Path: /languages/
 *
 * @package Storefront_Product_Sharing
 * @category Core
 * @author James Koster
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Returns the main instance of Storefront_Product_Sharing to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Storefront_Product_Sharing
 */
function Storefront_Product_Sharing() {
	return Storefront_Product_Sharing::instance();
} // End Storefront_Product_Sharing()

Storefront_Product_Sharing();

/**
 * Main Storefront_Product_Sharing Class
 *
 * @class Storefront_Product_Sharing
 * @version	1.0.0
 * @since 1.0.0
 * @package	Storefront_Product_Sharing
 */
final class Storefront_Product_Sharing {
	/**
	 * Storefront_Product_Sharing The single instance of Storefront_Product_Sharing.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $token;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $version;

	// Admin - Start
	/**
	 * The admin object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $admin;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct() {
		$this->token 			= 'storefront-product-sharing';
		$this->plugin_url 		= plugin_dir_url( __FILE__ );
		$this->plugin_path 		= plugin_dir_path( __FILE__ );
		$this->version 			= '1.0.2';

		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'sps_load_plugin_textdomain' ) );

		add_action( 'init', array( $this, 'sps_setup' ) );
	}

	/**
	 * Main Storefront_Product_Sharing Instance
	 *
	 * Ensures only one instance of Storefront_Product_Sharing is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Storefront_Product_Sharing()
	 * @return Main Storefront_Product_Sharing instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	} // End instance()

	/**
	 * Load the localisation file.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function sps_load_plugin_textdomain() {
		load_plugin_textdomain( 'storefront-product-sharing', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	}

	/**
	 * Installation.
	 * Runs on activation. Logs the version number and assigns a notice message to a WordPress option.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install() {
		$this->_log_version_number();
	}

	/**
	 * Log the plugin version number.
	 * @access  private
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number() {
		// Log the version number.
		update_option( $this->token . '-version', $this->version );
	}

	/**
	 * Setup all the things.
	 * Only executes if Storefront or a child theme using Storefront as a parent is active and the extension specific filter returns true.
	 * Child themes can disable this extension using the storefront_lines_and_circles_enabled filter
	 * @return void
	 */
	public function sps_setup() {
		$theme = wp_get_theme();

		if ( 'Storefront' == $theme->name || 'storefront' == $theme->template && apply_filters( 'storefront_lines_and_circles_supported', true ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'sps_styles' ), 999 );
			add_action( 'woocommerce_single_product_summary', array( $this, 'sps_product_sharing' ), 70 );

			// Hide the 'More' section in the customizer
			add_filter( 'storefront_customizer_more', '__return_false' );
		}
	}

	/**
	 * Enqueue CSS.
	 * @since   1.0.0
	 * @return  void
	 */
	public function sps_styles() {
		if ( ! get_theme_mod( 'storefront-pro-wc-prod-share-icons' ) ) { return; }
		wp_enqueue_style( 'sps-styles', plugins_url( '/assets/css/style.css', __FILE__ ) );
	}

	/**
	 * Product sharing links
	 */
	public function sps_product_sharing() {
		if ( ! get_theme_mod( 'storefront-pro-wc-prod-share-icons' ) ) { return; }
		$product_title 	= get_the_title();
		$product_url	= get_permalink();
		$product_img	= wp_get_attachment_url( get_post_thumbnail_id() );

		$facebook_url 	= 'https://www.facebook.com/sharer/sharer.php?u=' . $product_url;
		$twitter_url	= 'https://twitter.com/intent/tweet?status=' . rawurlencode( $product_title ) . '+' . $product_url;
		$pinterest_url	= 'https://pinterest.com/pin/create/bookmarklet/?media=' . $product_img . '&url=' . $product_url . '&is_video=false&description=' . rawurlencode( $product_title );
		$email_url		= 'mailto:?subject=' . rawurlencode( $product_title ) . '&body=' . $product_url;
		?>
		<div class="storefront-product-sharing">
			<ul>
				<li class="twitter"><a href="<?php echo esc_url( $twitter_url ); ?>"><span><?php _e( 'Share on Twitter', 'storefront-product-sharing' ); ?></span></a></li>
				<li class="facebook"><a href="<?php echo esc_url( $facebook_url ); ?>"><span><?php _e( 'Share on Facebook', 'storefront-product-sharing' ); ?></span></a></li>
				<li class="pinterest"><a href="<?php echo esc_url( $pinterest_url ); ?>"><span><?php _e( 'Pin this product', 'storefront-product-sharing' ); ?></span></a></li>
				<li class="email"><a href="<?php echo esc_url( $email_url ); ?>"><span><?php _e( 'Share via Email', 'storefront-product-sharing' ); ?></span></a></li>
			</ul>
		</div>
		<?php
	}
} // End Class
