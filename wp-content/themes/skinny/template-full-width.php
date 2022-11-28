<?php
/**
 * Template Name: Full Width Template
 *
 * @package Skinny
 */

get_header();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'full' );

		endwhile; // End of the loop.
		?>
	</main><!-- #main -->
</div><!-- .content-area -->

<?php
get_footer();
