<?php

namespace Objectiv\Plugins\Checkout\Compatibility\Plugins;

use Aelia\WC\CurrencySwitcher\Definitions;
use Aelia\WC\CurrencySwitcher\Settings;
use Aelia\WC\CurrencySwitcher\WC_Aelia_CurrencySwitcher;
use Objectiv\Plugins\Checkout\Compatibility\CompatibilityAbstract;

class AeliaCurrencySwitcher extends CompatibilityAbstract {
	public function is_available(): bool {
		return class_exists( '\\Aelia\\WC\\CurrencySwitcher\\WC_Aelia_CurrencySwitcher' );
	}

	public function pre_init() {
		add_filter( 'wc_aelia_cs_customer_country', array( $this, 'filter_currency_during_update_checkout' ) );
	}

	public function filter_currency_during_update_checkout( $result ): string {
		$force_currency_by_country = WC_Aelia_CurrencySwitcher::instance()->force_currency_by_country();

		if ( Settings::OPTION_DISABLED === $force_currency_by_country ) {
			return $result;
		}

		// phpcs:ignore
		$ajax_action = sanitize_text_field( wp_unslash( $_GET['wc-ajax'] ?? '' ) );

		if ( 'update_checkout' !== $ajax_action ) {
			return $result;
		}

		if ( Settings::OPTION_SHIPPING_COUNTRY === $force_currency_by_country ) {
			// phpcs:disable WordPress.Security.NonceVerification.Missing
			return sanitize_text_field( wp_unslash( $_POST[ Definitions::ARG_CHECKOUT_REVIEW_SHIPPING_COUNTRY ] ?? '' ) );
			// phpcs:enable WordPress.Security.NonceVerification.Missing
		}

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		return sanitize_text_field( wp_unslash( $_POST[ Definitions::ARG_CHECKOUT_REVIEW_BILLING_COUNTRY ] ?? '' ) );
		// phpcs:enable WordPress.Security.NonceVerification.Missing
	}
}
