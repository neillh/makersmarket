<?php
/**
 * Full image product template
 * @since 3.0.0
 * @developer wpdevelopment.me <shramee@wpdevelopment.me>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php
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

function sfp_return_large() {
	return 'large';
}
function sfp_return_medium() {
	return 'medium';
}
add_filter( 'woocommerce_gallery_image_size', 'sfp_return_large' );
add_filter( 'woocommerce_gallery_thumbnail_size', 'sfp_return_medium' );
?>

<div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php woocommerce_show_product_images(); ?>

	<div class="summary entry-summary">

		<?php
		woocommerce_show_product_sale_flash();
		woocommerce_template_single_title();
		woocommerce_template_single_rating();
		woocommerce_template_single_price();
		woocommerce_template_single_add_to_cart();
		woocommerce_template_single_sharing();
		woocommerce_template_single_excerpt();
		woocommerce_template_single_meta();
		do_action( 'woocommerce_share' );
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
