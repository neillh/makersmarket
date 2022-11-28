<?php
/**
 * Core setup, site hooks and filters.
 *
 * @package Skinny\Core
 */

namespace Skinny\Core;

use Skinny\Fonts\Fonts;

/**
 * Set up theme defaults and register supported WordPress features.
 *
 * @return void
 */
function setup() {
	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'after_setup_theme', $n( 'development_environment' ) );
	add_action( 'after_setup_theme', $n( 'i18n' ) );
	add_action( 'after_setup_theme', $n( 'theme_setup' ) );
	add_action( 'widgets_init', $n( 'widgets_init' ) );

	// Add resource hinting for fonts enqueue.
	add_filter( 'wp_resource_hints', $n( 'resource_hints' ), 10, 2 );

	add_action( 'admin_init', $n( 'editor_styles' ) );
	add_action( 'wp_enqueue_scripts', $n( 'styles' ) );
	add_action( 'wp_enqueue_scripts', $n( 'scripts' ) );
	add_action( 'wp_print_footer_scripts', $n( 'skip_link_focus_fix' ) );
	add_filter( 'body_class', $n( 'body_classes' ) );
	add_filter( 'admin_body_class', $n( 'admin_body_classes' ) );
	add_filter( 'excerpt_more', $n( 'excerpt_more' ) );
	add_action( 'add_meta_boxes', $n( 'register_page_meta' ) );
	add_action( 'save_post', $n( 'save_page_options' ) );
}

/**
 * Check if this is an install is a local development environment
 */
function development_environment() {

	if ( is_readable( get_template_directory() . '/.dev/assets/development-environment.php' ) ) {

		require_once get_template_directory() . '/.dev/assets/development-environment.php'; // phpcs:ignore

	}

}

/**
 * Makes Theme available for translation.
 *
 * Translations can be added to the /languages directory.
 *
 * @return void
 */
function i18n() {

	load_theme_textdomain( 'skinny', get_template_directory() . '/languages' );

}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @codeCoverageIgnore
 */
function theme_setup() {
	$posts_content_width = skinny_get_thememod( 'posts_container_width' ) ? skinny_get_thememod( 'posts_container_width' ) : 800;
	// Filters the theme content width global; intended to be overruled from themes.
	// phpcs:ignore WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = (int) apply_filters( 'skinny_content_width', $posts_content_width );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// Add image sizes.
	add_image_size( 'skinny-post-thumbnail', 1366, 720 );

	// This theme uses wp_nav_menu() in up to four locations.
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary', 'skinny' ),
			'footer'  => esc_html__( 'Footer', 'skinny' ),
		)
	);

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		apply_filters(
			'skinny_custom_logo_args',
			array(
				'height'      => 190,
				'width'       => 190,
				'flex-width'  => true,
				'flex-height' => true,
			)
		)
	);

	/**
	 *  Add support for the Site Logo plugin and the site logo functionality in JetPack
	 *  https://github.com/automattic/site-logo
	 *  http://jetpack.me/
	 */
	add_theme_support(
		'site-logo',
		apply_filters(
			'skinny_site_logo_args',
			array(
				'size' => 'full',
			)
		)
	);

	// Indicate that the theme works well in both Standard and Transitional template modes of the AMP plugin.
	add_theme_support(
		'amp',
		array(
			// The `paired` flag means that the theme retains logic to be fully functional when AMP is disabled.
			'paired' => true,
		)
	);

	// Add support for WooCommerce.
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-slider' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-zoom' );

	// Add support for block styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	// Add support for editor styles.
	add_theme_support( 'editor-styles' );

	// Add support for responsive embedded content.
	add_theme_support( 'responsive-embeds' );

	// Add custom editor font sizes.
	add_theme_support(
		'editor-font-sizes',
		array(
			array(
				'name'      => esc_html_x( 'Small', 'font size option label', 'skinny' ),
				'shortName' => esc_html_x( 'S', 'abbreviation of the font size option label', 'skinny' ),
				'size'      => 16,
				'slug'      => 'small',
			),
			array(
				'name'      => esc_html_x( 'Medium', 'font size option label', 'skinny' ),
				'shortName' => esc_html_x( 'M', 'abbreviation of the font size option label', 'skinny' ),
				'size'      => 18,
				'slug'      => 'medium',
			),
			array(
				'name'      => esc_html_x( 'Large', 'font size option label', 'skinny' ),
				'shortName' => esc_html_x( 'L', 'abbreviation of the font size option label', 'skinny' ),
				'size'      => 24,
				'slug'      => 'large',
			),
			array(
				'name'      => esc_html_x( 'Huge', 'font size option label', 'skinny' ),
				'shortName' => esc_html_x( 'XL', 'abbreviation of the font size option label', 'skinny' ),
				'size'      => 40,
				'slug'      => 'huge',
			),
		)
	);

	// Add custom editor color palette.
	add_theme_support(
		'editor-color-palette',
		array(
			array(
				'name'  => esc_html_x( 'Primary', 'name of the first color palette selection', 'skinny' ),
				'slug'  => 'primary',
				'color' => 'var(--skinny--color-primary)',
			),
			array(
				'name'  => esc_html_x( 'Secondary', 'name of the second color palette selection', 'skinny' ),
				'slug'  => 'secondary',
				'color' => 'var(--skinny--color-dark-bg)',
			),
			array(
				'name'  => esc_html_x( 'Tertiary', 'name of the third color palette selection', 'skinny' ),
				'slug'  => 'tertiary',
				'color' => 'var(--skinny--color-light-bg)',
			),
			array(
				'name'  => esc_html_x( 'Background', 'name of the fourth color palette selection', 'skinny' ),
				'slug'  => 'background',
				'color' => 'var(--skinny--color-white)',
			),
		)
	);
}

/**
 * Register widget area.
 *
 * @link https://codex.wordpress.org/Function_Reference/register_sidebar
 */
function widgets_init() {

	if ( skinny_is_woocommerce_activated() ) :
		$sidebar_args['shop'] = array(
			'name'        => __( 'Shop', 'skinny' ),
			'id'          => 'shop-area',
			'description' => esc_html__( 'Add widgets here to show on shop archives.', 'skinny' ),
		);
	endif;

	$sidebar_args['footer'] = array(
		'name'        => __( 'Footer', 'skinny' ),
		'id'          => 'footer-area',
		'description' => esc_html__( 'Add widgets here to show at the footer area.', 'skinny' ),
	);

	$sidebar_args = apply_filters( 'skinny_sidebar_args', $sidebar_args );

	foreach ( $sidebar_args as $sidebar => $args ) {
		$widget_tags = array(
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		);

		/**
		 * Dynamically generated filter hooks. Allow changing widget wrapper and title tags. See the list below.
		 *
		 * 'skinny_shop_widget_tags'
		 *
		 * 'skinny_footer_widget_tags'
		 */
		$filter_hook = sprintf( 'skinny_%s_widget_tags', $sidebar );
		$widget_tags = apply_filters( $filter_hook, $widget_tags );

		if ( is_array( $widget_tags ) ) {
			register_sidebar( $args + $widget_tags );
		}
	}
}

/**
 * Enqueue scripts for front-end.
 *
 * @return void
 */
function scripts() {

	$suffix = get_script_suffix();

	wp_enqueue_script( 'skinny-custom', get_theme_file_uri( "/assets/js/custom{$suffix}.js" ), array( 'jquery' ), filemtime( get_theme_file_path( "/assets/js/custom{$suffix}.js" ) ), true );

	wp_localize_script(
		'skinny-custom',
		'skinnyCustom',
		array(
			'isCustomizePreview'   => is_customize_preview(),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}

/**
 * Enqueues the editor styles.
 *
 * @return void
 */
function editor_styles() {
	$suffix = get_script_suffix();

	// Enqueue shared editor styles.
	add_editor_style(
		"assets/css/style-editor{$suffix}.css"
	);

	// Enqueue fonts.
	add_editor_style( Fonts::get_google_font_uri() );

}

/**
 * Enqueue styles for front-end.
 *
 * @return void
 */
function styles() {
	// Enqueue fonts.
	wp_enqueue_style( 'skinny-fonts', Fonts::get_google_font_uri(), array(), SKINNY_VERSION );

	// Enqueue main stylesheet.
	wp_enqueue_style(
		'skinny-style',
		get_theme_file_uri( 'style.css' ),
		array(),
		filemtime( get_theme_file_path( 'style.css' ) )
	);

}

/**
 * Fix skip link focus in IE11.
 *
 * This does not enqueue the script because it is tiny and because it is only for IE11,
 * thus it does not warrant having an entire dedicated blocking script being loaded.
 *
 * @link https://git.io/vWdr2
 */
function skip_link_focus_fix() {
	// The following is minified via `terser --compress --mangle -- js/skip-link-focus-fix.js`.
	?>
	<script>
		/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
	</script>
	<?php
}

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'skinny-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function body_classes( $classes ) {

	// If our main sidebar doesn't contain widgets, add no sidebar class.
	if ( skinny_is_woocommerce_activated() && ! is_active_sidebar( 'shop-area' ) ) {
		$classes[] = 'skinny-no-sidebar';
	}

	// Add class when on singular pages.
	if ( is_singular() ) {
		$classes[] = 'skinny-singular';
	}

	// Add class for current color scheme.
	$color_scheme = esc_attr( skinny_get_thememod( 'site_color_scheme' ) );
	if ( isset( $color_scheme ) ) {
		$classes[] = "$color_scheme-color-scheme";
		$classes[] = "is-$color_scheme-theme";
	}

	// Add class for retina logo.
	$is_logo_retina = skinny_get_thememod( 'site_logo_retina' );
	if ( $is_logo_retina ) {
		$classes[] = 'retinafy-logo';
	}

	global $post;
	if ( ( isset( $post ) && is_object( $post ) ) && is_page() ) {
			$hide_page_title = get_post_meta( $post->ID, 'skinny-hide-page-title', true );
			$classes[]       = $hide_page_title ? esc_attr( 'skinny-hide-page-title' ) : '';
	}

	return $classes;
}

/**
 * Adds custom classes to the array of body classes at admin page.
 *
 * @param string $classes Classes that are applied on body tag.
 * @return string
 */
function admin_body_classes( $classes ) {
	$admin_current_screen = get_current_screen();
	if ( ! method_exists( $admin_current_screen, 'is_block_editor' ) && ! $admin_current_screen->is_block_editor() ) {
		return;
	}
	if ( ! empty( $_GET['post'] ) ) {
		$post_id       = sanitize_key( $_GET['post'] );
		$template_name = get_post_meta( $post_id, '_wp_page_template', true );
		if ( 'template-full-width.php' === $template_name ) {
			$classes .= ' skinny-template-full-width ';
		}
	}

	return $classes;
}
/**
 * Filter the excerpt "read more" string.
 *
 * @param string $more "Read more" excerpt string.
 * @return string (Maybe) modified "read more" excerpt string.
 */
function excerpt_more( $more ) {
	return '&hellip;';
}

/**
 * Register page metaboxes.
 *
 * @return void
 */
function register_page_meta() {
	add_meta_box( 'skinny-page-options', __( 'Page Options', 'skinny' ), __NAMESPACE__ . '\page_options_meta', 'page', 'side', 'high' );
}

/**
 * Add page options meta markup.
 *
 * @param object $post Post object which holds post data.
 * @return void
 */
function page_options_meta( $post ) {
	// Nonce value.
	wp_nonce_field( 'skinny_page_options', 'skinny_page_options_nonce' );

	$value = get_post_meta( $post->ID, 'skinny-hide-page-title', true );
	printf(
		'<p><input type="checkbox" id="%1$s" name="%1$s" %2$s /><label for="%1$s">%3$s</label></p>',
		esc_attr( 'skinny-hide-page-title' ),
		checked( $value, 'on', false ),
		esc_html__( 'Hide Page Title', 'skinny' )
	);
}

/**
 * Save page options meta.
 *
 * @param string $post_id Holds post id.
 *
 * @return string|void
 */
function save_page_options( $post_id ) {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}
	// Check if nonce is set.
	if ( ! isset( $_POST['skinny_page_options_nonce'] ) ) {
		return $post_id;
	}

	$nonce = filter_input( INPUT_POST, 'skinny_page_options_nonce' );

	if ( ! $nonce ) {
		return $post_id;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $nonce, 'skinny_page_options' ) ) {
		return $post_id;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}

	$prev_value = get_post_meta( $post_id, 'skinny-hide-page-title', true );
	$value      = isset( $_POST['skinny-hide-page-title'] ) ? sanitize_text_field( wp_unslash( $_POST['skinny-hide-page-title'] ) ) : '';
	update_post_meta( $post_id, 'skinny-hide-page-title', $value, $prev_value );

	return $post_id;
}

/**
 * Return suffix for scripts & styles.
 *
 * @return string Returns suffix for the set environment.
 */
function get_script_suffix() {
	return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
}
