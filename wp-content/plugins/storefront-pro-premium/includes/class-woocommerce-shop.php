<?php
/**
 * Storefront_Pro_WooCommerce_Shop Class
 *
 * @class Storefront_Pro_WooCommerce_Shop
 * @version	1.0.0
 * @since 1.0.0
 * @package	Storefront_Pro
 */
class Storefront_Pro_WooCommerce_Shop extends Storefront_Pro_Abstract {

	protected $css = "\n/* WooCommerce Pages */";

	/** @var  Tracks if header is done for product archive */
	public static $shop_table_header_done;

	protected function its_product_archive() {
		if ( $this->get( 'wc-shop-columns', 3 ) ) {
			add_action( 'woocommerce_before_shop_loop', array( $this, 'product_loop_wrap' ), 40 );
			add_action( 'woocommerce_after_shop_loop', array( $this, 'product_loop_wrap_close' ), 5 );
		}

		//Product quick view
		if ( $this->get( 'wc-quick-view' ) ) {
			remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
			add_action( 'woocommerce_before_shop_loop_item', array( $this, 'quick_view_product_link' ), 10 );
		}

		//Shop layout
		if ( $shop_layout = $this->get( 'wc-shop-layout', '' ) ) {
			$method = 'shop_layout_' . $shop_layout;
			if ( method_exists( $this, $method ) ) {
				$this->$method();
			}
			if ( $this->get( 'wc-shop-sidebar' ) ) {
				if ( ! has_action( 'storefront_sidebar', 'storefront_get_sidebar' ) ) {
					add_action( 'storefront_sidebar', 'storefront_get_sidebar' );
				}
			} else {
				remove_action( 'storefront_sidebar', 'storefront_get_sidebar' );
				$this->css .= '.storefront-pro-active #primary.content-area{ width: 100%; margin: auto; }';
			}
		}

		if ( $this->get( 'wc-mob-store' ) ) {
			add_action( 'woocommerce_before_shop_loop', array( $this, 'mob_store_view' ), 7 );
			wp_enqueue_style( 'sfp-mob-store', $this->plugin_url . '/assets/css/mob-store.css', array(), SFP_VERSION );
		}

		//Shop breadcrumbs
		$hide_breadcrumbs = $this->get( 'hide-wc-breadcrumbs-wc' );
		$this->remove_breadcrumbs( $hide_breadcrumbs );
	}

	public function quick_view_product_link() {
		/** @var WC_Product */
		global $product;

		$product_data = array(
			'image'		=> htmlentities( woocommerce_get_product_thumbnail(), ENT_QUOTES ),
			'title'		=> htmlentities( get_the_title(), ENT_QUOTES ),
			'price'		=> htmlentities( '<p class="price">' . $product->get_price_html() . '</p>', ENT_QUOTES ),
			'excerpt'	=> htmlentities( $product->get_short_description(), ENT_QUOTES ),
		);

		ob_start();
		do_action( 'woocommerce_' . $product->get_type() . '_add_to_cart' );
		$product_data['a2c'] = htmlentities( ob_get_clean(), ENT_QUOTES );
		$data = '';
		foreach( $product_data as $attr => $val ) {
			$data .= " data-pqv-$attr='$val'";
		}
		echo "<a href='" . get_the_permalink() . "'$data class='woocommerce-LoopProduct-link sfp-quick-view'><span class='sfp-quick-view-label'>" . __( 'Quick View', SFP_TKN ) . '</span>';
	}

	/**
	 * Outputs the buttons for switching product layout
	 * @since 2.0.0
	 */
	public function mob_store_view() {
		?>
		<div class="layout-buttons">
			<i class="fas fa-th-large layout-masonry"></i>
			<i class="fas fa-th-list layout-list"></i>
		</div>
		<?php
	}

	/**
	 * Shop Layout
	 * Tweaks the WooCommerce layout based on settings
	 */
	public function shop() {

		//Shop alignment
		$this->css .=
			'.storefront-pro-active ul.products li.product { ' .
			'text-align: ' . $this->get( 'wc-shop-alignment', 'center' ) . '; }';

		//Shop show results count
		if ( ! $this->get( 'wc-display-product-results-count', true ) ) {
			remove_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 20 );
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		}

		//Shop show results count
		if ( $this->get( 'wc-hide-cat-prod-count' ) ) {
			add_filter( 'woocommerce_subcategory_count_html', '__return_false', 20 );
		}

		//Shop show product sorting
		if ( ! $this->get( 'wc-display-product-sorting', true ) ) {
			remove_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 );
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10 );
		}

		$this->shop_products_elements();
	}

	protected function shop_products_elements() {
		//Show product image
		if ( ! $this->get( 'wc-display-product-image', true ) ) {
			remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
		}

		//Show product title
		if ( ! $this->get( 'wc-display-product-title', true ) ) {
			remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
		}

		//Show product rating
		if ( ! $this->get( 'wc-display-rating', true ) ) {
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
		}

		$this->shop_products_sale_elements();
	}

	protected function shop_products_sale_elements() {
		//Show product sale flash
		if ( ! $this->get( 'wc-display-sale-flash', true ) ) {
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 6 );
		}

		//Show product price
		if ( ! $this->get( 'wc-display-price', true ) ) {
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
		}

		//Show product add to cart button
		if ( ! $this->get( 'wc-display-add-to-cart', true ) ) {
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		}
	}

	/**
	 * Removes woocommerce breadcrumbs
	 * @param bool $remove Whether or not to hide the breadcrumbs
	 */
	public function remove_breadcrumbs( $remove = true ) {

		if ( $remove ) {
			remove_action( 'storefront_before_content', 'woocommerce_breadcrumb', 10 );
			$this->css .= '.site-header { margin-bottom: 4.236em; }';
		}
	}

	/**
	 * Product loop wrap
	 * @return void
	 */
	public function product_loop_wrap() {
		$columns = $this->get( 'wc-shop-columns', 3 );
		echo '<div class="columns-' . $columns . '">';
	}

	/**
	 * Product loop wrap
	 * @return void
	 */
	public function product_loop_wrap_close() {
		echo '</div>';
	}
} // End class