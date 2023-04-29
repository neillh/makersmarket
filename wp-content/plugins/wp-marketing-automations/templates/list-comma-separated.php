<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** checking if woocommerce exists other wise return */
if ( ! function_exists( 'bwfan_is_woocommerce_active' ) || ! bwfan_is_woocommerce_active() ) {
	return;
}

$product_names = [];
foreach ( $products as $product ) {
	if ( ! $product instanceof WC_Product ) {
		continue;
	}
	$name = $product->get_name();
	if ( $product instanceof WC_Product_Variation ) {
		$name .= ' - ' . $product->get_attribute_summary();
	}
	$product_names[] = esc_html__( $name );
}

$explode_operator = apply_filters( 'bwfan_product_name_separator', ', ', 1 );
echo implode( $explode_operator, $product_names ); //phpcs:ignore WordPress.Security.EscapeOutput
