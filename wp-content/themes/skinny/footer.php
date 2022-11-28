<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Skinny
 */
?>
	</div><!-- #content -->

	<div class="site-footer__cta entry-content">
		<?php Skinny\display_footer_cta(); ?>
	</div>

	<footer id="colophon" class="site-footer py-large">
		<div class="footer__inner <?php echo esc_attr( Skinny\site_container_class() ); ?>">

			<div class="footer-widget-area">
				<?php Skinny\footer_widgets(); ?>
			</div>

			<div class="footer-bar flex">
				<div class="site-info">
					<span class="footer-text">
						<?php
						echo wp_kses( skinny_get_thememod(  'footer_text' ), wp_kses_allowed_html() ); // phpcs:ignore
						?>
					</span>
				</div><!-- .site-info -->

				<nav class="footer-navigation">
					<?php
					if ( has_nav_menu( 'footer' ) ) :
						wp_nav_menu(
							array(
								'theme_location' => 'footer',
								'menu_id'        => 'footer-menu',
								'menu_class'     => 'menu list-reset',
								'depth'          => '-1',
							)
						);
					endif;
					?>
				</nav>
			</div><!-- .footer-bar -->
		</div><!-- .footer-inner -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
