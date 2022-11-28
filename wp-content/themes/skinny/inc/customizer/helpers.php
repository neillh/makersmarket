<?php
/**
 * Customizer helpers.
 *
 * @package Skinny\Customizer
 */

namespace Skinny\Customizer;

/**
 * Sanitize a radio field setting from the customizer.
 *
 * @param string $value The radio field value being saved.
 * @param string $setting The name of the setting being saved.
 *
 * @return string
 */
function sanitize_radio( $value, $setting ) {

	$input = sanitize_title( $value );

	$choices = $setting->manager->get_control( $setting->id )->choices;

	return array_key_exists( $input, $choices ) ? $input : $setting->default;

}

/**
 * Only allow values between a certain minimum & maxmium range
 *
 * @param  number   Input to be sanitized
 * @return number   Sanitized input
 */
function in_range( $input, $min, $max ) {
	if ( $input < $min ) {
		$input = $min;
	}
	if ( $input > $max ) {
		$input = $max;
	}
	return $input;
}

/**
 * Slider sanitization.
 *
 * @param  string   Slider value to be sanitized
 * @return string   Sanitized input
 */
function sanitize_range( $input, $setting ) {
	$attrs = $setting->manager->get_control( $setting->id )->input_attrs;
	$min   = ( isset( $attrs['min'] ) ? $attrs['min'] : $input );
	$max   = ( isset( $attrs['max'] ) ? $attrs['max'] : $input );
	$step  = ( isset( $attrs['step'] ) ? $attrs['step'] : 1 );
	if ( is_numeric( $input ) ) {
		$number = floor( $input / $attrs['step'] ) * $attrs['step'];
	} else {
		$number = $input;
	}
	return in_range( $number, $min, $max );
}

/**
 * Alpha Color (Hex & RGBa) sanitization
 *
 * @param  string   Input to be sanitized
 * @return string   Sanitized input
 */
function sanitize_hex_rgba( $input, $setting ) {
	if ( empty( $input ) || is_array( $input ) ) {
		return $setting->default;
	}
	if ( false === strpos( $input, 'rgba' ) ) {
		// If string doesn't start with 'rgba' then santize as hex color
		$input = sanitize_hex_color( $input );
	} else {
		// Sanitize as RGBa color
		$input = str_replace( ' ', '', $input );
		sscanf( $input, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
		$input = 'rgba(' . in_range( $red, 0, 255 ) . ',' . in_range( $green, 0, 255 ) . ',' . in_range( $blue, 0, 255 ) . ',' . in_range( $alpha, 0, 1 ) . ')';
	}
	return $input;
}

/**
 * Switch sanitization
 *
 * @param  string $input  Switch value.
 * @return integer  Sanitized value
 */
function sanitize_switch( $input ) {
	if ( true === $input ) {
		return 1;
	}

	return 0;
}

/**
 * Sanitize responsive  Spacing
 *
 * @param  number $val Customizer setting input number.
 * @return number        Return number.
 */
function sanitize_responsive_spacing( $val ) {

	$spacing = array(
		'desktop'      => array(
			'top'    => '',
			'right'  => '',
			'bottom' => '',
			'left'   => '',
		),
		'tablet'       => array(
			'top'    => '',
			'right'  => '',
			'bottom' => '',
			'left'   => '',
		),
		'mobile'       => array(
			'top'    => '',
			'right'  => '',
			'bottom' => '',
			'left'   => '',
		),
		'desktop-unit' => 'px',
		'tablet-unit'  => 'px',
		'mobile-unit'  => 'px',
	);

	if ( isset( $val['desktop'] ) ) {
		$spacing['desktop'] = array_map(
			function ( $value ) {
				return ( is_numeric( $value ) && $value >= 0 ) ? $value : '';
			},
			$val['desktop']
		);

		$spacing['tablet'] = array_map(
			function ( $value ) {
				return ( is_numeric( $value ) && $value >= 0 ) ? $value : '';
			},
			$val['tablet']
		);

		$spacing['mobile'] = array_map(
			function ( $value ) {
				return ( is_numeric( $value ) && $value >= 0 ) ? $value : '';
			},
			$val['mobile']
		);

		if ( isset( $val['desktop-unit'] ) ) {
			$spacing['desktop-unit'] = $val['desktop-unit'];
		}

		if ( isset( $val['tablet-unit'] ) ) {
			$spacing['tablet-unit'] = $val['tablet-unit'];
		}

		if ( isset( $val['mobile-unit'] ) ) {
			$spacing['mobile-unit'] = $val['mobile-unit'];
		}

		return $spacing;

	} else {
		foreach ( $val as $key => $value ) {
			$val[ $key ] = is_numeric( $val[ $key ] ) ? $val[ $key ] : '';
		}
		return $val;
	}

}

/**
 * Sanitize Responsive Typography
 *
 * @param  array|number $val Customizer setting input number.
 * @return array        Return number.
 */
function sanitize_responsive_typo( $val ) {

	$responsive = array(
		'desktop'      => '',
		'tablet'       => '',
		'mobile'       => '',
		'desktop-unit' => '',
		'tablet-unit'  => '',
		'mobile-unit'  => '',
	);
	if ( is_array( $val ) ) {
		$responsive['desktop']      = is_numeric( $val['desktop'] ) ? $val['desktop'] : '';
		$responsive['tablet']       = is_numeric( $val['tablet'] ) ? $val['tablet'] : '';
		$responsive['mobile']       = is_numeric( $val['mobile'] ) ? $val['mobile'] : '';
		$responsive['desktop-unit'] = in_array( $val['desktop-unit'], array( '', 'px', 'em', 'rem', '%' ) ) ? $val['desktop-unit'] : 'px';
		$responsive['tablet-unit']  = in_array( $val['tablet-unit'], array( '', 'px', 'em', 'rem', '%' ) ) ? $val['tablet-unit'] : 'px';
		$responsive['mobile-unit']  = in_array( $val['mobile-unit'], array( '', 'px', 'em', 'rem', '%' ) ) ? $val['mobile-unit'] : 'px';
	} else {
		$responsive['desktop'] = is_numeric( $val ) ? $val : '';
	}
	return $responsive;
}

/**
 * Text sanitization
 *
 * @param  string   Input to be sanitized (either a string containing a single string or multiple, separated by commas)
 * @return string   Sanitized input
 */
function sanitize_text( $input ) {
	if ( strpos( $input, ',' ) !== false ) {
		$input = explode( ',', $input );
	}
	if ( is_array( $input ) ) {
		foreach ( $input as $key => $value ) {
			$input[ $key ] = sanitize_text_field( $value );
		}
		$input = implode( ',', $input );
	} else {
		$input = sanitize_text_field( $input );
	}
	return $input;
}

/*
 * Get all the Re-usable blocks with id & title.
 *
 * @return array $blocks
 */
function get_reusable_blocks() {
	$blocks = array(
		'select' => __( 'Select a block', 'skinny' ),
	);

	$args = array(
		'numberposts' => -1, // phpcs:ignore
		'post_type'   => 'wp_block',
	);

	$data = get_posts( $args );

	if ( ! empty( $data ) ) {
		foreach ( $data as $key => $block ) {
			$blocks[ $block->ID ] = $block->post_title;
		}
	}

	return $blocks;
}

