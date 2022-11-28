<?php
/**
 * Customizer styles.
 *
 * @package Skinny\Customizer
 */

namespace Skinny\Customizer;

use Skinny\Core\CSS;
use Skinny\Fonts\Fonts;

/**
 * Outputs dynamic styles for customizer options.
 *
 * @return void
 */
function dynamic_styles() {
	$css = new CSS();

	$comment_author_attr = esc_attr__( 'Author', 'skinny' );

	$css->add(
		array(
			'selectors'    => array(
				'.comments-area ol.comment-list .comment.bypostauthor .comment-author::before',
				'.comments-area ol.children .comment.bypostauthor .comment-author::before',
			),
			'declarations' => array(
				'content' => $comment_author_attr,
			),
		)
	);

	/* Content width */
	$pages_container_width = skinny_get_thememod( 'pages_container_width' );
	$posts_container_width = skinny_get_thememod( 'posts_container_width' );

	if ( '' !== $pages_container_width && is_page() ) {
		$css->add(
			array(
				'selectors'    => array(
					'body',
				),
				'declarations' => array(
					'--skinny--max-w-singular'         => $pages_container_width . 'px',
					'--skinny--max-w-singular-content' => $pages_container_width . 'px',
				),
			)
		);
	}

	if ( '' !== $posts_container_width && is_single() ) {
		$css->add(
			array(
				'selectors'    => array(
					'body',
				),
				'declarations' => array(
					'--skinny--max-w-singular'         => $posts_container_width . 'px',
					'--skinny--max-w-singular-content' => $posts_container_width . 'px',
				),
			)
		);
	}

	$data = array(
		$css->build(),
		/* Colors */
		get_customizer_colors_css()->build(),
		/* Typography */
		get_customizer_typography_css()->build(),
		/* Buttons */
		get_customizer_theme_btn_css()->build(),
	);

	// Revert back to string from array.
	$css_data = implode( ' ', array_unique( $data ) );

	wp_add_inline_style(
		'skinny-style',
		$css_data
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\dynamic_styles' );

/**
 * Print custom styles for block editor.
 *
 * @return void
 */
function print_block_editor_styles() {
	$admin_current_screen = get_current_screen();
	if ( ! method_exists( $admin_current_screen, 'is_block_editor' ) && ( ! is_null( $admin_current_screen ) && ! $admin_current_screen->is_block_editor() ) ) {
		return;
	}

	$css = new CSS();

	$css->add(
		array(
			'selectors'    => array(
				'body.skinny-template-full-width .edit-post-visual-editor .editor-styles-wrapper .wp-block',
				"body.skinny-template-full-width .edit-post-visual-editor .editor-styles-wrapper .wp-block[data-type='core/group']:not([data-align='full']):not([data-align='wide']):not([data-align='left']):not([data-align='right'])",
				"body.skinny-template-full-width .edit-post-visual-editor .editor-styles-wrapper .wp-block[data-type='core/cover']:not([data-align='full']):not([data-align='wide']):not([data-align='left']):not([data-align='right'])",
			),
			'declarations' => array(
				'max-width' => '100%',
			),
		)
	);

	$css->add(
		array(
			'selectors'    => array(
				"body.skinny-template-full-width .edit-post-visual-editor .editor-styles-wrapper .block-editor-block-list__layout.is-root-container > .wp-block[data-align='full']",
			),
			'declarations' => array(
				'margin-left'  => 0,
				'margin-right' => 0,
			),
		)
	);

	$css->add(
		array(
			'selectors'    => array(
				'body.skinny-template-full-width .edit-post-visual-editor .editor-styles-wrapper .block-editor-block-list__layout.is-root-container',
			),
			'declarations' => array(
				'margin-left'  => '-10px',
				'margin-right' => '-10px',
			),
		)
	);

	/* Content width */
	$pages_container_width = skinny_get_thememod( 'pages_container_width' );
	$posts_container_width = skinny_get_thememod( 'posts_container_width' );

	if ( '' !== $pages_container_width ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.post-type-page .edit-post-visual-editor .editor-styles-wrapper, .editor-styles-wrapper.block-editor-block-preview__container',
				),
				'declarations' => array(
					'--skinny--max-w-singular'         => $pages_container_width . 'px',
					'--skinny--max-w-singular-content' => $pages_container_width . 'px',
				),
			)
		);
	}

	if ( '' !== $posts_container_width ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.post-type-post .edit-post-visual-editor .editor-styles-wrapper, .editor-styles-wrapper.block-editor-block-preview__container',
				),
				'declarations' => array(
					'--skinny--max-w-singular'         => $posts_container_width . 'px',
					'--skinny--max-w-singular-content' => $posts_container_width . 'px',
				),
			)
		);
	}

	$data = array(
		$css->build(),
		/* Colors */
		get_customizer_colors_editor_css()->build(),
		/* Typography */
		get_customizer_typography_css()->build(),
		/* Buttons */
		get_customizer_theme_btn_css()->build(),
	);

	// Revert back to string from array.
	$css_data = implode( ' ', array_unique( $data ) );

	wp_add_inline_style( 'wp-block-editor', $css_data );
}
add_action( 'admin_print_styles', __NAMESPACE__ . '\print_block_editor_styles', 15 );

/**
 * Adds inline styles to TinyMCE.
 *
 * @return string
 */
function mce_css() {
	$data = array(
		/* Colors */
		get_customizer_colors_editor_css()->build(),
		/* Typography */
		get_customizer_typography_css()->build(),
		/* Buttons */
		get_customizer_theme_btn_css()->build(),
	);

	// Revert back to string from array.
	$css_data = implode( ' ', array_unique( $data ) );

	return $css_data;
}

/**
 * Add the theme CSS rules to the content editor.
 *
 * @since 1.0.0.
 *
 * @param  string $stylesheets The comma-separated string of stylesheet URLs.
 *
 * @return string               The modified string of stylesheet URLs.
 */
function mce_css_rules( $stylesheets ) {
	$stylesheets .= ',' . add_query_arg( 'action', 'skinny-css-rules', admin_url( 'admin-ajax.php' ) );
	return $stylesheets;
}

add_filter( 'mce_css', __NAMESPACE__ . '\mce_css_rules', 99 );

/**
 * Generates the theme CSS as an Ajax response.
 *
 * @since  1.0.0.
 *
 * @return void
 */
function ajax_css_rules() {
	// Make sure this is an Ajax request.
	if ( ! defined( 'DOING_AJAX' ) || true !== DOING_AJAX ) {
		return;
	}

	/**
	 * Filter whether the dynamic stylesheet will send headers telling the browser
	 * to cache the request. Set to false to turn off these headers.
	 *
	 * @since 1.0.0.
	 *
	 * @param bool    $cache_headers
	 */
	if ( true === apply_filters( 'skinny_stylesheet_cache_headers', true ) ) {
		/**
		 * Set headers for caching.
		 *
		 * @link http://stackoverflow.com/a/15000868
		 * @link http://www.mobify.com/blog/beginners-guide-to-http-cache-headers/
		 */
		$expires = HOUR_IN_SECONDS;
		header( 'Pragma: public' );
		header( 'Cache-Control: private, max-age=' . $expires );
		header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + $expires ) . ' GMT' );
	}

	// Set header for content type.
	header( 'Content-type: text/css' );

	// Echo the rules.
	echo wp_kses_post( mce_css() );

	// End the Ajax response.
	die();
}

add_action( 'wp_ajax_skinny-css-rules', __NAMESPACE__ . '\ajax_css_rules' );
add_action( 'wp_ajax_nopriv_skinny-css-rules', __NAMESPACE__ . '\ajax_css_rules' );


/**
 * Get customizer Theme Button values in CSS.
 *
 * @return string
 */
function get_customizer_theme_btn_css() {
	$css = new CSS();

	/*
	 * Buttons
	 */
	// Text transform.
	$btn_text_transform = skinny_get_thememod( 'font_btn_text_transform' );
	if ( $btn_text_transform ) {
		$css->add(
			array(
				'selectors'    => array(
					'body',
					'.edit-post-visual-editor .editor-styles-wrapper',
					'.editor-styles-wrapper.block-editor-block-preview__container',
				),
				'declarations' => array(
					'--skinny--btn-text-transform' => $btn_text_transform,
				),
			)
		);
	}

	// Font weight.
	$btn_font_weight = skinny_get_thememod( 'font_btn_weight' );
	if ( $btn_font_weight ) {
		$css->add(
			array(
				'selectors'    => array(
					'body',
					'.edit-post-visual-editor .editor-styles-wrapper',
					'.editor-styles-wrapper.block-editor-block-preview__container',
				),
				'declarations' => array(
					'--skinny--btn-font-weight' => $btn_font_weight,
				),
			)
		);
	}

	// Border radius.
	$btn_border_radius = skinny_get_thememod( 'btn_border_radius' );
	if ( $btn_border_radius ) {
		$css->add(
			array(
				'selectors'    => array(
					'body',
					'.edit-post-visual-editor .editor-styles-wrapper',
					'.editor-styles-wrapper.block-editor-block-preview__container',
				),
				'declarations' => array(
					'--skinny--btn-border-radius' => $btn_border_radius . 'px',
				),
			)
		);
	}

	return $css;
}

/**
 * Get customizer color values in CSS.
 *
 * @return string
 */
function get_customizer_colors_css() {
	$css = new CSS();

	/*
	 * Colors.
	 */
	// Dark scheme colors.
	// Background color.
	$dark_scheme_bg = skinny_get_thememod( 'dark_scheme_body_bg_color' );
	if ( $dark_scheme_bg ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.dark-color-scheme',
				),
				'declarations' => array(
					'--skinny--color-scheme-bg' => $dark_scheme_bg,
				),
			)
		);
	}
	// Text color.
	$dark_scheme_text = skinny_get_thememod( 'dark_scheme_text_color' );
	if ( $dark_scheme_text ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.dark-color-scheme',
				),
				'declarations' => array(
					'--skinny--color-scheme-text' => $dark_scheme_text,
				),
			)
		);
	}
	// Accent color.
	$dark_scheme_accent = skinny_get_thememod( 'dark_scheme_accent_color' );
	if ( $dark_scheme_accent ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.dark-color-scheme',
				),
				'declarations' => array(
					'--skinny--color-primary' => $dark_scheme_accent,
				),
			)
		);
	}

	// Light scheme colors.
	// Background color.
	$light_scheme_bg = skinny_get_thememod( 'light_scheme_body_bg_color' );
	if ( $light_scheme_bg ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.light-color-scheme',
				),
				'declarations' => array(
					'--skinny--color-scheme-bg' => $light_scheme_bg,
				),
			)
		);
	}
	// Text color.
	$light_scheme_text = skinny_get_thememod( 'light_scheme_text_color' );
	if ( $light_scheme_text ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.light-color-scheme',
				),
				'declarations' => array(
					'--skinny--color-scheme-text' => $light_scheme_text,
				),
			)
		);
	}
	// Accent color.
	$light_scheme_accent = skinny_get_thememod( 'light_scheme_accent_color' );
	if ( $light_scheme_accent ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.light-color-scheme',
				),
				'declarations' => array(
					'--skinny--color-primary' => $light_scheme_accent,
				),
			)
		);
	}

	/**
	 * Button colors.
	 */
	// Normal colors.
	// Dark Scheme.
	$dark_scheme_btn_bg_color = skinny_get_thememod( 'dark_scheme_btn_bg_color' );
	if ( $dark_scheme_btn_bg_color ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.dark-color-scheme',
				),
				'declarations' => array(
					'--skinny--btn-color-bg' => $dark_scheme_btn_bg_color,
				),
			)
		);
	}

	$dark_scheme_btn_text_color = skinny_get_thememod( 'dark_scheme_btn_text_color' );
	if ( $dark_scheme_btn_text_color ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.dark-color-scheme',
				),
				'declarations' => array(
					'--skinny--btn-color-text' => $dark_scheme_btn_text_color,
				),
			)
		);
	}

	// Light Scheme.
	$light_scheme_btn_bg_color = skinny_get_thememod( 'light_scheme_btn_bg_color' );
	if ( $light_scheme_btn_bg_color ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.light-color-scheme',
				),
				'declarations' => array(
					'--skinny--btn-color-bg' => $light_scheme_btn_bg_color,
				),
			)
		);
	}

	$light_scheme_btn_text_color = skinny_get_thememod( 'light_scheme_btn_text_color' );
	if ( $light_scheme_btn_text_color ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.light-color-scheme',
				),
				'declarations' => array(
					'--skinny--btn-color-text' => $light_scheme_btn_text_color,
				),
			)
		);
	}

	// Hover colors.
	// Dark Scheme.
	$dark_scheme_btn_hover_bg_color = skinny_get_thememod( 'dark_scheme_btn_hover_bg_color' );
	if ( $dark_scheme_btn_hover_bg_color ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.dark-color-scheme',
				),
				'declarations' => array(
					'--skinny--btn-color-hover-bg' => $dark_scheme_btn_hover_bg_color,
				),
			)
		);
	}

	$dark_scheme_btn_hover_text_color = skinny_get_thememod( 'dark_scheme_btn_hover_text_color' );
	if ( $dark_scheme_btn_hover_text_color ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.dark-color-scheme',
				),
				'declarations' => array(
					'--skinny--btn-color-hover-text' => $dark_scheme_btn_hover_text_color,
				),
			)
		);
	}

	// Light Scheme.
	$light_scheme_btn_hover_bg_color = skinny_get_thememod( 'light_scheme_btn_hover_bg_color' );
	if ( $light_scheme_btn_hover_bg_color ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.light-color-scheme',
				),
				'declarations' => array(
					'--skinny--btn-color-hover-bg' => $light_scheme_btn_hover_bg_color,
				),
			)
		);
	}

	$light_scheme_btn_hover_text_color = skinny_get_thememod( 'light_scheme_btn_hover_text_color' );
	if ( $light_scheme_btn_hover_text_color ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.light-color-scheme',
				),
				'declarations' => array(
					'--skinny--btn-color-hover-text' => $light_scheme_btn_hover_text_color,
				),
			)
		);
	}

	// Custom Header.
	// Dark scheme: BG Overlay.
	$dark_scheme_ch_overlay = skinny_get_thememod( 'dark_scheme_custom_header_bg_color' );
	if ( $dark_scheme_ch_overlay ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.dark-color-scheme',
				),
				'declarations' => array(
					'--skinny--color-scheme-ch-overlay' => $dark_scheme_ch_overlay,
				),
			)
		);
	}
	// Light scheme: BG Overlay.
	$light_scheme_ch_overlay = skinny_get_thememod( 'light_scheme_custom_header_bg_color' );
	if ( $light_scheme_ch_overlay ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.light-color-scheme',
				),
				'declarations' => array(
					'--skinny--color-scheme-ch-overlay' => $light_scheme_ch_overlay,
				),
			)
		);
	}

	return $css;
}

/**
 * Get customizer color values in CSS for editors.
 *
 * @return string
 */
function get_customizer_colors_editor_css() {
	$css = new CSS();

	/*
	 * Colors.
	 */
	// Color scheme.
	$color_scheme = skinny_get_thememod( 'site_color_scheme' );

	// Dark scheme colors.
	// Background color.
	$dark_scheme_bg = skinny_get_thememod( 'dark_scheme_body_bg_color' );
	if ( 'dark' === $color_scheme && $dark_scheme_bg ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.wp-editor',
					'body .edit-post-visual-editor .editor-styles-wrapper',
					'body .editor-styles-wrapper.block-editor-block-preview__container',
					'body .block-editor-block-inspector',
				),
				'declarations' => array(
					'--skinny--color-scheme-bg' => $dark_scheme_bg,
				),
			)
		);
	}
	// Text color.
	$dark_scheme_text = skinny_get_thememod( 'dark_scheme_text_color' );
	if ( 'dark' === $color_scheme && $dark_scheme_text ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.wp-editor',
					'body .edit-post-visual-editor .editor-styles-wrapper',
					'body .editor-styles-wrapper.block-editor-block-preview__container',
					'body .block-editor-block-inspector',
				),
				'declarations' => array(
					'--skinny--color-scheme-text' => $dark_scheme_text,
				),
			)
		);
	}
	// Accent color.
	$dark_scheme_accent = skinny_get_thememod( 'dark_scheme_accent_color' );
	if ( 'dark' === $color_scheme && $dark_scheme_accent ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.wp-editor',
					'body .edit-post-visual-editor .editor-styles-wrapper',
					'body .editor-styles-wrapper.block-editor-block-preview__container',
					'body .block-editor-block-inspector',
				),
				'declarations' => array(
					'--skinny--color-primary' => $dark_scheme_accent,
				),
			)
		);
	}

	// Light scheme colors.
	// Background color.
	$light_scheme_bg = skinny_get_thememod( 'light_scheme_body_bg_color' );
	if ( 'light' === $color_scheme && $light_scheme_bg ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.wp-editor',
					'body .edit-post-visual-editor .editor-styles-wrapper',
					'body .editor-styles-wrapper.block-editor-block-preview__container',
					'body .block-editor-block-inspector',
				),
				'declarations' => array(
					'--skinny--color-scheme-bg' => $light_scheme_bg,
				),
			)
		);
	}
	// Text color.
	$light_scheme_text = skinny_get_thememod( 'light_scheme_text_color' );
	if ( 'light' === $color_scheme && $light_scheme_text ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.wp-editor',
					'body .edit-post-visual-editor .editor-styles-wrapper',
					'body .editor-styles-wrapper.block-editor-block-preview__container',
					'body .block-editor-block-inspector',
				),
				'declarations' => array(
					'--skinny--color-scheme-text' => $light_scheme_text,
				),
			)
		);
	}
	// Accent color.
	$light_scheme_accent = skinny_get_thememod( 'light_scheme_accent_color' );
	if ( 'light' === $color_scheme && $light_scheme_accent ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.wp-editor',
					'body .edit-post-visual-editor .editor-styles-wrapper',
					'body .editor-styles-wrapper.block-editor-block-preview__container',
					'body .block-editor-block-inspector',
				),
				'declarations' => array(
					'--skinny--color-primary' => $light_scheme_accent,
				),
			)
		);
	}

	/**
	 * Button colors.
	 */
	// Normal colors.
	// Dark Scheme.
	$dark_scheme_btn_bg_color = skinny_get_thememod( 'dark_scheme_btn_bg_color' );
	if ( 'dark' === $color_scheme && $dark_scheme_btn_bg_color ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.wp-editor',
					'body .edit-post-visual-editor .editor-styles-wrapper',
					'body .editor-styles-wrapper.block-editor-block-preview__container',
					'body .block-editor-block-inspector',
				),
				'declarations' => array(
					'--skinny--btn-color-bg' => $dark_scheme_btn_bg_color,
				),
			)
		);
	}

	$dark_scheme_btn_text_color = skinny_get_thememod( 'dark_scheme_btn_text_color' );
	if ( 'dark' === $color_scheme && $dark_scheme_btn_text_color ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.wp-editor',
					'body .edit-post-visual-editor .editor-styles-wrapper',
					'body .editor-styles-wrapper.block-editor-block-preview__container',
					'body .block-editor-block-inspector',
				),
				'declarations' => array(
					'--skinny--btn-color-text' => $dark_scheme_btn_text_color,
				),
			)
		);
	}

	// Light Scheme.
	$light_scheme_btn_bg_color = skinny_get_thememod( 'light_scheme_btn_bg_color' );
	if ( 'light' === $color_scheme && $light_scheme_btn_bg_color ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.wp-editor',
					'body .edit-post-visual-editor .editor-styles-wrapper',
					'body .editor-styles-wrapper.block-editor-block-preview__container',
					'body .block-editor-block-inspector',
				),
				'declarations' => array(
					'--skinny--btn-color-bg' => $light_scheme_btn_bg_color,
				),
			)
		);
	}

	$light_scheme_btn_text_color = skinny_get_thememod( 'light_scheme_btn_text_color' );
	if ( 'light' === $color_scheme && $light_scheme_btn_text_color ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.wp-editor',
					'body .edit-post-visual-editor .editor-styles-wrapper',
					'body .editor-styles-wrapper.block-editor-block-preview__container',
					'body .block-editor-block-inspector',
				),
				'declarations' => array(
					'--skinny--btn-color-text' => $light_scheme_btn_text_color,
				),
			)
		);
	}

	// Hover colors.
	// Dark Scheme.
	$dark_scheme_btn_hover_bg_color = skinny_get_thememod( 'dark_scheme_btn_hover_bg_color' );
	if ( 'dark' === $color_scheme && $dark_scheme_btn_hover_bg_color ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.wp-editor',
					'body .edit-post-visual-editor .editor-styles-wrapper',
					'body .editor-styles-wrapper.block-editor-block-preview__container',
					'body .block-editor-block-inspector',
				),
				'declarations' => array(
					'--skinny--btn-color-hover-bg' => $dark_scheme_btn_hover_bg_color,
				),
			)
		);
	}

	$dark_scheme_btn_hover_text_color = skinny_get_thememod( 'dark_scheme_btn_hover_text_color' );
	if ( 'dark' === $color_scheme && $dark_scheme_btn_hover_text_color ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.wp-editor',
					'body .edit-post-visual-editor .editor-styles-wrapper',
					'body .editor-styles-wrapper.block-editor-block-preview__container',
					'body .block-editor-block-inspector',
				),
				'declarations' => array(
					'--skinny--btn-color-hover-text' => $dark_scheme_btn_hover_text_color,
				),
			)
		);
	}

	// Light Scheme.
	$light_scheme_btn_hover_bg_color = skinny_get_thememod( 'light_scheme_btn_hover_bg_color' );
	if ( 'light' === $color_scheme && $light_scheme_btn_hover_bg_color ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.wp-editor',
					'body .edit-post-visual-editor .editor-styles-wrapper',
					'body .editor-styles-wrapper.block-editor-block-preview__container',
					'body .block-editor-block-inspector',
				),
				'declarations' => array(
					'--skinny--btn-color-hover-bg' => $light_scheme_btn_hover_bg_color,
				),
			)
		);
	}

	$light_scheme_btn_hover_text_color = skinny_get_thememod( 'light_scheme_btn_hover_text_color' );
	if ( 'light' === $color_scheme && $light_scheme_btn_hover_text_color ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.wp-editor',
					'body .edit-post-visual-editor .editor-styles-wrapper',
					'body .editor-styles-wrapper.block-editor-block-preview__container',
					'body .block-editor-block-inspector',
				),
				'declarations' => array(
					'--skinny--btn-color-hover-text' => $light_scheme_btn_hover_text_color,
				),
			)
		);
	}

	// Custom Header.
	// Dark scheme: BG Overlay.
	$dark_scheme_ch_overlay = skinny_get_thememod( 'dark_scheme_custom_header_bg_color' );
	if ( 'dark' === $color_scheme && $dark_scheme_ch_overlay ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.wp-editor',
					'body .edit-post-visual-editor .editor-styles-wrapper',
					'body .editor-styles-wrapper.block-editor-block-preview__container',
					'body .block-editor-block-inspector',
				),
				'declarations' => array(
					'--skinny--color-scheme-ch-overlay' => $dark_scheme_ch_overlay,
				),
			)
		);
	}
	// Light scheme: BG Overlay.
	$light_scheme_ch_overlay = skinny_get_thememod( 'light_scheme_custom_header_bg_color' );
	if ( 'light' === $color_scheme && $light_scheme_ch_overlay ) {
		$css->add(
			array(
				'selectors'    => array(
					'body.wp-editor',
					'body .edit-post-visual-editor .editor-styles-wrapper',
					'body .editor-styles-wrapper.block-editor-block-preview__container',
					'body .block-editor-block-inspector',
				),
				'declarations' => array(
					'--skinny--color-scheme-ch-overlay' => $light_scheme_ch_overlay,
				),
			)
		);
	}

	$css->add(
		array(
			'selectors' => array(
				'body .block-editor-block-inspector',
			),
			'declarations' => array(
				'--skinny--color-light-bg' => '#F3F3F3',
				'--skinny--color-dark-bg' => '#1D1E25',
				'--skinny--color-white' => '#FFFFFF',
			),
		)
	);

	return $css;
}

/**
 * Get customizer typography values in CSS.
 *
 * @return string
 */
function get_customizer_typography_css() {
	$css = new CSS();

	/* Typography */
	$font_body     = Fonts::get_font_stack( skinny_get_thememod( 'font_body' ) );
	$font_headings = Fonts::get_font_stack( skinny_get_thememod( 'font_headings' ) );

	if ( '' !== $font_body ) {
		$css->add(
			array(
				'selectors'    => array(
					'body',
					'.edit-post-visual-editor .editor-styles-wrapper',
					'.editor-styles-wrapper.block-editor-block-preview__container',
				),
				'declarations' => array(
					'--skinny--type-body' => $font_body,
				),
			)
		);
	}

	if ( '' !== $font_headings ) {
		$css->add(
			array(
				'selectors'    => array(
					'body',
					'.edit-post-visual-editor .editor-styles-wrapper',
					'.editor-styles-wrapper.block-editor-block-preview__container',
				),
				'declarations' => array(
					'--skinny--type-heading' => $font_headings,
				),
			)
		);
	}

	// Font body size.
	$font_base_size = skinny_get_thememod( 'font_base_size' );

	$font_base_size_desktop = skinny_responsive_font( $font_base_size, 'desktop' );
	$font_base_size_tablet  = skinny_responsive_font( $font_base_size, 'tablet' );
	$font_base_size_mobile  = skinny_responsive_font( $font_base_size, 'mobile' );

	// Relative font sizes
	$percent = apply_filters(
		'skinny_font_relative_size',
		array(
			// Relative to base font size.
			'xxl'          => 164,
			'xl'           => 140,
			'body'         => 113,
			'body-smaller' => 100,
			'caption'      => 90,
			'label'        => 90,
			'h1'           => 469,
			'm-h1'         => 300,
			'h2'           => 338,
			'm-h2'         => 270,
			'h3'           => 158,
			'h4'           => 150,
			'h5'           => 90,
			'site-title'   => 220,
		)
	);

	// Desktop sizes.
	$size_text_body_xxl     = skinny_get_relative_font_size( $font_base_size_desktop, $percent['xxl'] );
	$size_text_body_xl      = skinny_get_relative_font_size( $font_base_size_desktop, $percent['xl'] );
	$size_text_body         = skinny_get_relative_font_size( $font_base_size_desktop, $percent['body'] );
	$size_text_body_smaller = skinny_get_relative_font_size( $font_base_size_desktop, $percent['body-smaller'] );
	$size_text_body_caption = skinny_get_relative_font_size( $font_base_size_desktop, $percent['caption'] );
	$size_text_body_label   = skinny_get_relative_font_size( $font_base_size_desktop, $percent['label'] );

	$size_text_heading_h1 = skinny_get_relative_font_size( $font_base_size_desktop, $percent['h1'] );
	$size_text_heading_h2 = skinny_get_relative_font_size( $font_base_size_desktop, $percent['h2'] );
	$size_text_heading_h3 = skinny_get_relative_font_size( $font_base_size_desktop, $percent['h3'] );
	$size_text_heading_h4 = skinny_get_relative_font_size( $font_base_size_desktop, $percent['h4'] );
	$size_text_heading_h5 = skinny_get_relative_font_size( $font_base_size_desktop, $percent['h5'] );
	$size_text_site_title = skinny_get_relative_font_size( $font_base_size_desktop, $percent['site-title'] );

	if ( '' !== $font_base_size_desktop ) {
		$css->add(
			array(
				'selectors'    => array(
					'body',
					'.edit-post-visual-editor .editor-styles-wrapper',
					'.editor-styles-wrapper.block-editor-block-preview__container',
				),
				'declarations' => array(
					'--skinny--type-text-xxl'     => $size_text_body_xxl,
					'--skinny--type-text-xl'      => $size_text_body_xl,
					'--skinny--type-text-body'    => $size_text_body,
					'--skinny--type-text-smaller' => $size_text_body_smaller,
					'--skinny--type-text-caption' => $size_text_body_caption,
					'--skinny--type-text-label'   => $size_text_body_label,
				),
			)
		);
	}

	if ( '' !== $font_base_size_desktop ) {
		$css->add(
			array(
				'selectors'    => array(
					'body',
					'.edit-post-visual-editor .editor-styles-wrapper',
					'.editor-styles-wrapper.block-editor-block-preview__container',
				),
				'declarations' => array(
					'--skinny--type-heading-xxl'    => $size_text_heading_h1,
					'--skinny--type-heading-xl'     => $size_text_heading_h2,
					'--skinny--type-heading-large'  => $size_text_heading_h3,
					'--skinny--type-heading-medium' => $size_text_heading_h4,
					'--skinny--type-heading-small'  => $size_text_heading_h5,
				),
			)
		);

		$css->add(
			array(
				'selectors'    => array(
					'.header__title .site-title',
				),
				'declarations' => array(
					'font-size' => $size_text_site_title,
				),
			)
		);
	}

	// Tablet sizes.
	$size_text_body_xxl     = skinny_get_relative_font_size( $font_base_size_tablet, $percent['xxl'] );
	$size_text_body_xl      = skinny_get_relative_font_size( $font_base_size_tablet, $percent['xl'] );
	$size_text_body         = skinny_get_relative_font_size( $font_base_size_tablet, $percent['body'] );
	$size_text_body_smaller = skinny_get_relative_font_size( $font_base_size_tablet, $percent['body-smaller'] );
	$size_text_body_caption = skinny_get_relative_font_size( $font_base_size_tablet, $percent['caption'] );
	$size_text_body_label   = skinny_get_relative_font_size( $font_base_size_tablet, $percent['label'] );

	$size_text_heading_h1 = skinny_get_relative_font_size( $font_base_size_tablet, $percent['h1'] );
	$size_text_heading_h2 = skinny_get_relative_font_size( $font_base_size_tablet, $percent['h2'] );
	$size_text_heading_h3 = skinny_get_relative_font_size( $font_base_size_tablet, $percent['h3'] );
	$size_text_heading_h4 = skinny_get_relative_font_size( $font_base_size_tablet, $percent['h4'] );
	$size_text_heading_h5 = skinny_get_relative_font_size( $font_base_size_tablet, $percent['h5'] );
	$size_text_site_title = skinny_get_relative_font_size( $font_base_size_tablet, $percent['site-title'] );

	if ( '' !== $font_base_size_tablet ) {
		$css->add(
			array(
				'selectors'    => array(
					'body',
					'.edit-post-visual-editor .editor-styles-wrapper',
					'.editor-styles-wrapper.block-editor-block-preview__container',
				),
				'declarations' => array(
					'--skinny--type-text-xxl'     => $size_text_body_xxl,
					'--skinny--type-text-xl'      => $size_text_body_xl,
					'--skinny--type-text-body'    => $size_text_body,
					'--skinny--type-text-smaller' => $size_text_body_smaller,
					'--skinny--type-text-caption' => $size_text_body_caption,
					'--skinny--type-text-label'   => $size_text_body_label,
				),
				'media'        => 'screen and (max-width: 959px)',
			)
		);
	}

	if ( '' !== $font_base_size_tablet ) {
		$css->add(
			array(
				'selectors'    => array(
					'body',
					'.edit-post-visual-editor .editor-styles-wrapper',
					'.editor-styles-wrapper.block-editor-block-preview__container',
				),
				'declarations' => array(
					'--skinny--type-heading-xxl'    => $size_text_heading_h1,
					'--skinny--type-heading-xl'     => $size_text_heading_h2,
					'--skinny--type-heading-large'  => $size_text_heading_h3,
					'--skinny--type-heading-medium' => $size_text_heading_h4,
					'--skinny--type-heading-small'  => $size_text_heading_h5,
				),
				'media'        => 'screen and (max-width: 959px)',
			)
		);

		$css->add(
			array(
				'selectors'    => array(
					'.header__title .site-title',
				),
				'declarations' => array(
					'font-size' => $size_text_site_title,
				),
				'media'        => 'screen and (max-width: 959px)',
			)
		);
	}

	// Mobile sizes.
	$size_text_body_xxl     = skinny_get_relative_font_size( $font_base_size_mobile, $percent['xxl'] );
	$size_text_body_xl      = skinny_get_relative_font_size( $font_base_size_mobile, $percent['xl'] );
	$size_text_body         = skinny_get_relative_font_size( $font_base_size_mobile, $percent['body'] );
	$size_text_body_smaller = skinny_get_relative_font_size( $font_base_size_mobile, $percent['body-smaller'] );
	$size_text_body_caption = skinny_get_relative_font_size( $font_base_size_mobile, $percent['caption'] );
	$size_text_body_label   = skinny_get_relative_font_size( $font_base_size_mobile, $percent['label'] );

	$size_text_heading_h1 = skinny_get_relative_font_size( $font_base_size_mobile, $percent['m-h1'] );
	$size_text_heading_h2 = skinny_get_relative_font_size( $font_base_size_mobile, $percent['m-h2'] );
	$size_text_heading_h3 = skinny_get_relative_font_size( $font_base_size_mobile, $percent['h3'] );
	$size_text_heading_h4 = skinny_get_relative_font_size( $font_base_size_mobile, $percent['h4'] );
	$size_text_heading_h5 = skinny_get_relative_font_size( $font_base_size_mobile, $percent['h5'] );
	$size_text_site_title = skinny_get_relative_font_size( $font_base_size_mobile, $percent['site-title'] );

	if ( '' !== $font_base_size_mobile ) {
		$css->add(
			array(
				'selectors'    => array(
					'body',
					'.edit-post-visual-editor .editor-styles-wrapper',
					'.editor-styles-wrapper.block-editor-block-preview__container',
				),
				'declarations' => array(
					'--skinny--type-text-xxl'     => $size_text_body_xxl,
					'--skinny--type-text-xl'      => $size_text_body_xl,
					'--skinny--type-text-body'    => $size_text_body,
					'--skinny--type-text-smaller' => $size_text_body_smaller,
					'--skinny--type-text-caption' => $size_text_body_caption,
					'--skinny--type-text-label'   => $size_text_body_label,
				),
				'media'        => 'screen and (max-width: 599px)',
			)
		);
	}

	if ( '' !== $font_base_size_mobile ) {
		$css->add(
			array(
				'selectors'    => array(
					'body',
					'.edit-post-visual-editor .editor-styles-wrapper',
					'.editor-styles-wrapper.block-editor-block-preview__container',
				),
				'declarations' => array(
					'--skinny--type-heading-xxl'    => $size_text_heading_h1,
					'--skinny--type-heading-xl'     => $size_text_heading_h2,
					'--skinny--type-heading-large'  => $size_text_heading_h3,
					'--skinny--type-heading-medium' => $size_text_heading_h4,
					'--skinny--type-heading-small'  => $size_text_heading_h5,
				),
				'media'        => 'screen and (max-width: 599px)',
			)
		);

		$css->add(
			array(
				'selectors'    => array(
					'.header__title .site-title',
				),
				'declarations' => array(
					'font-size' => $size_text_site_title,
				),
				'media'        => 'screen and (max-width: 599px)',
			)
		);
	}

	// Line Heights.
	$font_base_line_height = skinny_get_thememod( 'font_base_line_height' );

	$font_base_line_height_desktop = skinny_responsive_font( $font_base_line_height, 'desktop' );
	$font_base_line_height_tablet  = skinny_responsive_font( $font_base_line_height, 'tablet' );
	$font_base_line_height_mobile  = skinny_responsive_font( $font_base_line_height, 'mobile' );

	// Relative line heights.
	$percent = apply_filters(
		'skinny_font_relative_line_height',
		array(
			// Relative to base line height.
			'xxl'          => 94.6,
			'xl'           => 94.6,
			'body'         => 100,
			'body-smaller' => 94.6,
			'caption'      => 78,
			'label'        => 78,
			'h1'           => 61.2,
			'h2'           => 76.8,
			'h3'           => 75.6,
			'h4'           => 65,
			'h5'           => 72.3,
		)
	);

	// Desktop sizes.
	$line_height_text_body_xxl     = skinny_get_relative_font_size( $font_base_line_height_desktop, $percent['xxl'], '%' );
	$line_height_text_body_xl      = skinny_get_relative_font_size( $font_base_line_height_desktop, $percent['xl'], '%' );
	$line_height_text_body         = skinny_get_relative_font_size( $font_base_line_height_desktop, $percent['body'], '%' );
	$line_height_text_body_smaller = skinny_get_relative_font_size( $font_base_line_height_desktop, $percent['body-smaller'], '%' );
	$line_height_text_body_caption = skinny_get_relative_font_size( $font_base_line_height_desktop, $percent['caption'], '%' );
	$line_height_text_body_label   = skinny_get_relative_font_size( $font_base_line_height_desktop, $percent['label'], '%' );

	$line_height_text_heading_h1 = skinny_get_relative_font_size( $font_base_line_height_desktop, $percent['h1'], '%' );
	$line_height_text_heading_h2 = skinny_get_relative_font_size( $font_base_line_height_desktop, $percent['h2'], '%' );
	$line_height_text_heading_h3 = skinny_get_relative_font_size( $font_base_line_height_desktop, $percent['h3'], '%' );
	$line_height_text_heading_h4 = skinny_get_relative_font_size( $font_base_line_height_desktop, $percent['h4'], '%' );
	$line_height_text_heading_h5 = skinny_get_relative_font_size( $font_base_line_height_desktop, $percent['h5'], '%' );

	if ( '' !== $font_base_line_height_desktop ) {
		$css->add(
			array(
				'selectors'    => array(
					'body',
					'.edit-post-visual-editor .editor-styles-wrapper',
					'.editor-styles-wrapper.block-editor-block-preview__container',
				),
				'declarations' => array(
					'--skinny--type-text-xxl-height'     => $line_height_text_body_xxl,
					'--skinny--type-text-xl-height'      => $line_height_text_body_xl,
					'--skinny--type-text-body-height'    => $line_height_text_body,
					'--skinny--type-text-smaller-height' => $line_height_text_body_smaller,
					'--skinny--type-text-caption-height' => $line_height_text_body_caption,
					'--skinny--type-text-label-height'   => $line_height_text_body_label,
				),
			)
		);
	}

	if ( '' !== $font_base_line_height_desktop ) {
		$css->add(
			array(
				'selectors'    => array(
					'body',
					'.edit-post-visual-editor .editor-styles-wrapper',
					'.editor-styles-wrapper.block-editor-block-preview__container',
				),
				'declarations' => array(
					'--skinny--type-heading-xxl-height'    => $line_height_text_heading_h1,
					'--skinny--type-heading-xl-height'     => $line_height_text_heading_h2,
					'--skinny--type-heading-large-height'  => $line_height_text_heading_h3,
					'--skinny--type-heading-medium-height' => $line_height_text_heading_h4,
					'--skinny--type-heading-small-height'  => $line_height_text_heading_h5,
				),
			)
		);
	}

	// Tablet sizes.
	$line_height_text_body_xxl     = skinny_get_relative_font_size( $font_base_line_height_tablet, $percent['xxl'], '%' );
	$line_height_text_body_xl      = skinny_get_relative_font_size( $font_base_line_height_tablet, $percent['xl'], '%' );
	$line_height_text_body         = skinny_get_relative_font_size( $font_base_line_height_tablet, $percent['body'], '%' );
	$line_height_text_body_smaller = skinny_get_relative_font_size( $font_base_line_height_tablet, $percent['body-smaller'], '%' );
	$line_height_text_body_caption = skinny_get_relative_font_size( $font_base_line_height_tablet, $percent['caption'], '%' );
	$line_height_text_body_label   = skinny_get_relative_font_size( $font_base_line_height_tablet, $percent['label'], '%' );

	$line_height_text_heading_h1 = skinny_get_relative_font_size( $font_base_line_height_tablet, $percent['h1'], '%' );
	$line_height_text_heading_h2 = skinny_get_relative_font_size( $font_base_line_height_tablet, $percent['h2'], '%' );
	$line_height_text_heading_h3 = skinny_get_relative_font_size( $font_base_line_height_tablet, $percent['h3'], '%' );
	$line_height_text_heading_h4 = skinny_get_relative_font_size( $font_base_line_height_tablet, $percent['h4'], '%' );
	$line_height_text_heading_h5 = skinny_get_relative_font_size( $font_base_line_height_tablet, $percent['h5'], '%' );

	if ( '' !== $font_base_size_tablet ) {
		$css->add(
			array(
				'selectors'    => array(
					'body',
					'.edit-post-visual-editor .editor-styles-wrapper',
					'.editor-styles-wrapper.block-editor-block-preview__container',
				),
				'declarations' => array(
					'--skinny--type-text-xxl-height'     => $line_height_text_body_xxl,
					'--skinny--type-text-xl-height'      => $line_height_text_body_xl,
					'--skinny--type-text-body-height'    => $line_height_text_body,
					'--skinny--type-text-smaller-height' => $line_height_text_body_smaller,
					'--skinny--type-text-caption-height' => $line_height_text_body_caption,
					'--skinny--type-text-label-height'   => $line_height_text_body_label,
				),
				'media'        => 'screen and (max-width: 959px)',
			)
		);
	}

	if ( '' !== $font_base_size_tablet ) {
		$css->add(
			array(
				'selectors'    => array(
					'body',
					'.edit-post-visual-editor .editor-styles-wrapper',
					'.editor-styles-wrapper.block-editor-block-preview__container',
				),
				'declarations' => array(
					'--skinny--type-heading-xxl-height'    => $line_height_text_heading_h1,
					'--skinny--type-heading-xl-height'     => $line_height_text_heading_h2,
					'--skinny--type-heading-large-height'  => $line_height_text_heading_h3,
					'--skinny--type-heading-medium-height' => $line_height_text_heading_h4,
					'--skinny--type-heading-small-height'  => $line_height_text_heading_h5,
				),
				'media'        => 'screen and (max-width: 959px)',
			)
		);
	}

	// Mobile sizes.
	$line_height_text_body_xxl     = skinny_get_relative_font_size( $font_base_line_height_mobile, $percent['xxl'], '%' );
	$line_height_text_body_xl      = skinny_get_relative_font_size( $font_base_line_height_mobile, $percent['xl'], '%' );
	$line_height_text_body         = skinny_get_relative_font_size( $font_base_line_height_mobile, $percent['body'], '%' );
	$line_height_text_body_smaller = skinny_get_relative_font_size( $font_base_line_height_mobile, $percent['body-smaller'], '%' );
	$line_height_text_body_caption = skinny_get_relative_font_size( $font_base_line_height_mobile, $percent['caption'], '%' );
	$line_height_text_body_label   = skinny_get_relative_font_size( $font_base_line_height_mobile, $percent['label'], '%' );

	$line_height_text_heading_h1 = skinny_get_relative_font_size( $font_base_line_height_mobile, $percent['h1'], '%' );
	$line_height_text_heading_h2 = skinny_get_relative_font_size( $font_base_line_height_mobile, $percent['h2'], '%' );
	$line_height_text_heading_h3 = skinny_get_relative_font_size( $font_base_line_height_mobile, $percent['h3'], '%' );
	$line_height_text_heading_h4 = skinny_get_relative_font_size( $font_base_line_height_mobile, $percent['h4'], '%' );
	$line_height_text_heading_h5 = skinny_get_relative_font_size( $font_base_line_height_mobile, $percent['h5'], '%' );

	if ( '' !== $font_base_line_height_mobile ) {
		$css->add(
			array(
				'selectors'    => array(
					'body',
					'.edit-post-visual-editor .editor-styles-wrapper',
					'.editor-styles-wrapper.block-editor-block-preview__container',
				),
				'declarations' => array(
					'--skinny--type-text-xxl-height'     => $line_height_text_body_xxl,
					'--skinny--type-text-xl-height'      => $line_height_text_body_xl,
					'--skinny--type-text-body-height'    => $line_height_text_body,
					'--skinny--type-text-smaller-height' => $line_height_text_body_smaller,
					'--skinny--type-text-caption-height' => $line_height_text_body_caption,
					'--skinny--type-text-label-height'   => $line_height_text_body_label,
				),
				'media'        => 'screen and (max-width: 599px)',
			)
		);
	}

	if ( '' !== $font_base_line_height_mobile ) {
		$css->add(
			array(
				'selectors'    => array(
					'body',
					'.edit-post-visual-editor .editor-styles-wrapper',
					'.editor-styles-wrapper.block-editor-block-preview__container',
				),
				'declarations' => array(
					'--skinny--type-heading-xxl-height'    => $line_height_text_heading_h1,
					'--skinny--type-heading-xl-height'     => $line_height_text_heading_h2,
					'--skinny--type-heading-large-height'  => $line_height_text_heading_h3,
					'--skinny--type-heading-medium-height' => $line_height_text_heading_h4,
					'--skinny--type-heading-small-height'  => $line_height_text_heading_h5,
				),
				'media'        => 'screen and (max-width: 599px)',
			)
		);
	}

	return $css;
}
