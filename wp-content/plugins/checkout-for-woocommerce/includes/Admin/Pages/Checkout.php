<?php

namespace Objectiv\Plugins\Checkout\Admin\Pages;

use Objectiv\Plugins\Checkout\Managers\PlanManager;
use Objectiv\Plugins\Checkout\Managers\SettingsManager;

/**
 * @link checkoutwc.com
 * @since 5.0.0
 * @package Objectiv\Plugins\Checkout\Admin\Pages
 */
class Checkout extends PageAbstract {
	public function __construct() {

		parent::__construct( cfw__( 'Checkout', 'checkout-wc' ), 'manage_options', 'checkout' );
	}

	public function output() {
		$this->output_form_open();
		?>
		<div class="space-y-6">
			<?php cfw_admin_page_section( 'Steps', 'Control the checkout steps.', $this->get_steps_fields() ); ?>
			<?php cfw_admin_page_section( 'Login and Registration', 'Control how login and registration function on your checkout page.', $this->get_login_and_registration_fields() ); ?>
			<?php cfw_admin_page_section( 'Field Options', 'Control how different checkout fields appear.', $this->get_field_option_fields() ); ?>
			<?php cfw_admin_page_section( 'Address Options', 'Control address fields.', $this->get_address_options_fields() ); ?>
			<?php cfw_admin_page_section( 'Address Completion and Validation', 'Control some mobile only checkout behaviors.', $this->get_address_completion_and_validation_fields() ); ?>
			<?php cfw_admin_page_section( 'Mobile Options', 'Control mobile specific features.', $this->get_mobile_options_fields() ); ?>
		</div>
		<?php
		$this->output_form_close();
	}

	public function get_steps_fields() {
		ob_start();

		$this->output_checkbox_row(
			'skip_cart_step',
			cfw__( 'Disable Cart Step', 'checkout-wc' ),
			cfw__( 'Disable to skip the cart and redirect customers directly to checkout after adding a product to the cart. (Incompatible with Side Cart)', 'checkout-wc' )
		);

		$this->output_checkbox_row(
			'skip_shipping_step',
			cfw__( 'Disable Shipping Step', 'checkout-wc' ),
			cfw__( 'Disable to hide the shipping method step. Useful if you only have one shipping option for all orders.', 'checkout-wc' )
		);

		/**
		 * Fires at the bottom steps settings container
		 *
		 * @since 7.0.0
		 *
		 * @param Checkout $checkout_admin_page The checkout settings admin page
		 */
		do_action( 'cfw_after_admin_page_checkout_steps_section', $this );

		return ob_get_clean();
	}

	public function get_login_and_registration_fields() {
		ob_start();

		$this->output_radio_group_row(
			'registration_style',
			cfw__( 'Registration', 'checkout-wc' ),
			cfw__( 'Choose how customers obtain a password when registering an account.' ),
			'enhanced',
			array(
				'enhanced'    => cfw__( 'Enhanced (Recommended)', 'checkout-wc' ),
				'woocommerce' => cfw__( 'WooCommerce Default', 'checkout-wc' ),
			),
			array(
				'enhanced'    => cfw__( 'Automatically generate a username and password and email it to the customer using the native WooCommerce functionality. (Recommended)', 'checkout-wc' ),
				'woocommerce' => cfw__( 'A password field is provided for the customer to select their own password. Not recommended.', 'checkout-wc' ),
			)
		);

		$this->output_radio_group_row(
			'user_matching',
			cfw__( 'User Matching', 'checkout-wc' ),
			cfw__( 'Choose how to handle guest orders and accounts.' ),
			'enabled',
			array(
				'enabled'     => cfw__( 'Enabled (Recommended)', 'checkout-wc' ),
				'woocommerce' => cfw__( 'WooCommerce Default', 'checkout-wc' ),
			),
			array(
				'enabled'     => cfw__( 'Automatically matches guest orders to user accounts on new purchase as well as on registration of a new user. (Recommended)', 'checkout-wc' ),
				'woocommerce' => cfw__( 'Guest orders will not be linked to matching accounts.', 'checkout-wc' ),
			)
		);

		return ob_get_clean();
	}

	public function get_field_option_fields() {
		$settings           = SettingsManager::instance();
		$order_notes_enable = ! has_filter( 'woocommerce_enable_order_notes_field' ) || ( $settings->get_setting( 'enable_order_notes' ) === 'yes' && 1 === cfw_count_filters( 'woocommerce_enable_order_notes_field' ) );

		$order_notes_notice_replacement_text = '';

		if ( ! $order_notes_enable && defined( 'WC_CHECKOUT_FIELD_EDITOR_VERSION' ) ) {
			$order_notes_notice_replacement_text = cfw__( 'This setting is overridden by WooCommerce Checkout Field Editor.', 'checkout-wc' );
		}

		ob_start();

		$this->output_checkbox_row(
			'enable_order_notes',
			cfw__( 'Enable Order Notes Field', 'checkout-wc' ),
			cfw__( 'Enable or disable WooCommerce Order Notes field. (Default: Disabled)', 'checkout-wc' ),
			array(
				'enabled'                => $order_notes_enable,
				'show_overridden_notice' => false === $order_notes_enable,
				'overridden_notice'      => $order_notes_notice_replacement_text,
			)
		);

		$this->output_checkbox_row(
			'enable_coupon_code_link',
			cfw__( 'Hide Coupon Code Field Behind Link', 'checkout-wc' ),
			cfw__( 'Initially hide coupon field until "Have a coupon code?" link is clicked.', 'checkout-wc' )
		);

		$this->output_checkbox_row(
			'enable_discreet_address_1_fields',
			cfw__( 'Enable Discreet House Number and Street Name Address Fields', 'checkout-wc' ),
			cfw__( 'Values are combined into a single address_1 field based on country selected by customer.', 'checkout-wc' )
		);

		$this->output_radio_group_row(
			'discreet_address_1_fields_order',
			'Discreet Address Fields Display Order',
			cfw__( 'Choose how display discreet address 1 fields.' ),
			'default',
			array(
				'default'   => cfw__( '[House Number] [Street Name]', 'checkout-wc' ),
				'alternate' => cfw__( '[Street Name] [House Number]', 'checkout-wc' ),
			),
			array(
				'default'   => cfw__( 'Display the House Number before the Street Name. (Default)', 'checkout-wc' ),
				'alternate' => cfw__( 'Display the Street Name before the House Number.', 'checkout-wc' ),
			),
			array(
				'nested' => true,
			)
		);

		$this->output_checkbox_row(
			'hide_optional_address_fields_behind_link',
			cfw__( 'Hide Optional Address Fields Behind Links', 'checkout-wc' ),
			cfw__( 'Recommended to increase conversions. Example link text: Add Company (optional)', 'checkout-wc' )
		);

		$this->output_checkbox_row(
			'use_fullname_field',
			cfw__( 'Enable Full Name Field', 'checkout-wc' ),
			cfw__( 'Enable to replace first and last name fields with a single full name field.', 'checkout-wc' )
		);

		$this->output_checkbox_row(
			'enable_highlighted_countries',
			cfw__( 'Enable Highlighted Countries', 'checkout-wc' ),
			cfw__( 'Promote selected countries to the top of the countries list in country dropdowns.', 'checkout-wc' )
		);

		$this->output_countries_multiselect(
			'highlighted_countries',
			'Highlighted Countries',
			'The countries to show first in country dropdowns.',
			(array) SettingsManager::instance()->get_setting( 'highlighted_countries' ),
			array(
				'nested' => true,
			)
		);

		/**
		 * Fires at the bottom steps settings container
		 *
		 * @since 7.0.0
		 *
		 * @param Checkout $checkout_admin_page The checkout settings admin page
		 */
		do_action( 'cfw_after_admin_page_field_options_section', $this );

		return ob_get_clean();
	}

	public function get_address_options_fields() {
		ob_start();

		$this->output_checkbox_row(
			'force_different_billing_address',
			cfw__( 'Force Different Billing Address', 'checkout-wc' ),
			cfw__( 'Remove option to use shipping address as billing address.', 'checkout-wc' )
		);

		$this->output_checkbox_group(
			'enabled_billing_address_fields',
			cfw__( 'Enabled Billing Address Fields', 'checkout-wc' ),
			cfw__( 'Determine which billing address fields are visible for customers.', 'checkout-wc' ),
			array(
				'billing_first_name' => 'First name',
				'billing_last_name'  => 'Last name',
				'billing_address_1'  => 'Address 1',
				'billing_address_2'  => 'Address 2',
				'billing_company'    => 'Company',
				'billing_country'    => 'Country',
				'billing_postcode'   => 'Postcode',
				'billing_state'      => 'State',
				'billing_city'       => 'City',
				'billing_phone'      => 'Phone',
			),
			(array) SettingsManager::instance()->get_setting( 'enabled_billing_address_fields' )
		);

		return ob_get_clean();
	}

	public function get_address_completion_and_validation_fields() {
		ob_start();

		/**
		 * Fires at the bottom steps settings container
		 *
		 * @since 7.0.0
		 *
		 * @param Checkout $checkout_admin_page The checkout settings admin page
		 */
		do_action( 'cfw_after_admin_page_address_options_section', $this );

		return ob_get_clean();
	}

	public function get_mobile_options_fields() {
		ob_start();

		$this->output_checkbox_row(
			'show_mobile_coupon_field',
			cfw__( 'Enable Mobile Coupon Field', 'checkout-wc' ),
			cfw__( 'Show coupon field above payment gateways on mobile devices. Helps customers find the coupon field without expanding the cart summary.', 'checkout-wc' )
		);

		$this->output_checkbox_row(
			'show_logos_mobile',
			cfw__( 'Enable Mobile Credit Card Logos', 'checkout-wc' ),
			cfw__( 'Show the credit card logos on mobile. Note: Many gateway logos cannot be rendered properly on mobile. It is recommended you test before enabling. Default: Off', 'checkout-wc' )
		);

		$this->output_text_input_row(
			'cart_summary_mobile_label',
			cfw__( 'Cart Summary Mobile Label', 'checkout-wc' ),
			cfw__( 'Example: Show order summary and coupons', 'checkout-wc' ) . '<br/>' . cfw__( 'If left blank, this default will be used: ', 'checkout-wc' ) . cfw__( 'Show order summary', 'checkout-wc' )
		);

		return ob_get_clean();
	}
}
