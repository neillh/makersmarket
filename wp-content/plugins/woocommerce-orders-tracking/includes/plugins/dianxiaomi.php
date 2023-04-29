<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Dianxiaomi
 */
if ( ! class_exists( 'VI_WOOCOMMERCE_ORDERS_TRACKING_PLUGINS_Dianxiaomi' ) ) {
	class VI_WOOCOMMERCE_ORDERS_TRACKING_PLUGINS_Dianxiaomi {
		protected static $settings;

		/**
		 * VI_WOOCOMMERCE_ORDERS_TRACKING_PLUGINS_Dianxiaomi constructor.
		 */
		public function __construct() {
			self::$settings = VI_WOOCOMMERCE_ORDERS_TRACKING_DATA::get_instance();
			if ( is_plugin_active( 'dianxiaomi/dianxiaomi.php' ) ) {
				add_action( 'admin_enqueue_scripts', array(
					$this,
					'admin_enqueue_scripts'
				) );
				add_action( 'admin_init', array(
					$this,
					'admin_init'
				) );
				add_action( 'woo_orders_tracking_settings_integration', array(
					$this,
					'add_settings'
				) );
				if ( self::$settings->get_params( 'dianxiaomi_enable' ) ) {
					add_filter( 'dianxiaomi_api_order_response', array(
						$this,
						'dianxiaomi_api_order_response'
					), 99, 4 );
				}
			}
		}

		public function add_settings() {
			?>
            <div class="vi-ui segment">

                <div class="vi-ui small positive message">
                    <div><?php esc_html_e( 'Dianxiaomi integration', 'woocommerce-orders-tracking' ) ?></div>
                </div>
                <table class="form-table">
                    <tbody>
                    <tr>
                        <th>
                            <label for="<?php echo esc_attr( self::set( 'dianxiaomi_enable' ) ) ?>"><?php esc_html_e( 'Enable', 'woocommerce-orders-tracking' ) ?></label>
                        </th>
                        <td>
                            <div class="vi-ui toggle checkbox">
                                <input type="checkbox"
                                       name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[dianxiaomi_enable]"
                                       id="<?php echo esc_attr( self::set( 'dianxiaomi_enable' ) ) ?>"
                                       value="1" <?php checked( self::$settings->get_params( 'dianxiaomi_enable' ), '1' ) ?>><label><?php esc_html_e( 'Enable Dianxiaomi integration', 'woocommerce-orders-tracking' ) ?></label>
                            </div>
                            <p><?php esc_html_e( 'Enable this to sync tracking numbers with Dianxiaomi plugin whenever you sync Dianxiaomi with your store', 'woocommerce-orders-tracking' ) ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="<?php echo esc_attr( self::set( 'dianxiaomi_send_email' ) ) ?>"><?php esc_html_e( 'Send email', 'woocommerce-orders-tracking' ) ?></label>
                        </th>
                        <td>
                            <div class="vi-ui toggle checkbox">
                                <input type="checkbox"
                                       name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[dianxiaomi_send_email]"
                                       id="<?php echo esc_attr( self::set( 'dianxiaomi_send_email' ) ) ?>"
                                       value="1" <?php checked( self::$settings->get_params( 'dianxiaomi_send_email' ), '1' ) ?>><label></label>
                            </div>
                            <p><?php esc_html_e( 'When tracking numbers are synced with Dianxiaomi, send an email to customers if tracking info changes', 'woocommerce-orders-tracking' ) ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="<?php echo esc_attr( self::set( 'dianxiaomi_change_status' ) ) ?>"><?php esc_html_e( 'Change order status', 'woocommerce-orders-tracking' ) ?></label>
                        </th>
                        <td>
							<?php
							$dianxiaomi_change_status = self::$settings->get_params( 'dianxiaomi_change_status' );
							?>
                            <select id="<?php echo esc_attr( self::set( 'dianxiaomi_change_status' ) ) ?>"
                                    class="vi-ui dropdown"
                                    name=<?php echo esc_attr( self::set( 'settings' ) ) ?>[dianxiaomi_change_status]">
                                <option value=""><?php esc_html_e( 'Not change', 'woocommerce-orders-tracking' ) ?></option>
								<?php
								foreach ( wc_get_order_statuses() as $all_option_k => $all_option_v ) {
									?>
                                    <option value="<?php echo esc_attr( $all_option_k ) ?>" <?php selected( $all_option_k, $dianxiaomi_change_status ) ?>><?php echo esc_html( $all_option_v ) ?></option>
									<?php
								}
								?>
                            </select>
                            <p><?php esc_html_e( 'Change order status when tracking number is added from Dianxiaomi', 'woocommerce-orders-tracking' ) ?></p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
			<?php
		}

		/**
		 * @param $order_data
		 * @param $order
		 * @param $fields
		 * @param $server
		 *
		 * @return mixed
		 * @throws Exception
		 */
		public function dianxiaomi_api_order_response( $order_data, $order, $fields, $server ) {
			try {
				if ( isset( $order_data['trackings'] ) && is_array( $order_data['trackings'] ) && count( $order_data['trackings'] ) ) {
					$trackings = array_pop( $order_data['trackings'] );
					if ( ! empty( $trackings['tracking_provider'] ) && ! empty( $trackings['tracking_number'] ) ) {
						$tracking_number = $trackings['tracking_number'];
						$mapping         = self::$settings->get_params( 'dianxiaomi_courier_mapping' );
						if ( is_array( $mapping ) && count( $mapping ) ) {
							if ( ! empty( $mapping[ $trackings['tracking_provider'] ] ) ) {
								$carrier_slug = $mapping[ $trackings['tracking_provider'] ];
								$carrier      = self::$settings->get_shipping_carrier_by_slug( $carrier_slug );
								if ( is_array( $carrier ) && count( $carrier ) ) {
									$carrier_url  = $carrier['url'];
									$carrier_name = $carrier['name'];
									if ( ! empty( $carrier['display_name'] ) ) {
										$display_name = $carrier['display_name'];
									} else {
										$display_name = $carrier_name;
									}
									$carrier_type = $carrier['carrier_type'];
									$order_id     = isset( $order_data['id'] ) ? $order_data['id'] : '';
									if ( $order_id ) {
										$order = wc_get_order( $order_id );
										if ( $order ) {
											$line_items = $order->get_items();
											if ( count( $line_items ) ) {
												$tracking_url_import   = self::$settings->get_url_tracking( $carrier_url, $tracking_number, $carrier_slug, $order->get_shipping_postcode(), false, true, $order_id );
												$order_tracking_change = false;
												$send_mail_array       = array();
												$now                   = time();
												foreach ( $line_items as $item_id => $item ) {
													$tracking_change       = true;
													$item_tracking_data    = wc_get_order_item_meta( $item_id, '_vi_wot_order_item_tracking_data', true );
													$current_tracking_data = array(
														'tracking_number' => '',
														'carrier_slug'    => '',
														'carrier_url'     => '',
														'carrier_name'    => '',
														'carrier_type'    => '',
														'time'            => $now,
													);
													if ( $item_tracking_data ) {
														$item_tracking_data = vi_wot_json_decode( $item_tracking_data );
														foreach ( $item_tracking_data as $order_tracking_data_k => $order_tracking_data_v ) {
															if ( $order_tracking_data_v['tracking_number'] == $tracking_number ) {
																$current_tracking_data = $order_tracking_data_v;
																if ( $order_tracking_data_k === ( count( $item_tracking_data ) - 1 ) ) {
																	$tracking_change = false;
																}
																unset( $item_tracking_data[ $order_tracking_data_k ] );
																break;
															}
														}
														$item_tracking_data = array_values( $item_tracking_data );
													} else {
														$item_tracking_data = array();
													}
													$current_tracking_data['tracking_number'] = $tracking_number;
													$current_tracking_data['carrier_slug']    = $carrier_slug;
													$current_tracking_data['carrier_url']     = $carrier_url;
													$current_tracking_data['carrier_name']    = $carrier_name;
													$current_tracking_data['carrier_type']    = $carrier_type;

													$item_tracking_data[] = $current_tracking_data;
													wc_update_order_item_meta( $item_id, '_vi_wot_order_item_tracking_data', vi_wot_json_encode( $item_tracking_data ) );
													$send_mail_array[] = array(
														'order_item_id'   => $item_id,
														'order_item_name' => $item->get_name(),
														'tracking_number' => $tracking_number,
														'carrier_url'     => $carrier_url,
														'tracking_url'    => $tracking_url_import,
														'carrier_name'    => $display_name,
													);

													if ( $tracking_change ) {
														$order_tracking_change = true;
													}
												}
												if ( $server->method === 'POST' && $order_tracking_change ) {
													if ( self::$settings->get_params( 'dianxiaomi_send_email' ) && count( $send_mail_array ) ) {
														VI_WOOCOMMERCE_ORDERS_TRACKING_ADMIN_EMAIL::send_email( $order_id, $send_mail_array, true );
													}
													$dianxiaomi_change_status = self::$settings->get_params( 'dianxiaomi_change_status' );
													if ( $dianxiaomi_change_status && in_array( $dianxiaomi_change_status, array_keys( wc_get_order_statuses() ) ) ) {
														$order->update_status( $dianxiaomi_change_status );
													}
													VI_WOOCOMMERCE_ORDERS_TRACKING_DATA::add_tracking_to_service( $tracking_number, $carrier_slug, $carrier_name, $order_id, $api_error );
												}
											}
										}
									}
								}
							}
						}
					}
				}
			} catch ( Error $error ) {
				VI_WOOCOMMERCE_ORDERS_TRACKING_ADMIN_LOG::wc_log( $error->getMessage(), 'debug', 'debug' );
			} catch ( Exception $exception ) {
				VI_WOOCOMMERCE_ORDERS_TRACKING_ADMIN_LOG::wc_log( $exception->getMessage(), 'debug', 'debug' );
			}

			return $order_data;
		}

		public function admin_init() {
			$option_page = isset( $_POST['option_page'] ) ? sanitize_text_field( $_POST['option_page'] ) : '';
			$action      = isset( $_POST['action'] ) ? sanitize_text_field( $_POST['action'] ) : '';
			if ( $option_page === 'dianxiaomi_option_group' && $action === 'update' && isset( $_POST['dianxiaomi_option_name'] ) ) {
				$dianxiaomi_courier_mapping           = isset( $_POST['dianxiaomi_courier_mapping'] ) ? stripslashes_deep( $_POST['dianxiaomi_courier_mapping'] ) : array();
				$params                               = self::$settings->get_params();
				$params['dianxiaomi_courier_mapping'] = $dianxiaomi_courier_mapping;
				update_option( 'woo_orders_tracking_settings', $params );
			}
		}

		public function admin_enqueue_scripts() {
			global $pagenow;
			$page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';
			if ( $pagenow === 'options-general.php' && $page === 'dianxiaomi-setting-admin' ) {
				wp_enqueue_script( 'woo-orders-tracking-dianxiaomi', VI_WOOCOMMERCE_ORDERS_TRACKING_JS . 'dianxiaomi.js', array( 'jquery' ), VI_WOOCOMMERCE_ORDERS_TRACKING_VERSION );
				$dianxiaomi_option_name = get_option( 'dianxiaomi_option_name' );
				wp_localize_script( 'woo-orders-tracking-dianxiaomi', 'woo_orders_tracking_dianxiaomi', array(
					'dianxiaomi_courier_mapping' => self::$settings->get_params( 'dianxiaomi_courier_mapping' ),
					'carriers'                   => VI_WOOCOMMERCE_ORDERS_TRACKING_DATA::get_carriers(),
					'dianxiaomi_couriers'        => empty( $dianxiaomi_option_name['couriers'] ) ? array() : explode( ',', $dianxiaomi_option_name['couriers'] ),
				) );
			}
		}

		private static function set( $name, $set_name = false ) {
			return VI_WOOCOMMERCE_ORDERS_TRACKING_DATA::set( $name, $set_name );
		}
	}
}
