<?php

namespace AustraliaPost\Core\Api;

use AustraliaPost\Helpers\Utilities;
use WC_Product;
use WP_Error;

class General extends Abstract_Calculator {

	const OBLIGATORY_SOD_THRESHOLD = 500;
	public $supported_services = [
		'AUS_PARCEL_REGULAR' => 'Regular Post',
		'AUS_PARCEL_EXPRESS' => 'Express Post',
		'AUS_PARCEL_COURIER' => 'Courier Post'
	];
	public $supported_international_services = [
		'INT_PARCEL_SEA_OWN_PACKAGING' => 'Economy Sea',
		'INT_PARCEL_AIR_OWN_PACKAGING' => 'Economy Air',
		'INT_PARCEL_STD_OWN_PACKAGING' => 'Standard International',
		'INT_PARCEL_COR_OWN_PACKAGING' => 'Courier International',
		'INT_PARCEL_EXP_OWN_PACKAGING' => 'Express International',
	];
	private $letter_calculate_domestic_url = 'https://digitalapi.auspost.com.au/postage/letter/domestic/calculate.json';
	private $letter_services_domestic_url = 'https://digitalapi.auspost.com.au/postage/letter/domestic/service.json';
	private $letter_calculate_intl_url = 'https://digitalapi.auspost.com.au/postage/letter/international/calculate.json';
	private $letter_services_intl_url = 'https://digitalapi.auspost.com.au/postage/letter/international/service.json';
	private $domestic_calculate_url = 'https://digitalapi.auspost.com.au/postage/parcel/domestic/calculate.json';
	private $domestic_services_url = 'https://digitalapi.auspost.com.au/postage/parcel/domestic/service';
	private $postage_intl_url = 'https://digitalapi.auspost.com.au/postage/parcel/international/service.json';
	private $intl_calculate_url = 'https://digitalapi.auspost.com.au/postage/parcel/international/calculate.json';
	private $delivery_confirmation = [
		'AUS_LETTER_EXPRESS_SMALL',
		'AUS_LETTER_EXPRESS_MEDIUM',
		'AUS_LETTER_EXPRESS_LARGE',
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
	];

	// satchels services
	private $letters_services = [
		'domestic'      => [
			'regular'  => [
				'AUS_LETTER_REGULAR_SMALL',
				'AUS_LETTER_REGULAR_LARGE',
				'AUS_LETTER_REGULAR_LARGE_125',
				'AUS_LETTER_REGULAR_LARGE_250',
				'AUS_LETTER_REGULAR_LARGE_500',
			],
			'express'  => [
				'AUS_LETTER_EXPRESS_SMALL',
				'AUS_LETTER_EXPRESS_MEDIUM',
				'AUS_LETTER_EXPRESS_LARGE',
			],
			'priority' => [
				'AUS_LETTER_PRIORITY_SMALL',
				'AUS_LETTER_PRIORITY_LARGE_125',
				'AUS_LETTER_PRIORITY_LARGE_250',
				'AUS_LETTER_PRIORITY_LARGE_500',
			]

		],
		'international' => [
			'registered'  => [
				'INT_LETTER_REG_SMALL',
				'INT_LETTER_REG_LARGE',
				'INT_LETTER_REG_SMALL_ENVELOPE',
				'INT_LETTER_REG_LARGE_ENVELOPE',
			],
			'economy_air' => [
				'INT_LETTER_AIR_OWN_PACKAGING_LIGHT',
				'INT_LETTER_AIR_OWN_PACKAGING_MEDIUM_LIGHT',
				'INT_LETTER_AIR_OWN_PACKAGING_MEDIUM',
				'INT_LETTER_AIR_OWN_PACKAGING_HEAVY',
			],
			'courier'     => [
				'INT_LETTER_COR_OWN_PACKAGING',
			],
			'express'     => [
				'INT_LETTER_EXP_OWN_PACKAGING',
			],
			'standard'    => [
				'INT_PARCEL_STD_OWN_PACKAGING',
			],
		]
	];
	private $extra_cover = [
		'INT_PARCEL_AIR_OWN_PACKAGING' => 500,
		'INT_PARCEL_SEA_OWN_PACKAGING' => 5000,
		'INT_PARCEL_STD_OWN_PACKAGING' => 5000,
		'INT_PARCEL_EXP_OWN_PACKAGING' => 5000,
		'AUS_PARCEL_REGULAR'           => 5000,
		'AUS_PARCEL_COURIER'           => 5000,
		'AUS_PARCEL_EXPRESS'           => 5000,
	];
	private $supported_letter_services = [
		'AUS_LETTER_REGULAR_SMALL' => 'Small Letter',
		'AUS_LETTER_REGULAR_LARGE' => 'Large Letter',

		'AUS_LETTER_EXPRESS_SMALL'  => 'Express Post Small Envelope',
		'AUS_LETTER_EXPRESS_MEDIUM' => 'Express Post Medium Envelope',
		'AUS_LETTER_EXPRESS_LARGE'  => 'Express Post Large Envelope',

		'AUS_LETTER_REGULAR_LARGE_125'  => 'Regular Large Light Letter',
		'AUS_LETTER_REGULAR_LARGE_250'  => 'Regular Large Medium Letter',
		'AUS_LETTER_REGULAR_LARGE_500'  => 'Regular Large Heavy Letter',
		'AUS_LETTER_PRIORITY_SMALL'     => 'Priority Small Letter',
		'AUS_LETTER_PRIORITY_LARGE_125' => 'Priority Large Light Letter',
		'AUS_LETTER_PRIORITY_LARGE_250' => 'Priority Large Medium Letter',
		'AUS_LETTER_PRIORITY_LARGE_500' => 'Priority Large Heavy Letter',
		'INT_LETTER_COR_OWN_PACKAGING'  => 'Courier Letter',
		'INT_LETTER_EXP_OWN_PACKAGING'  => 'Express Letter',
		'INT_PARCEL_STD_OWN_PACKAGING'  => 'Standard Letter',
		'INT_LETTER_REG_SMALL'          => 'Registered Small Letter',
		'INT_LETTER_REG_LARGE'          => 'Registered Large Letter',
		'INT_LETTER_REG_SMALL_ENVELOPE' => 'Registered Small Envelope',
		'INT_LETTER_REG_LARGE_ENVELOPE' => 'Registered Large Envelope',

		'INT_LETTER_AIR_OWN_PACKAGING_LIGHT'        => 'Economy Air Light Letter',
		'INT_LETTER_AIR_OWN_PACKAGING_MEDIUM_LIGHT' => 'Economy Air Medium Light Letter',
		'INT_LETTER_AIR_OWN_PACKAGING_MEDIUM'       => 'Economy Air Medium Letter',
		'INT_LETTER_AIR_OWN_PACKAGING_HEAVY'        => 'Economy Air Heavy Letter',
	];
	private $satchels_services = [
		'AUS_PARCEL_REGULAR' => [
			'AUS_PARCEL_REGULAR_SATCHEL_500G',
			'AUS_PARCEL_REGULAR_SATCHEL_SMALL',
			'AUS_PARCEL_REGULAR_SATCHEL_MEDIUM',
			'AUS_PARCEL_REGULAR_SATCHEL_3KG',
			'AUS_PARCEL_REGULAR_SATCHEL_LARGE',
			'AUS_PARCEL_REGULAR_SATCHEL_EXTRA_LARGE',
		],
		'AUS_PARCEL_EXPRESS' => [
			'AUS_PARCEL_EXPRESS_SATCHEL_500G',
			'AUS_PARCEL_EXPRESS_SATCHEL_SMALL',
			'AUS_PARCEL_EXPRESS_SATCHEL_MEDIUM',
			'AUS_PARCEL_EXPRESS_SATCHEL_3KG',
			'AUS_PARCEL_EXPRESS_SATCHEL_LARGE',
			'AUS_PARCEL_EXPRESS_SATCHEL_EXTRA_LARGE',
		],
		'AUS_PARCEL_COURIER' => [
			'AUS_PARCEL_COURIER_SATCHEL_LARGE'
		]
	];
	private $extra_cover_cost = 2.5;
	private $signature_on_delivery_cost = 2.95;
	private $intl_extra_cover_intial_cost = 0;
	private $intl_extra_cover_factor_cost = 4;
	private $intl_sod_fee = 5.5;
	private $settings;

	// since 1.6
	private $only_letters = true;
	private $delivery_confirmation_suboption = [
		'AUS_LETTER_REGULAR_SMALL',
		'AUS_LETTER_REGULAR_LARGE',
		'AUS_LETTER_REGULAR_LARGE_125',
		'AUS_LETTER_PRIORITY_SMALL',
		'AUS_LETTER_PRIORITY_LARGE_125',
		'AUS_LETTER_PRIORITY_LARGE_250',
		'AUS_LETTER_PRIORITY_LARGE_500',
	];

	private $package;

	public function __construct( $settings ) {
		$this->settings = $settings;
		parent::__construct( $settings );
	}

	public function calculate( $package_details, $package ) {
		$this->debug( 'Packing Details', $package_details );

		$this->package = $package;

		$rates = [];

		if ( $package['destination']['country'] == 'AU' ) {
			$rates = $this->add_tracked_letters_rates( $package_details, $rates );
		}

		// if there are more enabled letters options, calculate them, otherwise, just return the tracked letters.
		if ( count( $rates ) > 0 && ! is_array( $this->settings['enabled_domestic_letters'] ) ) {
			return $rates;
		}

		foreach ( $package_details as $pack ) {
			$weight   = $pack['weight'];
			$height   = $pack['height'];
			$width    = $pack['width'];
			$length   = $pack['length'];
			$postcode = $pack['postcode'];
			if ( $package['destination']['country'] == 'AU' ) {
				if ( isset( $rates ) ) {
					$rates = $this->get_domestical_rates( $rates, $weight, $height, $width, $length, $package['destination']['postcode'], $package['cart_subtotal'], $postcode );
				} else {
					$rates = $this->get_domestical_rates( 0, $weight, $height, $width, $length, $package['destination']['postcode'], $package['cart_subtotal'], $postcode );
				}
			} else {
				if ( $length <= 105 ) {
					if ( isset( $rates ) ) {
						$rates = $this->get_international_rates( $rates, $weight, $height, $width, $length, $package['destination']['country'], $package['cart_subtotal'] );
					} else {
						$rates = $this->get_international_rates( 0, $weight, $height, $width, $length, $package['destination']['country'], $package['cart_subtotal'] );
					}
				}
			}
			if ( isset( $rates['error'] ) ) {
				if ( $this->settings['debug_mode'] == 'yes' ) {
					wc_add_notice( $rates['error'], 'error' );

					return false;
				}
			}
		}

		return $rates;
	}

	/**
	 * @param $old_rates
	 * @param $weight
	 * @param $height
	 * @param $width
	 * @param $length
	 * @param $destination
	 * @param $order_cost
	 * @param $postcode
	 *
	 * @return mixed
	 */
	private function get_domestical_rates( $old_rates, $weight, $height, $width, $length, $destination, $order_cost, $postcode ) {
		list( $query_params, $api_calculate_endpoint, $api_services_endpoint ) = $this->get_query_parameters( $weight, $height, $width, $length );
		$query_params['from_postcode'] = $postcode;
		$query_params['to_postcode']   = $destination;

		$this->debug( 'Australia Post REQUEST', [ $api_services_endpoint . htmlspecialchars( http_build_query( $query_params ) ) ] );

		$response = $this->get_domestic_services( $api_services_endpoint, $query_params );

		if ( is_wp_error( $response ) ) {
			return [ 'error' => 'Unknown Problem. Please Contact the admin' ];
		}

		$aus_response = json_decode( wp_remote_retrieve_body( $response ) );

		//since 1.7.1 improve debugging mode
		$this->debug( 'Australia Post RESPONSE', $aus_response );

		if ( isset( $aus_response->error ) ) {
			return [ 'error' => $aus_response->error->errorMessage ];
		}

		if ( $aus_response->services ) {
			//if user choose only one option, woocommerce will save it as a string.
			$services          = is_array( $aus_response->services->service ) ? $aus_response->services->service : [ $aus_response->services->service ];
			$filtered_domestic = $this->filter_domestic( $services, $query_params );
			$this->debug( 'filtered_domestic', $filtered_domestic );


			foreach ( $services as $service ) {

				if ( in_array( $service->code, $filtered_domestic ) ) {
					// calculate standard and sign on delivery
					$query_params['service_code'] = $service->code;
					// @since 1.7
					//if extra_cover enabled, orders cost more than 500 must have SoD.
					if ( $this->settings['enable_extra_cover'] === 'yes' && $this->settings['seperate_extracover_sod'] == 'no' && $order_cost > self::OBLIGATORY_SOD_THRESHOLD ) {
						$this->settings['signature_on_delivery'] = 'yes';
					}

					// Signature on Delivery only for Parcels
					$query_params = $this->get_shipping_options_for_domestice( $service, $query_params );

					//since 1.7.1 improve debugging mode
					$this->debug( 'Australia Post REQUEST', [ htmlspecialchars( http_build_query( $query_params ) ) ] );
					//since 1.7.2 if the package is a letter, remove size otherwise some services will not work.
					$query_params  = $this->filter_parames_for_letter_shipping( $query_params );
					$cacl_response = $this->get_prices_by_service_code( $query_params, $api_calculate_endpoint );


					//since 1.7.1 improve debugging mode
					$this->debug( 'Australia Post RESPONSE', $cacl_response );

					if ( ( isset( $cacl_response->status ) && $cacl_response->status === "Failed" ) || ( isset( $cacl_response->error ) ) ) {
						continue;
					}
					// Extra Cover
					$extra_cover_total = 0;
					WC()->session->set( 'extra_cover_total', 0 );
					if ( $this->settings['enable_extra_cover'] === 'yes' ) {
						//if extra_cover enabled, orders cost more than 500 must have SoD.
						$sod_fee           = ( $order_cost > self::OBLIGATORY_SOD_THRESHOLD && $this->settings['seperate_extracover_sod'] ) ? $this->signature_on_delivery_cost : 0;
						$extra_cover_total = $this->get_domestic_extra_cover_cost( $order_cost ) + $sod_fee;
						WC()->session->set( 'extra_cover_total', $extra_cover_total );
						if ( $this->settings['seperate_extracover_sod'] == 'yes' ) {
							$extra_cover_total                         = 0;
							$cacl_response->postage_result->total_cost = $cacl_response->postage_result->total_cost - $extra_cover_total;
						}
					}

					$sod_fee = 0;
					WC()->session->set( 'sod_fee', 0 );
					if ( $query_params['option_code'] == 'AUS_SERVICE_OPTION_SIGNATURE_ON_DELIVERY' && ! ( $order_cost > self::OBLIGATORY_SOD_THRESHOLD && $this->settings['enable_extra_cover'] == 'yes' ) ) {
						$sod_fee = $this->signature_on_delivery_cost;
						if ( $this->settings['seperate_extracover_sod'] == 'yes' ) {
							$sod_fee = 0;
							if ( isset( $cacl_response->postage_result->total_cost ) ) {
								WC()->session->set( 'sod_fee', $this->signature_on_delivery_cost );
								$cacl_response->postage_result->total_cost = $cacl_response->postage_result->total_cost - $this->signature_on_delivery_cost;
							}
						}
					}
					if ( isset( $cacl_response->postage_result ) ) {
						$rate_cost                                            = ( is_array( $cacl_response->postage_result->costs->cost ) ) ? $cacl_response->postage_result->costs->cost[0]->cost : $cacl_response->postage_result->costs->cost->cost;
						$rate_cost                                            = ( ( $this->calculate_handling_fee( $rate_cost ) + $rate_cost ) ) + ( ( isset( $old_rates[ $this->get_rate_label( $service->code ) ]['cost'] ) ) ? $old_rates[ $this->get_rate_label( $service->code ) ]['cost'] : 0 ) + $extra_cover_total + $sod_fee;
						$total_shipping_cost                                  = $this->strip_shipping_tax( $rate_cost );
						$old_rates[ $this->get_rate_label( $service->code ) ] = [
							'id'    => 'aus:' . $service->code . ':' . $this->settings['instance_id'],
							'label' => $this->get_rate_label( $service->code ) . $this->get_duration_text( $cacl_response ),
							'cost'  => round( $total_shipping_cost, 2 ),
						];
					}
				}
			}


		}

		return $old_rates;
	}

	/**
	 * @param $weight
	 * @param $height
	 * @param $width
	 * @param $length
	 *
	 * @return array
	 */
	private function get_query_parameters( $weight, $height, $width, $length ) {
		$query_params           = [];
		$is_letter              = false;
		$query_params['length'] = $length;
		$query_params['width']  = $width;
		$query_params['height'] = $height;
		$query_params['weight'] = $weight;
		if ( $this->should_deemphasize_satchels_dimensions( $weight ) ) {
			$query_params['length'] = 5;
			$query_params['width']  = 5;
			$query_params['height'] = 1;
		}
		//INFO since 1.6 add letters calculations
		$api_calculate_endpoint = $this->domestic_calculate_url;
		$api_services_endpoint  = $this->domestic_services_url;
		if ( $this->is_letter( $query_params, 'AU' ) && $this->only_letters === true ) {
			$api_calculate_endpoint = $this->letter_calculate_domestic_url;
			$api_services_endpoint  = $this->letter_services_domestic_url;
			$dimensions             = $this->sorted_dimensions( $length, $width, $height );
			$query_params['weight'] = $weight * 1000; // convert to grams
			$is_letter              = true;

			if ( $this->should_deemphasize_satchels_dimensions( $weight ) ) {
				$query_params['width']     = 10;
				$query_params['length']    = 10;
				$query_params['thickness'] = 10;
				$query_params['height']    = 10;
			} else {
				$query_params['width']     = $dimensions['width'] * 10;
				$query_params['length']    = $dimensions['length'] * 10;
				$query_params['thickness'] = $dimensions['height'] * 10;
				$query_params['height']    = $dimensions['height'] * 10;

			}
		}
		if ( $is_letter === false && $this->should_deemphasize_satchels_dimensions( $weight ) ) {
			$query_params['length'] = 1;
			$query_params['width']  = 1;
			$query_params['height'] = 1;
		}

		return [ $query_params, $api_calculate_endpoint, $api_services_endpoint ];
	}

	/**
	 * @param $weight
	 *
	 * @return bool
	 */
	private function should_deemphasize_satchels_dimensions( $weight ) {
		return ! empty( $this->settings['satchels'] ) && $this->settings['deemphasize_satchels_dimensions'] === 'yes' && $weight <= 5;
	}

	/**
	 * get_postcode_for_shipping function.
	 *
	 * @access private
	 *
	 * @param $params
	 *
	 * @return bool $is_letter
	 * @internal param array $dimensions
	 */
	private function is_letter( $params, $country = 'AU' ) {
		if ( $this->settings['enable_letters'] == 'no' ) {
			return false;
		}

		if ( $this->settings['enabled_domestic_letters'] === '' && $country === 'AU' ) {
			return false;
		}

		if ( $this->settings['enabled_intl_letters'] === '' && $country !== 'AU' ) {
			return false;
		}

		$slug = "aupost_not_letter";
		foreach ( $this->package['contents'] as $item_id => $values ) {
			/** @var WC_Product $_product */
			$_product = $values['data'];
			$terms    = get_the_terms( $_product->get_id(), 'product_shipping_class' );

			if ( $terms ) {
				foreach ( $terms as $term ) {
					$shipping_class = $term->slug;
					if ( $slug === $shipping_class ) {
						return false;
					}
				}
			}
		}

		$dimensions = [ $params['height'], $params['width'], $params['length'] ];
		asort( $dimensions );
		$dimensions = array_values( $dimensions );

		$thickness  = $dimensions[0];
		$length     = $dimensions[1];
		$width      = $dimensions[2];
		$weight     = $params['weight'] * 1000;
		$max_weight = 500;
		/*
		To be considered a letter, your item must:
		- weigh less than 500g
		- contain flexible items
		- have a rectangular shape
		- be no larger than a B4 envelope (260mm x 360mm x 20mm)
		- be no thicker than 20mm
		 */
		if ( $weight > $max_weight ) {
			return false;
		}

		if ( $thickness > ( 2 ) ) {
			return false;
		}

		if ( $width > ( 36 ) ) {
			return false;
		}

		if ( $length > ( 26 ) ) {
			return false;
		}

		return true;
	}

	/**
	 * @param $length
	 * @param $width
	 * @param $height
	 *
	 * @return array
	 */
	private function sorted_dimensions( $length, $width, $height ) {
		$dimensions = [ $height, $width, $length ];
		asort( $dimensions );
		$dimensions = array_values( $dimensions );

		return [
			'height' => $dimensions[0],
			'width'  => $dimensions[1],
			'length' => $dimensions[2],
		];
	}

	/**
	 * @param $api_services_endpoint
	 * @param $query_params
	 *
	 * @return array|WP_Error
	 */
	private function get_domestic_services( $api_services_endpoint, $query_params ) {
		$response = wp_remote_get( $api_services_endpoint . '?' . http_build_query( $query_params ), [
			'timeout'   => 70,
			'sslverify' => 0,
			'headers'   => [
				'AUTH-KEY' => $this->settings['api_key'],
			]
		] );

		return $response;
	}

	/**
	 * filter_domestic function.
	 * Filter to add the satchel services.
	 * @access private
	 *
	 * @param $available_services
	 * @param $params
	 *
	 * @return array $filtered_options
	 */
	private function filter_domestic( $available_services, $params ) {
		$domestic_options = ( ! is_array( $this->settings['domestic_options'] ) ) ? [ $this->settings['domestic_options'] ] : $this->settings['domestic_options'];

		$available_services_codes = [];
		if ( is_array( $available_services ) ) {
			foreach ( $available_services as $available_service ) {
				$available_services_codes[] = $available_service->code;
			}
		}

		$filtered_options = $domestic_options;
		foreach ( $domestic_options as $key => $value ) {
			switch ( $value ) {
				case 'AUS_PARCEL_REGULAR':
					if ( isset( $this->settings['satchels']['regular'] ) ) {
						if ( $params['weight'] <= $this->get_max_satchel_weight() && Utilities::fit_satchels( $this->settings['satchels']['regular'], $params ) ) {
							unset( $filtered_options[ $key ] );
							foreach ( $this->satchels_services['AUS_PARCEL_REGULAR'] as $s ) {
								if ( $this->is_satchel_enabled( $s ) && in_array( $s, $available_services_codes ) ) {
									$filtered_options[] = $s;
								}
							}
						}
					}
					break;
				case 'AUS_PARCEL_EXPRESS':
					if ( isset( $this->settings['satchels']['express'] ) ) {
						if ( $params['weight'] <= $this->get_max_satchel_weight() && Utilities::fit_satchels( $this->settings['satchels']['express'], $params ) ) {
							unset( $filtered_options[ $key ] );
							foreach ( $this->satchels_services['AUS_PARCEL_EXPRESS'] as $s ) {
								if ( $this->is_satchel_enabled( $s ) && in_array( $s, $available_services_codes ) ) {
									$filtered_options[] = $s;
								}
							}
						}
					}
					break;
				case 'AUS_PARCEL_COURIER':
					if ( isset( $this->settings['satchels']['courier'] ) ) {
						if ( $params['weight'] <= $this->get_max_satchel_weight() && Utilities::fit_satchels( $this->settings['satchels']['courier'], $params ) ) {
							unset( $filtered_options[ $key ] );
							foreach ( $this->satchels_services['AUS_PARCEL_COURIER'] as $s ) {
								if ( $this->is_satchel_enabled( $s ) && in_array( $s, $available_services_codes ) ) {
									$filtered_options[] = $s;
								}
							}
						}
					}
					break;

			}
		}

		if ( empty( array_intersect( $available_services_codes, $filtered_options ) ) ) {
			if ( in_array( 'AUS_PARCEL_REGULAR', $domestic_options ) ) {
				$filtered_options['AUS_PARCEL_REGULAR'] = 'AUS_PARCEL_REGULAR';
			}
			if ( in_array( 'AUS_PARCEL_EXPRESS', $domestic_options ) ) {
				$filtered_options['AUS_PARCEL_EXPRESS'] = 'AUS_PARCEL_EXPRESS';
			}
		}

		if ( isset( $this->settings['enabled_domestic_letters'] ) ) {
			if ( ! empty( $this->settings['enabled_domestic_letters'] ) ) {
				foreach ( $this->settings['enabled_domestic_letters'] as $domestic_letters_option ) {
					foreach ( $this->letters_services['domestic'][ $domestic_letters_option ] as $letter_code ) {
						$filtered_options[ $letter_code ] = $letter_code;
					}
				}
			}
		}


		return apply_filters( 'australia_post_filter_domestic_options', $filtered_options );

	}

	/**
	 * @return float|int
	 * @since 1.7.0
	 */
	private function get_max_satchel_weight() {
		return 5;
	}

	/**
	 * @param string $satchel
	 *
	 * @return bool
	 */
	private function is_satchel_enabled( $satchel ) {
		switch ( $satchel ) {
			case 'AUS_PARCEL_REGULAR_SATCHEL_SMALL':
				return ( isset( $this->settings['satchels']['regular']['small'] ) );
			case 'AUS_PARCEL_REGULAR_SATCHEL_MEDIUM':
				return ( isset( $this->settings['satchels']['regular']['1kg'] ) );
			case 'AUS_PARCEL_REGULAR_SATCHEL_LARGE':
				return ( isset( $this->settings['satchels']['regular']['medium'] ) );
			case 'AUS_PARCEL_REGULAR_SATCHEL_EXTRA_LARGE':
				return ( isset( $this->settings['satchels']['regular']['large'] ) );
			case 'AUS_PARCEL_EXPRESS_SATCHEL_SMALL':
				return ( isset( $this->settings['satchels']['express']['small'] ) );
			case 'AUS_PARCEL_EXPRESS_SATCHEL_MEDIUM':
				return ( isset( $this->settings['satchels']['express']['1kg'] ) );
			case 'AUS_PARCEL_EXPRESS_SATCHEL_LARGE':
				return ( isset( $this->settings['satchels']['express']['medium'] ) );
			case 'AUS_PARCEL_EXPRESS_SATCHEL_EXTRA_LARGE':
				return ( isset( $this->settings['satchels']['express']['large'] ) );
			case 'AUS_PARCEL_COURIER_SATCHEL_LARGE':
				return ( isset( $this->settings['satchels']['courier']['medium'] ) );
		}

		return false;
	}

	/**
	 * @param $service
	 * @param $query_params
	 *
	 * @return mixed
	 */
	private function get_shipping_options_for_domestice( $service, $query_params ) {
		$query_params['option_code'] = ( $this->settings['signature_on_delivery'] == 'yes' && ! in_array( $service->code, [
				'AUS_PARCEL_COURIER_SATCHEL_LARGE',
				'AUS_LETTER_REGULAR_LARGE_250',
				'AUS_LETTER_REGULAR_LARGE_500',
				'AUS_PARCEL_COURIER'
			] ) ) ? 'AUS_SERVICE_OPTION_SIGNATURE_ON_DELIVERY' : 'AUS_SERVICE_OPTION_STANDARD';
		if ( $this->settings['signature_on_delivery'] == 'yes' && in_array( $service->code, $this->delivery_confirmation ) ) {
			$query_params['option_code'] = 'AUS_SERVICE_OPTION_SIGNATURE_ON_DELIVERY';
		}
		if ( $this->settings['signature_on_delivery'] == 'yes' && in_array( $service->code, $this->delivery_confirmation_suboption ) ) {
			$query_params['option_code']    = 'AUS_SERVICE_OPTION_STANDARD';
			$query_params['suboption_code'] = 'AUS_SERVICE_OPTION_SIGNATURE_ON_DELIVERY';
		}

		return $query_params;
	}

	/**
	 * @param $query_params
	 *
	 * @return mixed
	 */
	private function filter_parames_for_letter_shipping( $query_params ) {
		if ( isset( $query_params['thickness'] ) ) {
			unset( $query_params['thickness'] );
			unset( $query_params['height'] );
			unset( $query_params['width'] );
			unset( $query_params['length'] );
		}

		return $query_params;
	}

	/**
	 * @param array $query_params
	 *
	 * @param $api_calculate_endpoint
	 *
	 * @return array|mixed|object
	 */
	private function get_prices_by_service_code( $query_params, $api_calculate_endpoint ) {

		$cacl_response = wp_remote_get( $api_calculate_endpoint . '?' . http_build_query( $query_params ), [
			'timeout'   => 70,
			'sslverify' => 0,
			'headers'   => [
				'AUTH-KEY' => $this->settings['api_key'],
			]
		] );

		return json_decode( wp_remote_retrieve_body( $cacl_response ) );
	}

	private function get_domestic_extra_cover_cost( $order_cost ) {

		if ( $order_cost <= 100 ) {
			return 0;
		}

		$order_cost = min( $order_cost, 5000 );

		$hundreds = ceil( $order_cost / 100 ) - 1;

		return $this->extra_cover_cost * $hundreds;
	}

	/**
	 * get_rate_label function.
	 * get the rate label for domestic services, I used this function to get the
	 * label for satchel services.
	 * @access private
	 *
	 * @param string $code
	 *
	 * @return string $label
	 */
	private function get_rate_label( $code ) {
		//check the satchels codes first
		switch ( $code ) {
			case 'AUS_PARCEL_REGULAR_SATCHEL_SMALL':
			case 'AUS_PARCEL_REGULAR_SATCHEL_MEDIUM':
			case 'AUS_PARCEL_REGULAR_SATCHEL_LARGE':
			case 'AUS_PARCEL_REGULAR_SATCHEL_EXTRA_LARGE':
				$code = 'AUS_PARCEL_REGULAR';
				break;
			case 'AUS_PARCEL_EXPRESS_SATCHEL_SMALL':
			case 'AUS_PARCEL_EXPRESS_SATCHEL_MEDIUM':
			case 'AUS_PARCEL_EXPRESS_SATCHEL_LARGE':
			case 'AUS_PARCEL_EXPRESS_SATCHEL_EXTRA_LARGE':
				$code = 'AUS_PARCEL_EXPRESS';
				break;
			case 'AUS_PARCEL_COURIER_SATCHEL_LARGE':
				$code = 'AUS_PARCEL_COURIER';
				break;
		}

		if ( isset( $this->settings['custom_titles'][ $code ] ) && $this->settings['custom_titles'][ $code ] != '' ) {
			return $this->settings['custom_titles'][ $code ];
		}
		if ( strpos( $code, 'LETTER' ) !== false ) {
			return 'Australia Post ' . $this->supported_letter_services[ $code ];
		}
		if ( strpos( $code, 'REGULAR' ) !== false ) {
			return 'Australia Post ' . $this->supported_services['AUS_PARCEL_REGULAR'];
		}
		if ( strpos( $code, 'EXPRESS' ) !== false ) {
			return 'Australia Post ' . $this->supported_services['AUS_PARCEL_EXPRESS'];
		}
		if ( strpos( $code, 'COURIER' ) !== false ) {
			return 'Australia Post ' . $this->supported_services['AUS_PARCEL_COURIER'];
		}
		//since 1.7.2
		if ( strpos( $code, 'INT' ) !== false ) {
			return 'Australia Post ' . $this->supported_international_services[ $code ];
		}

		return '';
	}

	/**
	 * @param $cacl_response
	 *
	 * @return string
	 */
	private function get_duration_text( $cacl_response ) {
		$duration = '';

		if ( $this->settings['show_duration'] !== 'yes' ) {
			return $duration;
		}

		if ( isset( $cacl_response->postage_result->delivery_time ) ) {
			$duration = ' (' . $cacl_response->postage_result->delivery_time . ')';
		}

		return $duration;
	}

	/**
	 * @param $old_rates
	 * @param $weight
	 * @param $height
	 * @param $width
	 * @param $length
	 * @param $destination
	 * @param $order_cost
	 *
	 * @return array
	 */
	private function get_international_rates( $old_rates, $weight, $height, $width, $length, $destination, $order_cost ) {
		$rates                        = [];
		$query_params['country_code'] = $destination;

		// since 1.6 letters support
		$dimensions['width']    = $width;
		$dimensions['height']   = $height;
		$dimensions['length']   = $length;
		$dimensions['weight']   = $weight;
		$api_calculate_endpoint = $this->intl_calculate_url;
		$api_services_endpoint  = $this->postage_intl_url;
		$is_letter              = false;
		if ( $this->is_letter( $dimensions, $destination ) && $this->only_letters === true ) {
			$api_calculate_endpoint = $this->letter_calculate_intl_url;
			$api_services_endpoint  = $this->letter_services_intl_url;

			$query_params['weight'] = $weight * 1000; // convert to grams
			$is_letter              = true;
		} else {
			$query_params['weight'] = $weight;
		}

		$this->debug( 'Australia Post REQUEST', [ htmlspecialchars( $api_services_endpoint . '?' . http_build_query( $query_params ) ) ] );
		$response     = wp_remote_get( $api_services_endpoint . '?' . http_build_query( $query_params ), [
			'timeout'   => 70,
			'sslverify' => 0,
			'headers'   => [
				'AUTH-KEY' => $this->settings['api_key'],
			]
		] );
		$aus_response = json_decode( wp_remote_retrieve_body( $response ) );

		$this->debug( 'Australia Post RESPONSE', $aus_response );
		if ( isset( $aus_response->services ) ) {
			//if user choose only one option, woocommerce will save it as a string.
			$intl_options = ( ! is_array( $this->settings['intl_options'] ) ) ? [ $this->settings['intl_options'] ] : $this->settings['intl_options'];

			$services = is_array( $aus_response->services->service ) ? $aus_response->services->service : [ $aus_response->services->service ];

			foreach ( $services as $service ) {

				if ( in_array( $service->code, $intl_options ) || ( $is_letter === true && in_array( $service->code, $this->filter_international_letters( $dimensions ) ) ) ) {
					// calculate standard and sign on delivery
					$query_params['service_code'] = $service->code;
					$extra_cover_total            = 0;
					$sod_fee                      = 0;
					// only SeaMail and AirMail support SoD and EC.
					WC()->session->set( 'extra_cover_total', 0 );
					WC()->session->set( 'sod_fee ', 0 );
					if ( in_array( $service->code, $this->delivery_confirmation ) ) {
						//@since 1.7 Extra Cover
						if ( $this->settings['enable_extra_cover'] == 'yes' ) {
							$query_params['suboption_code'] = 'INT_EXTRA_COVER';
							$query_params['extra_cover']    = ceil( $order_cost );
							$extra_cover_total              = $this->get_intl_extra_cover_cost( $order_cost, $service->code );
							if ( 'yes' === $this->settings['seperate_extracover_sod'] ) {
								$extra_cover_total = 0;
								WC()->session->set( 'extra_cover_total', $this->get_intl_extra_cover_cost( $order_cost, $service->code ) );
							}
						}

						// @since 1.7 add SoD for international
						if ( $this->settings['signature_on_delivery'] == 'yes' ) {
							$query_params['suboption_code'] = 'INT_EXTRA_COVER';
							$query_params['option_code']    = 'INT_SIGNATURE_ON_DELIVERY';
							$query_params['extra_cover']    = ceil( $order_cost );
							$sod_fee                        = $this->intl_sod_fee;
							if ( $this->settings['seperate_extracover_sod'] == 'yes' ) {
								$sod_fee = 0;
								WC()->session->set( 'sod_fee', $this->intl_sod_fee );
							}
						}
					}

					// @since 1.4.2 show delivery duration
					$duration = '';
					//since 1.7.2 improve debugging mode
					$this->debug( 'Australia Post REQUEST', $query_params );
					$cacl_response = wp_remote_get( $api_calculate_endpoint . '?' . http_build_query( $query_params ), [
						'timeout'   => 70,
						'sslverify' => 0,
						'headers'   => [
							'AUTH-KEY' => $this->settings['api_key'],
						]
					] );

					if ( isset( $cacl_response->error ) ) {
						return [ 'error' => $cacl_response->error->errorMessage ];
					}

					$cacl_response = json_decode( wp_remote_retrieve_body( $cacl_response ) );
					//since 1.7.2 improve debugging mode
					$this->debug( 'Australia Post RESPONSE', $cacl_response );
					if ( isset( $cacl_response->postage_result ) ) {
						$rate_cost               = ( is_array( $cacl_response->postage_result->costs->cost ) ) ? $cacl_response->postage_result->costs->cost[0]->cost : $cacl_response->postage_result->costs->cost->cost;
						$total_shipping_cost     = ( ( $this->calculate_handling_fee( $rate_cost ) + $rate_cost ) ) + ( ( isset( $old_rates[ $service->code ]['cost'] ) ) ? $old_rates[ $service->code ]['cost'] : 0 ) + $extra_cover_total + $sod_fee;
						$rates[ $service->code ] = [
							'id'    => 'aus:' . $service->code . ':' . $this->settings['instance_id'],
							'label' => $this->get_rate_label( $service->code ) . $duration,
							'cost'  => round( $total_shipping_cost, 2 ),
						];
					}
				}
			}
		}

		return $rates;
	}

	/**
	 * @param $dimensions
	 *
	 * @return array
	 */
	private function filter_international_letters( $dimensions ) {
		$filtered_options = [];
		if ( ! empty( $this->settings['enabled_intl_letters'] ) ) {
			foreach ( $this->settings['enabled_intl_letters'] as $intl_letters_option ) {
				foreach ( $this->letters_services['international'][ $intl_letters_option ] as $letter_code ) {
					if ( $this->is_letter_option_fit_dimensions( $letter_code, $dimensions ) ) {
						$filtered_options[ $letter_code ] = $letter_code;
					}
				}
			}
		}

		// remove large option, if the item fit in the small option.
		if ( isset( $filtered_options['INT_LETTER_REG_SMALL'] ) && isset( $filtered_options['INT_LETTER_REG_LARGE'] ) ) {
			unset( $filtered_options['INT_LETTER_REG_LARGE'] );
		}
		if ( isset( $filtered_options['INT_LETTER_REG_SMALL_ENVELOPE'] ) && isset( $filtered_options['INT_LETTER_REG_LARGE_ENVELOPE'] ) ) {
			unset( $filtered_options['INT_LETTER_REG_LARGE_ENVELOPE'] );
		}

		return $filtered_options;
	}

	/**
	 * @param $letter_code
	 * @param $dimensions
	 *
	 * @return bool
	 */
	private function is_letter_option_fit_dimensions( $letter_code, $dimensions ) {
		$dimensions_by_letter_service = [
			'INT_LETTER_REG_SMALL'                => [ 'length' => 13, 'width' => 24 ],
			'INT_LETTER_REG_SMALL_ENVELOPE'       => [ 'length' => 13, 'width' => 24 ],
			'INT_LETTER_REG_LARGE'                => [ 'length' => 25, 'width' => 35.5 ],
			'INT_LETTER_REG_LARGE_ENVELOPE'       => [ 'length' => 25, 'width' => 35.5 ],
			'INT_LETTER_AIR_OWN_PACKAGING_MEDIUM' => [ 'length' => 13, 'width' => 24 ],
		];


		if ( ! isset( $dimensions_by_letter_service[ $letter_code ] ) ) {
			return true;
		}

		if ( $dimensions['length'] > $dimensions_by_letter_service[ $letter_code ]['length'] ) {
			return false;
		}

		if ( $dimensions['width'] > $dimensions_by_letter_service[ $letter_code ]['width'] ) {
			return false;
		}

		return true;
	}

	private function get_intl_extra_cover_cost( $order_cost, $service_code ) {

		if ( $order_cost <= 100 ) {
			return 0;
		}

		if ( isset( $this->extra_cover[ $service_code ] ) ) {
			$order_cost = min( $order_cost, $this->extra_cover[ $service_code ] );
		}

		$hundreds = ceil( $order_cost / 100 );

		return $this->intl_extra_cover_intial_cost + ( ( $hundreds - 1 ) * $this->intl_extra_cover_factor_cost );
	}
}
