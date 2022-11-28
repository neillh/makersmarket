<?php
/**
 * Template part for displaying posts content in single.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Skinny
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-container" itemscope itemtype="https://schema.org/Article">
		<header class="entry-header <?php echo esc_attr( Skinny\site_container_class( 'singular' ) ); ?>">
			<?php the_title( '<h1 class="entry-title"><span itemprop="name">', '</span></h1>' ); ?>
			<div class="entry-meta">
				<?php Skinny\posted_on(); ?>
			</div>
		</header><!-- .entry-header -->
		<?php Skinny\featured_image(); ?>
		<div class="entry-content" itemprop="articleBody">
			<?php
			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'skinny' ),
				'after'  => '</div>',
			) );

			if ( get_edit_post_link() ) :

				edit_post_link(
					sprintf(
						wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
							__( 'Edit <span class="screen-reader-text">%s</span>', 'skinny' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						get_the_title()
					),
					'<span class="edit-link">',
					'</span>'
				);

			endif;
			?>
		</div><!-- .entry-content -->
		<div class="entry-footer <?php echo esc_attr( Skinny\site_container_class( 'singular' ) ); ?>">
			<?php Skinny\post_tags(); ?>
		</div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
