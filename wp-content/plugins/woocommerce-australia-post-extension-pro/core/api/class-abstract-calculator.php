<?php

namespace AustraliaPost\Core\Api;

use AustraliaPost\Core\Constants;
use AustraliaPost\Helpers\Utilities;

abstract class Abstract_Calculator {

	private $settings;

	/**
	 * Abstract_Calculator constructor.
	 *
	 * @param $settings
	 */
	public function __construct($settings) {
		$this->settings = $settings;
		//since 1.7.1 improve debugging mode
		$this->debug(__('Australia Post debug mode is on - to hide these messages, turn debug mode off in the plugin\'s settings page.', 'woocommerce-australia-post-pro'));
		$this->debug_environment();
		$this->debug('Plugin Settings', $this->settings);
	}

	public abstract function calculate( $package_details, $package);

	/**
	 * [calculate the handling fees]
	 *
	 * @param $cost
	 *
	 * @return int|string [number]       [description]
	 * @internal param $ [number] $cost [description]
	 */
	public function calculate_handling_fee($cost)
	{
		if ($this->settings['handling_fee'] == '') {
			return 0;
		}

		if (substr($this->settings['handling_fee'], -1) == '%') {
			$handling_fee = trim(str_replace('%', '', $this->settings['handling_fee']));
			return ($cost * ($handling_fee / 100));
		}

		if (is_numeric($this->settings['handling_fee'])) {
			return $this->settings['handling_fee'];
		}
		return 0;
	}

	/**
	 * Output a message
	 *
	 * @param string $title
	 * @param mixed $data
	 * @param string $type
	 */
	protected function debug($title, $data = null, $type = 'notice')
	{

		if (!isset($this->settings['debug_mode']))
			return;

		if ($this->settings['debug_mode'] !== 'yes')
			return;

		if (!current_user_can('manage_options'))
			return;

		if (!$data) {
			$message = $title;
		} else {
			$message = sprintf('%s: <pre>%s</pre>', $title,  Utilities::_json_encode($data, JSON_PRETTY_PRINT));
		}

		wc_add_notice($message, $type);
	}

	private function debug_environment()
	{
		$this->debug('Environment',
			[
				'plugin_version' => AUSPOST_CURRENT_VERSION,
				'php_version' => phpversion(),
			]
		);
	}

	/**
	 * @param $rate_cost
	 *
	 * @return float
	 */
	protected function strip_shipping_tax($rate_cost){
		if ( 'yes' !== $this->settings['enable_stripping_tax']) {
			return $rate_cost;
		}
		return round($rate_cost / 1.1, 2);
	}


	protected function add_tracked_letters_rates( $packages, $rates ) {
		if ( $this->settings['enable_letters'] !== 'yes' ) {
			return $rates;
		}

		if (!is_array($this->settings['tracked_letters'])) {
			return $rates;
		}

		$enabledTrackedLetters = array_filter( $this->settings['tracked_letters'], function ( $letter ) {
			return $letter['enabled'] === 'on';
		} );

		if ( count( $enabledTrackedLetters ) === 0 ) {
			return $rates;
		}

		foreach ( $packages as $package ) {
			foreach ( $enabledTrackedLetters as $key => $trackedLetter ) {

				if ( $this->package_fit_tracked_letter( $package, $key ) ) {
					$priority = ( isset( $trackedLetter['priority'] ) && $trackedLetter['priority'] === 'on' ) ? 0.5 : 0;
					$title    = ( isset( $this->settings['custom_titles'][ $key ] ) && trim( $this->settings['custom_titles'][ $key ] ) != '' ) ? $this->settings['custom_titles'][ $key ] : Constants::domestic_tracked_letters[ $key ]['name'];
					$price    = ( isset( $trackedLetter['price'] ) && is_numeric( $trackedLetter['price'] ) ) ? $trackedLetter['price'] : Constants::domestic_tracked_letters[ $key ]['price'];
					$price    += $this->get_extra_cover_for_tracked_letters( $package['value'] );
					$price    =  $this->strip_shipping_tax( $price );
					$price    += $this->calculate_handling_fee( $price );
					if ( ! isset( $rates[ $key ] ) ) {
						$rates[ $key ] = [
							'id'    => $key,
							'cost'  => round( floatval( $price + $priority ), 2 ),
							'label' => $title,
						];
					} else {
						$rates[ $key ]['cost'] += round( floatval( $price + $priority ), 2 );
					}
					break;
				}
			}
		}

		return $rates;
	}

	/**
	 * @param array $package
	 * @param string $letterCode
	 *
	 * @return bool
	 */
	protected function package_fit_tracked_letter( $package, $letterCode ) {

		$dimensions = Constants::domestic_tracked_letters[ $letterCode ]['dimensions'];
		$max_weight = Constants::domestic_tracked_letters[ $letterCode ]['max_weight'];

		if ( $package['weight'] * 100 > $max_weight ) {
			return false;
		}

		if ( $package['height'] * 10 > $dimensions['h'] ) {
			return false;
		}

		if ( $package['width'] * 10 > $dimensions['w'] ) {
			return false;
		}

		if ( $package['length'] * 10 > $dimensions['l'] ) {
			return false;
		}

		return true;
	}

	protected function get_extra_cover_for_tracked_letters( $value ) {
		if ( $this->settings['enable_extra_cover'] !== 'yes' ) {
			return 0;
		}

		if ( $value <= 100 ) {
			return 0;
		}

		if ( $value > 500 ) {
			return 0;
		}

		$hundreds = floor( ( $value / 100 ) );

		return $hundreds * 2.5;
	}

}
