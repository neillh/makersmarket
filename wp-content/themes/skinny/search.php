<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Skinny
 */

global $query_string;

$total_results = 0;
$query_args    = explode( '&', $query_string );
$search_query  = array();

if ( strlen( $query_string ) > 0 ) {
	foreach ( $query_args as $key => $string ) {
		$query_split                     = explode( '=', $string );
		$search_query[ $query_split[0] ] = urldecode( $query_split[1] );
	}
}

$search_results = new WP_Query( $search_query );

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php Skinny\custom_header(); ?>
			<div class="site-main__inner">
				<?php
				if ( skinny_is_woocommerce_activated() ) :
					// Output woocommerce products.
					$search_query['post_type'] = 'product';
					$the_query                 = new WP_Query( $search_query );
					$total_results             = $the_query->found_posts;
					global $product;
					if ( $the_query->have_posts() ) {
						?>

						<div class="search-result woocommerce <?php echo esc_attr( Skinny\site_container_class() ); ?>">
							<h2 class="search-result-title">
								<span>
									<?php esc_html_e( 'Products', 'skinny' ); ?>
									<span><?php echo absint( $total_results ); ?></span>
								</span>
							</h2>
							<ul class="search-result-list products columns-<?php echo esc_attr( wc_get_default_products_per_row() ); ?>">
								<?php
								while ( $the_query->have_posts() ) :
									$the_query->the_post();

									/**
									 * Include content-product template for content.
									 */
									get_template_part( 'template-parts/content', 'product' );
								endwhile;
								?>
							</ul>
							<?php
							// Pagination.
							Skinny\get_pagination( $the_query );
							?>
						</div>
						<?php
						/* Restore original Post Data */
						wp_reset_postdata();
					}
				endif;

				// Output posts.
				$search_query['post_type'] = 'post';
				$the_query                 = new WP_Query( $search_query );
				$total_results             = $the_query->found_posts;

				if ( $the_query->have_posts() ) :
					?>
					<div class="search-result posts">
						<h2 class="search-result-title <?php echo esc_attr( Skinny\site_container_class() ); ?>">
							<span>
								<?php esc_html_e( 'Posts', 'skinny' ); ?>
								<span><?php echo absint( $total_results ); ?></span>
							</span>
						</h2>
						<div class="search-result-list">
							<?php
							while ( $the_query->have_posts() ) :
								$the_query->the_post();

								/*
								* Include the post-content template for the content.
								*/
								get_template_part( 'template-parts/content' );

							endwhile;
							?>
						</div>
						<?php
						// Pagination.
						Skinny\get_pagination( $the_query );
						?>
					</div>
					<?php
					/* Restore original Post Data */
					wp_reset_postdata();
				endif;
				?>

				<?php
				// Output pages.
				$search_query['post_type'] = 'page';
				$the_query                 = new WP_Query( $search_query );
				$total_results             = $the_query->found_posts;

				if ( $the_query->have_posts() ) :
					?>
					<div class="search-result pages">
						<h2 class="search-result-title <?php echo esc_attr( Skinny\site_container_class() ); ?>">
							<span>
								<?php esc_html_e( 'Pages', 'skinny' ); ?>
								<span><?php echo absint( $total_results ); ?></span>
							</span>
						</h2>
						<div class="search-result-list">
							<?php
							while ( $the_query->have_posts() ) :
								$the_query->the_post();
								/*
								* Include the post-content template for the content.
								*/
								get_template_part( 'template-parts/content' );
							endwhile;
							?>
						</div>
						<?php
						// Pagination.
						Skinny\get_pagination( $the_query );
						?>
					</div>
					<?php
					/* Restore original Post Data */
					wp_reset_postdata();
			endif;
				?>

			<!-- if no search result -->
			<?php
			if ( ! $search_results->have_posts() ) :
				get_template_part( 'template-parts/content', 'none' );
			endif;
			?>
			</div>
			</div><!-- .site-main__inner -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
