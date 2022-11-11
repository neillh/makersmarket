<?php
$sfp_modules = apply_filters( 'storefront_pro_modules', array(
	'storefront-pro-skins'       => array(
		'Name'        => 'Storefront Pro Skins',
		'Description' => 'Save instances of all customizer settings to apply later on demand.',
		'InstallURI'  => admin_url( "/plugin-install.php?s=storefront+pro+skins&tab=search&type=term" ),
		'AuthorURI'   => 'https://www.pootlepress.com',
		'Author'      => 'Pootlepress',
		'Image'       => "//ps.w.org/storefront-pro-skins/assets/icon-256x256.png?ver=" . SFP_VERSION,
		'ActiveClass' => 'Storefront_Pro_Skins',
	),
	'storefront-pro-sales-pop'   => array(
		'Name'        => 'Storefront Pro Sales Pop',
		'Description' => 'Shows fully customizable sales pop up to drive more sales.',
		'InstallURI'  => admin_url( "/plugin-install.php?s=storefront+pro+sales+pop&tab=search&type=term" ),
		'AuthorURI'   => 'https://www.pootlepress.com',
		'Author'      => 'Pootlepress',
		'Image'       => "//ps.w.org/storefront-pro-sales-pop/assets/icon-256x256.jpg?ver=" . SFP_VERSION,
		'ActiveClass' => 'Storefront_Pro_Sales_Pop',
	),
	'storefront-pro-live-search' => array(
		'Name'        => 'Storefront Pro Live Search',
		'Description' => 'Instant search products.',
		'InstallURI'  => '',
		'AuthorURI'   => 'https://www.pootlepress.com',
		'Author'      => 'Pootlepress',
		'Image'       => SFP_URL . '/assets/img/live-search.jpg',
		'actions'     => [
			'Rebuild product index' => admin_url(
				'admin-ajax.php?action=sfp-live-search-clear-cache&nonce=' . wp_create_nonce( 'live-search-reload-cache' ) ),
		],
		'ActiveClass' => 'Storefront_Pro_Live_Search',
	),
	'storefront-wishlist'       => array(
		'Name'        => 'Storefront Wishlist',
		'Description' => 'Let your customers save items to wishlist to buy later.',
		'InstallURI'  => admin_url( "/plugin-install.php?s=storefront+wishlist&tab=search&type=term" ),
		'AuthorURI'   => 'https://www.pootlepress.com',
		'Author'      => 'Pootlepress',
		'Image'       => "//ps.w.org/storefront-wishlist/assets/icon-256x256.png?ver=" . SFP_VERSION,
		'ActiveClass' => 'Storefront_Wishlist',
	),
) );

?>
<style>

	/* Addons page */
	.sfp-addon-img a {
		width: 120px;
		height: 120px;
		display: block;
		background-size: cover;
	}

	.sfp-addon-img {
		float: left;
		padding: 20px 7px 7px 20px;
	}

	.sfp-addon-card-wrap {
		-ms-box-sizing: border-box;
		-moz-box-sizing: border-box;
		-webkit-box-sizing: border-box;
		box-sizing: border-box;
		width: 98%;
		margin: 1% 1%;
		min-width: 340px;
		float: left;
		border: 1px solid #ddd;
		background-color: #fff;
	}

	@media only screen and (min-width:1200px) {
		.sfp-addon-card-wrap {
			width: 48%;
		}
	}
	.sfp-addon-card-wrap:after {
		content: close-quote;
		display: block;
		clear: both;
	}

	.sfp-addon-description cite {
		display: block;
		margin: 1em 0;
	}

	.sfp-addon-name h3 {
		margin-top: 0;
	}

	.sfp-addon-name a {
		text-decoration: none;
		line-height: 1.5;
		display: block;
	}

	.sfp-addon-details {
		margin-left: 140px;
		padding: 20px;
		min-height: 124px;
	}

	.sfp-addon-footer {
		padding: 16px;
		background-color: #f5f5f5;
		border-top: 1px solid #ddd;
	}

	.sfp-addon-installed .dashicons-yes, .sfp-addon-installed .dashicons-yes + span {
		color: #0b0;
	}
	.sfp-addon-installed .dashicons-yes {
		line-height: 20px;
		font-size: 35px;
		padding: 0 16px 0 0;
	}

	.sfp-addon-card.active .activate {
		display: none;
	}

	.sfp-addon-card .deactivate {
		display: none;
	}

	.sfp-addon-card.active .deactivate {
		display: inline-block;
		vertical-align: middle;
		margin: 5px 7px 4px 0;
	}

	.sfp-addon-card.active .deactivate.button {
		float: right;
		margin-top: 0;
	}
</style>

<div class="sfp-modules modules-free">
	<h2>Download these plugins to extend the functionality of Storefront Pro</h2>
	<?php
	foreach ( $sfp_modules as $slug => $plugin ) {
		$card_classes = class_exists( $plugin['ActiveClass'] ) ? 'sfp-addon-card active' : 'sfp-addon-card';
		?>
		<div id="<?php echo $slug ?>" class="sfp-addon-card-wrap">
			<div class="<?php echo $card_classes; ?>">
				<div class="sfp-addon-img">
					<a class="thickbox"
					   style="background-image: url(<?php echo $plugin['Image']; ?>);"></a>
				</div>

				<div class="sfp-addon-details">
					<div class="sfp-addon-name">
						<h3><?php echo $plugin['Name']; ?></h3>
					</div>
					<div class="desc sfp-addon-description">
						<p class="sfp-addon-description"><?php echo strip_tags( $plugin['Description'], '<a>' ); ?></p>
						<cite><?php echo "By <a href='$plugin[AuthorURI]'>$plugin[Author]</a>"; ?></cite>
					</div>
				</div>
				<div class="sfp-addon-footer">
					<div class="sfp-addon-controls sfp-addon-installed">
						<?php
						if ( strpos( $card_classes, 'active' ) ) {
							echo '<span class="dashicons dashicons-yes"></span>';
							if ( ! empty ( $plugin['actions'] ) ) {
								foreach( $plugin['actions'] as $label => $link )
								echo "<a href='$link' class='button pootle right'>" . __( $label ) . "</a>";
							}
							_e( 'Active', 'storefront-pro' );
						} else if ( is_dir( WP_PLUGIN_DIR . '/' . $slug ) ) {
							$activate_url = admin_url( "plugins.php" );
							echo "<a href='$activate_url' target='_blank' class='button pootle right'>" . __( 'Activate' ) . "</a>";
						} else {
							echo "<a href='$plugin[InstallURI]' class='button pootle right'>" . __( 'Install' ) . "</a>";
						}
						?>
						<div class="clear"></div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	?>
	<div class="clear"></div>
</div>
