<?php
/*
Plugin Name: WPC Variation Swatches for WooCommerce
Plugin URI: https://wpclever.net/
Description: WooCommerce Variation Swatches by WPClever
Version: 2.3.4
Author: WPClever
Author URI: https://wpclever.net
Text Domain: wpc-variation-swatches
Domain Path: /languages/
Requires at least: 4.0
Tested up to: 6.1
WC requires at least: 3.0
WC tested up to: 7.5
*/

use Automattic\WooCommerce\Utilities\FeaturesUtil;

defined( 'ABSPATH' ) || exit;

! defined( 'WPCVS_VERSION' ) && define( 'WPCVS_VERSION', '2.3.4' );
! defined( 'WPCVS_FILE' ) && define( 'WPCVS_FILE', __FILE__ );
! defined( 'WPCVS_URI' ) && define( 'WPCVS_URI', plugin_dir_url( __FILE__ ) );
! defined( 'WPCVS_DIR' ) && define( 'WPCVS_DIR', plugin_dir_path( __FILE__ ) );
! defined( 'WPCVS_REVIEWS' ) && define( 'WPCVS_REVIEWS', 'https://wordpress.org/support/plugin/wpc-variation-swatches/reviews/?filter=5' );
! defined( 'WPCVS_CHANGELOG' ) && define( 'WPCVS_CHANGELOG', 'https://wordpress.org/plugins/wpc-variation-swatches/#developers' );
! defined( 'WPCVS_DISCUSSION' ) && define( 'WPCVS_DISCUSSION', 'https://wordpress.org/support/plugin/wpc-variation-swatches' );
! defined( 'WPC_URI' ) && define( 'WPC_URI', WPCVS_URI );

include 'includes/wpc-dashboard.php';
include 'includes/wpc-menu.php';
include 'includes/wpc-kit.php';

if ( ! function_exists( 'wpcvs_init' ) ) {
	add_action( 'plugins_loaded', 'wpcvs_init', 11 );

	function wpcvs_init() {
		// load text-domain
		load_plugin_textdomain( 'wpc-variation-swatches', false, basename( __DIR__ ) . '/languages/' );

		if ( ! function_exists( 'WC' ) || ! version_compare( WC()->version, '3.0', '>=' ) ) {
			add_action( 'admin_notices', 'wpcvs_notice_wc' );

			return;
		}

		if ( ! class_exists( 'WPCleverWpcvs' ) && class_exists( 'WC_Product' ) ) {
			class WPCleverWpcvs {
				protected static $settings = [];
				protected static $localization = [];
				protected static $instance = null;

				public static function instance() {
					if ( is_null( self::$instance ) ) {
						self::$instance = new self();
					}

					return self::$instance;
				}

				function __construct() {
					self::$settings     = (array) get_option( 'wpcvs_settings', [] );
					self::$localization = (array) get_option( 'wpcvs_localization', [] );

					add_action( 'init', [ $this, 'init' ] );
					add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ] );
					add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );

					// add field for attributes
					add_filter( 'product_attributes_type_selector', [ $this, 'type_selector' ] );

					$attribute_taxonomies = wc_get_attribute_taxonomies();

					foreach ( $attribute_taxonomies as $attribute_taxonomy ) {
						add_action( 'pa_' . $attribute_taxonomy->attribute_name . '_add_form_fields', [
							$this,
							'show_field'
						] );
						add_action( 'pa_' . $attribute_taxonomy->attribute_name . '_edit_form_fields', [
							$this,
							'show_field'
						] );
						add_action( 'create_pa_' . $attribute_taxonomy->attribute_name, [ $this, 'save_field' ] );
						add_action( 'edited_pa_' . $attribute_taxonomy->attribute_name, [ $this, 'save_field' ] );
						add_filter( "manage_edit-pa_{$attribute_taxonomy->attribute_name}_columns", [
							$this,
							'custom_columns'
						] );
						add_filter( "manage_pa_{$attribute_taxonomy->attribute_name}_custom_column", [
							$this,
							'custom_columns_content'
						], 10, 3 );
					}

					add_filter( 'woocommerce_dropdown_variation_attribute_options_html', [
						$this,
						'variation_attribute_options_html'
					], 199, 2 );

					// settings page
					add_action( 'admin_init', [ $this, 'register_settings' ] );
					add_action( 'admin_menu', [ $this, 'admin_menu' ] );

					// settings link
					add_filter( 'plugin_action_links', [ $this, 'wpcvs_action_links' ], 10, 2 );
					add_filter( 'plugin_row_meta', [ $this, 'wpcvs_row_meta' ], 10, 2 );

					// archive page
					if ( self::get_setting( 'archive_enable', 'no' ) === 'yes' ) {
						if ( self::get_setting( 'archive_position', 'before' ) === 'before' ) {
							add_action( 'woocommerce_after_shop_loop_item', [ $this, 'archive' ], 9 );
						} elseif ( self::get_setting( 'archive_position', 'before' ) === 'after' ) {
							add_action( 'woocommerce_after_shop_loop_item', [ $this, 'archive' ], 11 );
						}
					}

					// ajax add to cart
					add_action( 'wp_ajax_wpcvs_add_to_cart', [ $this, 'ajax_add_to_cart' ] );
					add_action( 'wp_ajax_nopriv_wpcvs_add_to_cart', [ $this, 'ajax_add_to_cart' ] );

					// variation
					add_filter( 'woocommerce_available_variation', [ $this, 'available_variation' ], 100, 3 );

					// WPC Smart Messages
					add_filter( 'wpcsm_locations', [ $this, 'wpcsm_locations' ] );

					// HPOS compatibility
					add_action( 'before_woocommerce_init', function () {
						if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
							FeaturesUtil::declare_compatibility( 'custom_order_tables', WPCVS_FILE );
						}
					} );
				}

				public static function get_settings() {
					return apply_filters( 'wpcvs_get_settings', self::$settings );
				}

				public static function get_setting( $name, $default = false ) {
					if ( ! empty( self::$settings ) && isset( self::$settings[ $name ] ) ) {
						$setting = self::$settings[ $name ];
					} else {
						$setting = get_option( 'wpcvs_' . $name, $default );
					}

					return apply_filters( 'wpcvs_get_setting', $setting, $name, $default );
				}

				public static function localization( $key = '', $default = '' ) {
					$str = '';

					if ( ! empty( $key ) && ! empty( self::$localization[ $key ] ) ) {
						$str = self::$localization[ $key ];
					} elseif ( ! empty( $default ) ) {
						$str = $default;
					}

					return apply_filters( 'wpcvs_localization_' . $key, $str );
				}

				function init() {
					add_shortcode( 'wpcvs_archive', [ $this, 'shortcode_archive' ] );
				}

				function shortcode_archive( $attrs ) {
					$attrs = shortcode_atts( [
						'id' => null,
					], $attrs, 'wpcvs_archive' );

					ob_start();
					$this->archive( $attrs['id'] );

					return ob_get_clean();
				}

				function ajax_add_to_cart() {
					if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'wpcvs_nonce' ) ) {
						die( esc_html__( 'Permissions check failed!', 'wpc-variation-swatches' ) );
					}

					$product_id   = (int) $_POST['product_id'];
					$variation_id = (int) $_POST['variation_id'];
					$quantity     = (float) $_POST['quantity'];
					$variation    = (array) json_decode( stripslashes( $_POST['attributes'] ) );

					if ( $product_id && $variation_id ) {
						$item_key = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );

						if ( ! empty( $item_key ) ) {
							WC_AJAX::get_refreshed_fragments();
						}
					}

					$data = [
						'error'       => true,
						'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
					];

					wp_send_json( $data );
				}

				function available_variation( $available, $variable, $variation ) {
					$thumbnail_id   = $available['image_id'];
					$thumbnail_size = apply_filters( 'woocommerce_thumbnail_size', 'woocommerce_thumbnail' );
					$thumbnail_src  = wp_get_attachment_image_src( $thumbnail_id, $thumbnail_size );

					if ( $thumbnail_id ) {
						$available['image']['wpcvs_src']    = $thumbnail_src[0];
						$available['image']['wpcvs_srcset'] = function_exists( 'wp_get_attachment_image_srcset' ) ? wp_get_attachment_image_srcset( $thumbnail_id, $thumbnail_size ) : false;
						$available['image']['wpcvs_sizes']  = function_exists( 'wp_get_attachment_image_sizes' ) ? wp_get_attachment_image_sizes( $thumbnail_id, $thumbnail_size ) : false;
					}

					return $available;
				}

				function scripts() {
					if ( self::get_setting( 'tooltip', 'top' ) !== 'no' ) {
						wp_enqueue_style( 'hint', WPCVS_URI . 'assets/css/hint.css' );
					}

					if ( self::get_setting( 'archive_enable', 'no' ) === 'yes' ) {
						wp_enqueue_script( 'wc-add-to-cart-variation' );
					}

					wp_enqueue_style( 'wpcvs-frontend', WPCVS_URI . 'assets/css/frontend.css', [], WPCVS_VERSION );
					wp_enqueue_script( 'wpcvs-frontend', WPCVS_URI . 'assets/js/frontend.js', [ 'jquery' ], WPCVS_VERSION, true );

					$archive_product = apply_filters( 'wpcvs_archive_product_selector', '' );

					if ( empty( $archive_product ) ) {
						$archive_product = self::get_setting( 'archive_product', '.product' );
					}

					$archive_image = apply_filters( 'wpcvs_archive_image_selector', '' );

					if ( empty( $archive_image ) ) {
						$archive_image = self::get_setting( 'archive_image', '.attachment-woocommerce_thumbnail' );
					}

					$archive_atc = apply_filters( 'wpcvs_archive_atc_selector', '' );

					if ( empty( $archive_atc ) ) {
						$archive_atc = self::get_setting( 'archive_atc', '.add_to_cart_button' );
					}

					$archive_atc_text = apply_filters( 'wpcvs_archive_atc_text_selector', '' );

					if ( empty( $archive_atc_text ) ) {
						$archive_atc_text = self::get_setting( 'archive_atc_text', '.add_to_cart_button' );
					}

					wp_localize_script( 'wpcvs-frontend', 'wpcvs_vars', [
							'ajax_url'         => admin_url( 'admin-ajax.php' ),
							'nonce'            => wp_create_nonce( 'wpcvs_nonce' ),
							'second_click'     => self::get_setting( 'second_click', 'no' ),
							'archive_enable'   => self::get_setting( 'archive_enable', 'no' ),
							'archive_product'  => ! empty( $archive_product ) ? esc_attr( $archive_product ) : '.product',
							'archive_image'    => ! empty( $archive_image ) ? esc_attr( $archive_image ) : '.attachment-woocommerce_thumbnail',
							'archive_atc'      => ! empty( $archive_atc ) ? esc_attr( $archive_atc ) : '.add_to_cart_button',
							'archive_atc_text' => ! empty( $archive_atc_text ) ? esc_attr( $archive_atc_text ) : '.add_to_cart_button',
							'add_to_cart'      => apply_filters( 'wpcvs_add_to_cart', self::localization( 'add_to_cart', esc_html__( 'Add to cart', 'wpc-variation-swatches' ) ) ),
							'select_options'   => apply_filters( 'wpcvs_select_options', self::localization( 'select_options', esc_html__( 'Select options', 'wpc-variation-swatches' ) ) ),
							'view_cart'        => apply_filters( 'wpcvs_view_cart', '<a href="' . wc_get_cart_url() . '" class="added_to_cart wc-forward" title="' . esc_attr( self::localization( 'view_cart', esc_html__( 'View cart', 'wpc-variation-swatches' ) ) ) . '">' . esc_html( self::localization( 'view_cart', esc_html__( 'View cart', 'wpc-variation-swatches' ) ) ) . '</a>' ),
						]
					);
				}

				function admin_scripts() {
					$args = [
						'placeholder_img' => wc_placeholder_img_src()
					];
					wp_enqueue_script( 'wpcvs-backend', WPCVS_URI . 'assets/js/backend.js', [
						'jquery',
						'wp-color-picker'
					], WPCVS_VERSION, true );
					wp_localize_script( 'wpcvs-backend', 'wpcvs_vars', $args );
				}

				function register_settings() {
					// settings
					register_setting( 'wpcvs_settings', 'wpcvs_settings' );
					// localization
					register_setting( 'wpcvs_localization', 'wpcvs_localization' );
				}

				function admin_menu() {
					add_submenu_page( 'wpclever', esc_html__( 'WPC Variation Swatches', 'wpc-variation-swatches' ), esc_html__( 'Variation Swatches', 'wpc-variation-swatches' ), 'manage_options', 'wpclever-wpcvs', [
						$this,
						'admin_menu_content'
					] );
				}

				function admin_menu_content() {
					$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'settings';
					?>
					<div class="wpclever_settings_page wrap">
						<h1 class="wpclever_settings_page_title"><?php echo esc_html__( 'WPC Variation Swatches', 'wpc-variation-swatches' ) . ' ' . WPCVS_VERSION; ?></h1>
						<div class="wpclever_settings_page_desc about-text">
							<p>
								<?php printf( esc_html__( 'Thank you for using our plugin! If you are satisfied, please reward it a full five-star %s rating.', 'wpc-variation-swatches' ), '<span style="color:#ffb900">&#9733;&#9733;&#9733;&#9733;&#9733;</span>' ); ?>
								<br/>
								<a href="<?php echo esc_url( WPCVS_REVIEWS ); ?>" target="_blank"><?php esc_html_e( 'Reviews', 'wpc-variation-swatches' ); ?></a> |
								<a href="<?php echo esc_url( WPCVS_CHANGELOG ); ?>" target="_blank"><?php esc_html_e( 'Changelog', 'wpc-variation-swatches' ); ?></a> |
								<a href="<?php echo esc_url( WPCVS_DISCUSSION ); ?>" target="_blank"><?php esc_html_e( 'Discussion', 'wpc-variation-swatches' ); ?></a>
							</p>
						</div>
						<?php if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) { ?>
							<div class="notice notice-success is-dismissible">
								<p><?php esc_html_e( 'Settings updated.', 'wpc-variation-swatches' ); ?></p>
							</div>
						<?php } ?>
						<div class="wpclever_settings_page_nav">
							<h2 class="nav-tab-wrapper">
								<a href="<?php echo admin_url( 'admin.php?page=wpclever-wpcvs&tab=settings' ); ?>" class="<?php echo esc_attr( $active_tab === 'settings' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
									<?php esc_html_e( 'Settings', 'wpc-variation-swatches' ); ?>
								</a>
								<a href="<?php echo admin_url( 'admin.php?page=wpclever-wpcvs&tab=localization' ); ?>" class="<?php echo esc_attr( $active_tab === 'localization' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
									<?php esc_html_e( 'Localization', 'wpc-variation-swatches' ); ?>
								</a>
								<a href="<?php echo admin_url( 'admin.php?page=wpclever-kit' ); ?>" class="nav-tab">
									<?php esc_html_e( 'Essential Kit', 'wpc-variation-swatches' ); ?>
								</a>
							</h2>
						</div>
						<div class="wpclever_settings_page_content">
							<?php if ( $active_tab === 'settings' ) {
								$button_default   = self::get_setting( 'button_default', 'no' );
								$second_click     = self::get_setting( 'second_click', 'no' );
								$tooltip          = self::get_setting( 'tooltip', 'top' );
								$style            = self::get_setting( 'style', 'square' );
								$archive_enable   = self::get_setting( 'archive_enable', 'no' );
								$archive_position = self::get_setting( 'archive_position', 'before' );
								$archive_limit    = self::get_setting( 'archive_limit', '10' );
								?>
								<form method="post" action="options.php">
									<table class="form-table">
										<tr class="heading">
											<th colspan="2">
												<?php esc_html_e( 'General', 'wpc-variation-swatches' ); ?>
											</th>
										</tr>
										<tr>
											<th><?php esc_html_e( 'Button swatch by default', 'wpc-variation-swatches' ); ?></th>
											<td>
												<select name="wpcvs_settings[button_default]">
													<option value="yes" <?php selected( $button_default, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-variation-swatches' ); ?></option>
													<option value="no" <?php selected( $button_default, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-variation-swatches' ); ?></option>
												</select> <span class="description">
                                                    <?php esc_html_e( 'Turn the default type to button type.', 'wpc-variation-swatches' ); ?>
                                                </span>
											</td>
										</tr>
										<tr>
											<th><?php esc_html_e( 'Enable second click to undo?', 'wpc-variation-swatches' ); ?></th>
											<td>
												<select name="wpcvs_settings[second_click]">
													<option value="yes" <?php selected( $second_click, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-variation-swatches' ); ?></option>
													<option value="no" <?php selected( $second_click, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-variation-swatches' ); ?></option>
												</select> <span class="description">
                                                    <?php esc_html_e( 'Enable/disable click again to undo the selection on current attribute.', 'wpc-variation-swatches' ); ?>
                                                </span>
											</td>
										</tr>
										<tr>
											<th><?php esc_html_e( 'Tooltip position', 'wpc-variation-swatches' ); ?></th>
											<td>
												<select name="wpcvs_settings[tooltip]">
													<option value="top" <?php selected( $tooltip, 'top' ); ?>><?php esc_html_e( 'Top', 'wpc-variation-swatches' ); ?></option>
													<option value="right" <?php selected( $tooltip, 'right' ); ?>><?php esc_html_e( 'Right', 'wpc-variation-swatches' ); ?></option>
													<option value="bottom" <?php selected( $tooltip, 'bottom' ); ?>><?php esc_html_e( 'Bottom', 'wpc-variation-swatches' ); ?></option>
													<option value="left" <?php selected( $tooltip, 'left' ); ?>><?php esc_html_e( 'Left', 'wpc-variation-swatches' ); ?></option>
													<option value="no" <?php selected( $tooltip, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-variation-swatches' ); ?></option>
												</select>
											</td>
										</tr>
										<tr>
											<th><?php esc_html_e( 'Style', 'wpc-variation-swatches' ); ?></th>
											<td>
												<select name="wpcvs_settings[style]">
													<option value="square" <?php selected( $style, 'square' ); ?>><?php esc_html_e( 'Square', 'wpc-variation-swatches' ); ?></option>
													<option value="rounded" <?php selected( $style, 'rounded' ); ?>><?php esc_html_e( 'Rounded', 'wpc-variation-swatches' ); ?></option>
												</select>
											</td>
										</tr>
										<tr class="heading">
											<th colspan="2">
												<?php esc_html_e( 'Shop/ Archive', 'wpc-variation-swatches' ); ?>
											</th>
										</tr>
										<tr>
											<th><?php esc_html_e( 'Enable', 'wpc-variation-swatches' ); ?></th>
											<td>
												<select name="wpcvs_settings[archive_enable]">
													<option value="yes" <?php selected( $archive_enable, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-variation-swatches' ); ?></option>
													<option value="no" <?php selected( $archive_enable, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-variation-swatches' ); ?></option>
												</select> <span class="description">
                                                    <?php esc_html_e( 'Enable swatches for shop/ archive page.', 'wpc-variation-swatches' ); ?>
                                                </span>
											</td>
										</tr>
										<tr>
											<th><?php esc_html_e( 'Position', 'wpc-variation-swatches' ); ?></th>
											<td>
												<select name="wpcvs_settings[archive_position]">
													<option value="before" <?php selected( $archive_position, 'before' ); ?>><?php esc_html_e( 'Before add to cart button', 'wpc-variation-swatches' ); ?></option>
													<option value="after" <?php selected( $archive_position, 'after' ); ?>><?php esc_html_e( 'After add to cart button', 'wpc-variation-swatches' ); ?></option>
													<option value="none" <?php selected( $archive_position, 'none' ); ?>><?php esc_html_e( 'None', 'wpc-variation-swatches' ); ?></option>
												</select> <span class="description">
                                                    <?php printf( esc_html__( 'Swatches position on archive page. You also can use the shortcode: %s', 'wpc-variation-swatches' ), '<code>[wpcvs_archive]</code>' ); ?>
                                                </span>
											</td>
										</tr>
										<tr>
											<th><?php esc_html_e( 'Limit', 'wpc-variation-swatches' ); ?></th>
											<td>
												<input type="number" min="0" max="500" name="wpcvs_settings[archive_limit]" value="<?php echo esc_attr( $archive_limit ); ?>"/>
												<span class="description">
													<?php esc_html_e( 'Maximum terms of each attribute will be shown on archive page.', 'wpc-variation-swatches' ); ?>
                                                </span>
											</td>
										</tr>
										<tr>
											<th><?php esc_html_e( 'Product wrapper selector', 'wpc-variation-swatches' ); ?></th>
											<td>
												<?php $archive_product = apply_filters( 'wpcvs_archive_product_selector', '' ); ?>
												<input type="text" name="wpcvs_settings[archive_product]" value="<?php echo esc_attr( ! empty( $archive_product ) ? $archive_product : self::get_setting( 'archive_product' ) ); ?>"
													<?php echo( ! empty( $archive_product ) ? 'readonly' : 'placeholder=".product"' ); ?>/>
												<span class="description">
													<?php printf( esc_html__( 'Archive product wrapper selector. Default: %s', 'wpc-variation-swatches' ), '<code>.product</code>' ); ?>
                                                </span>
											</td>
										</tr>
										<tr>
											<th><?php esc_html_e( 'Image selector', 'wpc-variation-swatches' ); ?></th>
											<td>
												<?php $archive_image = apply_filters( 'wpcvs_archive_image_selector', '' ); ?>
												<input type="text" name="wpcvs_settings[archive_image]" value="<?php echo esc_attr( ! empty( $archive_image ) ? $archive_image : self::get_setting( 'archive_image' ) ); ?>"
													<?php echo( ! empty( $archive_image ) ? 'readonly' : 'placeholder=".attachment-woocommerce_thumbnail"' ); ?>/>
												<span class="description">
													<?php printf( esc_html__( 'Archive product image selector to show variation image. Default: %s', 'wpc-variation-swatches' ), '<code>.attachment-woocommerce_thumbnail</code>' ); ?>
                                                </span>
											</td>
										</tr>
										<tr>
											<th><?php esc_html_e( 'Add to cart button selector', 'wpc-variation-swatches' ); ?></th>
											<td>
												<?php $archive_atc = apply_filters( 'wpcvs_archive_atc_selector', '' ); ?>
												<input type="text" name="wpcvs_settings[archive_atc]" value="<?php echo esc_attr( ! empty( $archive_atc ) ? $archive_atc : self::get_setting( 'archive_atc' ) ); ?>"
													<?php echo( ! empty( $archive_atc ) ? 'readonly' : 'placeholder=".add_to_cart_button"' ); ?>/>
												<span class="description">
													<?php printf( esc_html__( 'Archive add to cart button selector. Default: %s', 'wpc-variation-swatches' ), '<code>.add_to_cart_button</code>' ); ?>
                                                </span>
											</td>
										</tr>
										<tr>
											<th><?php esc_html_e( 'Add to cart text selector', 'wpc-variation-swatches' ); ?></th>
											<td>
												<?php $archive_atc_text = apply_filters( 'wpcvs_archive_atc_text_selector', '' ); ?>
												<input type="text" name="wpcvs_settings[archive_atc_text]" value="<?php echo esc_attr( ! empty( $archive_atc_text ) ? $archive_atc_text : self::get_setting( 'archive_atc_text' ) ); ?>"
													<?php echo( ! empty( $archive_atc_text ) ? 'readonly' : 'placeholder=".add_to_cart_button"' ); ?>/>
												<span class="description">
													<?php printf( esc_html__( 'Archive add to cart button text selector. Default: %s', 'wpc-variation-swatches' ), '<code>.add_to_cart_button</code>' ); ?>
                                                </span>
											</td>
										</tr>
										<tr class="heading">
											<th colspan="2"><?php esc_html_e( 'Suggestion', 'wpc-variation-swatches' ); ?></th>
										</tr>
										<tr>
											<td colspan="2">
												To display custom engaging real-time messages on any wished positions, please install
												<a href="https://wordpress.org/plugins/wpc-smart-messages/" target="_blank">WPC Smart Messages for WooCommerce</a> plugin. It's free and available now on the WordPress repository.
											</td>
										</tr>
										<tr class="submit">
											<th colspan="2">
												<?php settings_fields( 'wpcvs_settings' ); ?><?php submit_button(); ?>
											</th>
										</tr>
									</table>
								</form>
							<?php } elseif ( $active_tab === 'localization' ) { ?>
								<form method="post" action="options.php">
									<table class="form-table">
										<tr class="heading">
											<th scope="row"><?php esc_html_e( 'General', 'wpc-variation-swatches' ); ?></th>
											<td>
												<?php esc_html_e( 'Leave blank to use the default text and its equivalent translation in multiple languages.', 'wpc-variation-swatches' ); ?>
											</td>
										</tr>
										<tr>
											<th><?php esc_html_e( 'Add to cart', 'wpc-variation-swatches' ); ?></th>
											<td>
												<input type="text" class="regular-text" name="wpcvs_localization[add_to_cart]" value="<?php echo esc_attr( self::localization( 'add_to_cart' ) ); ?>" placeholder="<?php esc_attr_e( 'Add to cart', 'wpc-variation-swatches' ); ?>"/>
											</td>
										</tr>
										<tr>
											<th><?php esc_html_e( 'Select options', 'wpc-variation-swatches' ); ?></th>
											<td>
												<input type="text" class="regular-text" name="wpcvs_localization[select_options]" value="<?php echo esc_attr( self::localization( 'select_options' ) ); ?>" placeholder="<?php esc_attr_e( 'Select options', 'wpc-variation-swatches' ); ?>"/>
											</td>
										</tr>
										<tr>
											<th><?php esc_html_e( 'View cart', 'wpc-variation-swatches' ); ?></th>
											<td>
												<input type="text" class="regular-text" name="wpcvs_localization[view_cart]" value="<?php echo esc_attr( self::localization( 'view_cart' ) ); ?>" placeholder="<?php esc_attr_e( 'View cart', 'wpc-variation-swatches' ); ?>"/>
											</td>
										</tr>
										<tr>
											<th><?php esc_html_e( 'More', 'wpc-variation-swatches' ); ?></th>
											<td>
												<input type="text" class="regular-text" name="wpcvs_localization[more]" value="<?php echo esc_attr( self::localization( 'more' ) ); ?>" placeholder="<?php esc_attr_e( '+%d More', 'wpc-variation-swatches' ); ?>"/>
											</td>
										</tr>
										<tr class="submit">
											<th colspan="2">
												<?php settings_fields( 'wpcvs_localization' ); ?><?php submit_button(); ?>
											</th>
										</tr>
									</table>
								</form>
							<?php } ?>
						</div>
					</div>
					<?php
				}

				function wpcvs_action_links( $links, $file ) {
					static $plugin;

					if ( ! isset( $plugin ) ) {
						$plugin = plugin_basename( __FILE__ );
					}

					if ( $plugin === $file ) {
						$settings = '<a href="' . admin_url( 'admin.php?page=wpclever-wpcvs&tab=settings' ) . '">' . esc_html__( 'Settings', 'wpc-variation-swatches' ) . '</a>';
						array_unshift( $links, $settings );
					}

					return (array) $links;
				}

				function wpcvs_row_meta( $links, $file ) {
					static $plugin;

					if ( ! isset( $plugin ) ) {
						$plugin = plugin_basename( __FILE__ );
					}

					if ( $plugin === $file ) {
						$row_meta = [
							'support' => '<a href="' . esc_url( WPCVS_DISCUSSION ) . '" target="_blank">' . esc_html__( 'Community support', 'wpc-variation-swatches' ) . '</a>',
						];

						return array_merge( $links, $row_meta );
					}

					return (array) $links;
				}

				function type_selector( $types ) {
					global $pagenow;

					if ( ( $pagenow === 'post-new.php' ) || ( $pagenow === 'post.php' ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
						return $types;
					} else {
						$types['select'] = esc_html__( 'Select', 'wpc-variation-swatches' );
						$types['button'] = esc_html__( 'Button', 'wpc-variation-swatches' );
						$types['color']  = esc_html__( 'Color', 'wpc-variation-swatches' );
						$types['image']  = esc_html__( 'Image', 'wpc-variation-swatches' );
						$types['radio']  = esc_html__( 'Radio', 'wpc-variation-swatches' );

						return $types;
					}
				}

				function show_field( $term_or_tax ) {
					if ( is_object( $term_or_tax ) ) {
						// is term
						$term_id    = $term_or_tax->term_id;
						$attr_id    = wc_attribute_taxonomy_id_by_name( $term_or_tax->taxonomy );
						$attr       = wc_get_attribute( $attr_id );
						$wrap_start = '<tr class="form-field"><th><label>';
						$wrap_mid   = '</label></th><td>';
						$wrap_end   = '</td></tr>';
					} else {
						// is taxonomy
						$term_id    = 0;
						$attr_id    = wc_attribute_taxonomy_id_by_name( $term_or_tax );
						$attr       = wc_get_attribute( $attr_id );
						$wrap_start = '<div class="form-field"><label>';
						$wrap_mid   = '</label>';
						$wrap_end   = '</div>';
					}

					$wpcvs_tooltip = get_term_meta( $term_id, 'wpcvs_tooltip', true );

					switch ( $attr->type ) {
						case 'button':
							$wpcvs_val = get_term_meta( $term_id, 'wpcvs_button', true );
							echo $wrap_start . esc_html__( 'Button', 'wpc-variation-swatches' ) . $wrap_mid . '<input id="wpcvs_button" name="wpcvs_button" value="' . esc_attr( $wpcvs_val ) . '" type="text"/>' . $wrap_end;
							echo $wrap_start . esc_html__( 'Tooltip', 'wpc-variation-swatches' ) . $wrap_mid . '<input id="wpcvs_tooltip" name="wpcvs_tooltip" value="' . esc_attr( $wpcvs_tooltip ) . '" type="text"/>' . $wrap_end;

							break;
						case 'color':
							$wpcvs_val = get_term_meta( $term_id, 'wpcvs_color', true );
							echo $wrap_start . esc_html__( 'Color', 'wpc-variation-swatches' ) . $wrap_mid . '<input class="wpcvs_color" id="wpcvs_color" name="wpcvs_color" value="' . esc_attr( $wpcvs_val ) . '" type="text"/>' . $wrap_end;
							echo $wrap_start . esc_html__( 'Tooltip', 'wpc-variation-swatches' ) . $wrap_mid . '<input id="wpcvs_tooltip" name="wpcvs_tooltip" value="' . esc_attr( $wpcvs_tooltip ) . '" type="text"/>' . $wrap_end;

							break;
						case 'image':
							wp_enqueue_media();
							$wpcvs_val = get_term_meta( $term_id, 'wpcvs_image', true );

							if ( $wpcvs_val ) {
								$image = wp_get_attachment_thumb_url( $wpcvs_val );
							} else {
								$image = wc_placeholder_img_src();
							}

							echo $wrap_start . 'Image' . $wrap_mid; ?>
							<div id="wpcvs_image_thumbnail" style="float: left; margin-right: 10px;">
								<img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px"/></div>
							<div style="line-height: 60px;">
								<input type="hidden" id="wpcvs_image" name="wpcvs_image" value="<?php echo esc_attr( $wpcvs_val ); ?>"/>
								<button id="wpcvs_upload_image" type="button" class="wpcvs_upload_image button"><?php esc_html_e( 'Upload/Add image', 'wpc-variation-swatches' ); ?>
								</button>
								<button id="wpcvs_remove_image" type="button" class="wpcvs_remove_image button"><?php esc_html_e( 'Remove image', 'wpc-variation-swatches' ); ?>
								</button>
							</div>
							<?php
							echo $wrap_end;
							echo $wrap_start . 'Tooltip' . $wrap_mid . '<input id="wpcvs_tooltip" name="wpcvs_tooltip" value="' . esc_attr( $wpcvs_tooltip ) . '" type="text"/>' . $wrap_end;

							break;
						case 'radio':
							$wpcvs_val = get_term_meta( $term_id, 'wpcvs_radio', true );
							echo $wrap_start . esc_html__( 'Label', 'wpc-variation-swatches' ) . $wrap_mid . '<input id="wpcvs_radio" name="wpcvs_radio" value="' . esc_attr( $wpcvs_val ) . '" type="text"/>' . $wrap_end;
							echo $wrap_start . esc_html__( 'Tooltip', 'wpc-variation-swatches' ) . $wrap_mid . '<input id="wpcvs_tooltip" name="wpcvs_tooltip" value="' . esc_attr( $wpcvs_tooltip ) . '" type="text"/>' . $wrap_end;

							break;
						default:
							echo '';
					}
				}

				function save_field( $term_id ) {
					if ( isset( $_POST['wpcvs_color'] ) ) {
						update_term_meta( $term_id, 'wpcvs_color', sanitize_text_field( $_POST['wpcvs_color'] ) );
					}

					if ( isset( $_POST['wpcvs_button'] ) ) {
						update_term_meta( $term_id, 'wpcvs_button', sanitize_text_field( $_POST['wpcvs_button'] ) );
					}

					if ( isset( $_POST['wpcvs_image'] ) ) {
						update_term_meta( $term_id, 'wpcvs_image', sanitize_text_field( $_POST['wpcvs_image'] ) );
					}

					if ( isset( $_POST['wpcvs_radio'] ) ) {
						update_term_meta( $term_id, 'wpcvs_radio', sanitize_text_field( $_POST['wpcvs_radio'] ) );
					}

					if ( isset( $_POST['wpcvs_tooltip'] ) ) {
						update_term_meta( $term_id, 'wpcvs_tooltip', sanitize_text_field( $_POST['wpcvs_tooltip'] ) );
					}
				}

				function variation_attribute_options_html( $options_html, $args ) {
					$options    = $args['options'];
					$product    = $args['product'];
					$attribute  = $args['attribute'];
					$count      = 0;
					$limit      = absint( isset( $args['limit'] ) ? $args['limit'] : 0 );
					$hint       = self::get_setting( 'tooltip', 'top' );
					$hint_class = $hint !== 'no' ? 'hint--' . $hint : '';
					$style      = self::get_setting( 'style', 'square' );
					$attr_id    = wc_attribute_taxonomy_id_by_name( $attribute );

					ob_start();

					if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
						$attributes = $product->get_variation_attributes();
						$options    = $attributes[ $attribute ];
					}

					if ( $attr_id ) {
						$attr      = wc_get_attribute( $attr_id );
						$attr_type = isset( $attr->type ) ? $attr->type : 'select';

						$terms = wc_get_product_terms(
							$product->get_id(),
							$attribute,
							[
								'fields' => 'all',
							]
						);

						if ( ( $attr_type === 'select' ) && ( self::get_setting( 'button_default', 'no' ) === 'yes' ) ) {
							$attr_type = 'button';
						}

						if ( ( $attr_type !== '' ) && ( $attr_type !== 'select' ) ) {
							do_action( 'wpcvs_terms_above', $args );

							$terms_class = apply_filters( 'wpcvs_terms_class', 'wpcvs-terms wpcvs-type-' . $attr_type . ' wpcvs-style-' . $style, $terms, $args );
							echo '<div class="' . esc_attr( $terms_class ) . '" data-attribute="' . esc_attr( $attribute ) . '">';
							do_action( 'wpcvs_terms_before', $args );

							switch ( $attr_type ) {
								case 'button' :
									foreach ( $terms as $term ) {
										if ( ! $limit || ( $count < $limit ) ) {
											$val     = get_term_meta( $term->term_id, 'wpcvs_button', true ) ?: $term->name;
											$tooltip = get_term_meta( $term->term_id, 'wpcvs_tooltip', true ) ?: $val;
											$class   = apply_filters( 'wpcvs_term_class', 'wpcvs-term ' . $hint_class, $term, $args );
											do_action( 'wpcvs_term_before', $term );
											echo apply_filters( 'wpcvs_term_html', '<span class="' . esc_attr( $class ) . '" aria-label="' . esc_attr( $tooltip ) . '" title="' . esc_attr( $tooltip ) . '" data-term="' . esc_attr( $term->slug ) . '"><span>' . esc_html( $val ) . '</span></span>', $term, $args );
											do_action( 'wpcvs_term_after', $term );
										}

										$count ++;
									}

									break;
								case 'color':
									foreach ( $terms as $term ) {
										if ( ! $limit || ( $count < $limit ) ) {
											$val     = get_term_meta( $term->term_id, 'wpcvs_color', true ) ?: '';
											$tooltip = get_term_meta( $term->term_id, 'wpcvs_tooltip', true ) ?: $term->name;
											$class   = apply_filters( 'wpcvs_term_class', 'wpcvs-term ' . $hint_class, $term, $args );
											do_action( 'wpcvs_term_before', $term );
											echo apply_filters( 'wpcvs_term_html', '<span class="' . esc_attr( $class ) . '" aria-label="' . esc_attr( $tooltip ) . '" title="' . esc_attr( $tooltip ) . '" data-term="' . esc_attr( $term->slug ) . '"><span ' . ( ! empty( $val ) ? 'style="background-color: ' . esc_attr( $val ) . '"' : '' ) . '>' . esc_html( $val ) . '</span></span>', $term, $args );
											do_action( 'wpcvs_term_after', $term );
										}

										$count ++;
									}

									break;
								case 'image':
									foreach ( $terms as $term ) {
										if ( ! $limit || ( $count < $limit ) ) {
											$val     = get_term_meta( $term->term_id, 'wpcvs_image', true ) ? wp_get_attachment_thumb_url( get_term_meta( $term->term_id, 'wpcvs_image', true ) ) : wc_placeholder_img_src();
											$tooltip = get_term_meta( $term->term_id, 'wpcvs_tooltip', true ) ?: $term->name;
											$class   = apply_filters( 'wpcvs_term_class', 'wpcvs-term ' . $hint_class, $term, $args );
											do_action( 'wpcvs_term_before', $term );
											echo apply_filters( 'wpcvs_term_html', '<span class="' . esc_attr( $class ) . '" aria-label="' . esc_attr( $tooltip ) . '" title="' . esc_attr( $tooltip ) . '" data-term="' . esc_attr( $term->slug ) . '"><span><img src="' . esc_url( $val ) . '" alt="' . esc_attr( $term->name ) . '"/></span></span>', $term, $args );
											do_action( 'wpcvs_term_after', $term );
										}

										$count ++;
									}

									break;
								case 'radio':
									$name = uniqid( 'wpcvs_radio_' );

									foreach ( $terms as $term ) {
										if ( ! $limit || ( $count < $limit ) ) {
											$val     = get_term_meta( $term->term_id, 'wpcvs_radio', true ) ?: $term->name;
											$tooltip = get_term_meta( $term->term_id, 'wpcvs_tooltip', true ) ?: $term->name;
											$class   = apply_filters( 'wpcvs_term_class', 'wpcvs-term ' . $hint_class, $term, $args );
											do_action( 'wpcvs_term_before', $term );
											echo apply_filters( 'wpcvs_term_html', '<span class="' . esc_attr( $class ) . '" aria-label="' . esc_attr( $tooltip ) . '" title="' . esc_attr( $tooltip ) . '" data-term="' . esc_attr( $term->slug ) . '"><span><input type="radio" name="' . esc_attr( $name ) . '" value="' . esc_attr( $term->slug ) . '"/> ' . esc_html( $val ) . '</span></span>', $term, $args );
											do_action( 'wpcvs_term_after', $term );
										}

										$count ++;
									}

									break;
								default:
									break;
							}

							if ( $limit && ( $count > $limit ) ) {
								echo apply_filters( 'wpcvs_more_html', '<span class="wpcvs-more"><a href="' . esc_url( $product->get_permalink() ) . '">' . sprintf( self::localization( 'more', esc_html__( '+%d More', 'wpc-variation-swatches' ) ), ( $count - $limit ) ) . '</a></span>', ( $count - $limit ) );
							}

							do_action( 'wpcvs_terms_after', $args );
							echo '</div>';
							do_action( 'wpcvs_terms_below', $args );
						}
					} else {
						// custom attribute
						if ( self::get_setting( 'button_default', 'no' ) === 'yes' ) {
							do_action( 'wpcvs_terms_above', $args );

							$terms_class = apply_filters( 'wpcvs_terms_class', 'wpcvs-terms wpcvs-type-button wpcvs-style-' . $style, $options, $args );
							echo '<div class="' . esc_attr( $terms_class ) . '" data-attribute="' . esc_attr( wc_sanitize_taxonomy_name( $attribute ) ) . '">';
							do_action( 'wpcvs_terms_before', $args );

							foreach ( $options as $option ) {
								if ( ! $limit || ( $count < $limit ) ) {
									$class = apply_filters( 'wpcvs_term_class', 'wpcvs-term ' . $hint_class, $option, $args );
									do_action( 'wpcvs_term_before', $option );
									echo apply_filters( 'wpcvs_term_html', '<span class="' . esc_attr( $class ) . '" aria-label="' . esc_attr( $option ) . '" title="' . esc_attr( $option ) . '" data-term="' . esc_attr( $option ) . '"><span>' . esc_html( $option ) . '</span></span>', $option, $args );
									do_action( 'wpcvs_term_after', $option );
								}

								$count ++;
							}

							do_action( 'wpcvs_terms_after', $args );
							echo '</div>';
							do_action( 'wpcvs_terms_below', $args );
						}
					}

					return apply_filters( 'wpcvs_terms_html', ob_get_clean(), $args ) . $options_html;
				}

				function custom_columns( $columns ) {
					$columns['wpcvs_value']   = esc_html__( 'Value', 'wpc-variation-swatches' );
					$columns['wpcvs_tooltip'] = esc_html__( 'Tooltip', 'wpc-variation-swatches' );

					return $columns;
				}

				function custom_columns_content( $columns, $column, $term_id ) {
					if ( $column === 'wpcvs_value' ) {
						$term    = get_term( $term_id );
						$attr_id = wc_attribute_taxonomy_id_by_name( $term->taxonomy );
						$attr    = wc_get_attribute( $attr_id );

						switch ( $attr->type ) {
							case 'image':
								$val = get_term_meta( $term_id, 'wpcvs_image', true );
								echo '<img style="display: inline-block; border-radius: 3px; width: 40px; height: 40px; background-color: #eee; box-sizing: border-box; border: 1px solid #eee;" src="' . esc_url( $val ? wp_get_attachment_thumb_url( $val ) : wc_placeholder_img_src() ) . '"/>';

								break;
							case 'color':
								$val = get_term_meta( $term_id, 'wpcvs_color', true );
								echo '<span style="display: inline-block; border-radius: 3px; width: 40px; height: 40px; background-color: ' . esc_attr( $val ) . '; box-sizing: border-box; border: 1px solid #eee;"></span>';

								break;
							case 'button':
								$val = get_term_meta( $term_id, 'wpcvs_button', true );
								echo '<span style="display: inline-block; border-radius: 3px; height: 40px; line-height: 40px; padding: 0 15px; border: 1px solid #eee; background-color: #fff; min-width: 44px; box-sizing: border-box;">' . esc_html( $val ) . '</span>';

								break;
						}
					}

					if ( $column === 'wpcvs_tooltip' ) {
						echo get_term_meta( $term_id, 'wpcvs_tooltip', true );
					}
				}

				function archive( $product_id = null ) {
					global $product;
					$global_product = $product;

					if ( $product_id ) {
						$product = wc_get_product( $product_id );
					}

					if ( ! $product || ! $product->is_type( 'variable' ) ) {
						return;
					}

					$attributes           = $product->get_variation_attributes();
					$available_variations = $product->get_available_variations();
					$variations_json      = wp_json_encode( $available_variations );
					$variations_attr      = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

					if ( is_array( $attributes ) && ( count( $attributes ) > 0 ) ) {
						do_action( 'wpcvs_archive_variations_form_above', $product );
						echo '<div class="variations_form wpcvs_archive" data-product_id="' . absint( $product->get_id() ) . '" data-product_variations="' . $variations_attr . '">';
						do_action( 'wpcvs_archive_variations_form_before', $product );
						echo '<div class="variations">';
						do_action( 'wpcvs_archive_variations_before', $product );

						foreach ( $attributes as $attribute_name => $options ) { ?>
							<div class="variation">
								<div class="label">
									<?php echo wc_attribute_label( $attribute_name ); ?>
								</div>
								<div class="select">
									<?php
									$attr     = 'attribute_' . sanitize_title( $attribute_name );
									$selected = isset( $_REQUEST[ $attr ] ) ? wc_clean( stripslashes( urldecode( $_REQUEST[ $attr ] ) ) ) : $product->get_variation_default_attribute( $attribute_name );
									wc_dropdown_variation_attribute_options( [
										'options'          => $options,
										'attribute'        => $attribute_name,
										'product'          => $product,
										'limit'            => self::get_setting( 'archive_limit', '10' ),
										'selected'         => $selected,
										'show_option_none' => esc_html__( 'Choose', 'wpc-variation-swatches' ) . ' ' . wc_attribute_label( $attribute_name )
									] );
									?>
								</div>
							</div>
						<?php }

						echo '<div class="reset">' . apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'wpc-variation-swatches' ) . '</a>' ) . '</div>';
						do_action( 'wpcvs_archive_variations_after', $product );
						echo '</div>';
						do_action( 'wpcvs_archive_variations_form_after', $product );
						echo '</div>';
						do_action( 'wpcvs_archive_variations_form_below', $product );
					}

					$product = $global_product;
				}

				function wpcsm_locations( $locations ) {
					$locations['WPC Variation Swatches'] = [
						'wpcvs_terms_above'  => esc_html__( 'Above terms container', 'wpc-variation-swatches' ),
						'wpcvs_terms_below'  => esc_html__( 'Below terms container', 'wpc-variation-swatches' ),
						'wpcvs_terms_before' => esc_html__( 'Before terms container', 'wpc-variation-swatches' ),
						'wpcvs_terms_after'  => esc_html__( 'After terms container', 'wpc-variation-swatches' ),
						'wpcvs_term_before'  => esc_html__( 'Before term', 'wpc-variation-swatches' ),
						'wpcvs_term_after'   => esc_html__( 'After term', 'wpc-variation-swatches' ),
					];

					return $locations;
				}
			}

			return WPCleverWpcvs::instance();
		}
	}
}

if ( ! function_exists( 'wpcvs_notice_wc' ) ) {
	function wpcvs_notice_wc() {
		?>
		<div class="error">
			<p><strong>WPC Variation Swatches</strong> requires WooCommerce version 3.0 or greater.</p>
		</div>
		<?php
	}
}
