<?php

namespace Objectiv\Plugins\Checkout\Compatibility\Plugins;

use Aelia\WC\TaxDisplayByCountry\Definitions;
use Objectiv\Plugins\Checkout\Compatibility\CompatibilityAbstract;

class AeliaTaxDisplayByCountry extends CompatibilityAbstract {
	public function is_available(): bool {
		return isset( $GLOBALS['woocommerce-tax-display-by-country'] );
	}

	public function pre_init() {
		add_filter( 'wc_aelia_tdbc_customer_location', array( $this, 'filter_location_during_update_checkout' ) );
	}

	public function filter_location_during_update_checkout( $location ) {
		// phpcs:ignore
		$ajax_action = sanitize_text_field( wp_unslash( $_GET['wc-ajax'] ?? '' ) );

		if ( 'update_checkout' !== $ajax_action ) {
			return $location;
		}

		$tax_based_on = get_option( 'woocommerce_tax_based_on' );

		// Check shipping method at this point to see if we need special handling
		if ( isset( WC()->cart ) &&
		   apply_filters( 'woocommerce_apply_base_tax_for_local_pickup', true ) &&
		   WC()->cart->needs_shipping() &&
		   count( array_intersect( WC()->session->get( 'chosen_shipping_methods', array( get_option( 'woocommerce_default_shipping_method' ) ) ), apply_filters( 'woocommerce_local_pickup_methods', array( 'local_pickup' ) ) ) ) > 0 ) {
			$tax_based_on = 'base';
		}

		if ( 'shipping' === $tax_based_on ) {
			if ( isset( $_POST[ Definitions::ARG_CHECKOUT_SHIPPING_COUNTRY ] ) ) {
				$location['country'] = $_POST[ Definitions::ARG_CHECKOUT_SHIPPING_COUNTRY ];
			}
			if ( isset( $_POST[ Definitions::ARG_CHECKOUT_SHIPPING_STATE ] ) ) {
				$location['state'] = $_POST[ Definitions::ARG_CHECKOUT_SHIPPING_STATE ];
			}
		} else {
			if ( isset( $_POST[ Definitions::ARG_BILLING_COUNTRY ] ) ) {
				$location['country'] = $_POST[ Definitions::ARG_BILLING_COUNTRY ];
			}
			if ( isset( $_POST[ Definitions::ARG_BILLING_STATE ] ) ) {
				$location['state'] = $_POST[ Definitions::ARG_BILLING_STATE ];
			}
		}

		return $location;
	}
}
