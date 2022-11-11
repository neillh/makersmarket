<?php
/**
 * @developer wpdevelopment.me <shramee@wpdvelopment.me>
 * Plugin Name: Storefront Pro Live Search
 * Plugin URI: http://pootlepress.com/sfp-live-search
 * Description: Search WooCommerce products and categories live.
 * Version: 1.0
 * Author: pootlepress
 * Author URI: http://pootlepress.com
 * License: GPL2
 */
/** WooCommerce Live Search main class */
class Storefront_Pro_Live_Search {

	/** @var Storefront_Pro_Live_Search Instance */
	private static $instance = null;
	/** @var string Text domain */
	private $textdomain = null;

	/** Constructor */
	function __construct() {
		$this->textdomain = 'sfp-live-search';
		add_action( 'widgets_init', array( $this, 'register' ) );
		add_action( 'wp', array( $this, 'init' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ), 99 );
		add_action( 'rest_api_init', array( $this, 'rest_routes' ) );
		add_filter( 'storefront_pro_fields', array( $this, 'fields' ) );
		add_filter( 'storefront_handheld_footer_bar_links', array( $this, 'footer_links' ) );
		add_action( 'post_updated', array( $this, 'maybe_update_cache' ), 10, 3 );
		add_action( 'wp_ajax_sfp-live-search-clear-cache', array( $this, 'ajax_cache_clear' ), 10, 3 );
		//Now handled by REST api
//		add_action( 'wp_ajax_Storefront_Pro_Live_Search', array( $this, 'search' ) );
	}

	public function ajax_cache_clear () {
		$this->cache_all_products();
		$notice = 'Storefront live search produt cache cleared.';
		header( 'Location: ' . admin_url( 'themes.php?page=storefront-pro&tab=modules&notice=' . $notice ) );
		die();
	}

	/** @return Storefront_Pro_Live_Search Instance */
	static function instance() {
		if ( ! Storefront_Pro_Live_Search::$instance ) {
			Storefront_Pro_Live_Search::$instance = new Storefront_Pro_Live_Search();
		}

		return Storefront_Pro_Live_Search::$instance;
	}

	/** Initiate hooks */
	function fields( $fields ) {

		$section = __( 'Live search', SFP_TKN ); // Translated dynamically

		$fields[] = array(
			'id'       => 'show-live-search',
			'label'    => __( 'WooCommerce live search', SFP_TKN ),
			'description' => __( 'Only searches WooCommerce products.', SFP_TKN ),
			'section'  => 'Live search',
			'priority' => 10,
			'type'     => 'select',
			'default'  => '',
			'choices'  => array(
				'' => "Don't use",
				'1'   => 'Replace navigation search',
				'2'   => 'Show in header',
			),
		);
		$fields[] = array(
			'id'       => 'mobile-live-search',
			'label'    => __( 'WooCommerce live search on Mobile', SFP_TKN ),
			'description' => __( 'Show in mobile footer search icon', SFP_TKN ),
			'section'  => 'Live search',
			'priority' => 70,
			'type'     => 'checkbox',
		);
		$fields[] = array(
			'id'      => 'live-search-field-bg-clr',
			'label'   => __( 'Search box background color', SFP_TKN ),
			'section' => 'Live search',
			'type'    => 'color',
		);
		$fields[] = array(
			'id'      => 'live-search-field-text-clr',
			'label'   => __( 'Search box text color', SFP_TKN ),
			'section' => 'Live search',
			'type'    => 'color',
		);
		$fields[] = array(
			'id'      => 'live-search-results-bg-clr',
			'label'   => __( 'Search results background color', SFP_TKN ),
			'section' => 'Live search',
			'type'    => 'color',
		);
		$fields[] = array(
			'id'      => 'live-search-results-text-clr',
			'label'   => __( 'Search results text color', SFP_TKN ),
			'section' => 'Live search',
			'type'    => 'color',
		);
		$fields[] = array(
			'id'      => 'live-search-rounded-corners',
			'label'   => __( 'Search box/results rounded corners', SFP_TKN ),
			'section' => 'Live search',
			'type'    => 'select',
			'choices' => array(
				'0'     => 'Boxy',
				'7px'  => 'Curvaceous',
				'16px' => 'Really Curvaceous',
			),
		);
		return $fields;
	}

	/**
	 * Get Storefront Pro setting
	 * @param string $id
	 * @param null $default
	 *
	 * @return mixed Option value
	 */
	function get( $id, $default = null ) {
		return get_sfp_mod( $id, $default );
	}

	/** Initiate hooks */
	function init() {
		$show = $this->get( 'show-live-search' );
		if ( $show  ) {
			if ( 1 == $show ) {
				add_filter( 'sfp_search_form_html', array( $this, 'replace_storefront_search' ) );
			} else { // 2 == $show
				remove_action( 'storefront_header', 'storefront_product_search', 40 );
				add_filter( 'storefront_header', array( $this, 'header_searchbar' ), 23 );
				add_action( 'storefront_before_content', array( $this, 'after_header_tablet_searchbar' ), 9 );
			}
		}
	}

	/** Returns the search results */
	function rest_routes() {
		register_rest_route( 'sfp-live-search/v1', '/search', array(
			'methods'  => [ 'POST', 'GET' ],
			'callback' => array( $this, 'search' ),
			'permission_callback' => 'return_true',
		) );

	}

	/** Returns the search results */
	function header_searchbar() {
		?>
		<div class="sfp-header-live-search">
			<?php the_widget( 'Storefront_Pro_Live_Search_Widget' ); ?>
		</div>
		<?php
	}

	/** Returns the search results */
	function after_header_tablet_searchbar() {
		?>
		<div class="sfp-tablet-live-search">
			<div class="col-full">
				<?php the_widget( 'Storefront_Pro_Live_Search_Widget' ); ?>
			</div>
		</div>
		<?php
	}

	/** Returns the search results */
	function replace_storefront_search( $html, $args = [] ) {

		ob_start();
		the_widget( 'Storefront_Pro_Live_Search_Widget' );
		$live_search = ob_get_clean();
		if ( $live_search ) {
			return $live_search;
		} else {
			return $html;
		}
	}

	/** Returns the search results */
	function search() {

		$s         = filter_input( INPUT_POST, 's' );
		$s         = ! $s ? filter_input( INPUT_GET, 's' ) : $s;
		$json      = get_transient( "sfp-ls-q-$s" );

		if ( $json ) {
			return $json;
		} else {
			$cats = __( 'Categories', $this->textdomain );
			$prods = __( 'Products', $this->textdomain );
			$json = array(
			);

		}

		global $wpdb;

		$s = explode( ' ', $s );

		$qry = implode( '%" AND post_title LIKE "%', $s );

		$json[ $prods ] = $wpdb->get_results(
			'SELECT guid AS url, post_title AS title, m2.meta_value AS img ' .
			'FROM ' . $wpdb->posts . ' AS post  ' .
			'LEFT JOIN ' . $wpdb->postmeta . ' as m ON post.ID = m.post_id AND m.meta_key = "_thumbnail_id" ' .
			'LEFT JOIN ' . $wpdb->postmeta . ' as m2 ON m.meta_value = m2.post_id AND m2.meta_key = "_wp_attached_file" ' .
			'WHERE post_type = "product" AND post_title LIKE "%' . $qry . '%" LIMIT 25' );

		return $json;
	}

	/**
	 * @param Int $post_id
	 * @param WP_Post $post
	 */
	function maybe_update_cache( $post_id, $post ) {

		if ( ! $this->get( 'show-live-search' ) ) {
			return;
		}

		if ( $post && $post->post_type == 'product' ) {
			$this->cache_all_products();
		}
	}

	function cache_all_products() {

		$all_products = [];

		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => - 1,
			'tax_query'     => array(
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'term_taxonomy_id',
					'terms'    => wc_get_product_visibility_term_ids()['exclude-from-search'],
					'operator' => 'NOT IN',
				)
			)
		);
		$loop = new WP_Query( $args );
		while ( $loop->have_posts() ) : $loop->the_post();
			$prod = new stdClass();
			$prod->ID = get_the_ID();
			$prod->title = get_the_title();
			$prod->img = get_the_post_thumbnail_url( null, 'thumbnail' );
			$prod->url = get_permalink();
			$all_products[] = $prod;
		endwhile;

		update_option( "sfp-ls-all-products", $all_products );

		return $all_products;
	}

	/** Registers the widget */
	function register() {
		register_widget( "Storefront_Pro_Live_Search_Widget" );
	}

	/** Enqueue scripts and styles */
	function register_scripts() {
		wp_register_script( 'wcls-script', plugin_dir_url( __FILE__ ) . '/script.min.js', array( 'jquery' ), SFP_VERSION, 'in_footer' );
		wp_register_style( 'wcls-style', plugin_dir_url( __FILE__ ) . '/style.css' );

		if ( $this->get( 'show-live-search' ) ) {
			Storefront_Pro_Live_Search_Widget::$in_page = true;
			$this->enqueue();
		} else {
			// Check in footer if
			add_action( 'wp_footer', array( $this, 'enqueue' ), 9 );
		}
	}

	/** Enqueue scripts and styles */
	function enqueue() {

		if ( Storefront_Pro_Live_Search_Widget::$in_page ) {

			$products = get_option( "sfp-ls-all-products" );

			if ( ! $products ) {
				$products = $this->cache_all_products();
			}

			$upload_dir = wp_get_upload_dir();
			wp_enqueue_script( 'wcls-script' );
			wp_enqueue_style( 'wcls-style' );
			wp_localize_script( 'wcls-script', 'wclsAjax', array(
//			'url' => admin_url( 'admin-ajax.php' ),
				'url'        => get_rest_url( null, '/sfp-live-search/v1/search' ),
				'upload_dir' => $upload_dir['baseurl'],
				'categories' => __( 'Categories', $this->textdomain ),
				'products'   => __( 'Products', $this->textdomain ),
				'prods'      => $products,
//			'cats' => $categories,
			) );

			wp_add_inline_style( 'wcls-style',
				'.sfp-live-search-container form {' .
				'color:' . $this->get( 'live-search-field-text-clr' ) . ';' .
				'}' .

				'.sfp-live-search-container form * {color:inherit;}' .

				'.sfp-live-search-container ::-webkit-input-placeholder { color: inherit; }' . // Should not be merged
				'.sfp-live-search-container :-moz-placeholder { color: inherit; }' . // Should not be merged
				'.sfp-live-search-container ::-moz-placeholder { color: inherit; }' . // Should not be merged
				'.sfp-live-search-container :-ms-input-placeholder { color: inherit; }' . // Should not be merged

				'.sfp-live-search-container input.search-field.sfp-live-search-field,' .
				'.sfp-live-search-container input.search-field.sfp-live-search-field:focus  {' .
				'background:' . $this->get( 'live-search-field-bg-clr' ) . ';' .
				'color:inherit;' .
				'}' .

				'.sfp-live-search-container input.search-field.sfp-live-search-field,' .
				'.sfp-live-search-container .sfp-live-search-results {' .
				'-webkit-border-radius:' . $this->get( 'live-search-rounded-corners' ) . ';' .
				'border-radius:' . $this->get( 'live-search-rounded-corners' ) . ';' .
				'}' .

				'.sfp-live-search-container .sfp-live-search-results {' .
				'color:' . $this->get( 'live-search-results-text-clr' ) . ';' .
				'background:' . $this->get( 'live-search-results-bg-clr' ) . ';' .
				'}' );
		}
	}

	function footer_links( $links ) {
		$enabled = $this->get( 'mobile-live-search' );
		if ( $enabled ) {
			$links['search']['callback'] = array( $this, 'footer_search' );
		}

		return $links;
	}

	function footer_search() {
		echo '<a href="">' . esc_attr__( 'Search', 'storefront' ) . '</a>';
		?>
		<div class="site-search footer-search">
			<?php the_widget( 'Storefront_Pro_Live_Search_Widget' ); ?>
		</div>
		<?php
	}
}

include 'class-sfp-live-search-widget.php';

Storefront_Pro_Live_Search::instance();
