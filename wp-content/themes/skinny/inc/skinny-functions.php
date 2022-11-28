<?php
/**
 * Skinny functions.
 *
 * @package Skinny
 */

if ( ! function_exists( 'skinny_is_woocommerce_activated' ) ) {
	/**
	 * Query WooCommerce activation
	 */
	function skinny_is_woocommerce_activated() {
		return class_exists( 'WooCommerce' );
	}
}

/**
 * Get Font Size value
 */
if ( ! function_exists( 'skinny_responsive_font' ) ) {

	/**
	 * Get Font CSS value
	 *
	 * @param  array  $font    CSS value.
	 * @param  string $device  CSS device.
	 * @param  string $default Default value.
	 * @return mixed
	 */
	function skinny_responsive_font( $font, $device = 'desktop', $default = '' ) {

		$css_val = '';

		if ( isset( $font[ $device ] ) && isset( $font[ $device . '-unit' ] ) ) {
			if ( '' !== $default ) {
				$font_size = skinny_get_css_value( $font[ $device ], $font[ $device . '-unit' ], $default );
			} else {
				$font_size = skinny_get_font_css_value( $font[ $device ], $font[ $device . '-unit' ] );
			}
		} elseif ( is_numeric( $font ) ) {
			$font_size = skinny_get_css_value( $font );
		} else {
			$font_size = ( ! is_array( $font ) ) ? $font : '';
		}

		return $font_size;
	}
}


/**
 * Get Font Size value
 */
if ( ! function_exists( 'skinny_get_font_css_value' ) ) {

	/**
	 * Get Font CSS value
	 *
	 * @param  string $value        CSS value.
	 * @param  string $unit         CSS unit.
	 * @param  string $device       CSS device.
	 * @return mixed                CSS value depends on $unit & $device
	 */
	function skinny_get_font_css_value( $value, $unit = 'px', $device = 'desktop' ) {

		// If value is empty or 0 then return blank.
		if ( '' === $value || 0 === $value ) {
			return '';
		}

		$css_val = '';

		switch ( $unit ) {
			case 'em':
			case '%':
				$css_val = esc_attr( $value ) . $unit;
				break;

			case 'px':
				if ( is_numeric( $value ) || strpos( $value, 'px' ) ) {
					$value            = intval( $value );
					$fonts            = array();
					$body_font_size   = skinny_get_thememod( 'font_base_size' );
					$fonts['desktop'] = ( isset( $body_font_size['desktop'] ) && '' !== $body_font_size['desktop'] ) ? $body_font_size['desktop'] : 16;
					$fonts['tablet']  = ( isset( $body_font_size['tablet'] ) && '' !== $body_font_size['tablet'] ) ? $body_font_size['tablet'] : $fonts['desktop'];
					$fonts['mobile']  = ( isset( $body_font_size['mobile'] ) && '' !== $body_font_size['mobile'] ) ? $body_font_size['mobile'] : $fonts['tablet'];

					if ( $fonts[ $device ] ) {
						$css_val = esc_attr( $value );
					}
				} else {
					$css_val = esc_attr( $value );
				}
		}

		return $css_val;
	}
}


/**
 * Get CSS value
 */
if ( ! function_exists( 'skinny_get_css_value' ) ) {

	/**
	 * Get CSS value
	 *
	 * @param  string $value        CSS value.
	 * @param  string $unit         CSS unit.
	 * @param  string $default      CSS default font.
	 * @return mixed               CSS value depends on $unit
	 */
	function skinny_get_css_value( $value = '', $unit = 'px', $default = '' ) {

		if ( '' === $value && '' === $default ) {
			return $value;
		}

		$css_val = '';

		switch ( $unit ) {

			case 'px':
				$value   = ( '' !== $value ) ? $value : $default;
				$css_val = esc_attr( $value );
				break;
			case '%':
				$value   = ( '' !== $value ) ? $value : $default;
				$css_val = esc_attr( $value ) . $unit;
				break;

			case 'rem':
				if ( is_numeric( $value ) || strpos( $value, 'px' ) ) {
					$value          = intval( $value );
					$body_font_size = skinny_get_thememod( 'font_base_size' );
					if ( is_array( $body_font_size ) ) {
						$body_font_size_desktop = ( isset( $body_font_size['desktop'] ) && '' !== $body_font_size['desktop'] ) ? $body_font_size['desktop'] : 16;
					} else {
						$body_font_size_desktop = ( '' !== $body_font_size ) ? $body_font_size : 16;
					}

					if ( $body_font_size_desktop ) {
						$css_val = esc_attr( $value ) . 'px;font-size:' . ( esc_attr( $value ) / esc_attr( $body_font_size_desktop ) ) . $unit;
					}
				} else {
					$css_val = esc_attr( $value );
				}

				break;

			default:
				$value = ( '' !== $value ) ? $value : $default;
				if ( '' !== $value ) {
					$css_val = esc_attr( $value ) . $unit;
				}
		}

		return $css_val;
	}
}

if ( ! function_exists( 'skinny_get_relative_font_size' ) ) {
	/**
	 * Convert a font size to a relative size based on a starting value and percentage.
	 *
	 * @since  1.0.0.
	 *
	 * @param  mixed    $value         The value to base the final value on.
	 * @param  mixed    $percentage    The percentage of change.
	 * @param string    $unit          The unit in which values should get returned.
	 * @return float                   The converted value.
	 */
	function skinny_get_relative_font_size( $value, $percentage, $unit = 'px' ) {
		$value = (float) $value * ( $percentage / 100 );
		return $value ? $value . $unit : '';
	}
}

/**
 * Get theme settings.
 */
if ( ! function_exists( 'skinny_get_setting' ) ) {
	/**
	 * Get theme settings.
	 *
	 * @param string $option Settings id.
	 * @return string|array setting theme option values.
	 */
	function skinny_get_setting( $option = '') {
		$settings = \Skinny\Customizer\Theme_Options::settings();

		if ( '' === $option ) {
			return $settings;
		}

		return $settings[ $option ];
	}
}

/**
 * Get theme mod default.
 */
if ( ! function_exists( 'skinny_get_default' ) ) {
	/**
	 * Get theme mod default.
	 *
	 * @param string $option Settings id.
	 * @return string|array default theme option value.
	 */
	function skinny_get_default( $option ) {
		$setting = skinny_get_setting( $option );

		$default_value = '';
		if ( isset( $setting ) ) {
			$default_value = $setting;
		}

		/**
		 * Filter the default value for a particular setting.
		 *
		 * @since 1.0.0.
		 *
		 * @param string|array    $default_value    The default value of the setting.
		 * @param string          $option       The id of the setting.
		 */
		return apply_filters( 'skinny_theme_option_default_value', $default_value, $option );
	}
}


/**
 * Get theme mod value.
 */
if ( ! function_exists( 'skinny_get_thememod' ) ) {
	/**
	 * Get theme mod value.
	 *
	 * @param string $option Settings id.
	 * @return string|array $value theme option current value.
	 */
	function skinny_get_thememod( $option ) {
		$value = get_theme_mod( $option, skinny_get_default( $option ) );

		return apply_filters( 'skinny_theme_option_current_value', $value, $option );
	}
}

/**
 * Determines if a post, identified by the specified ID, exist
 * within the WordPress database.
 *
 * @param    int $id    The ID of the post to check.
 * @return   bool          True if the post exists; otherwise, false.
 * @since    1.0.0
 */
function skinny_post_exists( $id ) {
	return is_string( get_post_status( $id ) );
}
