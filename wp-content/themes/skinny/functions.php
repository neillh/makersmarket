<?php
/**
 * Skinny theme functions and definitions
 *
 * @package Skinny
 */

/**
 * Theme constants.
 */
define( 'SKINNY_VERSION', '1.1.2' );

/**
 * Load fonts.
 */
require_once get_parent_theme_file_path( 'inc/fonts/google-fonts.php' );
require_once get_parent_theme_file_path( 'inc/fonts/fonts.php' );

/**
 * Core setup, hooks, and filters.
 */
require_once get_parent_theme_file_path( 'inc/core.php' );
require_once get_parent_theme_file_path( 'inc/css.php' );

/**
 * Customizer settings & functions.
 */
require_once get_parent_theme_file_path( 'inc/customizer/theme-options.php' );
require_once get_parent_theme_file_path( 'inc/customizer.php' );

/**
 * Custom template tags for theme.
 */
require_once get_parent_theme_file_path( 'inc/template-tags.php' );


/**
 * WooCommerce hooks & filters.
 */
require_once get_parent_theme_file_path( 'inc/woocommerce.php' );


/*
 * Theme helper functions and shims.
 */
require_once get_parent_theme_file_path( 'inc/skinny-functions.php' );
require_once get_parent_theme_file_path( 'inc/wordpress-shims.php' );

/**
 * Run setup functions.
 */
Skinny\Core\setup();
Skinny\Customizer\setup();
Skinny\WooCommerce\setup();


function add_content_after_addtocart() {
	$current_product_id = get_the_ID();
	$product = wc_get_product( $current_product_id );
	$checkout_url = wc_get_checkout_url();

	if ( $product->is_type( 'simple' ) ) {
		echo '<button class="buy-now button"><a href="'.$checkout_url.'?add-to-cart='.$current_product_id.'" class="">Buy Now</a></button>';
	}
}
add_action( 'woocommerce_after_add_to_cart_button', 'add_content_after_addtocart' );
