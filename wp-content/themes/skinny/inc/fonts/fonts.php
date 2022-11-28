<?php

/**
 * Base class for handling fonts.
 *
 * @package Skinny
 */
namespace Skinny\Fonts;

/**
 * Class Skinny Fonts
 *
 */
class Fonts {
	/**
	 * Theme slug/prefix.
	 *
	 * @var $_slug theme slug/prefix.
	 */
	private static $_slug = 'skinny';

	/**
	 * Validate the font choice and get a font stack for it.
	 *
	 * @since  1.0.0.
	 *
	 * @param string $font    The 1st font in the stack.
	 * @return string             The full font stack.
	 */
	public static function get_font_stack( $font ) {
		$all_fonts = self::get_all_fonts();

		// Sanitize font choice.
		$font = self::sanitize_font_choice( $font );

		// Standard font.
		if ( isset( $all_fonts[ $font ]['stack'] ) && ! empty( $all_fonts[ $font ]['stack'] ) ) {
			$stack = $all_fonts[ $font ]['stack'];
		} elseif ( in_array( $font, self::all_font_choices(), true ) ) {
			$stack = "'" . $font . "','Helvetica Neue',Helvetica,Arial,sans-serif";
		} else {
			$stack = "'Helvetica Neue',Helvetica,Arial,sans-serif";
		}

		/**
		 * Allow developers to filter the full font stack.
		 *
		 * @param string    $stack    The font stack.
		 * @param string    $font     The font.
		 */
		return apply_filters( self::$_slug . '_font_stack', $stack, $font );
	}

	/**
	 * Return all the option keys for the specified font property.
	 *
	 * @since  1.3.0.
	 *
	 * @param  string $property    The font property to search for.
	 * @return array                  Array of matching font option keys.
	 */
	public static function get_font_property_option_keys( $property = 'font' ) {
		$font_keys = array(
			'font_body',
			'font_headings',
		);

		return $font_keys;
	}

	/**
	 * Build the HTTP request URL for Google Fonts.
	 *
	 * @since  1.0.0.
	 *
	 * @return string    The URL for including Google Fonts.
	 */
	public static function get_google_font_uri() {
		$keys  = self::get_font_property_option_keys();
		$fonts = array();

		foreach ( $keys as $key ) {
			$fonts[] = skinny_get_thememod( $key );
		}

		// De-dupe the fonts.
		$fonts         = array_unique( $fonts );
		$allowed_fonts = Google_Fonts::get_google_fonts();
		$family        = array();

		// Validate each font and convert to URL format.
		foreach ( $fonts as $font ) {
			$font = trim( $font );

			// Verify that the font exists.
			if ( array_key_exists( $font, $allowed_fonts ) ) {
				// Build the family name and variant string (e.g., "Open+Sans:regular,italic,700").
				$family[] = urlencode( $font ) . ':' . join( ',', self::choose_google_font_variants( $font, $allowed_fonts[ $font ]['variants'] ) );
			}
		}

		// Convert from array to string.
		if ( empty( $family ) ) {
			return '';
		} else {
			$request = 'https://fonts.googleapis.com/css?family=' . implode( '|', $family );
		}

		// Load the font subset.
		$subset            = skinny_get_thememod( 'font_subset' );
		$subsets_available = self::get_google_font_subsets();
		$subsets           = $subsets_available[ $subset ];

		// Append the subset string.
		if ( ! empty( $subsets ) ) {
			$request .= urlencode( '&subset=' . $subsets );
		}

		/**
		 * Filter the Google Fonts URL.
		 *
		 * @param string    $url    The URL to retrieve the Google Fonts.
		 */
		return apply_filters( self::$_slug . '_get_google_font_uri', esc_url( $request ) );
	}
	/**
	 * Given a font, chose the variants to load for the theme.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string $key The font to load variants for.
	 * @return array       The chosen variants.
	 */
	public static function load_google_font_variants( $key ) {

		$font_key = skinny_get_thememod( $key );

		$variants = array();
		$fonts    = Google_Fonts::get_google_fonts();

		if ( array_key_exists( $font_key, $fonts ) ) {
			$variants = $fonts[ $font_key ]['variants'];
		}

		$font_variants = array();

		foreach ( $variants as $index => $value ) {
			switch ( $value ) {
				case '100':
					$font_variants[ $value ] = __( 'Thin 100', 'skinny' );
					break;
				case '100italic':
					$font_variants[ $value ] = __( '100 Italic', 'skinny' );
					break;
				case '200':
					$font_variants[ $value ] = __( 'Extra-Light 200', 'skinny' );
					break;
				case '200italic':
					$font_variants[ $value ] = __( '200 Italic', 'skinny' );
					break;
				case '300':
					$font_variants[ $value ] = __( 'Light 300', 'skinny' );
					break;
				case '300italic':
					$font_variants[ $value ] = __( '300 Italic', 'skinny' );
					break;
				case 'regular':
				case '400':
					$font_variants[ $value ] = __( 'Regular 400', 'skinny' );
					break;
				case 'italic':
				case '400italic':
					$font_variants[ $value ] = __( '400 Italic', 'skinny' );
					break;
				case '500':
					$font_variants[ $value ] = __( 'Medium 500', 'skinny' );
					break;
				case '500italic':
					$font_variants[ $value ] = __( '500 Italic', 'skinny' );
					break;
				case '600':
					$font_variants[ $value ] = __( 'Semi-Bold 600', 'skinny' );
					break;
				case '600italic':
					$font_variants[ $value ] = __( '600 Italic', 'skinny' );
					break;
				case '700':
					$font_variants[ $value ] = __( 'Bold 700', 'skinny' );
					break;
				case '700italic':
					$font_variants[ $value ] = __( '700 Italic', 'skinny' );
					break;
				case '800':
					$font_variants[ $value ] = __( 'Extra-Bold 800', 'skinny' );
					break;
				case '800italic':
					$font_variants[ $value ] = __( '800 Italic', 'skinny' );
					break;
				case '900':
					$font_variants[ $value ] = __( 'Ultra-Bold 900', 'skinny' );
					break;
				case '900italic':
					$font_variants[ $value ] = __( '900 Italic', 'skinny' );
					break;
				default:
					$font_variants[ $value ] = ucfirst( $value );
					break;
			}
		}
		return $font_variants;
	}

	/**
	 * Given a font, chose the variants to load for the theme.
	 *
	 * Attempts to load regular, italic, and 700. If regular is not found, the first variant in the family is chosen. italic
	 * and 700 are only loaded if found. No fallbacks are loaded for those fonts.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string $font        The font to load variants for.
	 * @param  array  $variants    The variants for the font.
	 * @return array                  The chosen variants.
	 */
	public static function choose_google_font_variants( $font, $variants = array() ) {
		$chosen_variants = array();
		if ( empty( $variants ) ) {
			$fonts = Google_Fonts::get_google_fonts();

			if ( array_key_exists( $font, $fonts ) ) {
				$variants = $fonts[ $font ]['variants'];
			}
		}

		$font_keys = array(
			'font_body'    => skinny_get_thememod( 'font_body' ),
			'font_headings' => skinny_get_thememod( 'font_headings' ),
		);

		if ( $font_keys['font_body'] === $font ) {
			$font_variants    = skinny_get_thememod( 'font_body_load_variant' );
			$variants_to_load = explode( ',', $font_variants );
		} elseif ( $font_keys['font_headings'] === $font ) {
			$font_variants    = skinny_get_thememod( 'font_headings_load_variant' );
			$variants_to_load = explode( ',', $font_variants );
		}

		foreach ( $variants_to_load as $index => $value ) {
			if ( in_array( $value, $variants, true ) ) {
				$chosen_variants[] = $value;
			}
		}

		// If a "regular" variant is not found, get the first variant.
		if ( ! in_array( 'regular', $variants, true ) ) {
			$chosen_variants[] = $variants[0];
		} else {
			$chosen_variants[] = 'regular';
		}

		/**
		 * Allow developers to alter the font variant choice.
		 *
		 * @param array     $variants    The list of variants for a font.
		 * @param string    $font        The font to load variants for.
		 * @param array     $variants    The variants for the font.
		 */
		return apply_filters( self::$_slug . '_font_variants', array_unique( $chosen_variants ), $font, $variants );
	}

	/**
	 * Sanitize the Character Subset choice.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $value    The value to sanitize.
	 * @return array|mixed      The sanitized value.
	 */
	public static function sanitize_font_subset( $value ) {
		$subsets = self::get_google_font_subsets();
		if ( array_key_exists( $value, $subsets ) ) {
			return $value;
		}

		return '13';
	}
	/**
	 * Iterate through all the Google font data and build a list of unique subset options.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_google_font_subsets() {
		$subsets   = array();
		$font_data = Google_Fonts::get_google_fonts();

		foreach ( $font_data as $font => $data ) {
			if ( isset( $data['subsets'] ) ) {
				$subsets = array_merge( $subsets, (array) $data['subsets'] );
			}
		}

		$subsets = array_unique( $subsets );
		sort( $subsets );

		return $subsets;
	}

	/**
	 * Sanitize a font choice.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string $value    The font choice.
	 * @return string              The sanitized font choice.
	 */
	public static function sanitize_font_choice( $value ) {
		if ( ! is_string( $value ) ) {
			// The array key is not a string, so the chosen option is not a real choice.
			return '';
		} elseif ( array_key_exists( $value, self::all_font_choices() ) ) {
			return $value;
		} else {
			return '';
		}

		/**
		 * Filter the sanitized font choice.
		 *
		 * @param string    $value    The chosen font value.
		 */
		return apply_filters( self::$_slug . '_sanitize_font_choice', $return );
	}

	/**
	 * Packages the font choices into value/label pairs for use with the customizer.
	 *
	 * @since  1.0.0.
	 *
	 * @return array    The fonts in value/label pairs.
	 */
	public static function all_font_choices() {
		$fonts   = self::get_all_fonts();
		$choices = array();

		// Repackage the fonts into value/label pairs.
		foreach ( $fonts as $key => $font ) {
			$choices[ $key ] = $font['label'];
		}

		return $choices;
	}

	/**
	 * Compile font options from different sources.
	 *
	 * @since  1.0.0.
	 *
	 * @return array    All available fonts.
	 */
	public static function get_all_fonts() {
		$default        = self::get_default_fonts();
		$heading1       = array( 1 => array( 'label' => sprintf( '&mdash; %s &mdash;', esc_html__( 'Standard Fonts', 'skinny' ) ) ) );
		$standard_fonts = self::get_standard_fonts();

		$google_fonts = Google_Fonts::get_google_fonts();

		$serif_heading = array( 2 => array( 'label' => sprintf( '&mdash; %s &mdash;', esc_html__( 'Serif Fonts (Google)', 'skinny' ) ) ) );
		$serif_fonts   = wp_list_filter( $google_fonts, array( 'category' => 'serif' ) );

		$sans_serif_heading = array( 3 => array( 'label' => sprintf( '&mdash; %s &mdash;', esc_html__( 'Sans Serif Fonts (Google)', 'skinny' ) ) ) );
		$sans_serif_fonts   = wp_list_filter( $google_fonts, array( 'category' => 'sans-serif' ) );

		$display_heading = array( 4 => array( 'label' => sprintf( '&mdash; %s &mdash;', esc_html__( 'Display Fonts (Google)', 'skinny' ) ) ) );
		$display_fonts   = wp_list_filter( $google_fonts, array( 'category' => 'display' ) );

		$handwriting_heading = array( 4 => array( 'label' => sprintf( '&mdash; %s &mdash;', esc_html__( 'Handwriting Fonts (Google)', 'skinny' ) ) ) );
		$handwriting_fonts   = wp_list_filter( $google_fonts, array( 'category' => 'handwriting' ) );

		$monospace_heading = array( 4 => array( 'label' => sprintf( '&mdash; %s &mdash;', esc_html__( 'Monospace Fonts (Google)', 'skinny' ) ) ) );
		$monospace_fonts   = wp_list_filter( $google_fonts, array( 'category' => 'monospace' ) );

		return apply_filters(
			'skinny_all_fonts',
			array_merge(
				$default,
				$heading1,
				$standard_fonts,
				$serif_heading,
				$serif_fonts,
				$sans_serif_heading,
				$sans_serif_fonts,
				$display_heading,
				$display_fonts,
				$handwriting_heading,
				$handwriting_fonts,
				$monospace_heading,
				$monospace_fonts
			)
		);
	}
	/**
	 * Return an array of standard websafe fonts.
	 *
	 * @since  1.0.0.
	 *
	 * @return array    Standard websafe fonts.
	 */
	public static function get_standard_fonts() {
		return array(
			'serif'      => array(
				'label' => esc_html_x( 'Serif', 'font style', 'skinny' ),
				'stack' => 'Georgia,Times,"Times New Roman",serif',
			),
			'sans-serif' => array(
				'label' => esc_html_x( 'Sans Serif', 'font style', 'skinny' ),
				'stack' => '"Helvetica Neue",Helvetica,Arial,sans-serif',
			),
			'monospace'  => array(
				'label' => esc_html_x( 'Monospaced', 'font style', 'skinny' ),
				'stack' => 'Monaco,"Lucida Sans Typewriter	","Lucida Typewriter","Courier New",Courier,monospace',
			),
		);
	}
	/**
	 * Return default font set.
	 *
	 * @since  1.0.0.
	 *
	 * @return array    Default font set.
	 */
	public static function get_default_fonts() {
		return array(
			'inherit' => array(
				'label'    => esc_html_x( 'Default', 'font style', 'skinny' ),
				'variants' => array(),
			),
		);
	}
	/**
	 * Build a font weights string for Google Fonts.
	 *
	 * @since  1.0.0.
	 *
	 * @return array    The font weights for including Google Fonts.
	 */
	public static function get_google_font_weights() {
		return array(
			'inherit' => __( 'Default', 'skinny' ),
			100       => __( 'Thin 100', 'skinny' ),
			200       => __( 'Extra-Light 200', 'skinny' ),
			300       => __( 'Light 300', 'skinny' ),
			400       => __( 'Regular 400', 'skinny' ),
			500       => __( 'Medium 500', 'skinny' ),
			600       => __( 'Semi-Bold 600', 'skinny' ),
			700       => __( 'Bold 700', 'skinny' ),
			800       => __( 'Extra-Bold 800', 'skinny' ),
			900       => __( 'Ultra-Bold 900', 'skinny' ),
		);
	}

	/**
	 * Build a font text transform array for Fonts.
	 *
	 * @since  1.0.0.
	 *
	 * @return string[]    The font text transform for including Fonts.
	 */
	public static function get_font_text_transform() {
		return array(
			'inherit'    => 'Default',
			'capitalize' => 'Capitalize',
			'uppercase'  => 'Uppercase',
			'lowercase'  => 'Lowercase',
			'none'       => 'None',
		);
	}

	/**
	 * Return an array of fonts with only font and font variants.
	 *
	 * @since  1.0.0.
	 *
	 * @return array    Standard websafe fonts.
	 */
	public static function customizer_fonts_data_filtered() {
		$fonts          = Google_Fonts::get_google_fonts();
		$fonts_filtered = array();
		foreach ( $fonts as $index => $value ) {
			$fonts_filtered[ $index ] = $value['variants'];
		}
		return $fonts_filtered;
	}
}
