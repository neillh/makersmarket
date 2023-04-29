<?php
namespace AustraliaPost\Core;

class Constants {

	const default_api_key = '20b5d076-5948-448f-9be4-f2fd20d4c258';

	const supported_services = [
		'AUS_PARCEL_REGULAR' => 'Regular Post',
		'AUS_PARCEL_EXPRESS' => 'Express Post',
		'AUS_PARCEL_COURIER' => 'Courier Post'
	];

	const supported_international_services = [
		'INT_PARCEL_SEA_OWN_PACKAGING' => 'Economy Sea',
		'INT_PARCEL_AIR_OWN_PACKAGING' => 'Economy Air',
		'INT_PARCEL_STD_OWN_PACKAGING' => 'Standard International',
		'INT_PARCEL_COR_OWN_PACKAGING' => 'Courier International',
		'INT_PARCEL_EXP_OWN_PACKAGING' => 'Express International',
	];

	const domestic_letters_services = [
		'regular' => 'Regular Letters',
		'express' => 'Express Letters',
		'priority' => 'Priority Letters'
	];

	const intl_letters_services = [
		'registered'    => 'Registered Letters',
		'economy_air'   => 'Economy Air Letters',
		'courier'       => 'Courier Letters',
		'express'       => 'Express Letters',
		'standard'      => 'Standard Letters',
	];

	const domestic_tracked_letters = [
		'small_envelop' =>   [
			'name' => 'Small Tracked Envelop',
			'price' => 3.05,
			'dimensions' => ['l' => 240, 'w' => 130, 'h' => 5],
			'max_weight' => 125,
		],
		'medium_envelop' =>   [
			'name' => 'Medium Tracked Envelop',
			'price' => 5.10,
			'dimensions' => ['l' => 240, 'w' => 162, 'h' => 20],
			'max_weight' => 500,
		],
		'large_envelop' =>   [
			'name' => 'Large Tracked Envelop',
			'price' => 6.15,
			'dimensions' => ['l' => 324, 'w' => 229, 'h' => 20],
			'max_weight' => 500,
		]
	];
}
