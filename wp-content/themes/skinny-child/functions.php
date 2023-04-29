<?php
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
	$parenthandle = 'parent-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.
	$theme        = wp_get_theme();
	wp_enqueue_style( $parenthandle,
		get_template_directory_uri() . '/style.css',
		array(),  // If the parent theme code has a dependency, copy it to here.
		$theme->parent()->get( 'Version' )
	);
	wp_enqueue_style( 'child-style',
		get_stylesheet_directory_uri() . '/assets/dist/makersmarket-home.css',
		array( $parenthandle ),
		$theme->get( 'Version' ) // This only works if you have Version defined in the style header.
	);
}

function add_content_after_addtocart() {
	$current_product_id = get_the_ID();
	$product = wc_get_product( $current_product_id );
	$checkout_url = wc_get_checkout_url();

	if ( $product->is_type( 'simple' ) ) {
		echo '<button class="buy-now button"><a href="'.$checkout_url.'?add-to-cart='.$current_product_id.'" class="">Buy Now</a></button>';
	}
}
add_action( 'woocommerce_after_add_to_cart_button', 'add_content_after_addtocart' );

add_filter( 'woocommerce_billing_fields', 'bbloomer_move_checkout_email_field' );

function bbloomer_move_checkout_email_field( $address_fields ) {
    $address_fields['billing_email']['priority'] = 1;
    return $address_fields;
}

function mm_custom_login_logo() { ?>
	<style type="text/css">
		#login h1 a, .login h1 a {
			background-image: url(https://makersmarket.au/wp-content/uploads/2023/01/full-logo-black.png);
			height: 130px;
			width: 300px;
			background-size: 300px;
			background-repeat: no-repeat;
			padding-bottom: 10px;
		}
	</style>
<?php }
add_action( 'login_enqueue_scripts', 'mm_custom_login_logo' );
