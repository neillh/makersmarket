<?php
/**
 * Single product short description
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/short-description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.3.0
 */

// if ( ! defined( 'ABSPATH' ) ) {
// 	exit; // Exit if accessed directly.
// }

// global $post;

// $short_description = apply_filters( 'woocommerce_short_description', $post->post_excerpt );

// if ( ! $short_description ) {
// 	return;
// }

?>
<div class="woocommerce-product-details__short-description">
	<?php the_content(); ?>
	<?php //echo $short_description; // WPCS: XSS ok. ?>
</div>

<?php global $product; ?>
<table class="woocommerce-product-attributes shop_attributes">
	<?php foreach ( $product->get_attributes() as $product_attribute_key => $product_attribute ) { ?>
		<tr class="woocommerce-product-attributes-item woocommerce-product-attributes-item--<?php echo esc_attr( $product_attribute_key ); ?>">
			<th class="woocommerce-product-attributes-item__label"><?php echo wp_kses_post( $product_attribute['name'] ); ?></th>
			<td class="woocommerce-product-attributes-item__value"><?php echo wp_kses_post( $product_attribute['value'] ); ?></td>
		</tr>
	<?php } ?>
</table>
