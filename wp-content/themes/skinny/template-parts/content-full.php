<?php
/**
 * Template part for displaying page content in Full width template
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Skinny
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content full-width-content">
		<?php
		the_content();
		?>
	</div><!-- .full-width-content -->
</article>
