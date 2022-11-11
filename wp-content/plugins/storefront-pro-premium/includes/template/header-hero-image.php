<?php
?>
<div class='header-hero header-hero-image' style="<?php storefront_homepage_content_styles(); ?>">
	<style>
		.header-hero-image h1, .header-hero-image h2, .header-hero-image h3, .header-hero-image h4, .header-hero-image p {
			color: #fff;
			text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
		}
	</style>
	<div class="col-full">
		<?php

		if ( $header_height = $this->get( 'home-header-height' ) ) {
			echo "<style>.storefront-pro-active #page > .header-hero{min-height: {$header_height}px !important;}</style>";
		}

		if ( $mob_header_height = $this->get( 'home-header-mobile-height' ) ) {
			echo "<style>@media (max-width:768px) {.storefront-pro-active #page > .header-hero{min-height: {$mob_header_height}px !important;}}</style>";
		}
		if ( is_page_template( 'template-homepage.php' ) ) {
			while ( have_posts() ) {
				the_post();
				do_action( 'storefront_homepage' );
			}
			wp_reset_query();
		} elseif ( is_front_page() ) {
			the_title( '<h1 class="entry-title">', '</h1>' );
			?>
			<style>.site-main .entry-title{ display: none;}</style>
			<?php
		} else {
			$header_height = $this->get( 'sitewide-header-height' );
			if ( $header_height ) {
				echo "<style>.storefront-pro-active #page > .header-hero{min-height: {$header_height}px !important;}</style>";
			}
		}
		?>
		<script>
			( function( $ ) {
					if ( ! $( 'body' ).hasClass( 'sfp-nav-styleleft-vertical' ) ) {
						$( '.header-hero-image' ).css( 'padding-top', $( '#masthead' ).height() / 2 );
					}
			} )( jQuery );
		</script>
	</div>
</div>
