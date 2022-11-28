<?php
/**
 * The 404 template file
 *
 * @package Skinny
 */


get_header(); ?>

	<div id="primary" class="content-area 404-found">
		<main id="main" class="site-main" role="main">
			<div class="site-main__inner">
				<?php get_template_part( 'template-parts/content', 'none' ); ?>
			</div><!-- .site-main__inner -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();

