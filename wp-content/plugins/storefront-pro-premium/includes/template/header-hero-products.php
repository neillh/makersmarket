<div id="header-hero-slider-wrap" class="home-flexslider header-hero">
	<style>
		.header-hero .single-product div.product {
			position: static;
			display: inline;
		}

		.header-hero .single-product div.product .flex-caption.summary {
			max-width: 380px;
			margin: 50px 0;
		}
	</style>
	<div id="header-hero-slider" class="storefront-pro-flexslider" style="opacity: 0;">
		<?php

		$args            = array(
			'posts_per_page' => 5,
			'post_type'      => 'product',
			'meta_key'       => '_thumbnail_id',
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
				),
			),
		);
		$query           = new WP_Query( $args );
		$product_factory = new WC_Product_Factory();
		if ( $query->have_posts() ):
			$overlay = $this->get( 'header-slider-content-overlay' ) ? 'flex-caption-overlay' : '';

			add_filter( 'excerpt_length', array( $this, 'excerpt_length' ), 99 );
			$transition = 'slide';
			?>
			<div id="header-hero-posts-slider" class="storefront-pro-flexslider">
				<ul class="slides">
					<?php while ( $query->have_posts() ) {
						$query->the_post();
						$thumb = get_the_post_thumbnail_url( null, 'large' );
						if ( $thumb ) {
							global $product;
							$product = $product_factory->get_product( get_the_ID() );
							?>
							<li class="single-product">
								<div class='slide-img' style='background-image: url("<?php echo $thumb ?>")'>
									<div class="product">
										<div class="flex-caption <?php echo $overlay ?> summary entry-summary">
											<?php
											/**
											 * Hook: Woocommerce_single_product_summary.
											 *
											 * @hooked woocommerce_template_single_title - 5
											 * @hooked woocommerce_template_single_rating - 10
											 * @hooked woocommerce_template_single_price - 10
											 * @hooked woocommerce_template_single_excerpt - 20
											 * @hooked woocommerce_template_single_add_to_cart - 30
											 * @hooked woocommerce_template_single_meta - 40
											 * @hooked woocommerce_template_single_sharing - 50
											 * @hooked WC_Structured_Data::generate_product_data() - 60
											 */
											do_action( 'woocommerce_single_product_summary' );
											?>
										</div>
									</div>
								</div>
							</li>
							<?php
						}
					} ?>
				</ul>
			</div>
			<script>
				jQuery( function ( $ ) {
					var $headerHeroSlider = $( '#header-hero-slider' );
					$headerHeroSlider.flexslider( {
						smoothHeight: true,
						initDelay: 0,
						animationSpeed: 500,
						slideshowSpeed: 5000,
						start: function () {
							$headerHeroSlider.css( 'opacity', 1 );
						},
					} );
				} );
			</script>
		<?php
		remove_filter( 'excerpt_length', array( $this, 'excerpt_length' ), 99 );
		else:
		?>
			<div class="header-hero">
				<h2>Please add some featured posts to show a lovely slider here!</h2>
			</div>
			<?php
		endif;
		?>
	</div>
</div>
