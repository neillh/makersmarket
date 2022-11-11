<?php

require 'class-woocommerce-shop.php';

/**
 * Storefront_Pro_WooCommerce Class
 *
 * @class Storefront_Pro_WooCommerce
 * @version  1.0.0
 * @since 1.0.0
 * @package  Storefront_Pro
 */
class Storefront_Pro_WooCommerce extends Storefront_Pro_WooCommerce_Shop {

	public function init() {
		remove_action( 'storefront_header', 'storefront_header_cart', 60 );
		add_filter( 'storefront_loop_columns', array( $this, 'columns' ), 999 );
		add_filter( 'loop_shop_columns', array( $this, 'columns' ), 999 );
		add_action( 'wc_get_template_part', array( $this, 'wc_template' ), 999, 3 );
		add_action( 'woocommerce_locate_template', array( $this, 'wc_locate_template' ), 999, 3 );
	}

	public function init_css() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			$this->css .= '.storefront-pro-active #site-navigation > div { width: 100%; }';
			return;
		}

		if ( $this->get( 'nav-style' ) || ! $this->get( 'show-search-box' ) ) {
			remove_action( 'storefront_header', 'storefront_product_search', 40 );
		} else {
			$this->css .=
				'.sfp-nav-style.woocommerce-active .site-header .site-search{' .
				'width:' . $this->get( 'search-box-size' ) . '}' .
				'.site-header .site-search *{' .
				'color:' . $this->get( 'search-box-text-clr' ) . ';' .
				'}' .

				'.site-search ::-webkit-input-placeholder { color: inherit; }' . // Should not be merged
				'.site-search :-moz-placeholder { color: inherit; }' . // Should not be merged
				'.site-search ::-moz-placeholder { color: inherit; }' . // Should not be merged
				'.site-search :-ms-input-placeholder { color: inherit; }' . // Should not be merged

				'.storefront-pro-active.woocommerce-active .site-header .site-search input{' .
				'background:' . $this->get( 'search-box-bg-clr' ) . ';' .
				'-webkit-border-radius:' . $this->get( 'search-box-bo-rad' ) . ';' .
				'border-radius:' . $this->get( 'search-box-bo-rad' ) . ';' .
				'}';
		}

		//Header cart display
		$header_cart = $this->get( 'header-wc-cart' );
		add_action( 'storefront_pro_in' . $header_cart . '_nav', 'storefront_header_cart' );
		if ( ! empty( $header_cart ) ) {
			add_action( 'storefront_pro_in_nav', 'storefront_header_cart' );
			Storefront_Pro_Public::$desktop_css .= '#site-navigation.main-navigation .site-header-cart { display: none !important; }';
			$this->css                          .= '.storefront-pro-active #site-navigation > div { width: 100%; }';
		}

		$is_product_archive  = is_shop() || is_product_taxonomy();
		$is_checkout_process = is_cart() || is_checkout();

		$this->set_styles( $is_product_archive, $is_checkout_process );
	}

	/**
	 * Enqueue CSS and custom styles.
	 * @since  1.0.0
	 * @return string CSS
	 */
	public function set_styles( $is_product_archive, $is_checkout_process ) {

		//Shop css and hooks
		$this->shop();
		//Messages css
		$this->messages();

		if ( is_product() ) {
			//It's product page!
			$this->its_product();
		} else if ( $is_product_archive ) {
			Storefront_Pro::instance()->public->sfpSettings['shopLayout']     = $this->get( 'wc-shop-layout' );
			Storefront_Pro::instance()->public->sfpSettings['wcQuickView']    = $this->get( 'wc-quick-view' );
			Storefront_Pro::instance()->public->sfpSettings['mobStore']       = $this->get( 'wc-mob-store' );
			Storefront_Pro::instance()->public->sfpSettings['infiniteScroll'] = $this->get( 'wc-infinite-scroll' );
			//It's a product archive maybe a shop
			$this->its_product_archive();
		} else if ( $is_checkout_process ) {
			//Enable distraction free checkout if set
			$this->distraction_free_checkout();
			$hide_breadcrumbs = $this->get( 'hide-wc-breadcrumbs-checkout' );
			$this->remove_breadcrumbs( $hide_breadcrumbs );
		} else {
			//It's checkout process page
			$this->its_non_woocommerce_page();
		}
		//Header cart color
		$this->css .= '.storefront-pro-active .site-header-cart .cart-contents { color: ' . $this->get( 'header-wc-cart-color', '' ) . '; }';
		$this->css .= '.storefront-pro-active .site-header-cart .widget_shopping_cart *:not(.button) { color: ' . $this->get( 'header-wc-cart-dd-color', '#000000' ) . '; }';

		return $this->css;
	}

	/**
	 * Add CSS in <head> for styles handled by the Customizer
	 *
	 * @since 1.0.0
	 */
	public function messages() {
		$success_bg_clr  = $this->get( 'wc-success-bg-color', '#0f834d' );
		$success_txt_clr = $this->get( 'wc-success-text-color', '#ffffff' );
		$message_bg_clr  = $this->get( 'wc-info-bg-color', '#3D9CD2' );
		$message_txt_clr = $this->get( 'wc-info-text-color', '#ffffff' );
		$error_bg_clr    = $this->get( 'wc-error-bg-color', '#e2401c' );
		$error_txt_clr   = $this->get( 'wc-error-text-color', '#ffffff' );

		$this->css .=
			//Success message colors
			".woocommerce-message { background-color:{$success_bg_clr} !important; color:{$success_txt_clr} !important;}" .
			".woocommerce-message * { color:{$success_txt_clr} !important; }" .
			//Info message colors
			".woocommerce-info { background-color:{$message_bg_clr} !important; color:{$message_txt_clr} !important;}" .
			".woocommerce-info * { color:{$message_txt_clr} !important;}" .
			//Error message colors
			".woocommerce-error { background-color:{$error_bg_clr} !important; color:{$error_txt_clr} !important; }" .
			".woocommerce-error * { color:{$error_txt_clr} !important; }";
	}

	/**
	 * Shop Layout
	 * Tweaks the WooCommerce layout based on settings
	 */
	public function its_product() {

		$hide_breadcrumbs = $this->get( 'hide-wc-breadcrumbs-product' );
		$this->remove_breadcrumbs( $hide_breadcrumbs );
		if ( 'hrzntl-tabs' == $this->get( 'wc-product-tabs-layout', '' ) ) {
			$this->css .= '.storefront-pro-active .woocommerce-tabs .panel.wc-tab,.storefront-full-width-content .woocommerce-tabs ul.tabs.wc-tabs {width: 100%;padding: 0 1em;}';
			$this->css .= '.storefront-pro-active .woocommerce-tabs ul.tabs.wc-tabs {border-bottom: 1px solid rgba(0,0,0,.05);width: auto;float: none;margin-right: 0;padding-left: 1em;}';
			$this->css .= '.storefront-pro-active .woocommerce-tabs ul.tabs.wc-tabs li {display: inline-block;margin-right: 2em;border: 0;}';
			$this->css .= '.storefront-pro-active .woocommerce-tabs ul.tabs.wc-tabs li:after {display: none;}';
		}

		if ( 'full' == $this->get( 'wc-product-layout', '' ) || $this->get( 'wc-product-style', '' ) ) {
			remove_action( 'storefront_sidebar', 'storefront_get_sidebar' );
			$this->css .= '.storefront-pro-active .content-area{ width: 100%; margin: auto; }';
		}
		if ( 'full' == $this->get( 'wc-prod-share-icons-labels' ) ) {
			$this->css .= '.storefront-product-sharing ul li a span { display: none; }';
		}
		$this->css .= '.storefront-product-sharing ul li a:before, .storefront-product-sharing ul li a{ color: ' . $this->get( 'wc-prod-share-icons-color' ) . '!important; }';

		if ( ! $this->get( 'wc-product-tabs', true ) ) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
			remove_action( 'woocommerce_after_single_product_summary', array( $this, 'product_accordion' ), 10 );
		}

		if ( ! $this->get( 'wc-product-meta', true ) ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		}

		if ( ! $this->get( 'wc-rel-product', true ) ) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
		}

		$this->css .=
			'span.onsale{';

		if ( $this->get( 'wc-prod-sale-alignment' ) ) {
			$this->css .= 'position:absolute;top:0;' . $this->get( 'wc-prod-sale-alignment' ) . ':0;';
		}
		if ( $this->get( 'wc-prod-sale-style' ) ) {
			$sale_style = array(
				'circle'  => 'height:52px;width:52px !important;line-height:50px;padding:0;-webkit-border-radius: 50%;border-radius: 50%;',
				'slant-l' => '-webkit-transform: rotate(-45deg);transform: rotate(-45deg);margin:16px 3px;',
				'slant-r' => '-webkit-transform: rotate(45deg);transform: rotate(45deg);margin:16px 3px;',
			);

			$this->css .= ! empty( $sale_style[ $this->get( 'wc-prod-sale-style' ) ] ) ? $sale_style[ $this->get( 'wc-prod-sale-style' ) ] : '';
		}

		$this->css .=
			'color:' . $this->get( 'wc-prod-sale-text-color' ) . ';' .
			'background-color:' . $this->get( 'wc-prod-sale-bg-color' ) . ';' .
			'border-color:' . $this->get( 'wc-prod-sale-border-color' ) . ';}';
	}

	private function distraction_free_checkout() {
		$css = &$this->css;
		if ( $this->get( 'wc-co-distraction-free' ) ) {
			remove_all_actions( 'storefront_header' );
			remove_all_actions( 'storefront_footer' );
			remove_action( 'storefront_sidebar', 'storefront_get_sidebar' );
			$css .= 'body.woocommerce-cart #primary{ width: 100%; }';
			$css .= 'body.woocommerce-cart, .storefront-pro-active.right-sidebar .content-area{ width: auto; margin: auto; }';
			$css .= '.secondary-navigation, .site-header, .site-footer { display: none; } ';
		}
	}

	protected function its_non_woocommerce_page() {
		//Remove breadcrumbs on archives
		$this->remove_breadcrumbs( ( is_archive() || is_home() ) && $this->get( 'hide-wc-breadcrumbs-archives' ) );
		//Remove breadcrumbs on posts
		$this->remove_breadcrumbs( is_singular( 'post' ) && $this->get( 'hide-wc-breadcrumbs-posts', true ) );
		//Remove breadcrumbs on pages
		$this->remove_breadcrumbs( is_page() && $this->get( 'hide-wc-breadcrumbs-pages' ) );
	}

	/**
	 * Specifies the number of columns for products on the shop page
	 *
	 * @param int $cols Columns
	 *
	 * @return int Columns
	 * @filter storefront_loop_columns
	 * @since 1.0.0
	 */
	public function columns( $cols ) {
		$columns = $this->get( 'wc-shop-columns', 3 );
		if ( $columns ) {
			return $columns;
		} else {
			return $cols;
		}
	}

	/**
	 * Enqueue CSS and custom styles.
	 * @since  1.0.0
	 * @return string CSS
	 */
	public function styles() {
		return $this->css;
	}

	/**
	 * Add custom product template
	 * @since  1.0.0
	 * @return string CSS
	 */
	public function wc_locate_template( $file, $name ) {
		if ( 'single-product/tabs/tabs.php' == $name ) {
			if ( 'accordion' == $this->get( 'wc-product-tabs-layout', '' ) ) {
				return dirname( __FILE__ ) . '/template/accordion-tabs.php';
			}
		}

		return $file;
	}

	/**
	 * Add custom product template
	 * @since  1.0.0
	 * @return string CSS
	 */
	public function wc_template( $file, $template, $name ) {
		global $sfp_template, $sfp_layout;
		$sfp_template = $file;
		if ( 'content-single-product' == "$template-$name" ) {
			$sfp_layout = $this->get( 'wc-product-style' );
			if ( str_replace( 'full', '', $sfp_layout ) ) {
				return dirname( __FILE__ ) . '/template/product.php';
			}
		} elseif ( ( is_shop() || is_product_taxonomy() ) && 'content-product' == "$template-$name" ) {
			$layout = $this->get( 'wc-shop-layout' );
			$new_file = dirname( __FILE__ ) . '/template/shop-' . $layout . '.php';
			$new_file = apply_filters( 'storefront_pro_shop_template', $new_file, $layout );
			if ( file_exists( $new_file ) ) {
				return $new_file;
			}
		}

		return $file;
	}

	/**
	 * Product data accordion
	 */
	public function product_accordion() {
		include dirname( __FILE__ ) . '/template/accordion-tabs.php';
	}
} // End class
