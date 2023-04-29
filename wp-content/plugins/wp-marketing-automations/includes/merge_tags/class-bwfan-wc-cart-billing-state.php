<?php

class BWFAN_WC_Cart_Billing_State extends Cart_Merge_Tag {

	private static $instance = null;


	public function __construct() {
		$this->tag_name        = 'cart_billing_state';
		$this->tag_description = __( 'Cart Billing State', 'wp-marketing-automations' );
		add_shortcode( 'bwfan_cart_billing_state', array( $this, 'parse_shortcode' ) );
		$this->priority = 7;
	}

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Parse the merge tag and return its value.
	 *
	 * @param $attr
	 *
	 * @return mixed|string|void
	 */
	public function parse_shortcode( $attr ) {
		if ( true === BWFAN_Merge_Tag_Loader::get_data( 'is_preview' ) ) {
			return $this->get_dummy_preview();
		}

		$cart_details = BWFAN_Merge_Tag_Loader::get_data( 'cart_details' );

		if ( empty( $cart_details ) ) {
			$abandoned_id = BWFAN_Merge_Tag_Loader::get_data( 'cart_abandoned_id' );
			$cart_details = BWFAN_Model_Abandonedcarts::get( $abandoned_id );
		}

		if ( empty( $cart_details ) ) {
			return $this->parse_shortcode_output( '', $attr );
		}
		
		$field_value  = $this->get_cart_value( 'billing_state', $cart_details );

		return $this->parse_shortcode_output( $field_value, $attr );
	}

	/**
	 * Show dummy value of the current merge tag.
	 *
	 * @return string
	 */
	public function get_dummy_preview() {
		return 'Sydney';
	}

	public function post_value_check( $field_value ) {
		$country = ( isset( $this->checkout_data['billing_country'] ) && ! empty( $this->checkout_data['billing_country'] ) ) ? $this->checkout_data['billing_country'] : '';
		if ( empty( $country ) || empty( $field_value ) ) {
			return '';
		}

		$states = WC()->countries->get_states( $country );
		$state  = ! empty( $states[ $field_value ] ) ? $states[ $field_value ] : '';

		return $state;
	}


}

/**
 * Register this merge tag to a group.
 */
if ( bwfan_is_woocommerce_active() ) {
	BWFAN_Merge_Tag_Loader::register( 'wc_ab_cart', 'BWFAN_WC_Cart_Billing_State', null, 'Abandoned Cart' );
}