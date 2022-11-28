<?php
/**
 * WordPress shims.
 *
 * @package Skinny
 */

if ( ! function_exists( 'wp_body_open' ) ) {
	/**
	 * Adds backwards compatibility for wp_body_open() introduced with WordPress 5.2
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_body_open/
	 * @return void
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

/**
 * Filter allowedtags for anchor tag's target attribute.
 *
 * @param array $allowedposttags Contains list of allowed html tags.
 *
 * @return mixed|array
 */
function skinny_update_anchor_tag( $allowedposttags ) {
	// Add direct index to avoid tempering with main anchor array.
	$allowedposttags['a']['target'] = true;

	return $allowedposttags;
}

add_filter( 'wp_kses_allowed_html', 'skinny_update_anchor_tag', 1 );
