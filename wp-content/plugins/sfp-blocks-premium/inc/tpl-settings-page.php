<?php if ( class_exists( 'Caxton' ) ) {
	wp_enqueue_style( 'caxton-front', Caxton::$url . 'assets/front.css' );
} ?>
<style>
	body {
		background: #dee7ec;
	}
	.sfpbk-admin-acc-1 {
		color: #fff;
		background-color: #5e9ebd;
	}

	.sfpbk-admin-acc-1 [class*='bg-white'] {
		color: #444;
	}

	.sfpbk-admin-acc-1 h1 {
		color: inherit;
	}

	.bg-white {
		background: #fff;
		color: #333;
	}

	.bg-black {
		background: black;
		color: #eee;
	}

	.sfp-blocks-admin * {
		box-sizing: border-box;
	}

	.sfp-blocks-admin {
		line-height: 1.4;
		max-width: 1150px;
		margin: auto;
	}

	.sfpbk-hide-notice .notice, .sfpbk-hide-notice div.updated, .sfpbk-hide-notice div.error {
		display: none !important;
	}

</style>

<div class="wrap sfp-blocks-admin">
	<header class="flex items-center bg-white mv4">
		<div class="w-50 sfpbk-admin-acc-1 sfpbk-hide-notice">
			<div class="pa3">
				<h1>
					<b class="h1">Storefront Blocks v<?php echo Storefront_Pro_Blocks::$version ?></b>
				</h1>
			</div>
			<div class="bg-black-20 pa3">
				<div class="f5">Fully customize the WooCommerce Shop Page and Category Pages</div>
			</div>
		</div>

		<div class="w-50 ph4">
			<img src="https://www.pootlepress.com/wp-content/uploads/2019/10/storefront-blocks-for-woocommerce-2-1024x232.png"
					 alt="Storefront Blocks" style="max-width: 320px" class="db maa">
		</div>

	</header>

	<div class="mb4 pa5 bg-white">
		<div class="f4">
			Please take a minute to watch these short video tutorials to get the best out of Storefront Blocks.
		</div>
	</div>
	<div class="ma0 flex items-center bg-white">
		<div class="pa2 w-50 bg-black">
			<div class="aspect-ratio--16x9 relative">
				<iframe src="https://player.vimeo.com/video/335877571" frameborder="0" allow="autoplay; fullscreen"
								class="absolute--fill w-100 h-100" allowfullscreen></iframe>
			</div>
		</div>
		<div class="w-50 f3 pa5">How to customize the WooCommerce Shop Page<br></div>
	</div>
	<div class="ma0 flex items-center bg-white flex-row-reverse">
		<div class="pa2 w-50 bg-black">
			<div class="aspect-ratio--16x9 relative">
				<iframe src="https://player.vimeo.com/video/387103486" frameborder="0" allow="autoplay; fullscreen"
								class="absolute--fill w-100 h-100" allowfullscreen></iframe>
			</div>
		</div>
		<div class="w-50 f3 pa5">How to customize WooCommerce Category Pages<br></div>
	</div>
	<div class="ma0 flex items-center bg-white">
		<div class="pa2 w-50 bg-black">
			<div class="aspect-ratio--16x9 relative">
				<iframe src="https://player.vimeo.com/video/342241752" frameborder="0" allow="autoplay; fullscreen"
								class="absolute--fill w-100 h-100" allowfullscreen></iframe>
			</div>
		</div>
		<div class="w-50 f3 pa5">How to customize WooCommerce Home page<br></div>
	</div>
	<div class="ma0 flex items-center bg-white flex-row-reverse">
		<div class="pa2 w-50 bg-black">
			<div class="aspect-ratio--16x9 relative">
				<iframe src="https://player.vimeo.com/video/282827922" frameborder="0" allow="autoplay; fullscreen"
								class="absolute--fill w-100 h-100" allowfullscreen></iframe>
			</div>
		</div>
		<div class="w-50 f3 pa5">How to showcase your Products<br></div>
	</div>

</div>