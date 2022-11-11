<?php

/**
 * WooBuilder blocks Admin class
 */
class WooBuilder_Blocks_Admin {

	/** @var WooBuilder_Blocks_Admin Instance */
	private static $_instance = null;

	/* @var string $token Plugin token */
	public $token;

	/* @var string $url Plugin root dir url */
	public $url;

	/* @var string $path Plugin root dir path */
	public $path;

	/* @var string $version Plugin version */
	public $version;

	/** @var WP_Post Temp for restoring post after REST API preload dispatches  */
	private $last_post;

	/**
	 * Constructor function.
	 * @access  private
	 * @since  1.0.0
	 */
	private function __construct() {
		$this->token   = WooBuilder_Blocks::$token;
		$this->url     = WooBuilder_Blocks::$url;
		$this->path    = WooBuilder_Blocks::$path;
		$this->version = WooBuilder_Blocks::$version;
	} // End __construct()

	/**
	 * Main WooBuilder blocks Instance
	 * Ensures only one instance of Storefront_Extension_Boilerplate is loaded or can be loaded.
	 * @return WooBuilder_Blocks_Admin instance
	 * @since  1.0.0
	 */
	public static function instance() {
		if ( null == self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	} // End instance()

	/**
	 * Saves a copy of current post for using later
	 * @uses self::$last_post
	 */
	function save_global_post() {
		global $post;
		if ( $post && WooBuilder_Blocks::enabled( $post->ID ) ) {
			$this->last_post = $post;
		}
	} // End save_global_post()

	/**
	 * Restores the original global post from saved copy.
	 * @uses self::$last_post
	 */
	function restore_global_post() {
		global $post;
		if( $this->last_post ) {
			$post = $this->last_post;
			$this->last_post = null; // Clear temp var
		}
	} // End restore_global_post()

	protected function get_product_options_data() {
		$prods = get_posts( [
			'post_type'      => 'product',
			'numberposts'    => 99,
			'posts_per_page' => 99,
			'orderby'        => 'title',
			'order'          => 'ASC'
		] );

		$products_data = [];

		foreach ( $prods as $prod ) {
			/** @var WP_Term $cat Category object */
			$image = get_the_post_thumbnail_url( $prod->ID, 'thumbnail' );
			$image_large = get_the_post_thumbnail_url( $prod->ID, 'medium_large' );
//			$products_data[] = [
//				'image' => $image,
//				'value' => $prod->ID,
//				'label' => get_the_title( $prod->ID ),
//			];
			$products_data[ $prod->ID ] = [ get_the_title( $prod->ID ), $image, $image_large, ];
		}

		return $products_data;
	}

	/**
	 * Adds front end stylesheet and js
	 * @action wp_enqueue_scripts
	 */
	public function enqueue() {
		/** @var $post WP_Post */
		global $post;

		$products_data = $this->get_product_options_data();

		$is_fse = false;
		if ( WooBuilder_Blocks_FSE::is_admin_fse() ) {
			$is_fse = true;
			$product_id = array_keys( $products_data )[0];
			$is_woobk_type = true;
			$type = 'template';
		} else {
			$type = $post->post_type;
			$product_id = $post->ID;
			$is_woobk_type = WooBuilder_Blocks::is_type( $post->post_type );
		}


		$token = $this->token;
		$url   = $this->url;
		$ver   = $this->version;

		wp_enqueue_style( $token . '-css', $url . '/assets/admin.css', null, $ver );

		wp_enqueue_script( $token . '-utils', $url . '/assets/utils.js', [ 'jquery' ], $ver );

		wp_enqueue_script( $token . '-js', $url . '/assets/admin.js', [ 'caxton', 'jquery' ], $ver );

		if ( $is_woobk_type ) {
			wp_enqueue_script( $token . '-meta-js', $url . '/assets/admin-meta.js', [ 'caxton', 'jquery' ], $ver );
		}

		$pages = get_pages();
		$pages_options = [];

		/** @var WP_Post $page */
		foreach( $pages as $page ) {
			$pages_options[] = [
				'value' => $page->ID,
				'label' => $page->post_title,
			];
		}


		wp_localize_script(
			$token . '-js',
			'woobuilderData',
			apply_filters( 'woobuilder_js_vars', [
				'meta_keys'                => WooBuilder_Blocks::meta_keys(),
				'prods'                    => $products_data,
				'post'                     => $product_id,
				'post_type'                => $type,
				'is_my_type'               => $is_woobk_type,
				'thumbnail'                => get_the_post_thumbnail_url( $product_id, 'large' ),
				'switchToDefaultEditorUrl' => add_query_arg( 'toggle-woobuilder', 'false' ),
				'assets_url'               => $url . '/assets/',
				'img_url'                  => $url . '/assets/img/',
				'pages'                    => $pages_options,
				'is_full_site_editing'     => $is_fse
			] )
		);
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

	public function permission_callback() {
		return is_user_logged_in();
	}

	function rest_api_init() {

		$routes = WooBuilder_Blocks::blocks();

		foreach ( $routes as $route ) {
			$callback = [ $this, "api_$route" ];
			if ( ! is_callable( $callback ) ) {
				$callback = function () use ( $route ) {
					return $this->handle_product_endpoint( $route );
				};
			}

			register_rest_route( 'woobuilder_blocks/v1', "/$route", array(
				'permission_callback' => [ $this, 'permission_callback' ],
				'methods'  => 'GET',
				'callback' => $callback,
			) );
		}
	}

	private function handle_product_endpoint( $endpoint ) {
		$public = WooBuilder_Blocks::instance()->public;

		$function = "render_$endpoint";

		$query = new WP_Query( [
			'p'           => $_REQUEST['post'],
			'post_type'   => 'product',
			'post_status' => array( 'publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash' ),
		] );

		$GLOBALS['wp_query'] = $query;

		global $product, $post;

		$query->the_post();
		$product = wc_get_product( $post );

		add_filter( 'woobuilder_product_meta', [ $this, 'admin_meta_notice' ] );
		add_action( 'woocommerce_product_get_rating_html', [ $this, 'rating_for_wp_admin' ], 10, 3 );

		if ( ! has_action( 'woocommerce_simple_add_to_cart' ) ) {
			add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			add_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
			add_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );
			add_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
			add_action( 'woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30 );
			add_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );
			add_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
		}

		if ( method_exists( $this, $function ) ) {
			return $this->$function( $_REQUEST );
		}

		return $public->$function( $_REQUEST );
	}

	public function rating_for_wp_admin( $html, $rating, $reviews_count ) {

		if ( ! $reviews_count ) {
			return $this->notice( 'Rating will be displayed here after reviews are done.' );
		}

		$html = '';

		for ( $i = 1; $i < 6; $i ++ ) {
			if ( $rating - $i > .8 ) {
				$html .= '<span class="dashicons dashicons-star-filled"></span>';
			} else if ( $rating - $i > .1 ) {
				$html .= '<span class="dashicons dashicons-star-half"></span>';
			} else {
				$html .= '<span class="dashicons dashicons-star-empty"></span>';
			}
		}
		return $html;
	}

	private function notice( $msg, $type = 'info' ) {
		return "<div class='notice notice-$type notice-large'>$msg</div>";
	}

	public function admin_meta_notice( $meta_html ) {
		if ( ! $meta_html ) {
			return $this->notice( 'Product categories, tags and SKU will be displayed here once added.' );
		}

		return $meta_html;
	}

	public function render_cover() {
		global $post;
		return get_the_post_thumbnail_url( $post, 'large' );
	}

	public function render_tabs() {
		return
			$this->notice( 'Product tabs will be displayed here on product page.' );
	}

	public function render_reviews() {
		return $this->notice( 'Product reviews will be displayed here on product page.' );
	}

	public function render_related_products() {
		return $this->notice( 'Related products will be displayed here on product page.' );
	}

	public function render_upsell_products() {
		return $this->notice( 'Upsell products will be displayed here on product page.' );
	}

	public function block_categories( $categories ) {
		$categories[] = [
			'slug'  => 'woobuilder',
			'title' => __( 'Woobuilder', 'woobuilder-blocks' ),
		];

		return $categories;
	}

	/**
	 * Enables Gutenberg on products
	 *
	 * @param bool $can_edit
	 * @param string $post_type
	 *
	 * @return bool Enable gutenberg
	 */
	function enable_gutenberg_products( $can_edit, $post_type ) {
		if ( 'woobuilder_template' == $post_type ) {
			return true;
		}

		if ( isset( $_GET['toggle-woobuilder'] ) ) {

			if ( ! $_GET['toggle-woobuilder'] || $_GET['toggle-woobuilder'] == 'false' ) {
				$_GET['toggle-woobuilder'] = false;
				delete_post_meta( get_the_ID(), 'woobuilder_template_applied' );
				delete_post_meta( get_the_ID(), 'woobuilder' );
			}

			update_post_meta( get_the_ID(), 'woobuilder', $_GET['toggle-woobuilder'] );

			return $_GET['toggle-woobuilder'];
		}

		return 'product' === $post_type ? WooBuilder_Blocks::enabled() : $can_edit;
	}

	public function rest_request_after_callbacks( $response, $handler, $request ) {
		if ( '/wp/v2/' . get_post_type() . '/' . get_the_ID() == $request->get_route() && isset( $_GET['toggle-woobuilder'] ) ) {
			if ( ! get_post_meta( get_the_ID(), 'woobuilder_template_applied', 'single' ) ) {
				if ( $_GET['toggle-woobuilder'] === '__presets' ) {
					$template = [
						'tpl' => '<!-- wp:woobuilder/tpl {"tpl":"","Background":"","Alignment":"1","Vertical Alignment":"","Inner Padding top":"1","Inner Padding bottom":"1","Inner Padding left":"1","Inner Padding right":"1","Inner Padding left/right tablet":"1","Inner Padding left/right mobile":"1","Inner Padding unit":"px","Background image":"","Background image position":"","Background parallax":"","Background color":"","Gradient color":"","Gradient type":"linear-gradient( ","Background colors opacity":"1"} -->
<div class="wp-block-woobuilder-tpl relative"><div class="absolute absolute--fill"><div class="absolute absolute--fill cover bg-center" style="background-color:;background-image:linear-gradient( );"></div><div class="absolute absolute--fill" style="background-color:;background-image:linear-gradient( );opacity:1;"></div></div><div class="relative woobuilder-tpl" style="padding-top:5px;padding-left:5px;padding-bottom:5px;padding-right:5px;grid-template-columns:repeat(12, 1fr)" data-tablet-css="padding-left:1em;padding-right:1em;" data-mobile-css="padding-left:1em;padding-right:1em;"></div></div>
<!-- /wp:woobuilder/tpl -->',
					];
				} else {
					$template = WooBuilder_Blocks::template( $_GET['toggle-woobuilder'] );
				}
				if ( ! empty( $template['tpl'] ) ) {
					$template = wp_parse_args( $template, [ 'meta' => [] ] );
					$response->data['content']['raw'] = $template['tpl'];
					$response->data['meta'] = wp_parse_args( $template['meta'], $response->data['meta'] );
					update_post_meta( get_the_ID(), 'woobuilder_template_applied', 1 );
				}
			}
		}

		return $response;
	}

	public function admin_footer( $post ) {
		include 'tpl/admin-template-picker.php';
	}

	/**
	 * Adds admin only actions
	 * @action admin_init
	 *
	 * @param WP_Post $post
	 */
	public function product_meta_fields( $post ) {
		if ( 'product' !== $post->post_type ) {
			return;
		}
		?>
		<div class="clear misc-pub-section">
			<a href="#woobuilder-enable-dialog" class="button button-primary" id="toggle-woobuilder">
				<?php _e( 'Enable WooBuilder Blocks', $this->token ); ?></a>
		</div>
		<?php
	}

	public function save_post( $post ) {}

	function admin_menu() {
		add_submenu_page(
			'woocommerce',
			__( 'WooBuilder Blocks', 'woobuilder-blocks' ),
			__( 'WooBuilder Blocks', 'woobuilder-blocks' ),
			'manage_options',
			'woobuilder-blocks',
			function() { include dirname( __FILE__ ) . '/tpl-settings-page.php'; }
		);
	}
}