<?php
/**
 * Skinny Theme Options.
 *
 * @package     Skinny
 */
namespace Skinny\Customizer;

/**
 * Theme Options.
 */
class Theme_Options {
	/**
	 * Set theme option settings.
	 *
	 * @return array Settings of theme customizer options.
	 */
	public static function settings() {
		// Theme settings.
		$theme_settings = array(

			/**
			 * Colors
			 */
			// Color scheme.
			'site_color_scheme'                   => 'dark',
			// Dark scheme colors.
			'dark_scheme_body_bg_color'           => '#1d1e25',
			'dark_scheme_text_color'              => '#ffffff',
			'dark_scheme_accent_color'            => '#fdbf70',

			'dark_scheme_btn_bg_color'            => '#fdbf70',
			'dark_scheme_btn_text_color'          => '',
			'dark_scheme_btn_hover_bg_color'      => '#3739b2',
			'dark_scheme_btn_hover_text_color'    => '#ffffff',

			// Light scheme colors.
			'light_scheme_body_bg_color'          => 'rgba(255,255,255,1)',
			'light_scheme_text_color'             => '#1d1e25',
			'light_scheme_accent_color'           => '#d15c5c',

			'light_scheme_btn_bg_color'           => '#fdbf70',
			'light_scheme_btn_text_color'         => '',
			'light_scheme_btn_hover_bg_color'     => '',
			'light_scheme_btn_hover_text_color'   => '',

			// Custom header bg overlay color.
			'dark_scheme_custom_header_bg_color'  => 'rgba(29,30,37,0.8)',
			'light_scheme_custom_header_bg_color' => 'rgba(0,0,0,0.3)',

			/**
			 * Typography
			 */
			// General.
			// Font Body.
			'font_body'                           => 'DM Sans',
			'font_body_load_variant'              => implode( ',', array( '700', 'regular' ) ),
			'font_base_size'                      => array(
				'desktop'      => 16,
				'mobile'       => 15,
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
			),
			'font_base_line_height'               => array(
				'desktop'      => 180,
				'desktop-unit' => '%',
				'tablet-unit'  => '%',
				'mobile-unit'  => '%',
			),

			// Headings.
			'font_headings'                       => 'DM Serif Display',
			'font_headings_load_variant'          => implode( ',', array( '700', 'regular' ) ),

			// Font subset.
			'font_subset'                         => '17',

			/**
			 * Button
			 */
			'font_btn_text_transform'             => 'uppercase',
			'font_btn_weight'                     => '700',
			'btn_border_radius'                   => 0,

			/**
			 * Layout
			 */
			'pages_container_width'               => 1140,
			'posts_container_width'               => 800,

			/**
			 * Header
			 */
			'header_layout_variations'            => 'default',
			'site_logo_retina'                    => false,
			'search_btn_toggle'                   => true,
			'cart_widget_toggle'                  => true,
			'account_btn'                         => false,
			'color_scheme_toggle'                 => true,

			/**
			 * Footer
			 */
			'footer_cta_block'                    => 'select',
			'footer_cta_homepage_toggle'          => true,
			'footer_cta_single_posts_toggle'      => false,
			'footer_cta_blog_archives_toggle'     => false,
			'footer_cta_single_products_toggle'   => true,
			'footer_cta_shop_archives_toggle'     => false,
			'footer_widget_cols'                  => 4,
			'footer_text'                         => sprintf(
			/* translators: Footer Remarks*/
				esc_html__( 'Copyright &copy;%s', 'skinny' ),
				gmdate( 'Y' )
			),

			/**
			 * Site controls
			 */
			'blog_header_background'              => get_parent_theme_file_uri( '/assets/images/default-blog-bg.jpg' ),
			'shop_header_background'              => get_parent_theme_file_uri( '/assets/images/default-shop-bg.jpg' ),
		);

		return apply_filters( 'skinny_theme_settings', $theme_settings );
	}
}
