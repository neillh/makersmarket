<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Skinny
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-container <?php echo esc_attr( Skinny\site_container_class() ); ?>" itemscope itemtype="https://schema.org/Article">
		<header class="entry-header <?php echo esc_attr( Skinny\site_container_class( 'singular-content' ) ); ?>">
			<?php
			if ( 'post' === get_post_type() ) :
				?>
				<div class="entry-meta">
					<?php Skinny\posted_on(); ?>
				</div><!-- .entry-meta -->
			<?php
			endif;
			if ( is_singular() ) :
				the_title( '<h1 class="entry-title"><span itemprop="name">', '</span></h1>' );
			else :
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark"><span itemprop="name">', '</span></a></h2>' );
			endif;
			?>
		</header><!-- .entry-header -->

		<?php Skinny\featured_image(); ?>

		<div class="entry-content" itemprop="articleBody">
			<?php
			the_excerpt();
			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'skinny' ),
					'after'  => '</div>',
				)
			);
			?>
		</div><!-- .entry-content -->

		<div class="entry-footer">
			<a href="<?php the_permalink(); ?>" class="read-more button" itemprop="url" ><?php esc_html_e( 'Read more', 'skinny' ); ?></a>
		</div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
