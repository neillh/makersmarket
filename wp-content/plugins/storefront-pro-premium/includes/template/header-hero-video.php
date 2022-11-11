<?php
$bg_img = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
?>
<style>
	#page > #masthead ~ .header-hero {
		min-height: 0;
	}
	.header-hero-video-outer-wrap {
		position: relative;
		height: 56.25vw;
		width: 100vw;
		overflow: hidden;
	}
	<?php
		if ( $header_height = $this->get( 'home-header-height' ) ) {
			echo ".header-hero .header-hero-video-outer-wrap{height: {$header_height}px !important;}";
		}
		if ( $mob_header_height = $this->get( 'home-header-mobile-height' ) ) {
			echo "@media (max-width:768px) {.header-hero .header-hero-video-outer-wrap{height: {$mob_header_height}px !important;}}";
		} ?>
	.header-hero-video-inner-wrap,
	.header-hero-video-inner-wrap iframe {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
	}

	#vidtop-content {
		top: 0;
		color: #fff;
	}
	/* region Video heading/text */
	.vid-info {
		position: absolute;
		top: 0;
		right: 0;
		width: 33%;
		background: rgba(0, 0, 0, 0.3);
		color: #fff;
		padding: 1rem;
		font-family: Avenir, Helvetica, sans-serif;
	}

	.vid-info h1 {
		font-size: 2rem;
		font-weight: 700;
		margin-top: 0;
		line-height: 1.2;
	}

	.vid-info a {
		display: block;
		color: #fff;
		text-decoration: none;
		background: rgba(0, 0, 0, 0.5);
		transition: .6s background;
		border-bottom: none;
		margin: 1rem auto;
		text-align: center;
	}

	/* endregion */
</style>
<div class='header-hero header-hero-video' data-featured-image="<?php echo $bg_img; ?>">
<div class="header-hero-video-outer-wrap">
<div class="header-hero-video-inner-wrap">
		<?php
		$html = $GLOBALS['wp_embed']->autoembed( $this->get( "home-video-url" ) );
		$qry = '?_&feature=oembed&rel=0&controls=0&showinfo=0&autoplay=1&loop=1&enablejsapi=1&api=1';
		echo preg_replace( '/src="([^"]+)"/', "src='$1$qry'", $html );
		?>
</div>
</div>
</div>
