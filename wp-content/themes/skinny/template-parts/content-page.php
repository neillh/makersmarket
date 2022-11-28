<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Skinny
 */

$page_thumbnail = has_post_thumbnail() ? 'style=background-image:url(' . get_the_post_thumbnail_url() . ');' : '';
$no_thumbnail   = ! has_post_thumbnail() ? 'no-thumb' : '';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-container" itemscope itemtype="https://schema.org/Article">
		<header class="entry-header <?php echo esc_attr( $no_thumbnail ); ?>" <?php echo esc_attr( $page_thumbnail ); ?>>
			<div class="entry-header__cover"></div>
			<div class="entry-header__inner <?php echo esc_attr( Skinny\site_container_class( 'singular' ) ); ?>">
				<h1 class="entry-title"><span itemprop="name"><?php the_title(); ?></span></h1>
			</div>
		</header><!-- .entry-header -->

		<div class="entry-content" itemprop="articleBody">
			<?php
			the_content();

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'skinny' ),
					'after'  => '</div>',
				)
			);

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
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
