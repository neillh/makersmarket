<?php
$title_font = $this->get( 'header-hero-title-font' );
$title_size = $this->get( 'header-hero-title-size' );
$text_size  = $this->get( 'header-hero-text-size' );
?>
<style>
	.home.blog .site-header, .home.page:not(.page-template-template-homepage) .site-header, .home.post-type-archive-product .site-header {
		margin: 0;
	}

	.page-template-template-homepage .storefront-product-section:first-child {
		margin-top: 2.5em;
	}

	#page > .header-hero .storefront-hero__button-edit {
		display: none;
	}

	#page > .header-hero {
		min-height: 100vh;
		margin-bottom: 32px;
		display: flex;
		justify-content: center;
		align-items: center;
		flex-direction: column;
		background: center/cover;
	}

	#page > .header-hero iframe {
		height: 100%;
		width: 100%;
		max-width: none;
	}

	#page > nav.secondary-navigation ~ .header-hero {
		min-height: calc(100vh - 30px);
	}

	#page .header-hero .storefront-pro-flexslider .slides li .slide-img {
		min-height: 100vh;
		padding: 0;
	}

	<?php

	$header_height = is_front_page() ? $this->get( 'home-header-height' ) : $this->get( 'sitewide-header-height' );

	if ( $header_height ) {
		echo
		 '#page > nav.secondary-navigation ~ .header-hero,' .
		 '#page .header-hero .storefront-pro-flexslider .slides li .slide-img{' .
		 "min-height: {$header_height}px !important;}";
	}
	if ( $title_font || $title_size ) {
		echo "#page > .header-hero h1, #page > .header-hero h2, #page > .header-hero h3 {font-family:$title_font !important;font-size:{$title_size}px !important;}";
	}
	if ( $text_size ) {
		echo "#page > .header-hero, #page > .header-hero p, #page > .header-hero a {font-size:{$text_size}px !important;}";
	}
	?>
	#page > .header-hero .storefront-pro-flexslider .flex-direction-nav {
		display: none;
	}

	#page > .header-hero .storefront-pro-flexslider .slides li {
		padding: 0;
	}

	#page > .header-hero.home-flexslider {
		justify-content: center;
		align-items: stretch;
	}

	.sfp-tablet-live-search {
		z-index: 9;
		flex: 0 0 100%;
		color: #fff;
	}

	.sfp-tablet-live-search input {
		background: rgba(0,0,0,0.16);
		color: #fff;
		border: 1px solid #fff;
	}
	.sfp-tablet-live-search .widget {
		margin: 0;
	}

	@media (max-width: 768px) {
	<?php
	$mob_header_height = is_front_page() ? $this->get( 'home-header-mobile-height' ) : $this->get( 'sitewide-header-height' );
	if ( $mob_header_height ) {
		echo
		 '#page > nav.secondary-navigation ~ .header-hero,' .
		 '#page .header-hero .storefront-pro-flexslider .slides li .slide-img{' .
		 "min-height: {$mob_header_height}px !important;}";
	}
	?>
	}
</style>
<script>
	jQuery( function($) {
		$( '#masthead' ).append( $( '.sfp-tablet-live-search' ) );
	} );
</script>
