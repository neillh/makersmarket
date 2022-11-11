<?php
/**
 * Full gallery product template
 * @since 3.0.0
 * @developer wpdevelopment.me <shramee@wpdevelopment.me>
 */
global $post, $product, $woocommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * woocommerce_before_single_product hook.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}
?>

<div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="images">
	<?php
	$attachment_ids = wp_parse_args( $product->get_gallery_image_ids(), array( get_post_thumbnail_id() ) );
	if ( $attachment_ids ) {
			foreach ( $attachment_ids as $attachment_id ) {

				$classes = array( 'zoom' );
				$image_link = wp_get_attachment_url( $attachment_id );

				if ( ! $image_link )
					continue;

				$image_title 	= esc_attr( get_the_title( $attachment_id ) );
				$image_caption 	= esc_attr( get_post_field( 'post_excerpt', $attachment_id ) );

				$image = wp_get_attachment_image( $attachment_id, 'shop_single', 0, $attr = array(
					'title' => $image_title,
					'alt'   => $image_title
				) );

				$image_class = esc_attr( implode( ' ', $classes ) );

				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<a href="%s" class="%s" title="%s" data-rel="prettyPhoto[product-gallery]">%s</a>', $image_link, $image_class, $image_caption, $image ), $attachment_id, $post->ID, $image_class );
			}
	}
	?>
	</div>

	<div class="summary entry-summary">
		<?php
		woocommerce_show_product_sale_flash();
		/**
		 * woocommerce_single_product_summary hook.
		 *
		 * @hooked woocommerce_template_single_title - 5
		 * @hooked woocommerce_template_single_rating - 10
		 * @hooked woocommerce_template_single_price - 10
		 * @hooked woocommerce_template_single_excerpt - 20
		 * @hooked woocommerce_template_single_add_to_cart - 30
		 * @hooked woocommerce_template_single_meta - 40
		 * @hooked woocommerce_template_single_sharing - 50
		 */
		do_action( 'woocommerce_single_product_summary' );
		?>

	</div><!-- .summary -->

	<?php
	/**
	 * woocommerce_after_single_product_summary hook.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>

	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
