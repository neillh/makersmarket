<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package storefront
 */

get_header();
include 'styles.php';

//Thumbnail args
if ( empty( $sfp_thumb_size ) ) {
	$sfp_thumb_size = 'thumbnail';
	$sfp_thumb_args = array();
}

$i           = 0;
$posts_array = array();
?>
<style>
	.site-content > .col-full {
		margin: 0;
		padding: 0;
		max-width: none;
	}
</style>
<div id="primary" class="content-area sfp-awesome-layout-<?php echo $layout ?>">
	<main id="main" class="site-main section group" role="main">
		<?php if ( have_posts() ) :
			while ( have_posts() ) : the_post();
				$i ++;
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( "blog-tile blog-tile-$i" );
				echo ' style="background-image:url(' . get_the_post_thumbnail_url( null, $i < 3 ? 'large' : 'medium_large' ) . ')"' ?>
				         itemscope="" itemtype="https://schema.org/BlogPosting">
					<a href="<?php the_permalink() ?>" rel="bookmark">
					<span class="overlay">
						<span class="entry-header">
							<span class="entry-meta">
								<?php if ( 'post' == get_post_type() ) :

									$categories_list = get_the_category_list( __( ', ', 'storefront' ) );
									$tags_list = get_the_tag_list( '', __( ', ', 'storefront' ) );
									if ( $categories_list ) : ?>
										<span class="cat-links">
											<?php
											echo strip_tags( $categories_list );
											echo strip_tags( $tags_list );
											?>
										</span>
										<?php
									endif; // End if categories.
								endif; // End if post. ?>
							</span>
							<?php
							the_title( '<h1 class="entry-title" itemprop="name headline">', '</h1>' );
							?>
						</span><!-- .entry-header -->
					</span>
					</a>
				</article><!-- #post-## -->
				<?php
			endwhile;
		else :
			get_template_part( 'content', 'none' );
		endif;

		/**
		 * @hooked storefront_paging_nav - 10
		 */
		do_action( 'storefront_loop_after' );
		?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
