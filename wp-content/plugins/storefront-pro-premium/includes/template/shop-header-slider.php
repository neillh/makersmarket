<?php
global $wp_query;

wp_enqueue_script( 'flexslider' );
wp_enqueue_style( 'sfp-flexslider-styles' );

$args = $wp_query->query_vars;

if ( empty( $args['tax_query'] ) ) {
	$args['tax_query'] = [];
}

$args['tax_query'][] = array(
	'taxonomy' => 'product_visibility',
	'field'    => 'name',
	'terms'    => 'featured',
	'operator' => 'IN'
);

$query = new WP_Query( $args );

if ( ! $query->have_posts() ) {
	$query = $wp_query;
}

$height = $this->get( 'shop-header-height' );

?>
	<div id="shop-hero-slider-wrap" class="shop-flexslider">
		<style>

			#masthead {
				margin-bottom: 0;
			}

			div#header-hero-posts-slider .slides li .slide-img {
				padding: 0;
			}

			.shop-flexslider div.slide-img {
				background: center/cover;
			}

			.shop-flexslider div,
			.shop-flexslider p,
			.shop-flexslider a,
			.shop-flexslider h1,
			.shop-flexslider h2,
			.shop-flexslider h3,
			.shop-flexslider h4,
			.shop-flexslider h5,
			.shop-flexslider h6 {
				color: #fff;
			}

			.shop-flexslider .product.col-full {
				margin: 0;
				background: rgba(0,0,0,0.2);
				padding: <?php echo $height ? $height / 2.5 . 'px' : '2.5em' ?> 1em;
				max-width: none;
				width: 100%;
			}

			div#header-hero-posts-slider .slides .flex-caption {
				width: 300px;
				margin: auto;
				float: none;
			}

			.storefront-pro-active #shop-hero-slider .entry-title {
				font-size: 2.5em !important;
			}

		</style>
		<div id="shop-hero-slider" class="storefront-pro-flexslider" style="opacity: 0;">
			<?php

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
										<div class="product col-full">
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
						var $shopHeroSlider = $( '#shop-hero-slider' );
						$shopHeroSlider.flexslider( {
							smoothHeight: true,
							initDelay: 0,
							animationSpeed: 500,
							slideshowSpeed: 5000,
							start: function () {
								$shopHeroSlider.css( 'opacity', 1 );
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
<?php
wp_reset_query();