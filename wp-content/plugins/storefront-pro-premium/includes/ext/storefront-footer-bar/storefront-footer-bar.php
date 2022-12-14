<?php
/**
 * Plugin Name:			Storefront Footer Bar
 * Plugin URI:			http://woothemes.com/storefront/
 * Description:			Add a full width widgetised region above the default Storefront footer widget area.
 * Version:				1.0.1
 * Author:				WooThemes
 * Author URI:			http://woothemes.com/
 * Requires at least:	4.0.0
 * Tested up to:		4.2.2
 *
 * Text Domain: storefront-footer-bar
 * Domain Path: /languages/
 *
 * @package Storefront_Footer_Bar
 * @category Core
 * @author James Koster
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Returns the main instance of Storefront_Footer_Bar to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Storefront_Footer_Bar
 */
function Storefront_Footer_Bar() {
	return Storefront_Footer_Bar::instance();
} // End Storefront_Footer_Bar()

Storefront_Footer_Bar();

/**
 * Main Storefront_Footer_Bar Class
 *
 * @class Storefront_Footer_Bar
 * @version	1.0.0
 * @since 1.0.0
 * @package	Storefront_Footer_Bar
 */
final class Storefront_Footer_Bar {
	/**
	 * Storefront_Footer_Bar The single instance of Storefront_Footer_Bar.
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
		$this->token 			= 'storefront-footer-bar';
		$this->plugin_url 		= plugin_dir_url( __FILE__ );
		$this->plugin_path 		= plugin_dir_path( __FILE__ );
		$this->version 			= '1.0.1';

		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'sfb_load_plugin_textdomain' ) );

		add_action( 'init', array( $this, 'sfb_setup' ) );
	}

	/**
	 * Main Storefront_Footer_Bar Instance
	 *
	 * Ensures only one instance of Storefront_Footer_Bar is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Storefront_Footer_Bar()
	 * @return Main Storefront_Footer_Bar instance
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
	public function sfb_load_plugin_textdomain() {
		load_plugin_textdomain( 'storefront-footer-bar', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
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
	public function sfb_setup() {
		$theme = wp_get_theme();

		if ( 'Storefront' == $theme->name || 'storefront' == $theme->template && apply_filters( 'storefront_footer_bar_supported', true ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'sfb_styles' ), 999 );
			add_action( 'customize_register', array( $this, 'sfb_customize_register' ) );
			add_action( 'customize_preview_init', array( $this, 'sfb_customize_preview_js' ) );
			add_action( 'storefront_before_footer', array( $this, 'sfb_footer_bar' ), 10 );

			$this->sfb_register_widget_area();
		}
	}

	/**
	 * Storefront Footer Bar Setup
	 * Set up the extension, create the widget region etc.
	 */
	function sfb_register_widget_area() {
		register_sidebar( array(
			'name'          => __( 'Footer Bar', 'storefront-footer-bar' ),
			'id'            => 'footer-bar-1',
			'description'   => '',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
	}

	/**
	 * Customizer Controls and settings
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function sfb_customize_register( $wp_customize ) {
		/**
	     * Add a new section
	     */
        $wp_customize->add_section( 'sfb_section' , array(
		    'title'      	=> __( 'Footer Bar', 'storefront-extention-boilerplate' ),
		    'priority'   	=> 55,
		) );

		$wp_customize->add_setting( 'sfp_footer_bar', array(
			'default'       => '',
			'type'          => 'option'
		) );
		$wp_customize->add_control( 'sfp_footer_bar', array(
			'section'		=> 'sfb_section',
			'label'			=> __( 'Enable footer bar', 'storefront' ),
			'type'		=> 'checkbox',
			'priority'		=> 5,
		) );

		/**
		 * Background image
		 */
		$wp_customize->add_setting( 'sfb_background_image', array(
			'default'			=> '',
			'sanitize_callback'	=> 'esc_url_raw',
		) );

		$wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, 'sfb_background_image', array(
			'label'			=> __( 'Background image', 'storefront-footer-bar' ),
			'section'		=> 'sfb_section',
			'settings'		=> 'sfb_background_image',
			'priority'		=> 10,
		) ) );

		/**
		 * Background color
		 */
		$wp_customize->add_setting( 'sfb_background_color', array(
			'default'			=> apply_filters( 'storefront_default_header_background_color', '#2c2d33' ),
			'sanitize_callback'	=> 'sanitize_hex_color',
			'transport'			=> 'postMessage', // Refreshes instantly via js. See customizer.js. (default = refresh).
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sfb_background_color', array(
			'label'			=> __( 'Background color', 'storefront-footer-bar' ),
			'section'		=> 'sfb_section',
			'settings'		=> 'sfb_background_color',
			'priority'		=> 15,
		) ) );

		/**
		 * Heading color
		 */
		$wp_customize->add_setting( 'sfb_heading_color', array(
			'default'			=> apply_filters( 'sfb_default_heading_color', '#ffffff' ),
			'sanitize_callback'	=> 'sanitize_hex_color',
			'transport'			=> 'postMessage', // Refreshes instantly via js. See customizer.js. (default = refresh).
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sfb_heading_color', array(
			'label'			=> __( 'Heading color', 'storefront-footer-bar' ),
			'section'		=> 'sfb_section',
			'settings'		=> 'sfb_heading_color',
			'priority'		=> 20,
		) ) );

		/**
		 * Text color
		 */
		$wp_customize->add_setting( 'sfb_text_color', array(
			'default'			=> apply_filters( 'storefront_default_header_text_color', '#9aa0a7' ),
			'sanitize_callback'	=> 'sanitize_hex_color',
			'transport'			=> 'postMessage', // Refreshes instantly via js. See customizer.js. (default = refresh).
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sfb_text_color', array(
			'label'			=> __( 'Text color', 'storefront-footer-bar' ),
			'section'		=> 'sfb_section',
			'settings'		=> 'sfb_text_color',
			'priority'		=> 30,
		) ) );

		/**
		 * Link color
		 */
		$wp_customize->add_setting( 'sfb_link_color', array(
			'default'			=> apply_filters( 'storefront_default_header_link_color', '#ffffff' ),
			'sanitize_callback'	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sfb_link_color', array(
			'label'			=> __( 'Link color', 'storefront-footer-bar' ),
			'section'		=> 'sfb_section',
			'settings'		=> 'sfb_link_color',
			'priority'		=> 40,
		) ) );
	}

	/**
	 * Enqueue CSS and custom styles.
	 * @since   1.0.0
	 * @return  void
	 */
	public function sfb_styles() {
		wp_enqueue_style( 'sfb-styles', plugins_url( '/assets/css/style.css', __FILE__ ) );

		$footer_bar_bg_image 	= esc_url( get_theme_mod( 'sfb_background_image', '' ) );
		$footer_bar_bg 			= get_theme_mod( 'sfb_background_color', apply_filters( 'storefront_default_header_background_color', '#2c2d33' ) );
		$footer_bar_text 		= get_theme_mod( 'sfb_text_color', apply_filters( 'storefront_default_header_text_color', '#9aa0a7' ) );
		$footer_bar_headings 	= get_theme_mod( 'sfb_heading_color', apply_filters( 'sfb_default_heading_color', '#ffffff' ) );
		$footer_bar_links 		= get_theme_mod( 'sfb_link_color', apply_filters( 'storefront_default_header_link_color', '#ffffff' ) );
		$bg_image = $footer_bar_bg_image ? 'background-image: url(' . $footer_bar_bg_image . ');' : '';
		$sfb_style = '
		.sfb-footer-bar {
			background-color: ' . $footer_bar_bg . ';' .
			$bg_image . '
		}

		.sfb-footer-bar .widget {
			color: ' . $footer_bar_text . ';
		}

		.sfb-footer-bar .widget h1,
		.sfb-footer-bar .widget h2,
		.sfb-footer-bar .widget h3,
		.sfb-footer-bar .widget h4,
		.sfb-footer-bar .widget h5,
		.sfb-footer-bar .widget h6 {
			color: ' . $footer_bar_headings . ';
		}

		.sfb-footer-bar .widget a {
			color: ' . $footer_bar_links . ';
		}';

		wp_add_inline_style( 'sfb-styles', $sfb_style );
	}

	/**
	 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
	 *
	 * @since  1.0.0
	 */
	public function sfb_customize_preview_js() {
		wp_enqueue_script( 'sfb-customizer', plugins_url( '/assets/js/customizer.min.js', __FILE__ ), array( 'customize-preview' ), '1.1', true );
	}

	/**
	 * Footer bar display
	 */
	public function sfb_footer_bar() {
		if ( is_active_sidebar( 'footer-bar-1' ) && get_option( 'sfp_footer_bar' ) ) {
			echo '<div class="sfb-footer-bar"><div class="col-full">';
				dynamic_sidebar( 'footer-bar-1' );
			echo '</div></div>';
		}
	}
} // End Class
