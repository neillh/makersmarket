<?php
/**
 * Created by PhpStorm.
 * User: Shramee Srivastav <shramee.srivastav@gmail.com>
 * Date: 27/4/15
 * Time: 5:36 PM
 */


/**
 * Storefront_Pro_Admin Class
 *
 * @class Storefront_Pro_Admin
 * @version	1.0.0
 * @since 1.0.0
 * @package	Storefront_Pro
 */
final class Storefront_Pro_Admin extends Storefront_Pro_Abstract {

	/**
	 * The customizer control render object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $customizer;

	/**
	 * Called by parent::__construct
	 * Do initialization here
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function init(){

		//Enqueue scripts and styles
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 999 );
		//Customizer fields renderer
		$this->customizer = new Storefront_Pro_Customizer_Fields( $this->token, $this->plugin_path, $this->plugin_url );
		//Customize register
		add_action( $this->token . '-sections-filter-args', array( $this, 'filter_sections' ) );
		//Customize register
		add_action( $this->token . '-customize-register', array( $this, 'create_panels' ), 11 );
		//Customize register
		add_action( 'customize_register', array( $this->customizer, 'sfp_customize_register' ), 999 );
		//Customize preview init script
		add_action( 'customize_preview_init', array( $this, 'sfp_customize_preview_js' ) );
		//Admin notices
		add_action( 'admin_notices', array( $this, 'sfp_customizer_notice' ) );
		//Reset all Storefront pro options
		add_action( 'wp_ajax_storefront_pro_reset', array( $this, 'reset_all' ) );
		add_action( 'wp_ajax_storefront_pro_export', array( $this, 'export' ) );
		add_action( 'wp_ajax_storefront_pro_import', array( $this, 'import' ) );
		add_action( 'admin_bar_menu', array( $this, 'add_item' ), 999 );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	function admin_menu() {
		add_theme_page(
			__( 'Storefront Pro', SFP_TKN ),
			__( 'Storefront Pro', SFP_TKN ),
			'manage_options',
			'storefront-pro',
			function() { include dirname( __FILE__ ) . '/admin-tpl/settings-page.php'; }
		);
	}

	/**
	 * @param WP_Admin_Bar $admin_bar
	 */
	function add_item( $admin_bar ) {
		global $post;
		if ( function_exists('is_product') && is_product() ) {
			$url = urlencode( get_permalink( $post->ID ) . "?post_id={$post->ID}" );
			$args = array(
				'id'    => 'page-custo-link',
				'title' => __( 'Customize Product', SFP_TKN ),
				'href'  => admin_url( "customize.php?post_id={$post->ID}&autofocus[panel]=lib-pootle-page-customizer&url=" . $url ),
				'meta'  => array(
					'title' => __( 'Customize this page in customizer', SFP_TKN ), // Text will be shown on hovering
				),
			);
			$admin_bar->add_menu( $args );
		}
	}

	/**
	 * Resets all Storefront Pro options
	 * @action wp_ajax_storefront_pro_reset
	 */
	public function reset_all(){
		$redirect = filter_input( INPUT_GET, 'redirect' );
		if ( $redirect ) {
			$fields = storefront_pro_fields();
			foreach ( $fields as $f ) {
				$id = $f['id'];
				remove_theme_mod( "{$this->token}-{$id}" );
			}
			$this->add_notice( '<p>' . __( 'All Storefront options have been successfully reset.', SFP_TKN ) . '</p>' );
			header( 'Location:' . $redirect );
		}
	}

	/**
	 * Resets all Storefront Pro options
	 * @action wp_ajax_storefront_pro_reset
	 */
	public function export() {
		wp_send_json( get_theme_mods() );
	}

	/**
	 * Resets all Storefront Pro options
	 * @action wp_ajax_storefront_pro_reset
	 */
	public function import() {
		$json = filter_input( INPUT_POST, 'json' );
		$response = array(
			'msg'  => __( 'No data recieved.', SFP_TKN ),
			'type' => 'error',
		);

		if ( $json ) {
			$mods = json_decode( $json, 'array' );
			$response[ 'msg' ] = __( 'Error parsing the file contents.', SFP_TKN );
			if ( $mods ) {
				$theme = get_option( 'stylesheet' );
				$success = update_option( "theme_mods_$theme", $mods );
				$response[ 'msg' ] = __( 'Could not save settings from data.', SFP_TKN );
				if ( $success ) {
					$response[ 'msg' ] = __( 'Successfully imported settings from the file.', SFP_TKN );
					$response[ 'type' ] = 'success';
				}
			}
		}

		wp_send_json( $response );
	}

	public function enqueue() {
		global $pagenow;
		if ( 'nav-menus.php' == $pagenow ) {
			wp_enqueue_script( 'sfp-admin-menu', $this->plugin_url . '/assets/js/admin-menu.js', array( 'jquery' ), SFP_VERSION );
			wp_enqueue_script( 'sfp-fa-picker', $this->plugin_url . '/assets/js/fa-picker.js', array( 'jquery' ), SFP_VERSION );
			wp_enqueue_style( 'sfp-fa-picker', $this->plugin_url . '/assets/js/fa-picker.css', array(), SFP_VERSION );
			wp_enqueue_style( 'sfp-admin-menu', $this->plugin_url . '/assets/css/admin-menu.css', array(), SFP_VERSION );
			wp_enqueue_style( 'fontawesome', 'https://use.fontawesome.com/releases/v5.5.0/css/all.css', array(), '5.5.0' );

			wp_localize_script( 'sfp-fa-picker', 'sfpFAPickerL10n', [
				'cancel' => __( 'Cancel', SFP_TKN ),
				'accept' => __( 'Accept', SFP_TKN ),
			] );

		}
	}

	/**
	 * Filters the section arguments for making them sit in panels
	 * @param array $args Section arguments
	 * @filter storefront-pro-sections-filter-args
	 * @return array Arguments
	 */
	public function filter_sections ( $args ) {
		if ( in_array( $args['title'], array( __( 'Primary Navigation', SFP_TKN ), __( 'Secondary Navigation', SFP_TKN ), __( 'Header elements', SFP_TKN ), ) ) ) {
			$args['panel'] = 'sf-pro-header';
			if ( __( 'Mobile menu', SFP_TKN ) == $args['title'] ) {
				$args['description'] = __( 'Mobile menu customizations require menu assigned to Handheld Menu location', SFP_TKN );
			}
		} else if ( in_array( $args['title'], array( __( 'Product Page', SFP_TKN ), __( 'Shop', SFP_TKN ), __( 'Checkout', SFP_TKN ), ) ) ) {
			$args['panel'] = 'sf-pro-wc';
		} else if ( __( 'Shop Header Hero', SFP_TKN ) == $args['title'] ) {
			$args['title'] = __( 'Header Hero', SFP_TKN );
			$args['panel'] = 'sf-pro-wc';
		} else if ( __( 'Widgets', SFP_TKN ) == $args['title'] ) {
			$args['panel'] = 'sf-pro-footer';
		} else if ( in_array( $args['title'], array( __( 'Mobile Fixed Footer', SFP_TKN ), __( 'Mobile menu', SFP_TKN ), ) ) ) {
			$args['panel'] = 'sf-pro-mobile';
		} else if ( in_array( $args['title'], array( __( 'Content Elements', SFP_TKN ), ) ) ) {
			$args['panel'] = 'sf-pro-content';
		}

		return $args;
	}

	/**
	 * Filters the section arguments for making them sit in panels
	 * @param WP_Customize_Manager $man
	 * @filter storefront-pro-sections-filter-args
	 */
	public function create_panels ( $man ) {

		$man->add_control( new Storefront_Custom_Radio_Image_Control( $man, 'storefront_layout', array(
			'settings'		=> 'storefront_layout',
			'section'		=> 'storefront_layout',
			'label'			=> __( 'General Layout', 'storefront' ),
			'priority'		=> 1,
			'choices'		=> array(
				'right' 		=> get_template_directory_uri() . '/assets/images/customizer/controls/2cr.png',
				'left' 			=> get_template_directory_uri() . '/assets/images/customizer/controls/2cl.png',
				'full' 			=> $this->plugin_url . '/assets/img/admin/full.png',
			)
		) ) );

		/* Footer background */
		$man->add_setting( 'storefront_footer_gradient_color1', array(
			'default'           	=> apply_filters( 'storefront_default_footer_background_color', '#f0f0f0' ),
			'sanitize_callback' 	=> 'esc_attr',
		) );
		$man->add_control( new Lib_Customize_Alpha_Color_Control( $man, 'storefront_footer_gradient_color1', array(
			'label'	   				=> __( 'First Gradient color', 'storefront' ),
			'section'  				=> 'storefront_footer',
			'settings' 				=> 'storefront_footer_gradient_color1',
			'priority'				=> 2.5,
		) ) );
		$man->add_setting( 'storefront_footer_gradient_color2', array(
			'default'           	=> apply_filters( 'storefront_default_footer_background_color2', '#f0f0f0' ),
			'sanitize_callback' 	=> 'esc_attr',
		) );
		$man->add_control( new Lib_Customize_Alpha_Color_Control( $man, 'storefront_footer_gradient_color2', array(
			'label'	   				=> __( 'Second Gradient color', 'storefront' ),
			'section'  				=> 'storefront_footer',
			'settings' 				=> 'storefront_footer_gradient_color2',
			'priority'				=> 2.59,
		) ) );

		$man->add_panel( 'sf-pro-header', array(
			'title' => __( 'Header and Navigation', SFP_TKN ),
			'priority' => 23,
		) );

		$man->add_panel( 'sf-pro-mobile', array(
			'title' => __( 'Mobile', SFP_TKN ),
			'priority' => 23,
		) );

		$man->add_panel( 'sf-pro-content', array(
			'title' => __( 'Content', SFP_TKN ),
			'priority' => 25,
		) );

		$man->add_panel( 'sf-pro-blog', array(
			'title' => __( 'Posts', SFP_TKN ),
			'priority' => 30,
		) );

		$man->add_panel( 'sf-pro-wc', array(
			'title' => __( 'WooCommerce', SFP_TKN ),
			'priority' => 32,
		) );

		$man->add_panel( 'sf-pro-footer', array(
			'title' => __( 'Footer', SFP_TKN ),
			'priority' => 34,
		) );

		$man->add_setting( 'sfp_post_layout', array(
			'default'       => '',
			'type'          => 'option'
		) );

		$man->add_control( new Storefront_Custom_Radio_Image_Control( $man, 'sfp_post_layout', array(
			'settings'		=> 'sfp_post_layout',
			'section'		=> 'storefront_single_post',
			'label'			=> __( 'Post page Layout', 'storefront' ),
			'priority'		=> 7,
			'default'       => '',
			'choices'		=> array(
				'' => SFP_URL . '/assets/img/admin/layout-default.png',
				'1' => SFP_URL . '/assets/img/admin/layout-full-image.png',
				'2' => SFP_URL . '/assets/img/admin/layout-title-in-image.png',
			)
		) ) );

		$man->add_setting( 'sfp_blog_layout', array(
			'default'       => '',
			'type'          => 'option'
		) );

		$man->add_section( 'storefront_archive', array(
			'title' => __( 'Posts Page', SFP_TKN ),
			'panel' => 'sf-pro-blog',
			'priority' => 7,
		) );

		$man->add_section( 'storefront_single_post', array(
			'title' => __( 'Single post', SFP_TKN ),
			'panel' => 'sf-pro-blog',
			'priority' => 7,
		) );

		$man->add_section( 'storefront_footer', array(
			'title' => __( 'Layout', SFP_TKN ),
			'panel' => 'sf-pro-footer',
			'priority' => 7,
		) );

		$this->remove_control( $man, 'storefront_header_background_color' );
		$this->remove_control( $man, 'storefront_header_text_color' );
		$this->remove_control( $man, 'storefront_header_link_color' );
		$this->remove_control( $man, 'woocommerce_catalog_columns' );
		$this->remove_control( $man, 'woocommerce_catalog_rows' );

		$man->get_section( 'header_image' )->title = __( 'Header Elements', SFP_TKN );
		$man->get_section( 'header_image' )->panel = 'sf-pro-header';
		$man->get_section( 'header_image' )->priority = 7;
		$this->section_position( $man, 'background_image', 'sf-pro-content', 7 );

		$this->section_position( $man, 'woocommerce_checkout', 'sf-pro-wc', 70 );
		$this->section_position( $man, 'woocommerce_store_notice', 'sf-pro-wc', 70 );
		$this->section_position( $man, 'woocommerce_product_catalog', 'sf-pro-wc', 50 );
		$this->section_position( $man, 'woocommerce_product_images', 'sf-pro-wc', 30 );

		$this->section_position( $man, 'storefront_typography', 'sf-pro-content' );
		$this->section_position( $man, 'storefront_buttons', 'sf-pro-content' );
		$this->section_position( $man, 'storefront_layout', 'sf-pro-content' );
		$this->section_position( $man, 'sfb_section', 'sf-pro-footer' );
		$this->section_position( $man, 'shb_section', 'sf-pro-header' );

		if ( $man->get_control( 'storefront_sticky_add_to_cart' ) ) {
			$man->get_control( 'storefront_sticky_add_to_cart' )->section  = 'storefront-pro-section-product-page';
			$man->get_control( 'storefront_sticky_add_to_cart' )->priority = 70;
			$man->get_control( 'storefront_product_pagination' )->section  = 'storefront-pro-section-product-page';
			$man->get_control( 'storefront_product_pagination' )->priority = 75;
		}
	}

	private function section_position( WP_Customize_Manager $man, $section, $panel, $priority = '' ) {
		if ( $man->get_section( $section ) ) {
			$man->get_section( $section )->panel = $panel;
			if ( $priority ) {
				$man->get_section( $section )->priority = $priority;
			}
		}
	}

	private function remove_control( WP_Customize_Manager $man, $control ) {
		if ( $man->get_control( $control ) ) $man->get_control( $control )->section = 'nonexistent';
	}

	/**
	 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
	 *
	 * @since  1.0.0
	 */
	public function sfp_customize_preview_js() {
		wp_enqueue_script( 'sfp-customizer', $this->plugin_url . '/assets/js/customizer.min.js', array( 'customize-preview' ), SFP_VERSION, true );
	}

	/**
	 * Admin notice
	 * Checks the notice setup in install(). If it exists display it then delete the option so it's not displayed again.
	 * @since   1.0.0
	 * @return  void
	 */
	public function sfp_customizer_notice() {
		if ( $notices = get_option( 'sfp_activation_notice' ) ) {

			foreach ( $notices as $notice ) {
				echo '<div class="notice is-dismissible updated">' . $notice . '</div>';
			}

			delete_option( 'sfp_activation_notice' );
		}
	}

	/**
	 * Adds an admin notice
	 * @since   1.0.0
	 * @return  void
	 */
	public function add_notice( $notice ) {
		$notices = get_option( 'sfp_activation_notice', array() );

		$notices[] = $notice;

		update_option( 'sfp_activation_notice', $notices );
	}

} // End class