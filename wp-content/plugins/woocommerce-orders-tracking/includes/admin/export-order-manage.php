<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOOCOMMERCE_ORDERS_TRACKING_ADMIN_EXPORT_ORDER_MANAGE {
	private static $post_type = 'shop_order';
	private static $order_notes = array();

	private static function make_orders_setting( $settings ) {
		//check filter by order
		$args = array(
			'filter-order-status',
			'filter-order-billing-address',
			'filter-order-shipping-address',
			'filter-order-payment-method',
			'filter-order-shipping-method',
			'set-fields',
		);
		foreach ( $args as $key ) {
			if ( ! array_key_exists( $key, $settings ) ) {
				$settings[ $key ] = array();
			}
		}

		//set filename
		$filename             = $settings['filename'];
		$filename             = str_replace(
			array(
				'%y',
				'%m',
				'%d',
				'%h',
				'%i',
				'%s',
			), array(
			current_time( 'Y' ),
			current_time( 'm' ),
			current_time( 'd' ),
			current_time( 'H' ),
			current_time( 'i' ),
			current_time( 's' ),
		),
			$filename
		);
		$settings['filename'] = $filename . '.csv';

		return $settings;
	}

	private static function get_header_row( $fields = array() ) {
		$results = array();
		$default = self::get_fields_to_select();
		if ( empty( $fields ) ) {
			$results = $default;
		} else {
			$check_has_tracking_code = $check_has_carrier_id = false;
			$check_tracking_code     = $check_carrier_id = false;
			foreach ( $fields as $item ) {
				$t = explode( '{wotv}', $item );
				if ( is_array( $t ) && count( $t ) >= 2 ) {
					$field_type = trim( $t[0] );
					$field_key  = trim( $t[1] );
					if ( $field_key === '_vi_order_item_tracking_code' ) {
						$check_has_tracking_code = true;
					}
					if ( $field_key === '_vi_order_item_carrier_id' ) {
						$check_has_carrier_id = true;
					}
					foreach ( $default as $field_default ) {
						if ( $field_type === $field_default['type'] && $field_key === $field_default['key'] ) {
							$results[] = $field_default;
							if ( $field_default['key'] === '_vi_order_item_tracking_code' ) {
								$check_tracking_code = true;
							}
							if ( $field_default['key'] === '_vi_order_item_carrier_id' ) {
								$check_carrier_id = true;
							}
							continue;
						}
					}
				}
			}

			if ( ! $check_carrier_id && $check_has_carrier_id ) {
				$results[] = array(
					'type'  => 'order_item_meta',
					'key'   => '_vi_order_item_carrier_id',
					'title' => esc_html__( '( Order/Order Item ) Carrier Slug', 'woocommerce-orders-tracking' ),
				);
			}
			if ( ! $check_tracking_code && $check_has_tracking_code ) {
				$results[] = array(
					'type'  => 'order_item_meta',
					'key'   => '_vi_order_item_tracking_code',
					'title' => esc_html__( '( Order/Order Item ) Tracking Number', 'woocommerce-orders-tracking' ),
				);
			}
		}

		return $results;
	}

	private static function get_filter_by_shipping_method( $args ) {
		$methods = array();
		foreach ( $args as $string ) {
			$t = explode( ':', $string );
			if ( ! count( $t ) == 2 ) {
				continue;
			}
			list( $meta_key, $meta_value ) = array_map( 'trim', $t );
			$meta_key = addslashes( $meta_key );
			if ( ! array_key_exists( $meta_key, $methods ) ) {
				$methods[ $meta_key ] = array();
			}
			$methods[ $meta_key ][] = addslashes( $meta_value );
		}
		if ( ! empty( $methods ) ) {
			global $wpdb;
			$sql   = " SELECT order_id FROM {$wpdb->prefix}woocommerce_order_items WHERE order_item_type='shipping' AND order_item_id IN (  SELECT order_item_id FROM {$wpdb->prefix}woocommerce_order_itemmeta  WHERE ";
			$where = array();
			foreach ( $methods as $method => $ids ) {
				$where[] = ' ( meta_key=\'instance_id\' AND meta_value IN (' . join( ',', $ids ) . ' ) AND order_item_id IN (  SELECT order_item_id FROM wp_woocommerce_order_itemmeta WHERE (meta_key=\'method_id\' AND meta_value = \'' . $method . '\' )))';
			}
			$where   = join( ' OR ', $where ) . ')';
			$sql     .= $where;
			$results = $wpdb->get_col( $sql );

			return $results;
		}

		return $methods;
	}

	private static function parse_expressions( $args ) {
		$results    = array();
		$delimiters = array(
			'<>' => 'NOT IN',
			'='  => 'IN',
		);
		foreach ( $args as $expressions ) {
			$expressions = trim( $expressions );
			$op          = '';
			foreach ( $delimiters as $item => $value ) {
				$t = explode( $item, $expressions );
				if ( count( $t ) == 2 ) {
					$op = $value;
					break;
				}
			}
			if ( ! $op ) {
				continue;
			}
			list( $meta_key, $meta_value ) = array_map( 'trim', $t );
			$meta_key = addslashes( $meta_key );
			if ( ! array_key_exists( $meta_key, $results ) ) {
				$results[ $meta_key ] = array();
			}
			if ( ! array_key_exists( $op, $results[ $meta_key ] ) ) {
				$results[ $meta_key ][ $op ] = array();;
			}
			$results[ $meta_key ][ $op ][] = addslashes( $meta_value );
		}

		return $results;
	}

	private static function get_orders_ids( $export_settings ) {
		global $wpdb;
		$sql       = 'SELECT DISTINCT ' . $wpdb->posts . '.ID FROM ' . $wpdb->posts;
		$left_join = array();
		$where     = array(
			'post_type = \'' . self::$post_type . '\'',
		);
		//filter by date
		switch ( $export_settings['filter-order-date'] ) {
			case 'date_created':
				if ( ! empty( $export_settings['filter-order-date-from'] ) ) {
					$date_from = date( 'Y-m-d H:i:s', strtotime( $export_settings['filter-order-date-from'] ) );
					$where[]   = ' post_date  >= \'' . $date_from . '\' ';
				}
				if ( ! empty( $export_settings['filter-order-date-to'] ) ) {
					$date_to = date( 'Y-m-d H:i:s', strtotime( $export_settings['filter-order-date-to'] ) + 86400 );
					$where[] = '  post_date  < \'' . $date_to . '\' ';
				}

				break;
			case 'date_modified':
				if ( ! empty( $export_settings['filter-order-date-from'] ) ) {
					$date_from = date( 'Y-m-d', strtotime( $export_settings['filter-order-date-from'] ) );
					$where[]   = '  post_modified  >= \'' . $date_from . '\'';
				}
				if ( ! empty( $export_settings['filter-order-date-to'] ) ) {
					$date_to = date( 'Y-m-d', strtotime( $export_settings['filter-order-date-to'] ) + 86400 );
					$where[] = '  post_modified  < \'' . $date_to . '\'';
				}
				break;
			case 'date_completed':
				if ( ! empty( $export_settings['filter-order-date-from'] ) || ! empty( $export_settings['filter-order-date-to'] ) ) {
					$left_join[] = ' INNER JOIN ' . $wpdb->postmeta . ' AS order_meta_date_complete  ON ' . $wpdb->posts . '.ID = order_meta_date_complete.post_id   ';
					$where[]     = 'order_meta_date_complete.meta_key = \'_completed_date\' ';
					if ( ! empty( $export_settings['filter-order-date-from'] ) ) {
						$date_from = date( 'Y-m-d', strtotime( $export_settings['filter-order-date-from'] ) );
						$where[]   = 'order_meta_date_complete.meta_value  >= \'' . $date_from . '\'';
					}
					if ( ! empty( $export_settings['filter-order-date-to'] ) ) {
						$date_to = date( 'Y-m-d', strtotime( $export_settings['filter-order-date-to'] ) + 86400 );
						$where[] = 'order_meta_date_complete.meta_value  < \'' . $date_to . '\'';
					}
				}
				break;
			case 'date_paid':
				if ( ! empty( $export_settings['filter-order-date-from'] ) || ! empty( $export_settings['filter-order-date-to'] ) ) {
					$left_join[] = ' INNER JOIN ' . $wpdb->postmeta . ' AS order_meta_date_paid ON ' . $wpdb->posts . '.ID = order_meta_date_paid.post_id   ';
					$where[]     = 'order_meta_date_paid.meta_key = \'_paid_date\' ';
					if ( ! empty( $export_settings['filter-order-date-from'] ) ) {
						$date_from = date( 'Y-m-d', strtotime( $export_settings['filter-order-date-from'] ) );
						$where[]   = 'order_meta_date_paid.meta_value  >= \'' . $date_from . '\'';
					}
					if ( ! empty( $export_settings['filter-order-date-to'] ) ) {
						$date_to = date( 'Y-m-d', strtotime( $export_settings['filter-order-date-to'] ) + 86400 );
						$where[] = 'order_meta_date_paid.meta_value  < \'' . $date_to . '\'';
					}
				}
				break;
		}

		//filter by status
		if ( ! empty( $export_settings['filter-order-status'] ) ) {
			$filter_by_status = $export_settings['filter-order-status'];
			$filter_by_status = '\'' . join( '\' , \'', $filter_by_status ) . '\'';
			$where[]          = 'post_status IN ( ' . $filter_by_status . ' )';
		}
		//filter by billing address
		if ( ! empty( $export_settings['filter-order-billing-address'] ) ) {
			$billing_address = self::parse_expressions( $export_settings['filter-order-billing-address'] );
			foreach ( $billing_address as $meta_key => $value ) {
				$table       = 'order_meta' . $meta_key;
				$left_join[] = ' INNER JOIN ' . $wpdb->postmeta . ' AS ' . $table . '  ON ' . $wpdb->posts . '.ID = ' . $table . '.post_id   ';
				foreach ( $value as $condition => $meta_value ) {
					$where[] = ' ( ' . $table . '.meta_key = \'' . $meta_key . '\' AND ' . $table . '.meta_value ' . $condition . ' ( \'' . join( '\' , \'', $meta_value ) . '\' ) )';
				}
			}
		}
		//filter by shipping address
		if ( ! empty( $export_settings['filter-order-shipping-address'] ) ) {
			$shipping_address = self::parse_expressions( $export_settings['filter-order-shipping-address'] );
			foreach ( $shipping_address as $meta_key => $value ) {
				$table       = 'order_meta' . $meta_key;
				$left_join[] = ' INNER JOIN ' . $wpdb->postmeta . ' AS ' . $table . '  ON ' . $wpdb->posts . '.ID = ' . $table . '.post_id   ';
				foreach ( $value as $condition => $meta_value ) {
					$where[] = ' ( ' . $table . '.meta_key = \'' . $meta_key . '\' AND ' . $table . '.meta_value ' . $condition . ' ( \'' . join( '\' , \'', $meta_value ) . '\' ) )';
				}
			}
		}

		if ( ! empty( $export_settings['filter-order-payment-method'] ) ) {
			$left_join[] = ' INNER JOIN ' . $wpdb->postmeta . ' AS order_meta_payment_method  ON ' . $wpdb->posts . '.ID = order_meta_payment_method.post_id   ';
			$where[]     = ' ( order_meta_payment_method.meta_key = \'_payment_method\' AND order_meta_payment_method.meta_value IN ( \'' . join( '\' , \'', $export_settings['filter-order-payment-method'] ) . '\' ) )';
		}
		if ( ! empty( $export_settings['filter-order-shipping-method'] ) ) {
			$where_shipping_method = self::get_filter_by_shipping_method( $export_settings['filter-order-shipping-method'] );
			if ( ! empty( $where_shipping_method ) ) {
				$where[] = ' ' . $wpdb->posts . '.ID IN ( ' . join( ' , ', $where_shipping_method ) . ' )';
			}
		}

		$sql .= join( ' ', $left_join ) . ' WHERE ' . join( ' AND ', $where );
		//sort order id
		switch ( $export_settings['sort-order'] ) {
			case 'order_id':
				$sql .= ' ORDER BY ' . $wpdb->posts . '.ID ' . $export_settings['sort-order-in'];
				break;
			case 'order_created':
				$sql .= ' ORDER BY ' . $wpdb->posts . '.post_date ' . $export_settings['sort-order-in'];
				break;
			case 'order_modification':
				$sql .= ' ORDER BY ' . $wpdb->posts . '.post_modified  ' . $export_settings['sort-order-in'];
				break;
			default:
				$sql .= ' ORDER BY ' . $wpdb->posts . '.ID DESC';
		}
		$results = $wpdb->get_col( $sql );

		return $results;
	}

	public static function get_data_export( $export_settings, $limit = '' ) {
		$settings        = VI_WOOCOMMERCE_ORDERS_TRACKING_DATA::get_instance();
		$results         = array();
		$export_settings = self::make_orders_setting( $export_settings );

		$results['filename']   = $export_settings['filename'];
		$results['header_row'] = $results['content'] = array();
		$results['header_row'] = self::get_header_row( $export_settings['set-fields'] );

		$order_ids = self::get_orders_ids( $export_settings );
		if ( empty( $order_ids ) ) {
			return $order_ids;
		}
		if ( $limit && $limit < count( $order_ids ) ) {
			$order_ids = array_slice( $order_ids, 0, $limit - 1 );
		}

		$manage_tracking    = $settings->get_params( 'manage_tracking' );
		$track_per_quantity = $settings->get_params( 'track_per_quantity' );
		if ( $manage_tracking === 'order_only' ) {
			$track_per_quantity = false;
		}
		foreach ( $order_ids as $order_id ) {
			$order           = new WC_Order( $order_id );
			$tracking_number = get_post_meta( $order_id, '_wot_tracking_number', true );
			$carrier_slug    = get_post_meta( $order_id, '_wot_tracking_carrier', true );
			if ($manage_tracking === 'order_only'|| $tracking_number || $carrier_slug ) {
				$order_data  = array();
				$carrier_url = $carrier_name = $carrier_type = '';
				if ( $carrier_slug ) {
					$carrier = $settings->get_shipping_carrier_by_slug( $carrier_slug );
					if ( is_array( $carrier ) && count( $carrier ) ) {
						$carrier_url  = $settings->get_url_tracking( $carrier['url'], $tracking_number, $carrier_slug, $order->get_shipping_postcode(), false, false );
						$carrier_name = $carrier['name'];
						$carrier_type = $carrier['carrier_type'];
					}
				}
				foreach ( $results['header_row'] as $field ) {
					if ( ! is_array( $field ) ) {
						continue;
					}
					if ( $field['type'] === 'post_meta' ) {
						$order_data[ $field['key'] ] = get_post_meta( $order_id, $field['key'], true );
					} elseif ( $field ['type'] === 'wotv_field' ) {
						switch ( $field['key'] ) {
							case 'order_id':
								$order_data[ $field['key'] ] = $order_id;
								break;
							case 'tracking_number':
								$order_data[ $field['key'] ] = $tracking_number;
								break;
							case 'carrier_slug':
								$order_data[ $field['key'] ] = $carrier_slug;
								break;
							case 'carrier_url':
								$order_data[ $field['key'] ] = $carrier_url;
								break;
							case 'carrier_name':
								$order_data[ $field['key'] ] = $carrier_name;
								break;
							case 'order_note':
								$order_data[ $field['key'] ] = self::get_order_notes_to_export( $order_id );
								break;
							case 'customer_note':
								$order_data[ $field['key'] ] = $order->get_customer_note();
								break;
							case 'carrier_type':
								$order_data[ $field['key'] ] = $carrier_type;
								break;
							case 'order_number':
								$order_data[ $field['key'] ] = $order->get_order_number();
								break;
							case 'order_status':
								$order_data[ $field['key'] ] = $order->get_status();
								break;
							case 'order_subtotal':
								$order_data[ $field['key'] ] = $order->get_subtotal();
								break;
							case 'modification_date':
								$order_data[ $field['key'] ] = $order->get_date_modified() ? $order->get_date_modified()->format( ' Y-m-d H:i:s' ) : '';
								break;
							case 'create_date':
								$order_data[ $field['key'] ] = $order->get_date_created() ? $order->get_date_created()->format( ' Y-m-d H:i:s' ) : '';
								break;
							case 'shipping_method_title':
								$order_data[ $field['key'] ] = $order->get_shipping_method();
								break;
							case 'shipping_amount':
								$order_data[ $field['key'] ] = $order->get_shipping_total();
								break;
							case 'shipping_country_name':
								$countries_info              = new WC_Countries();
								$list_countries              = $countries_info->get_countries();
								$order_shipping_country_code = $order->get_shipping_country();
								$order_data[ $field['key'] ] = $list_countries[ $order_shipping_country_code ];
								break;
							case 'billing_country_name':
								$countries_info              = new WC_Countries();
								$list_countries              = $countries_info->get_countries();
								$order_billing_country_code  = $order->get_billing_country();
								$order_data[ $field['key'] ] = $list_countries[ $order_billing_country_code ];
								break;
							case 'billing_state_name':
								$countries_info              = new WC_Countries();
								$order_billing_country_code  = $order->get_billing_country();
								$list_states_billing         = $countries_info->get_states( $order_billing_country_code );
								$order_billing_state_code    = $order->get_billing_state();
								$order_data[ $field['key'] ] = ( $order_billing_state_code && $list_states_billing && is_array( $list_states_billing ) ) ? $list_states_billing[ $order_billing_state_code ] : '';
								break;
							case 'shipping_state_name':
								$countries_info              = new WC_Countries();
								$order_shipping_country_code = $order->get_shipping_country();
								$list_states_shipping        = $countries_info->get_states( $order_shipping_country_code );
								$order_shipping_state_code   = $order->get_shipping_state();
								$order_data[ $field['key'] ] = ( $order_shipping_state_code && $list_states_shipping && is_array( $list_states_shipping ) ) ? $list_states_shipping[ $order_shipping_state_code ] : '';
								break;
							default:
								$order_data[ $field['key'] ] = '';
						}
					}
				}
				$results['content'][] = self::get_row_data( $results['header_row'], null, $order, array(
					'tracking_number' => $tracking_number,
					'carrier_slug'    => $carrier_slug,
					'carrier_url'     => $carrier_url,
					'carrier_name'    => $carrier_name,
					'carrier_type'    => $carrier_type,
					'time'            => time(),
				) );
			}
			foreach ( $order->get_items() as $line_item ) {
				$item_tracking_data    = $line_item->get_meta( '_vi_wot_order_item_tracking_data', true );
				$current_tracking_data = array(
					'tracking_number' => '',
					'carrier_slug'    => '',
					'carrier_url'     => '',
					'carrier_name'    => '',
					'carrier_type'    => '',
					'time'            => time(),
				);
				if ( $item_tracking_data ) {
					$item_tracking_data    = vi_wot_json_decode( $item_tracking_data );
					$current_tracking_data = array_pop( $item_tracking_data );
				}
				$carrier = $settings->get_shipping_carrier_by_slug( $current_tracking_data['carrier_slug'] );
				if ( is_array( $carrier ) && count( $carrier ) ) {
					$current_tracking_data['carrier_url']  = $settings->get_url_tracking( $carrier['url'], $current_tracking_data['tracking_number'], $current_tracking_data['carrier_slug'], $order->get_shipping_postcode(), false, false );
					$current_tracking_data['carrier_name'] = $carrier['name'];
					$current_tracking_data['carrier_type'] = $carrier['carrier_type'];
				}
				$results['content'][] = self::get_row_data( $results['header_row'], $line_item, $order, $current_tracking_data );
				$item_quantity        = $line_item->get_quantity();
				if ( $track_per_quantity && $item_quantity > 1 ) {
					$item_tracking_data = $line_item->get_meta( '_vi_wot_order_item_tracking_data_by_quantity', true );
					if ( $item_tracking_data ) {
						$item_tracking_data = vi_wot_json_decode( $item_tracking_data );
					} else {
						$item_tracking_data = array();
					}
					for ( $quantity_index = 0; $quantity_index <= $item_quantity - 2; $quantity_index ++ ) {
						$current_tracking_data = array(
							'tracking_number' => '',
							'carrier_slug'    => '',
							'carrier_url'     => '',
							'carrier_name'    => '',
							'carrier_type'    => '',
							'time'            => time(),
						);
						if ( isset( $item_tracking_data[ $quantity_index ] ) ) {
							$current_tracking_data = $item_tracking_data[ $quantity_index ];
						}
						$results['content'][] = self::get_row_data( $results['header_row'], $line_item, $order, $current_tracking_data );
					}
				}
			}
		}

		return $results;
	}

	/**
	 * @param $header_row
	 * @param $line_item WC_Order_Item_Product|WC_Order_Item|null
	 * @param $order WC_Order
	 * @param $current_tracking_data
	 *
	 * @return array
	 */
	private static function get_row_data( $header_row, $line_item, $order, $current_tracking_data ) {
		$order_data = array();
		$order_id   = $order->get_id();
		foreach ( $header_row as $field ) {
			if ( ! is_array( $field ) ) {
				continue;
			}
			if ( $field['type'] === 'order_item_meta' ) {
				if ( $line_item ) {
					switch ( $field['key'] ) {
						case '_variation_id':
							$order_data[ $field['key'] ] = $line_item->get_variation_id();
							break;
						case '_product_id':
							$order_data[ $field['key'] ] = $line_item->get_product_id();
							break;
						default:
							$order_data[ $field['key'] ] = $line_item->get_meta( $field['key'], true );
					}
				} else {
					$order_data[ $field['key'] ] = '';
				}
			} elseif ( $field['type'] === 'post_meta' ) {
				$order_data[ $field['key'] ] = get_post_meta( $order_id, $field['key'], true );
			} elseif ( $field ['type'] === 'wotv_field' ) {
				switch ( $field['key'] ) {
					case 'order_id':
						$order_data[ $field['key'] ] = $order_id;
						break;
					case 'tracking_number':
					case 'carrier_slug':
					case 'carrier_url':
					case 'carrier_name':
					case 'carrier_type':
						$order_data[ $field['key'] ] = $current_tracking_data[ $field['key'] ];
						break;
					case 'order_note':
						$order_data[ $field['key'] ] = self::get_order_notes_to_export( $order_id );
						break;
					case 'customer_note':
						$order_data[ $field['key'] ] = $order->get_customer_note();
						break;
					case 'order_number':
						$order_data[ $field['key'] ] = $order->get_order_number();
						break;
					case 'order_status':
						$order_data[ $field['key'] ] = $order->get_status();
						break;
					case 'order_subtotal':
						$order_data[ $field['key'] ] = $order->get_subtotal();
						break;
					case 'modification_date':
						$order_data[ $field['key'] ] = $order->get_date_modified() ? $order->get_date_modified()->format( ' Y-m-d H:i:s' ) : '';
						break;
					case 'create_date':
						$order_data[ $field['key'] ] = $order->get_date_created() ? $order->get_date_created()->format( ' Y-m-d H:i:s' ) : '';
						break;
					case 'shipping_method_title':
						$order_data[ $field['key'] ] = $order->get_shipping_method();
						break;
					case 'shipping_amount':
						$order_data[ $field['key'] ] = $order->get_shipping_total();
						break;
					case 'shipping_country_name':
						$countries_info              = new WC_Countries();
						$list_countries              = $countries_info->get_countries();
						$order_shipping_country_code = $order->get_shipping_country();
						$order_data[ $field['key'] ] = $list_countries[ $order_shipping_country_code ];
						break;
					case 'billing_country_name':
						$countries_info              = new WC_Countries();
						$list_countries              = $countries_info->get_countries();
						$order_billing_country_code  = $order->get_billing_country();
						$order_data[ $field['key'] ] = $list_countries[ $order_billing_country_code ];
						break;
					case 'billing_state_name':
						$countries_info              = new WC_Countries();
						$order_billing_country_code  = $order->get_billing_country();
						$list_states_billing         = $countries_info->get_states( $order_billing_country_code );
						$order_billing_state_code    = $order->get_billing_state();
						$order_data[ $field['key'] ] = ( $order_billing_state_code && $list_states_billing && is_array( $list_states_billing ) ) ? $list_states_billing[ $order_billing_state_code ] : '';
						break;
					case 'shipping_state_name':
						$countries_info              = new WC_Countries();
						$order_shipping_country_code = $order->get_shipping_country();
						$list_states_shipping        = $countries_info->get_states( $order_shipping_country_code );
						$order_shipping_state_code   = $order->get_shipping_state();
						$order_data[ $field['key'] ] = ( $order_shipping_state_code && $list_states_shipping && is_array( $list_states_shipping ) ) ? $list_states_shipping[ $order_shipping_state_code ] : '';
						break;
					case 'order_item_id':
						$order_data[ $field['key'] ] = $line_item ? $line_item->get_id() : '';
						break;
					case 'order_item_cost':
						$order_data[ $field['key'] ] = $line_item ? ( $line_item->get_subtotal() / $line_item->get_quantity() ) : '';
						break;
					case 'order_item_quantity':
						$order_data[ $field['key'] ] = $line_item ? $line_item->get_quantity() : '';
						break;
					case 'product_name':
						$order_data[ $field['key'] ] = $line_item ? $line_item->get_name() : '';
						break;
					case 'product_sku':
						if ( ! $line_item ) {
							$order_data[ $field['key'] ] = '';
						} else {
							$product                     = $line_item->get_product();
							$order_data[ $field['key'] ] = $product ? $product->get_sku() : '';
						}
						break;
					case 'product_link':
						if ( ! $line_item ) {
							$order_data[ $field['key'] ] = '';
						} else {
							$product                     = $line_item->get_product();
							$order_data[ $field['key'] ] = $product ? $product->get_permalink() : '';
						}
						break;
					case 'product_img_link':
						if ( ! $line_item ) {
							$order_data[ $field['key'] ] = '';
						} else {
							$product                     = $line_item->get_product();
							$order_data[ $field['key'] ] = $product ? get_the_post_thumbnail_url( $product->get_id() ) : '';
						}
						break;
					case 'product_current_price':
						if ( ! $line_item ) {
							$order_data[ $field['key'] ] = '';
						} else {
							$product                     = $line_item->get_product();
							$order_data[ $field['key'] ] = $product ? $product->get_price() : '';
						}
						break;
					case 'product_short_description':
						if ( ! $line_item ) {
							$order_data[ $field['key'] ] = '';
						} else {
							$product                     = $line_item->get_product();
							$order_data[ $field['key'] ] = $product ? $product->get_short_description() : '';
						}
						break;
					case 'product_description':
						if ( ! $line_item ) {
							$order_data[ $field['key'] ] = '';
						} else {
							$product                     = $line_item->get_product();
							$order_data[ $field['key'] ] = $product ? $product->get_description() : '';
						}
						break;
					case 'product_tag':
						if ( ! $line_item ) {
							$order_data[ $field['key'] ] = '';
						} else {
							$product_tags       = wp_get_post_terms( $line_item->get_product_id(), 'product_tag' );
							$product_tags_array = array();
							if ( count( $product_tags ) > 0 ) {
								foreach ( $product_tags as $term ) {
									$product_tags_array[] = $term->name;
								}
							}
							$order_data[ $field['key'] ] = implode( ', ', $product_tags_array );
						}
						break;
					case 'product_category':
						if ( ! $line_item ) {
							$order_data[ $field['key'] ] = '';
						} else {
							$product_categories = wp_get_post_terms( $line_item->get_product_id(), 'product_cat' );
							if ( count( $product_categories ) > 0 ) {
								$order_data[ $field['key'] ] = $product_categories[0]->name;
							} else {
								$order_data[ $field['key'] ] = '';
							}
						}
						break;
					case 'product_all_category':
						if ( ! $line_item ) {
							$order_data[ $field['key'] ] = '';
						} else {
							$product_categories     = wp_get_post_terms( $line_item->get_product_id(), 'product_cat' );
							$product_categories_arr = array();
							if ( count( $product_categories ) > 0 ) {
								foreach ( $product_categories as $term ) {
									$product_categories_arr[] = $term->name;
								}
							}
							$order_data[ $field['key'] ] = implode( ', ', $product_categories_arr );
						}
						break;
					default:
				}
			}
		}

		return $order_data;
	}

	private static function get_order_ids_to_set_fields() {
		global $wpdb;
		$sql          = "SELECT count(*) FROM {$wpdb->posts} Where  post_type = 'shop_order'";
		$count_orders = $wpdb->get_col( $sql );
		$count_orders = $count_orders[0];
		if ( $count_orders > 1000 ) {
			$order_ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} Where  post_type = 'shop_order' LIMIT 1000" );
		} else {
			$order_ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} Where  post_type = 'shop_order'" );
		}

		return $order_ids;
	}

	private static function get_fields_post_meta() {
		$order_ids = self::get_order_ids_to_set_fields();
		$results   = array();
		global $wpdb;
		if ( $order_ids && is_array( $order_ids ) && count( $order_ids ) ) {
			$order_ids = join( ',', $order_ids );
			$fields    = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} Where  post_id IN ( {$order_ids} )" );
			if ( is_array( $fields ) && count( $fields ) ) {
				for ( $i = 0; $i < count( $fields ); $i ++ ) {
					$key = $fields[ $i ];
					if ( in_array( $key, array(
						'_billing_country',
						'_billing_state',
						'_shipping_country',
						'_shipping_state'
					) ) ) {
						$title     = ucwords( str_replace( array( '_billing_', '_shipping_' ), array(
								'( Billing )',
								'( Shipping )'
							), $key ) ) . ' Code';
						$results[] = array(
							'type'  => 'post_meta',
							'key'   => $key,
							'title' => $title,
						);
						continue;
					}
					$item = trim( str_replace( '_', ' ', $key ) );
					if ( strpos( $item, 'billing' ) === 0 ) {
						$item = '( Billing ) ' . $item;
					} elseif ( strpos( $item, 'shipping' ) === 0 ) {
						$item = '( Shipping ) ' . $item;
					} else {
						$item = '( Order ) ' . $item;
					}
					$item      = ucwords( $item );
					$results[] = array(
						'type'  => 'post_meta',
						'key'   => $key,
						'title' => $item,
					);
				}

				$field_other = array(
					array(
						'type'  => 'wotv_field',
						'key'   => 'order_subtotal',
						'title' => esc_html__( '( Order ) Order Subtotal', 'woocommerce-orders-tracking' ),
					),
					array(
						'type'  => 'wotv_field',
						'key'   => 'modification_date',
						'title' => esc_html__( '( Order ) Modification date', 'woocommerce-orders-tracking' ),
					),
					array(
						'type'  => 'wotv_field',
						'key'   => 'create_date',
						'title' => esc_html__( '( Order ) Create date', 'woocommerce-orders-tracking' ),
					),
					array(
						'type'  => 'wotv_field',
						'key'   => 'shipping_method_title',
						'title' => esc_html__( '( Shipping ) Shipping Method Title', 'woocommerce-orders-tracking' ),
					),
					array(
						'type'  => 'wotv_field',
						'key'   => 'shipping_amount',
						'title' => esc_html__( '( Shipping ) Shipping Amount', 'woocommerce-orders-tracking' ),
					),
					array(
						'type'  => 'wotv_field',
						'key'   => 'shipping_country_name',
						'title' => esc_html__( '( Shipping ) Country Name', 'woocommerce-orders-tracking' ),
					),
					array(
						'type'  => 'wotv_field',
						'key'   => 'billing_country_name',
						'title' => esc_html__( '( Billing ) Country Name', 'woocommerce-orders-tracking' ),
					),
					array(
						'type'  => 'wotv_field',
						'key'   => 'billing_state_name',
						'title' => esc_html__( '( Billing ) State Name', 'woocommerce-orders-tracking' ),
					),
					array(
						'type'  => 'wotv_field',
						'key'   => 'shipping_state_name',
						'title' => esc_html__( '( Shipping ) State Name', 'woocommerce-orders-tracking' ),
					),
				);
				$results     = array_merge( $field_other, $results );
			}
		}

		return $results;
	}

	private static function get_fields_order_line_item() {
		$order_ids = self::get_order_ids_to_set_fields();
		$results   = array();
		global $wpdb;
		if ( $order_ids && is_array( $order_ids ) && count( $order_ids ) ) {
			$order_ids = join( ',', $order_ids );
			$fields    = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->prefix}woocommerce_order_itemmeta as table1  JOIN {$wpdb->prefix}woocommerce_order_items  as table2 ON table1.order_item_id = table2.order_item_id  Where table2.order_item_type ='line_item' AND table2.order_id  IN ( {$order_ids} )" );
			if ( is_array( $fields ) && count( $fields ) ) {
				for ( $i = 0; $i < count( $fields ); $i ++ ) {
					$key = $fields[ $i ];
					if ( in_array( $key, array( 'Items', '_line_tax_data' ) ) ) {
						continue;
					}
					if ( $key === '_vi_order_item_carrier_id' ) {
						$results[] = array(
							'type'  => 'order_item_meta',
							'key'   => '_vi_order_item_carrier_id',
							'title' => esc_html__( '( Order/Order Item ) Carrier Slug', 'woocommerce-orders-tracking' ),
						);
						continue;
					}
					$item      = trim( str_replace( array( '_vi_order_item_', '_' ), array( ' ', ' ' ), $key ) );
					$item      = '( Order Item )' . ucwords( $item );
					$results[] = array(
						'type'  => 'order_item_meta',
						'key'   => $key,
						'title' => $item,
					);
				}
				$t       = array(
					array(
						'type'  => 'wotv_field',
						'key'   => 'order_item_id',
						'title' => esc_html__( '( Order Item ) Item ID', 'woocommerce-orders-tracking' ),
					),
					array(
						'type'  => 'wotv_field',
						'key'   => 'order_item_cost',
						'title' => esc_html__( '( Order Item ) Item Cost', 'woocommerce-orders-tracking' ),
					),
					array(
						'type'  => 'wotv_field',
						'key'   => 'order_item_quantity',
						'title' => esc_html__( '( Order Item ) Item Quantity', 'woocommerce-orders-tracking' ),
					),
					array(
						'type'  => 'wotv_field',
						'key'   => 'product_name',
						'title' => esc_html__( '( Order Item ) Product Name', 'woocommerce-orders-tracking' ),
					),
					array(
						'type'  => 'wotv_field',
						'key'   => 'product_sku',
						'title' => esc_html__( '( Order Item ) Product Sku', 'woocommerce-orders-tracking' ),
					),
					array(
						'type'  => 'wotv_field',
						'key'   => 'product_link',
						'title' => esc_html__( '( Order Item ) Product Link', 'woocommerce-orders-tracking' ),
					),
					array(
						'type'  => 'wotv_field',
						'key'   => 'product_img_link',
						'title' => esc_html__( '( Order Item ) Product Image Link', 'woocommerce-orders-tracking' ),
					),
					array(
						'type'  => 'wotv_field',
						'key'   => 'product_current_price',
						'title' => esc_html__( '( Order Item ) Product Current Price', 'woocommerce-orders-tracking' ),
					),
					array(
						'type'  => 'wotv_field',
						'key'   => 'product_short_description',
						'title' => esc_html__( '( Order Item ) Product Short Description', 'woocommerce-orders-tracking' ),
					),
					array(
						'type'  => 'wotv_field',
						'key'   => 'product_description',
						'title' => esc_html__( '( Order Item ) Product Description', 'woocommerce-orders-tracking' ),
					),
					array(
						'type'  => 'wotv_field',
						'key'   => 'product_tag',
						'title' => esc_html__( '( Order Item ) Product Tags', 'woocommerce-orders-tracking' ),
					),
					array(
						'type'  => 'wotv_field',
						'key'   => 'product_category',
						'title' => esc_html__( '( Order Item ) Product Category', 'woocommerce-orders-tracking' ),
					),
					array(
						'type'  => 'wotv_field',
						'key'   => 'product_all_category',
						'title' => esc_html__( '( Order Item ) Product Categories', 'woocommerce-orders-tracking' ),
					),
				);
				$results = array_merge( $t, $results );
			}
		}

		return $results;
	}

	public static function get_fields_to_select() {
		$field_other      = array(
			array(
				'type'  => 'wotv_field',
				'key'   => 'order_id',
				'title' => esc_html__( '( Order ) Order ID', 'woocommerce-orders-tracking' ),
			),
			array(
				'type'  => 'wotv_field',
				'key'   => 'order_number',
				'title' => esc_html__( '( Order ) Order Number', 'woocommerce-orders-tracking' ),
			),
			array(
				'type'  => 'wotv_field',
				'key'   => 'order_status',
				'title' => esc_html__( '( Order ) Order Status', 'woocommerce-orders-tracking' ),
			),
			array(
				'type'  => 'wotv_field',
				'key'   => 'tracking_number',
				'title' => esc_html__( '( Order/Order Item ) Tracking Number', 'woocommerce-orders-tracking' ),
			),
			array(
				'type'  => 'wotv_field',
				'key'   => 'carrier_slug',
				'title' => esc_html__( '( Order/Order Item ) Carrier Slug', 'woocommerce-orders-tracking' ),
			),
			array(
				'type'  => 'wotv_field',
				'key'   => 'carrier_url',
				'title' => esc_html__( '( Order/Order Item ) Tracking URL', 'woocommerce-orders-tracking' ),
			),
			array(
				'type'  => 'wotv_field',
				'key'   => 'carrier_name',
				'title' => esc_html__( '( Order/Order Item ) Carrier Name', 'woocommerce-orders-tracking' ),
			),
			array(
				'type'  => 'wotv_field',
				'key'   => 'order_note',
				'title' => esc_html__( '( Order ) Order Note', 'woocommerce-orders-tracking' ),
			),
			array(
				'type'  => 'wotv_field',
				'key'   => 'customer_note',
				'title' => esc_html__( '( Order ) Customer Note', 'woocommerce-orders-tracking' ),
			),
		);
		$field_post_meta  = self::get_fields_post_meta();
		$field_order_item = self::get_fields_order_line_item();
		$results          = array_merge( $field_other, $field_post_meta, $field_order_item );

		return $results;
	}

	public static function get_order_notes_to_export( $order_id ) {
		if ( ! isset( self::$order_notes[ $order_id ] ) ) {
			self::$order_notes[ $order_id ] = self::get_order_notes( $order_id );
		}

		return self::$order_notes[ $order_id ];
	}

	public static function get_order_notes( $order_id ) {
		$notes       = wc_get_order_notes( array( 'order_id' => $order_id ) );
		$return      = '';
		$notes_count = count( $notes );
		if ( $notes_count ) {
			foreach ( $notes as $note ) {
				if ( $note->added_by !== 'system' ) {
					if ( $note->date_created ) {
						$return .= "[{$note->date_created->date( 'Y-m-d H:i:s' )}] ";
					} else {
						$return .= "[] ";
					}
					$return .= wpautop( wptexturize( wp_kses_post( $note->content ) ) );
				}
			}
		}

		return $return;
	}
}