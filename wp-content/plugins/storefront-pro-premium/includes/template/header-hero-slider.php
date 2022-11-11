<div id="header-hero-slider-wrap" class="home-flexslider header-hero">
	<div id="header-hero-slider" class="storefront-pro-flexslider" style="opacity: 0;">
		<style>
			.header-hero #header-hero-slider .flex-caption {
				max-width: calc( 50vh + 250 );
				margin: 50px 0;
			}
		</style>
		<ul class="slides">
			<?php
			$overlay = $this->get( 'header-slider-content-overlay' ) ? 'flex-caption-overlay' : '';
			$default_cta = $this->get( 'header-slider-cta-btn-text' );
			$default_cta_link = $this->get( 'header-slider-cta-btn-link', '#' );

			for ( $i = 1; $i <= 5; $i ++ ) {
				$thumb = $this->get( "header-slide-$i-image" );
				$title = $this->get( "header-slide-$i-title" );
				$text  = $this->get( "header-slide-$i-text" );

				$slide_cta = $this->get( "header-slide-$i-cta-text" );
				$cta = $slide_cta ? $slide_cta : $default_cta;
				$slide_cta_link = $this->get( "header-slide-$i-cta-link" );
				$cta_link = $slide_cta_link ? $slide_cta_link : $default_cta_link;

				if ( $thumb ) {
					?>
					<li>
						<div class='slide-img' style="background-image: url('<?php echo $thumb; ?>')">
							<?php if ( "$title$text" ) { ?>
								<div class="flex-caption <?php echo $overlay; ?> header-slide-text">
									<?php
									echo $title ? "<h2 class='entry-title'>$title</h2>" : '';
									echo $text ? "<p class='entry-excerpt'>$text</p>" : '';
									if ( $cta && $cta_link ) {
										echo "<a class='button' href='$cta_link'>$cta</a>";
									}
									?>
								</div>
							<?php } ?>
						</div>
					</li>
					<?php
				}
			} ?>
		</ul>
	</div>
	<script>
		jQuery( function ( $ ) {
			var $headerHeroSlider = $( '#header-hero-slider' );
			$headerHeroSlider.flexslider( {
				smoothHeight: true,
				initDelay: 0,
				animationSpeed: 500,
				slideshowSpeed: <?php echo apply_filters( 'storefront_pro_header_hero_slideshow_speed', 5000 ) ?>,
				start: function () {
					$headerHeroSlider.css( 'opacity', 1 );
					$headerHeroSlider.find( '.flex-caption' ).css( 'margin', '50px 0' );
				},
			} );
		} );
	</script>
</div>
