<?php
/**
 * Created by PhpStorm.
 * User: shramee
 * Date: 18/11/16
 * Time: 12:56 PM
 */
?>
	<style>
		.videoWrapper {
			position: relative;
			padding-bottom: 56.5%;
			padding-top: 25px;
			height: 0;
			margin: 0 0 2.5em;
		}

		.videoWrapper iframe {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
		}

		h1 {
			font-weight: normal;
			margin: 1.6em 0;
		}

		h1 small {
			opacity: 0.7;
			float: right;
			font-size: 0.5em;
		}

		.sfp-wrap {
			width: 97%;
		}

		.sfp-wrap:after {
			display: block;
			content: '';
			clear: both;
		}

		.half-width {
			width: 50%;
			float: left;
		}
	</style>
	<div class="sfp-wrap">
	<?php

	$tabs = [ 'home', 'modules' ];

	$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : '';
	$active_tab = in_array( $active_tab, $tabs ) ? $active_tab : 'home';
	$href       = admin_url( 'themes.php?page=storefront-pro' );

	if ( ! empty( $_GET['notice'] ) ) {
		echo <<<HTML
<div class="notice notice-success is-dismissible" style="margin:7px 0">
	<p>$_GET[notice]</p>
</div>
HTML;

	}

	if ( ! empty( $_GET['error'] ) ) {
		echo <<<HTML
<div class="notice notice-error">
	<p>$_GET[error]</p>
</div>
HTML;

	}
	add_thickbox();
	?>

<div class="intro-videos">
	<section>
		<h1>Storefront Pro
			<small>Version <?php echo SFP_VERSION ?></small>
		</h1>
		<p>
			Storefront Pro let’s you easily customize the WooThemes Storefront theme.
		</p>
		<div style="max-width: 700px;margin:auto;">
			<div class="videoWrapper">
				<iframe frameborder="0" src="https://player.vimeo.com/video/268133764"
								webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
			</div>
		</div>
	</section>

	<section class="half-width" style="text-align:center;">
		<h2>WooBuilder Blocks</h2>
		<p>
			Build interesting product pages and increase revenue.
		</p>
		<div class="videoWrapper">
			<iframe frameborder="0" src="https://player.vimeo.com/video/334670154"
							webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
		</div>
		<a class="button pootle button-large" href="https://www.pootlepress.com/woobuilder-blocks/">Buy now</a>

		<p>
			<span>Single Site $49</span> |
			<span>5 Sites $75</span> |
			<span>25 Sites $99</span> |
			<span>Unlimited license $199</span>
		</p>
	</section>

	<section class="half-width" style="text-align:center;">
		<h2>Storefront Blocks</h2>
		<p>
			Showcase your WooCommerce products - beautifully
		</p>
		<div class="videoWrapper">
			<iframe frameborder="0" src="https://player.vimeo.com/video/282827922"
							webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
		</div>
		<a class="button pootle button-large" href="https://pootlepress.com/storefront-blocks">Buy now</a>

		<p>
			<span>Single Site $75</span> |
			<span>5 Sites $125</span> |
			<span>25 Sites $149</span> |
			<span>Unlimited license $199</span>
		</p>

	</section>
	<style>
		.sfp-wrap h2 {
			margin-top: .5em;
		}
		.intro-videos .half-width {
			width: 49%;
		}
		.intro-videos .half-width + .half-width {
			float: right;
		}

		.wp-core-ui  .button-large.pootle {
			font-size: 25px;
			line-height: 25px;
			height: auto;
			padding: .5em .7em;
		}
		.intro-videos .half-width .videoWrapper {
			margin-bottom: .5em;
		}
	</style>
	<div class="clear"></div>
</div>

	<hr>
	<h1>Marketing apps included with your Storefront Pro subscription</h1>
	<?php
	require "settings-page-tab-modules.php";
	?>
	<br>
	<hr>
	<h1>Changelog</h1>

<?php
require "settings-page-tab-changelog.php";

