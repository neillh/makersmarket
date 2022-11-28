<?php
/**
 * Template part for displaying products at search.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Skinny
 */

global $product;
?>
<li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-container" itemscope itemtype="https://schema.org/Product">
		<?php
		do_action( 'woocommerce_before_shop_loop_item' );

		do_action( 'woocommerce_before_shop_loop_item_title' );

		do_action( 'woocommerce_shop_loop_item_title' );
		do_action( 'woocommerce_after_shop_loop_item_title' );
		do_action( 'woocommerce_after_shop_loop_item' );
		?>
	</div>
</li><!-- #post-<?php the_ID(); ?> -->
