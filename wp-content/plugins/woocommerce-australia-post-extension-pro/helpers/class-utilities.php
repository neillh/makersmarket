<?php
namespace AustraliaPost\Helpers;


use AustraliaPost\Core\Data\Zone_Scope;

class Utilities {

	public static function php_more_than($version)
	{
		return version_compare(phpversion(), $version, '>=');
	}

	public static function php_less_than($version)
	{
		return version_compare(phpversion(), $version, '<');
	}

	public static function get_method_setting($key)
	{
		$value = get_option( 'woocommerce_instance_auspost_settings' );
		return ( isset( $value[ $key ] ) ) ? $value[ $key ] : null;
	}

	/**
	 * @param $satchels
	 * @param $params
	 * @since 1.7.0
	 *
	 * @return bool
	 */
	public static function fit_satchels($satchels, $params)
	{
		if (isset($satchels['large'])) {
			return self::fit_satchel_size($params, 'large');
		}

		if (isset($satchels['medium'])) {
			return self::fit_satchel_size($params, 'medium');
		}

		if (isset($satchels['1kg'])) {
			return self::fit_satchel_size($params, '1kg');
		}

		if (isset($satchels['small'])) {
			return self::fit_satchel_size($params, 'small');
		}

		return false;
	}

	/**
	 * @param $params
	 * @param $size
	 *
	 * @return bool
	 */
	public static function fit_satchel_size($params, $size)
	{
		$dimensions = [$params['length'], $params['width'], $params['height']];
		asort($dimensions);

		$params['length'] = floatval($dimensions[0]);
		$params['width'] = floatval($dimensions[1]);
		$params['height'] = floatval($dimensions[2]);

		$package_width_girth = ($params['height'] + $params['width']) * 2;
		$package_length_girth = ($params['height'] + $params['length']) * 2;
		$girths = [
			'large' => [
				'width' => 43.5,
				'length' => 51,
			],
			'medium' => [
				'width' => 30,
				'length' => 39,
			],
			'1kg' => [
				'width' => 26.5,
				'length' => 38.5,
			],
			'small' => [
				'width' => 21,
				'length' => 34,
			],
		];

		//check girth
		$satchel_width_girth =  ($girths[$size]['width'] * 2) - 1;
		$satchel_length_girth = ($girths[$size]['length'] * 2) - 1;

		if ($package_width_girth > $satchel_width_girth || $package_length_girth > $satchel_length_girth) {
			return false;
		}

		return ($params['length'] <= $girths[$size]['length'] && $params['width'] <= $girths[$size]['width']);
	}

	/**
	 * @param int $instance_id
	 * @return Zone_Scope
	 */
	public static function get_zone_scope( $instance_id ) {

		$locations = \WC_Shipping_Zones::get_zone_by('instance_id', intval($instance_id))->get_zone_locations();

		$countries = array();
		foreach ($locations as $location){

			if ($location->type === 'country'){
				if (!isset($countries[$location->code])) {
					$countries[$location->code] = $location->code;
				}
			}

			if ($location->type === 'state'){
				$country = explode(':', $location->code);
				if (!isset($countries[$country[0]])) {
					$countries[$country[0]] = $country[0];
				}

			}
		}

		return new Zone_Scope($countries);

	}

	public static function _json_encode($arr, $option = JSON_ERROR_NONE) {

		$precision = ini_get('precision');
		$serialize_precision = ini_get('serialize_precision');

		if ( version_compare(PHP_VERSION, '7.1', '>=') ) {
			ini_set( 'precision', 17 );
			ini_set( 'serialize_precision', -1 );
		}
		$str = json_encode($arr, $option);

		ini_set( 'precision', $precision );
		ini_set( 'serialize_precision', $serialize_precision );

		return $str;
	}

	public static function string_ends_with( $haystack, $needle ) {
		$length = strlen( $needle );
		if ( !$length ) {
			return true;
		}
		return substr( $haystack, -$length ) === $needle;
	}
}
