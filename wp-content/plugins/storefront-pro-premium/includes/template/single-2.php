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
?>

<div id="primary" class="content-area sfp-awesome-layout-1">
	<main id="main" class="site-main" role="main">
		<?php if ( have_posts() ) :
			while ( have_posts() ) : the_post();
			?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope="" itemtype="https://schema.org/BlogPosting">
			<div></div>
			<header class="entry-header">
				<?php if (has_post_thumbnail( get_the_ID() ) ) {
					$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
					<div id="kickass-feat" class="sfp-full-width-image-bg"
					     <?php if ( ! get_theme_mod( 'storefront-pro-single-fixed-featured-image' ) ) { echo 'data-top-bottom="background-position: center 160px;" data-top-top="background-position: center 0px;"';} ?> style="background-image: url('<?php echo $image[0]; ?>')">
						<div class="col-full">
							<h1 data-bottom="opacity:1; padding-bottom: 20%;" data--100-center="opacity:1; padding-bottom: 10%;" data-top-bottom="opacity:0.16; padding-bottom: 0%;" class="entry-title" itemprop="name headline">
								<?php the_title(); ?>
							</h1>
						</div><!-- .col-full -->
					</div>
				<?php } else {
				?>
					<div class="col-full">
						<?php the_title( '<h1 class="entry-title" itemprop="name headline">', '</h1>' ); ?>
					</div><!-- .col-full -->
				<?php } ?>
			</header><!-- .entry-header -->
			<div class="col-full">
				<div class="entry-content" itemprop="articleBody">
					<?php the_content( sprintf( __( 'Continue reading %s', 'storefront' ), get_the_title() ) ); ?>
				</div><!-- .entry-content -->
				<?php
				storefront_post_meta();
				?>
			</div><!-- .col-full -->

		</article><!-- #post-## -->

		<div class="col-full">
		<?php
		/**
		 * Functions hooked in to storefront_single_post_after action
		 *
		 * @hooked storefront_post_nav         - 10
		 * @hooked storefront_display_comments - 20
		 */
		do_action( 'storefront_single_post_bottom' );

		/**
		 * After single post
		 */
		do_action( 'storefront_single_post_after' );
		?>
		</div><!-- .col-full -->
			<?php
			endwhile;
		else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer(); ?>
