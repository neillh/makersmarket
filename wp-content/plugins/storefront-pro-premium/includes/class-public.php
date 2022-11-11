<?php
/**
 * Created by PhpStorm.
 * User: Shramee Srivastav <shramee.srivastav@gmail.com>
 * Date: 27/4/15
 * Time: 5:36 PM
 */


/**
 * Storefront_Pro_Public Class
 *
 * @class Storefront_Pro_Public
 * @version	1.0.0
 * @since 1.0.0
 * @package	Storefront_Pro
 */
final class Storefront_Pro_Public extends Storefront_Pro_Public_Templates {

	public static $desktop_css = '';

	public static $mobile_css = '';


	/** @var Storefront_Pro_Header_Nav Instance */
	public $header_nav_styles;

	/** @var Storefront_Pro_Content_Styles Instance */
	public $content_styles;

	/** @var Storefront_Pro_WooCommerce Instance */
	public $woocommerce_styles;

	/** @var Storefront_Pro_Footer_Styles Instance */
	public $footer_styles;

	protected $header;

	/** @var array Storefront pro js settings for localization */
	public $sfpSettings = array(
		'shopLayout'     => '',
		'wcQuickView'    => '',
		'mobStore'       => '',
		'infiniteScroll' => '',
	);

	/**
	 * Called by parent::__construct
	 * Do initialization here
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function init(){

		$this->init_templates();
		$this->woocommerce_styles = new Storefront_Pro_WooCommerce( $this->token, $this->plugin_path, $this->plugin_url );

		//Enqueue scripts and styles
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts_styles' ), 999 );
		//Add plugin classes to body
		add_filter( 'body_class', array( $this, 'body_class' ) );
		//exclude/include products in search
		add_filter( 'pre_get_posts', array( $this, 'pre_get_posts' ), 999 );
		//Products per page
		add_filter( 'loop_shop_per_page', array( $this, 'products_per_page' ), 999 );
		add_filter( 'pootlepb_render', array( $this, 'page_builder_styles' ) );
		add_filter( 'siteorigin_panels_render', array( $this, 'page_builder_styles' ) );
	}

	/**
	 * Enqueue CSS and custom styles.
	 * @since   1.0.0
	 * @return  void
	 */
	public function scripts_styles() {

		wp_register_script( 'flexslider', 'https://cdnjs.cloudflare.com/ajax/libs/flexslider/2.6.4/jquery.flexslider-min.js', array( 'jquery' ) );
		wp_register_style( 'sfp-flexslider-styles', $this->plugin_url . '/assets/css/flexslider.css' );

		wp_enqueue_style( 'sfp-styles', $this->plugin_url . '/assets/css/style.css', array(), SFP_VERSION );
		wp_dequeue_script( 'storefront-navigation' );
		wp_enqueue_script( 'sfp-skrollr', '//cdnjs.cloudflare.com/ajax/libs/skrollr/0.6.30/skrollr.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'sfp-script', $this->plugin_url . '/assets/js/public.js', array( 'sfp-skrollr' ), SFP_VERSION, true );
		wp_enqueue_script( 'jquery-masonry' );
//		wp_deregister_style( 'storefront-icons' );
		wp_deregister_style( 'fontawesome' );

		if ( ! $this->get( 'lazy-load-fa5' ) ) {
			wp_enqueue_style( 'fontawesome', '//use.fontawesome.com/releases/v5.5.0/css/all.css', array(), '5.5.0' );
		} else {
			add_action( 'wp_footer', function () {
				?>
				<script>
					setTimeout( function() {
						var css = document.createElement('link');
						css.href = '//use.fontawesome.com/releases/v5.5.0/css/all.css';
						css.rel = 'stylesheet';
						css.type = 'text/css';
						document.getElementsByTagName('body')[0].appendChild(css);
					}, 50 );
				</script>
				<?php
			} );
		}

		wp_localize_script( 'sfp-script', 'sfpPublicL10n', [
			'loading' => __( 'Loading', SFP_TKN ),
			'more' => __( 'More', SFP_TKN ),
		] );

		$this->sfpSettings['i18n'] = [
			'expand' => __( 'Expand', SFP_TKN ),
			'collapse' => __( 'Collapse', SFP_TKN ),
		];

		$this->header_nav_styles = new Storefront_Pro_Header_Nav( $this->token, $this->plugin_path, $this->plugin_url );
		$this->content_styles = new Storefront_Pro_Content_Styles( $this->token, $this->plugin_path, $this->plugin_url );
		$this->woocommerce_styles->init_css();
		$this->footer_styles = new Storefront_Pro_Footer_Styles( $this->token, $this->plugin_path, $this->plugin_url );

		$this->features();

		$css = "/*-----STOREFRONT PRO-----*/";

		$css .= $this->header_nav_styles->styles();

		$css .= $this->content_styles->styles();

		$css .= $this->woocommerce_styles->styles();

		$css .= $this->footer_styles->styles();

		$css .= '@media only screen and (min-width: 768px) {';

		$css .= self::$desktop_css;

		$css .= '}';

		$css .= '@media only screen and (max-width: 768px) {/* Mobile styles */';

		$css .= self::$mobile_css;

		$css .= '}';

		if ( function_exists( 'et_pb_is_pagebuilder_used' ) && et_pb_is_pagebuilder_used( get_the_ID() ) ) {
			$css .= '#content > .col-full { padding:0;max-width:none;margin:0; }';
			$css .= strip_tags( $this->page_builder_styles() );
		} else if ( $this->get( 'flush-content-with-header' ) ) {
			$css .= strip_tags( $this->page_builder_styles() );
		}

		wp_add_inline_style( 'sfp-styles', $css );

		$fonts_options = get_option( 'sf-pro-google-fonts', array() );
		$load_fonts = array();

		foreach ( $fonts_options as $option ) {
			$font = get_theme_mod( $option );
			if ( ! empty( $font ) && false === strpos( $font, 'serif' ) ) {
				$load_fonts[] = $font;
			}
		}
		if ( $load_fonts ) {
			wp_enqueue_style( 'sfp-google-fonts', '//fonts.googleapis.com/css?family=' . join( '%7C', $load_fonts ) );
		}

		wp_localize_script( 'sfp-script', 'sfpSettings', $this->sfpSettings );
	}

	public function features() {

		new SFP_Add_Nav_Icons();

		remove_action( 'storefront_loop_post', 'storefront_post_content', 30 );

		add_filter( 'excerpt_length', array( $this->content_styles, 'excerpt_length' ) );
		add_filter( 'excerpt_more', array( $this->content_styles, 'excerpt_more' ) );

		add_action( 'storefront_loop_post', array( $this->content_styles, 'content' ), 30 );

		if ( $this->get( 'header-sticky' ) ) {
			wp_enqueue_script( 'sfp-sticky-header', $this->plugin_url . '/assets/js/sticky-header.js', array( 'jquery' ), SFP_VERSION );
		}

		if ( ! isset( $_GET['ppbLiveEditor'] ) && $this->get( 'header-over-content' ) ) {
			$color = $this->get( 'header-over-content-color' );
			$headerOverContent =
				".storefront-pro-active #masthead {left:0;right:0;-webkit-transition:all 0.5s !important;transition:all 0.5s !important;border: none;}" .
				".storefront-pro-active #masthead:not(.sticky) {position: absolute;background:$color;}" .
				'.storefront-pro-active nav.secondary-navigation {margin-bottom:0 !important;}';
			self::$desktop_css .= $headerOverContent;
			self::$mobile_css .= $headerOverContent;
		}

		if ( 'full' == get_theme_mod( 'storefront_layout' ) ) {
			remove_action( 'storefront_sidebar', 'storefront_get_sidebar' );
		}

		// Infinite scroll
		if ( $this->get( 'wc-infinite-scroll' ) ) {
			add_action( 'woocommerce_before_shop_loop', array( $this, 'infinite_scroll_wrapper' ), 7 );
			add_action( 'woocommerce_after_shop_loop', array( $this, 'infinite_scroll_wrapper_close' ), 50 );
			self::$desktop_css .= '.jscroll-inner .woocommerce-pagination { display:none; }';
			wp_enqueue_script( 'jscroll', plugins_url( '/../assets/js/jquery.jscroll.min.js', __FILE__ ), array( 'jquery' ), SFP_VERSION );
		}

		remove_action( 'storefront_header', 'storefront_secondary_navigation', 30 );
		add_action( 'storefront_before_header', array( $this->header_nav_styles, 'secondary_navigation' ) );

	}

	/**
	 * Adds pb page styles to pb html
	 * @param string $html page builder HTML
	 * @return string pb html with pb page styles
	 * @filter pootlepb_render
	 */
	function page_builder_styles( $html = '' ) {
		return
			'
	<style>
		.home.blog .site-header, .home.post-type-archive-product .site-header,
		.home.page:not(.page-template-template-homepage) .site-header, .storefront-pro-active .site-header,
		.storefront-pro-active .storefront-breadcrumb, .storefront-pro-active .no-wc-breadcrumb .site-header { margin-bottom: 0; }
		.storefront-pro-active .storefront-breadcrumb { display: none; }
		.storefront-pro-active .hentry .entry-header { display: none; }
		.storefront-pro-active #secondary { margin-top: 4.236em; }
		.storefront-pro-active .page.hentry { margin: 0; padding: 0; border: none; }
		.storefront-pro-active .site-main,
		.sfp-nav-styleleft-vertical #content,
		.storefront-pro-active .content-area {margin: 0;}
	</style>' . $html;
	}

	/**
	 * Specifies the number of products on the shop page
	 * @param int $num Number of products per page
	 * @return int Number of products per page
	 * @filter storefront_products_per_page
	 * @since 1.0.0
	 */
	public function products_per_page( $num ) {
		$per_page = $this->get( 'wc-shop-products' );

		if ( $per_page ) {
			return $per_page;
		} else {
			return $num;
		}
	}

	/**
	 * Removes or adds products CPT in search
	 * @param WP_Query $query
	 */
	public function pre_get_posts( $query ) {
		if ( $query->is_main_query() ) {
			global $sfp_blog_grid;
			if ( $query->is_search && ! empty( $_GET['post_type'] ) ) {
				$post_types = $_GET['post_type'];
				$query->set( 'post_type', $post_types );
			}

			$post_archive = $query->is_category() || $query->is_tag() || $query->is_home();
			if ( $post_archive ) {
				$sfp_blog_grid = explode( ',', $this->get( 'blog-grid', '3,4' ) );
				$per_page      = array_product( $sfp_blog_grid );
				if ( $this->get( 'blog-layout' ) && $per_page ) {
					$query->set( 'posts_per_page', $per_page );
				}
			}
		}
	}

	/**
	 * Storefront Pro Body Class
	 * Adds a class based on the extension name and any relevant settings.
	 */
	public function body_class( $classes ) {
		$classes[] = 'layout-' . filter_input( INPUT_GET, 'layout' );
		$classes[] = 'storefront-pro-active';
		$classes[] = 'sfp-nav-style' . $this->get( 'nav-style' );
		$classes[] = 'sfp-shop-layout' . $this->get( 'wc-shop-layout' );
		if ( $this->get( 'header-sticky' ) && $this->get( 'header-hide-until-scroll' ) ) {
			$classes[] = 'header-hide-until-scroll';
		}

		if ( function_exists( 'is_shop' ) && is_shop() ) {
			if ( $this->get( 'wc-mob-store' ) && $this->get( 'wc-mob-store-layout' ) ) {
				$classes[] = $this->get( 'wc-mob-store-layout' );
			}
			if ( $this->get( 'wc-mob-dont-hide-breadcrumbs' ) ) {
				$classes[] = 'wc-mob-dont-hide-breadcrumbs';
			}
		}

		return $classes;
	}

	/**
	 * Infinite scroll wrapper
	 * @return void
	 */
	function infinite_scroll_wrapper() {
		echo '<div class="scroll-wrap">';
	}

	/**
	 * Infinite scroll wrapper close
	 * @return void
	 */
	function infinite_scroll_wrapper_close() {
		echo '</div>';
	}
} // End class
