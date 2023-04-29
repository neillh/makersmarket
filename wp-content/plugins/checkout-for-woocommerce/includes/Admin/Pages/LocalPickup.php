<?php

namespace Objectiv\Plugins\Checkout\Admin\Pages;

use Objectiv\Plugins\Checkout\Managers\PlanManager;
use Objectiv\Plugins\Checkout\Managers\SettingsManager;

/**
 * @link checkoutwc.com
 * @since 5.0.0
 * @package Objectiv\Plugins\Checkout\Admin\Pages
 * @author Clifton Griffin <clif@checkoutwc.com>
 */
class LocalPickup extends PageAbstract {
	public function __construct() {
		parent::__construct( cfw__( 'Local Pickup', 'checkout-wc' ), 'manage_options', 'local-pickup' );
	}

	public function output() {
		$this->output_form_open();
		?>
		<div class="space-y-6">
			<?php cfw_admin_page_section( 'Local Pickup', 'Control local pickup options.', $this->get_pickup_fields() ); ?>
		</div>
		<?php
		$this->output_form_close();
	}

	function get_pickup_fields() {
		ob_start();

		if ( ! PlanManager::has_premium_plan() ) {
			$notice = $this->get_upgrade_required_notice( PlanManager::get_english_list_of_required_plans_html() );
		}

		$this->output_checkbox_row(
			'enable_pickup',
			cfw__( 'Enable Local Pickup', 'checkout-wc' ),
			cfw__( 'Provide customer with the option to choose their delivery method. Choosing pickup bypasses the shipping address.', 'checkout-wc' ),
			array(
				'enabled' => PlanManager::has_premium_plan(),
				'notice'  => $notice ?? '',
			)
		);

		$this->output_checkbox_row(
			'enable_pickup_ship_option',
			cfw__( 'Enable Shipping Option', 'checkout-wc' ),
			cfw__( 'If you only offer pickup, uncheck this to hide the shipping option.', 'checkout-wc' ),
			array(
				'nested' => true,
			)
		);

		$this->output_text_input_row(
			'pickup_ship_option_label',
			cfw__( 'Shipping Option Label', 'checkout-wc' ),
			cfw__( 'If left blank, this default will be used: ', 'checkout-wc' ) . cfw__( 'Ship', 'checkout-wc' ),
			array(
				'nested' => true,
			)
		);

		$this->output_text_input_row(
			'pickup_option_label',
			cfw__( 'Local Pickup Option Label', 'checkout-wc' ),
			cfw__( 'If left blank, this default will be used: ', 'checkout-wc' ) . cfw__( 'Pick up', 'checkout-wc' ),
			array(
				'nested' => true,
			)
		);

		$this->output_checkbox_group(
			'pickup_methods',
			cfw__( 'Local Pickup Shipping Methods', 'checkout-wc' ),
			cfw__( 'Choose which shipping methods are local pickup options. Only these options will be shown when Pickup is selected. These options will be hidden if Delivery is selected.', 'checkout-wc' ),
			$this->get_shipping_methods(),
			(array) SettingsManager::instance()->get_setting( 'pickup_methods' ),
			array(
				'nested' => true,
			)
		);

		$this->output_text_input_row(
			'pickup_shipping_method_other_label',
			cfw__( 'Other Shipping Method', 'checkout-wc' ),
			cfw__( 'Enter the name of your local pickup shipping method. If you have multiple options, or the name varies, check the box below to use regular expressions.', 'checkout-wc' ),
			array(
				'nested' => true,
			)
		);

		$this->output_checkbox_row(
			'enable_pickup_shipping_method_other_regex',
			cfw__( 'Enable Regex', 'checkout-wc' ),
			cfw__( 'Match local shipping method name with regex.', 'checkout-wc' ),
			array(
				'nested' => true,
			)
		);

		$this->output_checkbox_row(
			'enable_pickup_method_step',
			cfw__( 'Enable Pickup Step', 'checkout-wc' ),
			cfw__( 'When Pickup is selected, show the shipping method step. Can be useful when integrating with plugins that allow customers to choose a pickup time slot, etc.', 'checkout-wc' ),
			array(
				'nested' => true,
			)
		);

		$this->output_checkbox_row(
			'hide_pickup_methods',
			cfw__( 'Hide Pickup Methods', 'checkout-wc' ),
			cfw__( 'On the pickup step, hide the actual pickup methods. If you need the pickup step and only have one pickup method, you should use this option.', 'checkout-wc' ),
			array(
				'nested' => true,
			)
		);
		?>
		<div class="cfw-admin-field-container relative flex">
			<a href="<?php echo admin_url( 'edit.php?post_type=cfw_pickup_location' ); ?>" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
				<?php cfw_e( 'Edit Pickup Locations', 'checkout-wc' ); ?>
			</a>
		</div>
		<?php

		return ob_get_clean();
	}

	public function get_shipping_methods(): array {
		// Get all shipping methods
		$data_store = \WC_Data_Store::load( 'shipping-zone' );
		$raw_zones  = $data_store->get_zones();
		$zones      = array();
		$methods    = array();
		foreach ( $raw_zones as $raw_zone ) {
			$zones[] = new \WC_Shipping_Zone( $raw_zone );
		}
		$zones[] = new \WC_Shipping_Zone( 0 ); // ADD ZONE "0" MANUALLY

		foreach ( $zones as $zone ) {
			$zone_shipping_methods = $zone->get_shipping_methods();
			foreach ( $zone_shipping_methods as $method ) {
				$methods[ $method->get_rate_id() ] = $zone->get_zone_name() . ': ' . $method->get_title();
			}
		}

		$methods['other'] = cfw__( 'Other', 'checkout-wc' );

		return $methods;
	}
}





