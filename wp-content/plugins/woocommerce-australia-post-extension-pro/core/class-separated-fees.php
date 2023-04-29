<?php

namespace AustraliaPost\Core;

use WC_Order;
use WC_Order_Item_Fee;

class Separated_Fees {
	private $auspost_settings;
	private $delivery_confirmation = array(
		'INT_PARCEL_SEA_OWN_PACKAGING',
		'INT_PARCEL_AIR_OWN_PACKAGING',
		'INT_PARCEL_STD_OWN_PACKAGING',
		'INT_PARCEL_EXP_OWN_PACKAGING',
		'AUS_PARCEL_REGULAR',
		'AUS_PARCEL_EXPRESS',
		'AUS_PARCEL_REGULAR_SATCHEL_SMALL',
		'AUS_PARCEL_REGULAR_SATCHEL_MEDIUM',
		'AUS_PARCEL_REGULAR_SATCHEL_LARGE',
		'AUS_PARCEL_REGULAR_SATCHEL_EXTRA_LARGE',
		'AUS_PARCEL_EXPRESS_SATCHEL_SMALL',
		'AUS_PARCEL_EXPRESS_SATCHEL_MEDIUM',
		'AUS_PARCEL_EXPRESS_SATCHEL_LARGE',
		'AUS_PARCEL_EXPRESS_SATCHEL_EXTRA_LARGE',
		'AUS_LETTER_EXPRESS_SMALL',
		'AUS_LETTER_EXPRESS_MEDIUM',
		'AUS_LETTER_EXPRESS_LARGE',
		'AUS_LETTER_PRIORITY_SMALL',
		'AUS_LETTER_PRIORITY_LARGE_125',
		'AUS_LETTER_PRIORITY_LARGE_250',
		'AUS_LETTER_PRIORITY_LARGE_500',
	);
	private $shipping_id = '';


	/**
	 * The single instance of the class.
	 *
	 * @var Separated_Fees
	 * @since 2.1.1
	 */
	protected static $_instance = null;

	/**
	 * @return Separated_Fees
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}


	public function __construct() {

		if ( ! function_exists( 'WC' ) ) {
			return;
		}
		$shipping_settings = $this->get_shipping_method_settings();

		if ( count($shipping_settings) < 1 ) {
			return;
		}

		if ( isset( $_POST['shipping_method'] ) ) {
			$this->shipping_id = explode( ':', $_POST['shipping_method'][0] );
			if ( isset( $this->shipping_id[1] ) ) {
				$this->shipping_id = $this->shipping_id[1];
			}
		}
		$this->auspost_settings        = $shipping_settings;
		$this->seperate_extracover_sod = ( isset( $this->auspost_settings['seperate_extracover_sod'] ) ) ? $this->auspost_settings['seperate_extracover_sod'] : 'no';

		if ($this->seperate_extracover_sod === 'no') {
		    return;
        }

		$this->enable_extra_cover      = $this->auspost_settings['enable_extra_cover'];
		$this->signature_on_delivery   = $this->auspost_settings['signature_on_delivery'];

		add_action( 'woocommerce_calculate_totals', array( $this, 'update_fee_total' ), 1 );
		if ( ! is_cart() ) {
			add_action( 'woocommerce_review_order_after_shipping', array( $this, 'add_optional_fee_checkout' ) );
		}

		add_action( 'woocommerce_new_order', array( $this, 'process_extra_fees' ) );
		add_action( 'woocommerce_cart_calculate_fees', array( $this, 'setup_fees' ), 10 );
		add_action( 'woocommerce_checkout_order_processed', array( $this, 'reset_fees' ) );

		if ( version_compare( WC()->version, '3.2.0', '>=' ) ) {
			add_action( 'woocommerce_checkout_order_processed', array( $this, 'recalculate_totals' ) );
		}

	}

	/**
	 * @param int $order_id
	 *
	 * @since 2.0.3
	 */
	public function recalculate_totals( $order_id ) {
		$order = wc_get_order( $order_id );
		$order->calculate_totals( false );
	}

	public function setup_fees() {
		// Allow the fees only for Australia Post.
		if(isset($_POST['shipping_method']) && strpos($_POST['shipping_method'][0], 'aus:') !== 0){
			return;
		}

		$fees = array();
		if ( $this->enable_extra_cover == 'yes' && ( is_numeric( WC()->session->extra_cover_total ) && WC()->session->extra_cover_total > 0 ) ) {
			$fees['extra_cover']['amount']   = WC()->session->extra_cover_total;
			$fees['extra_cover']['name'] = $this->extra_cover_label();
			$fees['extra_cover']['selected'] = ( isset( WC()->session->auspost_fees['extra_cover'] ) && WC()->session->auspost_fees['extra_cover']['selected'] == 'true' ) ? 'true' : 'false';
		}
		if ( $this->signature_on_delivery == 'yes' && is_numeric( WC()->session->sod_fee ) && WC()->session->sod_fee > 0 ) {
			$fees['sod']['amount']   = WC()->session->sod_fee;
			$fees['sod']['name'] = $this->sod_label();
			$fees['sod']['selected'] = ( isset( WC()->session->auspost_fees['sod'] ) && WC()->session->auspost_fees['sod']['selected'] == 'true' ) ? 'true' : 'false';
		}
		WC()->session->auspost_fees = $fees;
		if ( ! empty( $fees ) ) {
			$total_fee = 0;
			foreach ( $fees as $fee ) {
				if ( $fee['selected'] == "true" ) {
					$total_fee += $fee['amount'];
				}
			}
			if ( version_compare( WC()->version, '3.2.0', '>=' ) ) {
				$total = floatval( WC()->cart->get_total( 'int' ) );
				WC()->cart->set_total( $total_fee + $total );
			}
		}
		add_action( 'woocommerce_cart_totals_after_shipping', array( $this, 'add_new_fee_row' ), 10 );

	}

	/**
	 * add_new_fee_row function.
	 *
	 * @access public
	 * @return void
	 */
	public function add_new_fee_row() {

		$fees = array();
		if ( is_cart() ) {
			//cart page
			if ( isset( $_POST['fees'] ) ) {
				parse_str( $_POST['fees'], $fees );
			}
			if ( isset( $fees['fees'] ) ) {
				$fees = $fees['fees'];
			}
		} else {
			//checkout page
			if ( isset( $_POST['post_data'] ) ) {
				parse_str( $_POST['post_data'], $fees );
				parse_str( $fees['fees'], $fees );
			}
		}
		if ( ! empty( $fees ) ) {
			$session_fees = WC()->session->auspost_fees;
			if ( isset( $fees['sod'] ) or isset( $fees['extra_cover'] ) ) {
				if ( isset( $fees['sod'] ) ) {
					$session_fees['sod']['selected'] = 'true';
				} else {
					$session_fees['sod']['selected'] = 'false';
				}
				if ( isset( $fees['extra_cover'] ) ) {
					$session_fees['extra_cover']['selected'] = 'true';
				} else {
					$session_fees['extra_cover']['selected'] = 'false';
				}
			} else {
				$session_fees['extra_cover']['selected'] = 'false';
				$session_fees['sod']['selected']         = 'false';
			}
		} else {
			$session_fees = ( isset( WC()->session->auspost_fees ) ) ? WC()->session->auspost_fees : array();
		}
		WC()->session->auspost_fees = $session_fees;
		if ( ( isset( $_POST['shipping_method'] ) && ! in_array( $this->shipping_id, $this->delivery_confirmation ) ) ) {
			unset( $session_fees['sod'] );
		} else {
			$session_fees['sod']['amount']   = WC()->session->sod_fee;
			$session_fees['sod']['name']     = $this->sod_label();
			$session_fees['sod']['selected'] = ( isset( WC()->session->auspost_fees['sod'] ) && WC()->session->auspost_fees['sod']['selected'] == 'true' ) ? 'true' : 'false';
		}
		if ( version_compare( WC()->version, '3.2.0', '>=' ) ) {
			if ( count( $session_fees ) ) {
				$total_fee = 0;
				foreach ( $session_fees as $fee ) {
					if ( $fee['selected'] == "true" ) {
						$total_fee += $fee['amount'];
					}
				}
				if ( version_compare( WC()->version, '3.2.0', '>=' ) ) {
					$total = floatval( WC()->cart->get_total( 'int' ) );
					WC()->cart->set_total( $total_fee + $total );
				}
			}
		}
		foreach ( $session_fees as $key => $fee ) {
			if ( isset( $fee['name'] ) && isset( $fee['amount'] ) && $fee['amount'] > 0 ) {
				echo '<tr class="fee fee-' . sanitize_text_field( $key ) . '">
                <th class="name">' . $fee['name'] . '</th>
                <td data-title="' . sanitize_text_field( $fee['name'] ) . '"><input id="auspost_fee_' . $key . '" type="checkbox" name="fees[' . $key . ']" value="' . $fee['amount'] . '" title="' . sanitize_text_field( $fee['name'] ) . '" class="fee" ';
				if ( $fee['selected'] == 'true' ) {
					echo ' checked="checked"';
				}
				echo '/>';
				echo '<label for="auspost_fee_' . $key . '"> ' . wc_price( $fee['amount'] ) . '</label>';
				echo '</td>
                </tr>';
			}
		}


	}

	/**
	 * update_fee_total function.
	 *
	 * @access public
	 * @return void
	 */
	public function update_fee_total() {

		$fees = array();
		if ( is_cart() ) {
			//cart page
			if ( isset( $_POST['fees'] ) ) {
				parse_str( $_POST['fees'], $fees );
			}
			if ( isset( $fees['fees'] ) ) {
				$fees = $fees['fees'];
			}
		} else {
			//checkout page
			if ( isset( $_POST['post_data'] ) ) {
				parse_str( $_POST['post_data'], $fees );
				if ( isset( $fees['fees'] ) ) {
					$fees = $fees['fees'];
				}
			}
		}

		if ( ! empty( $fees ) && $fees != 'false' ) {
			$session_fees = WC()->session->auspost_fees;
			if ( isset( $fees['sod'] ) or isset( $fees['extra_cover'] ) ) {
				if ( isset( $fees['sod'] ) ) {
					$session_fees['sod']['selected'] = 'true';
				} else {
					$session_fees['sod']['selected'] = 'false';
				}
				if ( isset( $fees['extra_cover'] ) ) {
					$session_fees['extra_cover']['selected'] = 'true';
				} else {
					$session_fees['extra_cover']['selected'] = 'false';
				}
			} else {
				$session_fees['extra_cover']['selected'] = 'false';
				$session_fees['sod']['selected']         = 'false';
			}
		} else {


			$session_fees = ( isset( WC()->session->auspost_fees ) ) ? WC()->session->auspost_fees : array();

			$session_fees['extra_cover']['selected'] = ( isset( $session_fees['extra_cover'] ) && $session_fees['extra_cover']['selected'] == 'true' ) ? 'true' : 'false';
			$session_fees['sod']['selected']         = ( isset( $session_fees['sod'] ) && $session_fees['sod']['selected'] == 'true' ) ? 'true' : 'false';
			if ( $fees == 'false' ) {
				$session_fees['extra_cover']['selected'] = 'false';
				$session_fees['sod']['selected']         = 'false';
			}

		}
		if ( ( isset( $_POST['shipping_method'] ) && ! in_array( $this->shipping_id, $this->delivery_confirmation ) ) ) {
			unset( $session_fees['sod'] );
		}
		if ( count( $session_fees ) ) {
			$total_fee = 0;
			foreach ( $session_fees as $fee ) {
				if ( $fee['selected'] == "true" ) {
					$total_fee += $fee['amount'];
				}
			}
			if ( version_compare( WC()->version, '3.2.0', '>=' ) ) {
				$total = floatval( WC()->cart->get_total( 'int' ) );
				WC()->cart->set_total( $total_fee + $total );
			} else {
				WC()->cart->fee_total = $total_fee;
			}
		}
	}

	/**
	 * add_optional_fee_checkout function.
	 *
	 * @access public
	 * @return void
	 */
	public function add_optional_fee_checkout() {

		$post_key = ( is_cart() ) ? 'fees' : 'post_data';
		if ( isset( $_POST[ $post_key ] ) ) {
			parse_str( $_POST[ $post_key ], $post_data );
			$session_fees = WC()->session->auspost_fees;
			if ( isset( $post_data['fees']['sod'] ) or isset( $post_data['fees']['extra_cover'] ) ) {
				if ( $this->signature_on_delivery == 'yes' ) {
					if ( isset( $post_data['fees']['sod'] ) ) {
						$session_fees['sod']['selected'] = 'true';
					} else {
						$session_fees['sod']['selected'] = 'false';
					}
				}
				if ( $this->enable_extra_cover == 'yes' ) {
					if ( isset( $post_data['fees']['extra_cover'] ) ) {
						$session_fees['extra_cover']['selected'] = 'true';
					} else {
						$session_fees['extra_cover']['selected'] = 'false';
					}
				}
			} else {
				if ( $this->enable_extra_cover == 'yes' ) {
					$session_fees['extra_cover']['selected'] = 'false';
				}
				if ( $this->signature_on_delivery == 'yes' ) {
					$session_fees['sod']['selected'] = 'false';
				}
			}
		} else {
			$session_fees = ( isset( WC()->session->auspost_fees ) ) ? WC()->session->auspost_fees : array();
		}
		WC()->session->auspost_fees = $session_fees;
		if ( version_compare( WC()->version, '3.2.0', '>=' ) ) {
			if ( count( $session_fees ) ) {
				$total_fee = 0;
				foreach ( $session_fees as $fee ) {
					if ( $fee['selected'] == "true" ) {
						$total_fee += $fee['amount'];
					}
				}
				if ( version_compare( WC()->version, '3.2.0', '>=' ) ) {
					$total = floatval( WC()->cart->get_total( 'int' ) );
					WC()->cart->set_total( $total_fee + $total );
				} else {
					WC()->cart->fee_total = $total_fee;
				}
			}
		}
		if ( ( isset( $_POST['shipping_method'] ) && ! in_array( $this->shipping_id, $this->delivery_confirmation ) ) ) {
			unset( $session_fees['sod'] );
		}
		if ( count( $session_fees ) ) {
			foreach ( $session_fees as $key => $fee ) {
				if ( isset( $fee['name'] ) && isset( $fee['amount'] ) && $fee['amount'] > 0 ) {
					$tax_price  = ( isset( $fee['tax_price'] ) ) ? $fee['tax_price'] : 0;
					$fee_amount = ( WC()->cart->get_tax_price_display_mode() == 'excl' ) ? $fee['amount'] : $fee['amount'] + $tax_price;
					?>
                    <tr class="fee-optional">
                        <th><?php echo esc_html( $fee['name'] ); ?></th>
                        <td><input id="auspost_fee_<?php echo $key; ?>" type="checkbox" name="fees[<?php echo $key; ?>]"
                                   value="<?php echo $fee['amount']; ?>"
                                   title="<?php echo sanitize_text_field( $fee['name'] ); ?>"
                                   class="fee" <?php if ( $fee['selected'] == 'true' ) {
								echo ' checked="checked"';
							} ?> /><label
                                for="auspost_fee_<?php echo $key; ?>"> <?php echo wc_price( $fee_amount ); ?></label>
                        </td>
                    </tr>
					<?php
				}
			}
		}
	}

	/**
	 * process_optional_fees function.
	 *
	 * @access public
	 * @return void
	 */
	public function process_extra_fees( $order_id ) {

		if ( ! is_ajax() ) {
			remove_action( 'woocommerce_calculate_totals', array( $this, 'update_fee_total' ), 15 );
		}
		if ( is_cart() ) {
			parse_str( $_POST['fees'], $fees );
			$_POST['fees'] = $fees['fees'];
		}
		if ( isset( $_POST['fees'] ) ) {
			$session_fees = WC()->session->auspost_fees;
			if ( isset( $_POST['fees']['sod'] ) or isset( $_POST['fees']['extra_cover'] ) ) {
				if ( isset( $_POST['fees']['sod'] ) ) {
					$session_fees['sod']['selected'] = 'true';
				} else {
					$session_fees['sod']['selected'] = 'false';
				}
				if ( isset( $_POST['fees']['extra_cover'] ) ) {
					$session_fees['extra_cover']['selected'] = 'true';
				} else {
					$session_fees['extra_cover']['selected'] = 'false';
				}
			} else {
				$session_fees['extra_cover']['selected'] = 'false';
				$session_fees['sod']['selected']         = 'false';
			}
		} else {
			$session_fees = ( ! empty( WC()->session->auspost_fees ) ) ? WC()->session->auspost_fees : array();
		}
		WC()->session->auspost_fees = $session_fees;

		if ( ( isset( $_POST['shipping_method'] ) && ! in_array( $this->shipping_id, $this->delivery_confirmation ) ) ) {
			unset( $session_fees['sod'] );
		}
		if ( count( $session_fees ) ) {
			$total_fee = 0;
			foreach ( $session_fees as $key => $fee ) {
				if ( $fee['selected'] == "true" ) {
					$this->add_fee( $order_id, $fee );
					$total_fee += $fee['amount'];
				}
			}
			if ( version_compare( WC()->version, '3.2.0', '>=' ) ) {
				$total = floatval( WC()->cart->get_total( 'int' ) );
				WC()->cart->set_total( $total_fee + $total );
			} else {
				WC()->cart->fee_total = $total_fee;
			}

		}


	}

	/**
	 * reset_fees function.
	 *
	 * @access public
	 * @return void
	 */
	public function reset_fees() {

		WC()->session->auspost_fees      = array();
		WC()->session->extra_cover_total = null;
		WC()->session->sod_fee           = null;
		unset( WC()->session->auspost_fees );
		unset( WC()->session->extra_cover_total );
		unset( WC()->session->sod_fee );
	}

	public function add_fee( $order_id, $fee ) {
	    if (!class_exists('WC_Order')) {
	        return;
	    }
		$order = new WC_Order( $order_id );

		if ( version_compare( WC()->version, '3.0.0', 'lt' ) ) {
			WC()->cart->add_fee( $fee['name'], $fee['amount'] );
		} elseif ( version_compare( WC()->version, '3.2.0', '>=' ) ) {
			WC()->cart->fees_api()->add_fee( $fee );
			$order = new WC_Order( $order_id );
			$item  = new WC_Order_Item_Fee();
			$item->set_props( array(
				'name'      => $fee['name'],
				'tax_class' => 0,
				'total'     => $fee['amount'],
				'total_tax' => 0,
				'order_id'  => $order_id,
			) );
			$item->save();
			$order->add_item( $item );
		} else {
			$item = new WC_Order_Item_Fee();
			$item->set_props( array(
				'name'      => $fee['name'],
				'tax_class' => 0,
				'total'     => $fee['amount'],
				'total_tax' => 0,
				'order_id'  => $order_id,
			) );
			$item->save();
			$order->add_item( $item );
		}


	}

	/**
	 * @return string
	 */
	private function sod_label() {
	    $label = __('Signature on Delivery', 'woocommerce-australia-post-pro');
		if (isset($this->auspost_settings['signature_on_delivery_label']) && trim($this->auspost_settings['signature_on_delivery_label']) !== '') {
			$label = $this->auspost_settings['signature_on_delivery_label'];
		}

		return apply_filters('australia_post_signature_on_delivery_label', $label);
	}

	/**
	 * @return string
	 */
	private function extra_cover_label() {
		$label = __('Extra Cover', 'woocommerce-australia-post-pro');
		if (isset($this->auspost_settings['extra_cover_label']) && trim($this->auspost_settings['extra_cover_label']) !== '') {
			$label = $this->auspost_settings['extra_cover_label'];
		}

        return apply_filters('australia_post_extra_cover_label', $label);
	}

	/**
	 * @return array
	 */
	private function get_shipping_method_settings()
    {
	    $chosen_method = $this->get_chosen_method();
	    if (is_null($chosen_method)) {
	        return [];
        }

	    if ($chosen_method[0] !== 'aus') {
	        return [];
	    }

		$instance_settings = [];

	    if (isset($chosen_method[2]) && intval($chosen_method) > 0) {
		    $instance_id = $chosen_method[2];
		    $instance_settings = get_option(sprintf('woocommerce_instance_auspost_%s_settings', $instance_id));
	    }

	    if (!is_array($instance_settings)) {
		    return [];
	    }

	    if (count($instance_settings) < 1) {
	        return [];
	    }

	    $global_settings = get_option('woocommerce_instance_auspost_settings');
	    $global_settings = (is_array($global_settings))? $global_settings: [];
	    return array_merge($instance_settings, $global_settings);

	}

	/**
	 * @return array|null
	 */
	private function get_chosen_method ()
    {
	    $chosen_method_from_session = null;
	    $chosen_method = null;

	    if (function_exists('WC') && WC()->session) {
	        $chosen_method_from_session = WC()->session->get('chosen_shipping_methods');
        }

		if (isset($_POST['shipping_method'])) {
			$chosen_method = $_POST['shipping_method'];
		} elseif ($chosen_method_from_session !== null) {
			$chosen_method = $chosen_method_from_session;
		}
		if (!$chosen_method) {
			return null;
		}

		$chosen_method = explode(':', $chosen_method[0]);
        return $chosen_method;
	}
}
