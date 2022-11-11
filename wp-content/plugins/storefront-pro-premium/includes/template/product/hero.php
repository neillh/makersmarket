<?php
/**
 * Hero product template
 * @since 3.0.0
 * @developer wpdevelopment.me <shramee@wpdevelopment.me>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/** @var $product WC_Product */
global $product;
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

<div id="product-<?php the_ID(); ?>" <?php post_class( 'hero-layout' ); ?>>
	<div class="hero" style="background-image: url(<?php the_post_thumbnail_url( 'full' ); ?>)">
		<div class="col-full">
			<div class="summary entry-summary">
				<?php
				woocommerce_show_product_sale_flash();
				woocommerce_breadcrumb( array( 'home' => false ) );
				woocommerce_template_single_title();
				woocommerce_template_single_rating();
				woocommerce_template_single_price();
				woocommerce_template_single_add_to_cart();
				woocommerce_template_single_meta();
				woocommerce_template_single_sharing();
				woocommerce_show_product_images();
?>
			</div><!-- .summary -->
		</div><!-- .col-full -->
	</div><!-- .hero -->
	<div class="col-full">
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

		<meta itemprop="url" content="<?php the_permalink(); ?>"/>
	</div><!-- .col-full -->
</div><!-- #product-<?php the_ID(); ?> -->

<div class="col-full">
	<?php do_action( 'woocommerce_after_single_product' ); ?>
</div><!-- .col-full -->
