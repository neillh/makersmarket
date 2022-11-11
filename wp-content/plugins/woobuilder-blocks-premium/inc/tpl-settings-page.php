<?php wp_enqueue_style( 'caxton-front', Caxton::$url . 'assets/front.css' ); ?>
<style>
	body {
		background: #dee7ec;
	}
	.woobk-admin-acc-1 {
		color: #fff;
		background-color: #5e9ebd;
	}

	.woobk-admin-acc-1 [class*='bg-white'] {
		color: #444;
	}

	.woobk-admin-acc-1 h1 {
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

	.woobuilder-blocks-admin * {
		box-sizing: border-box;
	}

	.woobuilder-blocks-admin {
		line-height: 1.4;
		max-width: 1150px;
		margin: auto;
	}

	.woobk-hide-notice .notice, .woobk-hide-notice div.updated, .woobk-hide-notice div.error {
		display: none !important;
	}

</style>

<div class="wrap woobuilder-blocks-admin">
	<header class="flex items-center bg-white mv4">
		<div class="w-50 woobk-admin-acc-1 woobk-hide-notice">
			<div class="pa3">
				<h1>
					<b class="h1">WooBuilder Blocks v<?php echo WooBuilder_Blocks::$version ?></b>
				</h1>
			</div>
			<div class="bg-black-20 pa3">
				<div class="f5">Create awesome WooCommerce Single Product Pages</div>
			</div>
		</div>

		<div class="w-50 ph4">
			<img src="https://www.pootlepress.com/wp-content/uploads/2019/10/woobuilder-blocks-for-woocommerce-768x174.png"
					 alt="WooBuilder Blocks" style="max-width: 320px" class="db maa">
		</div>

	</header>

	<div class="mb4 pa5 bg-white">
		<div class="f4">
			WooBuilder blocks makes it easy to create any layout for your WooCommerce Single Product Page.

			WooBuilder Blocks uses the new Gutenberg editor.
			So customization of your Product Page layout is incredibly easy.
			We’ve built a collection of blocks that you can arrange in any way you like.
		</div>
	</div>

	<div class="mb4 bg-white">
		<div class="aspect-ratio--16x9 relative">
			<iframe src="https://player.vimeo.com/video/395454977" frameborder="0" allow="autoplay; fullscreen"
							class="absolute--fill w-100 h-100" allowfullscreen></iframe>
		</div>
	</div>

	<figure class="ma0 flex items-center bg-white flex-row-reverse">
		<div class="pa2 w-50 bg-black">
			<img src="https://www.pootlepress.com/wp-content/uploads/2019/08/carousel.gif"
					 alt="woocommerce product block carousel" class="wp-image-48679" title="WooBuilder 12">
		</div>
		<figcaption class="w-50 f3 pa5">Showcase your products – here we see the Product Carousel Block<br></figcaption>
	</figure>
	<figure class="ma0 flex items-center bg-white">
		<div class="pa2 w-50 bg-black">
			<img src="https://www.pootlepress.com/wp-content/uploads/2019/05/woobuilder-layouts-1.gif"
					 alt="woobuilder layouts" class="wp-image-48589" title="WooBuilder 14">
		</div>
		<figcaption class="w-50 f3 pa5">Drag and drop the blocks to re-arrange the Product layout</figcaption>
	</figure>
	<figure class="ma0 flex items-center bg-white flex-row-reverse">
		<div class="pa2 w-50 bg-black">
			<img src="https://www.pootlepress.com/wp-content/uploads/2019/08/fonts.gif"
					 alt="edit woocommerce product fonts" class="wp-image-48682" title="WooBuilder 15">
		</div>
		<figcaption class="w-50 f3 pa5">Full control over your Woocommerce Product fonts and sizes</figcaption>
	</figure>
</div>