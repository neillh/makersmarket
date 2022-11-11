<?php

/**
 * Storefront Pro Blocks Admin class
 */
class Storefront_Pro_Blocks_Admin {

	/** @var Storefront_Pro_Blocks_Admin Instance */
	private static $_instance = null;

	/* @var string $token Plugin token */
	public $token;

	/* @var string $url Plugin root dir url */
	public $url;

	/* @var string $path Plugin root dir path */
	public $path;

	/* @var string $version Plugin version */
	public $version;

	/**
	 * Main Storefront Pro Blocks Instance
	 * Ensures only one instance of Storefront_Extension_Boilerplate is loaded or can be loaded.
	 * @return Storefront_Pro_Blocks_Admin instance
	 * @since  1.0.0
	 */
	public static function instance() {
		if ( null == self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	} // End instance()

	/**
	 * Constructor function.
	 * @access  private
	 * @since  1.0.0
	 */
	private function __construct() {
		$this->token   = Storefront_Pro_Blocks::$token;
		$this->url     = Storefront_Pro_Blocks::$url;
		$this->path    = Storefront_Pro_Blocks::$path;
		$this->version = Storefront_Pro_Blocks::$version;
	} // End __construct()

	/**
	 * Adds front end stylesheet and js
	 * @action wp_enqueue_scripts
	 */
	public function enqueue() {
		$token = $this->token;
		$url   = $this->url;

		$is_fse = false;
		if ( Storefront_Pro_Blocks::is_admin_fse() ) {
			$is_fse = true;
		}

		wp_enqueue_style( 'sfp-blocks-front' );
		wp_enqueue_style( 'sfp-blocks-admin' );
		wp_enqueue_script( 'sfp-blocks-admin' );

		Storefront_Pro_Blocks_Public::theme_wc_styles();

		$cats = Storefront_Pro_Blocks_Public::prod_cats();

		$product_categories_data = [];

		foreach ( $cats as $cat ) {
			/** @var WP_Term $cat Category object */
			$product_categories_data[] = [
				'value' => $cat->term_id,
				'label' => $cat->name,
			];
		}

		$prods = Storefront_Pro_Blocks_Public::prods( [
			'max_items' => 999
		] );

		$tags = Storefront_Pro_Blocks_Public::prod_cats( [ 'taxonomy' => 'product_tag' ] );

		$product_tags_data = [];

		foreach ( $tags as $tag ) {
			/** @var WP_Term $tag Category object */
			$product_tags_data[] = [
				'value' => $tag->term_id,
				'label' => $tag->name,
			];
		}

		$products_data = [
			[
				'image' => $url . 'assets/pootle-portrait.png',
				'value' => 'recent-products',
				'label' => 'Recently viewed products',
			]
		];

		foreach ( $prods as $prod ) {
			/** @var WP_Term $cat Category object */
			$image           = get_the_post_thumbnail_url( $prod->ID, 'medium_large' );
			$products_data[] = [
				'image' => $image,
				'value' => $prod->ID,
				'label' => get_the_title( $prod->ID ),
			];
		}

		wp_localize_script( 'sfp-blocks-admin', 'sfpBlocksProps', [
			'tags'      => $product_tags_data,
			'cats'      => $product_categories_data,
			'prods'     => $products_data,
			'tableCols' => Storefront_Pro_Blocks::table_columns(),
			'is_fse'    => $is_fse,
		] );

		Storefront_Pro_Blocks::instance()->public->enqueue();
	}

	/**
	 * Adds request quote email class
	 * @param array $email_classes
	 * @return array
	 */
	public function woocommerce_email_classes( $email_classes = [] ) {
		include 'class-request-quote-email.php';
		$email_classes['SFPBK_Request_Quote_Email'] = new SFPBK_Request_Quote_Email();

		return $email_classes;
	}

	/**
	 * Updates product category redirect url field metadata
	 * @param int $term_id
	 * @param string $tt_id
	 * @param string $taxonomy
	 */
	public function save_product_cat_fields( $term_id, $tt_id = '', $taxonomy = '' ) {
		if ( isset( $_POST['sfpbk_redir_url'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'sfpbk_redir_url', esc_attr( $_POST['sfpbk_redir_url'] ) );
		}
	}

	/**
	 * Adds redirect URL field for add new product category form.
	 */
	public function new_product_cat_url_field() {
		?>
		<div class="form-field term-sfpbk-redir-url-wrap">
			<label for="sfpbk_redir_url"><?php esc_html_e( 'Redirect URL', 'woocommerce' ); ?></label>
			<input id="sfpbk_redir_url" name="sfpbk_redir_url" class="postform" type="url">
			<p>Use this to have your product category page redirect to a custom page.</p>
		</div>
		<?php
	}

	/**
	 * Adds redirect URL field for add edit product category form.
	 * @param WP_Term $term
	 */
	public function product_cat_url_field( $term ) {
		?>
		<tr class="form-field term-display-type-wrap">
			<th scope="row" valign="top">
				<label for="sfpbk_redir_url"><?php esc_html_e( 'Redirect URL', 'woocommerce' ); ?></label>
			</th>
			<td>
				<input id="sfpbk_redir_url" name="sfpbk_redir_url" class="postform" type="url"
							 value="<?php echo get_term_meta( $term->term_id, 'sfpbk_redir_url', true ); ?>">
				<p class="description">Use this to have your product category page redirect to a custom page.</p>
			</td>
		</tr>
		<?php
	}

	public function woocommerce_products_general_settings( $settings ) {
		foreach ( $settings as $key => $setting ) {
			if ( $setting['id'] === 'woocommerce_shop_page_id' ) {
				array_splice( $settings, $key + 1, 0, [
					[
						'title'   => __( 'Custom Shop page redirect', 'sfbk' ),
						'desc'    => __( 'Shop page will redirect to this page (301 redirect).', 'sfbk' ),
						'id'      => 'sfbk_custom_shop_page',
						'type'    => 'single_select_page',
						'default' => '',
						'class'   => 'wc-enhanced-select-nostd',
						'css'     => 'min-width:300px;',
					]
				] );
				break;
			}
		}

		return $settings;
	}

	/**
	 * Returns permission for rest routes
	 * @return bool Permission to access endpoint
	 * @uses is_user_logged_in
	 */
	public function rest_routes_permission() {
		return is_user_logged_in();
	}

	/**
	 * Register REST API endpoints
	 */
	public function rest_api_init() {
		register_rest_route( 'sfp_blocks/v1', '/filter_category', array(
			'methods'             => 'GET',
			'permission_callback' => [ $this, 'rest_routes_permission' ],
			'callback'            => [ $this, 'api_filter_category' ],
		) );

		register_rest_route( 'sfp_blocks/v1', '/category_grid', array(
			'methods'             => 'GET',
			'permission_callback' => [ $this, 'rest_routes_permission' ],
			'callback'            => [ $this, 'api_category_grid' ],
		) );

		register_rest_route( 'sfp_blocks/v1', '/category_square_grid', array(
			'methods'             => 'GET',
			'permission_callback' => [ $this, 'rest_routes_permission' ],
			'callback'            => [ $this, 'api_category_square_grid' ],
		) );

		register_rest_route( 'sfp_blocks/v1', '/products_grid', array(
			'methods'             => 'GET',
			'permission_callback' => [ $this, 'rest_routes_permission' ],
			'callback'            => [ $this, 'api_products_grid' ],
		) );

		register_rest_route( 'sfp_blocks/v1', '/product_cards', array(
			'methods'             => 'GET',
			'permission_callback' => [ $this, 'rest_routes_permission' ],
			'callback'            => [ $this, 'api_product_cards' ],
		) );

		register_rest_route( 'sfp_blocks/v1', '/products_slider', array(
			'methods'             => 'GET',
			'permission_callback' => [ $this, 'rest_routes_permission' ],
			'callback'            => [ $this, 'api_products_slider' ],
		) );

		register_rest_route( 'sfp_blocks/v1', '/products_flip', array(
			'methods'             => 'GET',
			'permission_callback' => [ $this, 'rest_routes_permission' ],
			'callback'            => [ $this, 'api_products_flip' ],
		) );

		register_rest_route( 'sfp_blocks/v1', '/products_carousel', array(
			'methods'             => 'GET',
			'permission_callback' => [ $this, 'rest_routes_permission' ],
			'callback'            => [ $this, 'api_products_carousel' ],
		) );

		register_rest_route( 'sfp_blocks/v1', '/products_wc_grid', array(
			'methods'             => 'GET',
			'permission_callback' => [ $this, 'rest_routes_permission' ],
			'callback'            => [ $this, 'api_products_normal_grid' ],
		) );

		register_rest_route( 'sfp_blocks/v1', '/products_list', array(
			'methods'             => 'GET',
			'permission_callback' => [ $this, 'rest_routes_permission' ],
			'callback'            => [ $this, 'api_products_list' ],
		) );

		register_rest_route( 'sfp_blocks/v1', '/product_hero', array(
			'methods'             => 'GET',
			'permission_callback' => [ $this, 'rest_routes_permission' ],
			'callback'            => [ $this, 'api_product_hero' ],
		) );

		register_rest_route( 'sfp_blocks/v1', '/products_masonry', array(
			'methods'             => 'GET',
			'permission_callback' => [ $this, 'rest_routes_permission' ],
			'callback'            => [ $this, 'api_products_masonry' ],
		) );

		register_rest_route( 'sfp_blocks/v1', '/products_table', array(
			'methods'             => 'GET',
			'permission_callback' => [ $this, 'rest_routes_permission' ],
			'callback'            => [ $this, 'api_products_table' ],
		) );

		register_rest_route( 'sfp_blocks/v1', '/sale_countdown', array(
			'methods'             => 'GET',
			'permission_callback' => [ $this, 'rest_routes_permission' ],
			'callback'            => [ $this, 'api_sale_countdown' ],
		) );


		register_rest_route( 'sfp_blocks/v1', '/archive_title', array(
			'methods'             => 'GET',
			'permission_callback' => [ $this, 'rest_routes_permission' ],
			'callback'            => [ $this, 'api_archive_title' ],
		) );
		register_rest_route( 'sfp_blocks/v1', '/archive_image', array(
			'methods'             => 'GET',
			'permission_callback' => [ $this, 'rest_routes_permission' ],
			'callback'            => [ $this, 'api_archive_image' ],
		) );
		register_rest_route( 'sfp_blocks/v1', '/archive_description', array(
			'methods'             => 'GET',
			'permission_callback' => [ $this, 'rest_routes_permission' ],
			'callback'            => [ $this, 'api_archive_description' ],
		) );

		register_rest_route( 'sfp_blocks/v1', '/breadcrumbs', array(
			'methods'             => 'GET',
			'permission_callback' => [ $this, 'rest_routes_permission' ],
			'callback'            => [ $this, 'api_breadcrumbs' ],
		) );
		register_rest_route( 'sfp_blocks/v1', '/sorting', array(
			'methods'             => 'GET',
			'permission_callback' => [ $this, 'rest_routes_permission' ],
			'callback'            => [ $this, 'api_sorting' ],
		) );
		register_rest_route( 'sfp_blocks/v1', '/results_count', array(
			'methods'             => 'GET',
			'permission_callback' => [ $this, 'rest_routes_permission' ],
			'callback'            => [ $this, 'api_results_count' ],
		) );
		register_rest_route( 'sfp_blocks/v1', '/pagination', array(
			'methods'             => 'GET',
			'permission_callback' => [ $this, 'rest_routes_permission' ],
			'callback'            => [ $this, 'api_pagination' ],
		) );
		register_rest_route( 'sfp_blocks/v1', '/mini-cart', array(
			'methods'             => 'GET',
			'permission_callback' => [ $this, 'rest_routes_permission' ],
			'callback'            => [ $this, 'api_mini_cart' ],
		) );
		register_rest_route( 'sfp_blocks/v1', '/shop_controls', array(
			'methods'             => 'GET',
			'permission_callback' => [ $this, 'rest_routes_permission' ],
			'callback'            => [ $this, 'api_shop_controls' ],
		) );

		register_rest_route( 'sfp_blocks/v1', '/products_sliding_tiles', array(
			'methods'             => 'GET',
			'permission_callback' => [ $this, 'rest_routes_permission' ],
			'callback'            => [ $this, 'api_products_sliding_tiles' ],
		) );

	}

	public function block_categories( $categories ) {
		$categories[] = [
			'slug'  => 'sfp-blocks',
			'title' => __( 'Storefront Blocks', 'sfp-blocks' ),
		];

		return $categories;
	}

	public function api_filter_category() {
		return Storefront_Pro_Blocks::instance()->public->block_filter_category( $_GET );
	}

	public function api_category_grid() {
		return Storefront_Pro_Blocks::instance()->public->block_category_grid( $_GET, 'data' );
	}

	public function api_category_square_grid() {
		return Storefront_Pro_Blocks::instance()->public->block_category_grid( $_GET, 'data' );
	}

	public function api_products_grid() {
		return Storefront_Pro_Blocks::instance()->public->block_products_grid( $_GET );
	}

	public function api_product_cards() {
		return Storefront_Pro_Blocks::instance()->public->block_products_cards( $_GET );
	}

	public function api_products_table() {
		return Storefront_Pro_Blocks::instance()->public->block_products_table( $_GET, 'data' );
	}

	public function api_products_slider() {
		return Storefront_Pro_Blocks::instance()->public->block_products_slides( $_GET );
	}

	public function api_products_flip() {
		return Storefront_Pro_Blocks::instance()->public->block_products_flip( $_GET );
	}

	public function api_products_carousel() {
		return Storefront_Pro_Blocks::instance()->public->block_products_carousel( $_GET );
	}

	public function api_products_normal_grid() {
		return Storefront_Pro_Blocks::instance()->public->block_normal_products_grid( $_GET );
	}

	public function api_products_list() {
		return Storefront_Pro_Blocks::instance()->public->block_products_list( $_GET );
	}

	public function api_product_hero() {
		return Storefront_Pro_Blocks::instance()->public->product_hero( $_GET );
	}

	public function api_products_masonry() {
		return Storefront_Pro_Blocks::instance()->public->block_products_masonry( $_GET );
	}

	public function api_sale_countdown() {
		if ( empty( $_GET['ending'] ) ) {
			return [
				'html' => '<div class="notice">Sale counter end time is required.</div>'
			];
		}

		return [
			'html' => Storefront_Pro_Blocks::instance()->public->sale_countdown( $_GET )
		];
	}

	function admin_menu() {
		add_submenu_page(
			'woocommerce',
			__( 'Storefront Blocks', 'storefront-blocks' ),
			__( 'Storefront Blocks', 'storefront-blocks' ),
			'manage_options',
			'storefront-blocks',
			function () {
				include dirname( __FILE__ ) . '/tpl-settings-page.php';
			}
		);
	}

	public function api_archive_title() {
		return [
			'html' => 'Product Category/Tag title',
		];
	}

	public function api_archive_image() {
		return [
			'html' => '<img src="' . WC()->plugin_url() . '/assets/images/placeholder.png' . '">'
		];
	}

	public function api_archive_description() {
		return [
			'html' => 'Product Category or Tag description over here if set.'
		];
	}

	private function imitate_shop_page() {
		$wc_qry = new WC_Query();
		add_action( 'pre_get_posts', array( $wc_qry, 'pre_get_posts' ) );
		query_posts( [ 'page_id' => wc_get_page_id( 'shop' ) ] );
		$GLOBALS['wp_query']->get_posts();

		$qry = Storefront_Pro_Blocks_Public::prods( [], 'return_query' );

		$products_per_page = apply_filters( 'loop_shop_per_page', wc_get_default_products_per_row() * wc_get_default_product_rows_per_page() );

		wc_setup_loop( [
			'total'        => $qry->found_posts,
			'total_pages'  => $qry->found_posts / $products_per_page,
			'per_page'     => $products_per_page,
			'current_page' => 1,
		] );
	}

	public function api_breadcrumbs() {
		query_posts( [ 'page_id' => wc_get_page_id( 'shop' ) ] );
		$resp = [
			'html' => Storefront_Pro_Blocks_Public::instance()->breadcrumbs( $_GET ),
		];
		wp_reset_postdata();
		return $resp;
	}

	public function api_sorting() {
		$this->imitate_shop_page();
		$resp = [
			'html' => Storefront_Pro_Blocks_Public::instance()->sorting( $_GET ),
		];
		wp_reset_postdata();
		return $resp;
	}

	public function api_results_count() {
		$this->imitate_shop_page();
		$resp = [
			'html' => Storefront_Pro_Blocks_Public::instance()->results_count( $_GET ),
		];
		wp_reset_postdata();
		return $resp;
	}

	public function api_pagination() {
		$this->imitate_shop_page();
		$resp = [
			'html' => Storefront_Pro_Blocks_Public::instance()->pagination( $_GET ),
		];
		wp_reset_postdata();
		return $resp;
	}

	public function api_mini_cart() {
		$this->imitate_shop_page();
		$resp = [
			'html' => Storefront_Pro_Blocks_Public::instance()->mini_cart( $_GET ),
		];
		wp_reset_postdata();
		return $resp;
	}

	public function api_shop_controls() {
		$this->imitate_shop_page();
		$resp = [
			'html' => Storefront_Pro_Blocks_Public::instance()->shop_controls( $_GET ),
		];
		wp_reset_postdata();
		return $resp;
	}

	public function api_products_sliding_tiles() {
		$this->imitate_shop_page();
		$resp = [
			'html' => Storefront_Pro_Blocks_Public::instance()->products_sliding_tiles( $_GET ),
		];
		wp_reset_postdata();
		return $resp;
	}

}