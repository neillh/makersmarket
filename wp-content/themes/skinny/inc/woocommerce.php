<?php
/**
 * WooCommerce Setup
 *
 * @package Skinny\WooCommerce
 */

namespace Skinny\WooCommerce;

use function Skinny\Core\get_script_suffix;
use function Skinny\site_container_class;

/**
 * Set up WooCommerce hooks
 *
 * @return void
 */
function setup() {

	if ( ! skinny_is_woocommerce_activated() ) {

		return;

	}

	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	/**
	 * Remove default WooCommerce wrapper
	 */
	// Remove actions.
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
	remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );
	remove_action( 'wp_footer', 'woocommerce_demo_store' );
	// Remove filters.
	add_filter( 'woocommerce_show_page_title', '__return_false' );
	remove_filter( 'woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories' );

	/**
	 * Add theme based revisions for Woo.
	 */
	add_action( 'woocommerce_before_main_content', $n( 'shop_header' ), 5 );
	add_filter( 'woocommerce_add_to_cart_fragments', $n( 'cart_link_fragment' ) );
	add_filter( 'woocommerce_get_stock_html', '__return_null' );
	add_action( 'wp_enqueue_scripts', $n( 'enqueue_scripts' ) );

	add_action( 'woocommerce_before_main_content', $n( 'wrapper_before_content' ) );
	add_action( 'woocommerce_after_main_content', $n( 'wrapper_after_content' ) );
	add_action( 'woocommerce_shop_loop_subcategory_title', $n( 'template_loop_category_title' ), 10 );
	add_action( 'wp_head', 'woocommerce_demo_store' );
	add_filter( 'woocommerce_sale_flash', $n( 'product_sale_text' ) );

	// Filter through category thumbnails and remove placeholder image.
	add_action( 'woocommerce_before_subcategory', $n( 'before_category_thumbnail' ), 9, 1 );
	add_action( 'woocommerce_after_subcategory', $n( 'after_category_thumbnail' ), 11, 1 );

	// Add separated categories column in WooCommerce loop.
	add_action( 'woocommerce_before_shop_loop', $n( 'show_product_subcategories' ), 20 );

	// Add stock amount at product page meta start.
	add_action( 'woocommerce_product_meta_start', $n( 'show_product_stock' ) );
}

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function enqueue_scripts() {
	$suffix = get_script_suffix();

	wp_enqueue_style( 'skinny-woocommerce-style', get_theme_file_uri( "/assets/css/woocommerce{$suffix}.css" ), array(), filemtime( get_theme_file_path( "/assets/css/woocommerce{$suffix}.css" ) ) );
}

/**
 * Whether or not the WooCommerce cart is enabled.
 *
 * @return bool True when enabled, else false.
 */
function should_show_woo_cart_item() {

	/**
	 * Filter whether to display the WooCommerce cart menu item.
	 * Default: `true`
	 *
	 * @var bool
	 */
	return (bool) apply_filters( 'skinny_wc_show_cart_menu', true );

}

/**
 * Adds a WooCommerce header cart.
 *
 * @return void
 */
function header_cart() {

	if ( ! class_exists( 'WooCommerce' ) || ! should_show_woo_cart_item() ) {

		return;

	}
	?>

	<div id="header__cart" class="header__cart-toggle">
			<?php
			cart_link();

			if ( ! is_cart() ) {
				the_widget(
					'WC_Widget_Cart',
					array(
						'title' => '',
					)
				);
			}
			?>
	</div>
	<?php

}

/**
 *
 * Generate WooCommerce header cart contents.
 *
 * @return void
 */
function cart_link() {

	/**
	 * Filters the cart menu item URL.
	 *
	 * @param string URL to the WooCommerce cart page.
	 */
	$cart_url = (string) apply_filters( 'skinny_menu_cart_url', wc_get_cart_url() );

	/**
	 * Filters the cart menu item alt text.
	 *
	 * @param string Alt text for the cart menu item.
	 */
	$cart_alt_text = (string) apply_filters( 'skinny_menu_cart_alt', __( 'View your shopping cart', 'skinny' ) );

	/**
	 * Filters the cart menu item text.
	 *
	 * @param string Text for the cart menu item.
	 */
	$cart_text = (string) apply_filters(
		'skinny_menu_cart_text',
		sprintf(
			'<p>%1$s</p><span class="item-count">%2$d</span>',
			esc_html__( 'Cart', 'skinny' ),
			wp_kses_data( WC()->cart->get_cart_contents_count() )
		)
	);

	if ( empty( $cart_text ) ) {

		$cart_text = sprintf(
			'%1$d',
			wp_kses_data( WC()->cart->get_cart_contents_count() )
		);

	}
	?>
	<a class="cart-contents" href="<?php echo esc_url( $cart_url ); ?>" title="<?php esc_attr( $cart_alt_text ); ?>">
		<?php echo wp_kses_post( $cart_text ); ?>
	</a>
	<?php
}


/**
 * Cart Fragments
 * Ensure cart contents update when products are added to the cart via AJAX
 *
 * @param array $fragments Fragments to refresh via AJAX.
 * @return array Fragments to refresh via AJAX
 */
function cart_link_fragment( $fragments ) {
	ob_start();
	cart_link();
	$fragments['a.cart-contents'] = ob_get_clean();

	return $fragments;
}

/**
 * Generate a WooCommerce account link
 *
 * @return void
 */
function account_link() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	/**
	 * Filters the account menu item URL.
	 *
	 * @param string URL to the WooCommerce account page.
	 */
	$account_url = (string) apply_filters( 'skinny_menu_account_url', wc_get_account_endpoint_url( 'dashboard' ) );

	/**
	 * Filters the account menu item text.
	 *
	 * @param string Text for the account menu item.
	 */
	$account_text = (string) apply_filters(
		'skinny_menu_account_text',
		sprintf(
			'<p>%1$s</p>',
			esc_html__( 'Account', 'skinny' )
		)
	);

	printf(
		'<a id="header__account-link" href="%1$s" class="header__account-link">%2$s</a>',
		esc_url( $account_url ),
		wp_kses_post( $account_text )
	);
}

/**
 * Add a shop header.
 *
 * @return void
 */
function shop_header() {
	if ( ! is_product() ) {
		// Use the fallback image value from Theme options.
		$image = skinny_get_thememod( 'shop_header_background' );
		$shop_title = ( is_shop() || is_product_taxonomy() ) ? woocommerce_page_title( false ) : get_the_title();

		printf(
			'<div class="shop-header"%1$s><div class="shop-header__cover"></div><div class="shop-header__inner %2$s"><h1 class="shop-title">%3$s</h1></div></div>',
			( $image ) ? ' style="background-image: url(' . esc_url( $image ) . ');" ' : ' ',
			esc_attr( site_container_class() ),
			esc_html( $shop_title )
		);
	}

	return false;
}

/**
 * Before Content
 * Wraps all WooCommerce content in wrappers which match the theme markup
 *
 * @return void
 */
function wrapper_before_content() {
	?>
	<div class="shop__inner <?php echo esc_attr( site_container_class() ); ?>">
		<div id="primary" class="content-area">
	<?php
}


/**
 * After Content
 * Closes the wrapping divs
 *
 * @return void
 */
function wrapper_after_content() {
	?>
		</div><!-- #primary -->
		<?php if ( ( is_shop() || is_post_type_archive( 'product' ) || is_tax( get_object_taxonomies( 'product' ) ) ) && is_active_sidebar( 'shop-area' ) ) : ?>
			<div class="sidebar-shop">
				<?php dynamic_sidebar( 'shop-area' ); ?>
			</div>
		<?php endif; ?>
	</div><!-- .shop__inner -->
	<?php
}


/**
 * Show the subcategory title in the product loop.
 *
 * @param object $category Category object.
 */
function template_loop_category_title( $category ) {
	?>
	<div class="woocommerce-loop-category__meta text-center">
		<h2 class="woocommerce-loop-category__title">
			<?php
			echo esc_html( $category->name );
			?>
		</h2>
		<?php
		if ( $category->count > 0 ) {
			$count_txt = _n( 'product', 'products', $category->count, 'skinny' );
			echo wp_kses_post( apply_filters( 'woocommerce_subcategory_count_html', ' <span class="count">' . esc_html( $category->count ) . " {$count_txt}</span>", $category ) );
		}
		?>
	</div>
	<?php
}

/**
 * Show sale text at products on sale.
 *
 * @return string
 */
function product_sale_text() {

	return '<span class="onsale">' . esc_html__( 'Sale', 'skinny' ) . '</span>';
}


/**
 * WooCommerce mini cart for mobile navigation.
 *
 * @return void
 */
function mobile_cart() {
	?>
	<div class="woo-mobile-cart">
		<div class="cart-contents">
			<span class="icon skinny-icon-cart"></span>
			<span class="count"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() ); ?></span>
			<span class="cart-amount"><?php echo wp_kses_data( WC()->cart->get_cart_total() ); ?></span>
			<a class="cart-link" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View Cart', 'skinny' ); ?>">
				<?php esc_html_e( 'View Cart', 'skinny' ); ?>
			</a>
		</div>
	</div>
	<?php
}

/**
 * Woocommerce account link for mobile navigation.
 *
 * @return void
 */
function mobile_account_link() {
	if ( ! is_customize_preview() && ! skinny_get_thememod( 'account_btn' ) ) {
		return;
	}
	?>
	<a class="account-link" href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>"><span class="icon skinny-icon-account"></span><?php esc_html_e( 'Account', 'skinny' ); ?></a>
	<?php
}

/**
 * Before category thumbnails, removes placeholder image and adds
 * a wrapper div.
 *
 * @param object $category Category object.
 */
function before_category_thumbnail( $category ) {
	if ( ! get_term_meta( $category->term_id, 'thumbnail_id', true ) ) {
		echo '<div class="no-category-image">';
	}
	remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
	add_action( 'woocommerce_before_subcategory_title', __NAMESPACE__ . '\custom_subcategory_thumbnail', 10, 1 );
}

/**
 * After category thumbnails, removes placeholder image and adds
 * a wrapper div.
 *
 * @param object $category Category object.
 */
function after_category_thumbnail( $category ) {
	if ( ! get_term_meta( $category->term_id, 'thumbnail_id', true ) ) {
		echo '</div>';
	}
}

/**
 * Category thumbnail helper.
 *
 * @param object $category Category object.
 */
function custom_subcategory_thumbnail( $category ) {
	$small_thumbnail_size = apply_filters( 'subcategory_archive_thumbnail_size', 'woocommerce_thumbnail' );
	$dimensions           = wc_get_image_size( $small_thumbnail_size );
	$thumbnail_id         = get_term_meta( $category->term_id, 'thumbnail_id', true );

	if ( $thumbnail_id ) {
		$image        = wp_get_attachment_image_src( $thumbnail_id, $small_thumbnail_size );
		$image        = $image[0];
		$image_srcset = function_exists( 'wp_get_attachment_image_srcset' ) ? wp_get_attachment_image_srcset( $thumbnail_id, $small_thumbnail_size ) : false;
		$image_sizes  = function_exists( 'wp_get_attachment_image_sizes' ) ? wp_get_attachment_image_sizes( $thumbnail_id, $small_thumbnail_size ) : false;
	} else {
		return;
	}

	if ( $image ) {
		// Prevent esc_url from breaking spaces in urls for image embeds.
		// Ref: https://core.trac.wordpress.org/ticket/23605.
		$image = str_replace( ' ', '%20', $image );

		// Add responsive image markup if available.
		if ( $image_srcset && $image_sizes ) {
			echo '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" srcset="' . esc_attr( $image_srcset ) . '" sizes="' . esc_attr( $image_sizes ) . '" />';
		} else {
			echo '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" />';
		}
	}
}

/**
 * Shows a separated list for categories/subcategories outside of main shop loop.
 *
 * @return void
 */
function show_product_subcategories() {
	?>
<ul class="products categories columns-<?php echo esc_attr( wc_get_loop_prop( 'columns' ) ); ?>">
	<?php echo wp_kses_post( woocommerce_maybe_show_product_subcategories() ); ?>
</ul>
	<?php
	wc_reset_loop();
}

/**
 * Show stock amount at product page meta start.
 *
 * @return void
 */
function show_product_stock() {
	global $product;
	$product_quantity = $product->get_stock_quantity();

	if ( isset( $product_quantity ) ) {
		echo '<span class="in-stock">' . esc_html( $product_quantity ) . esc_html__( ' in stock', 'woocommerce' ) . '</span>';
	}
}
