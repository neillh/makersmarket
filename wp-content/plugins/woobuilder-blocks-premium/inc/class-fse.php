<?php

class WooBuilder_Blocks_FSE {
	/** @var self Instance */
	private static $_instance;
	private static $_is_fse;

	protected function __construct() {
		$this->token   = WooBuilder_Blocks::$token;
		$this->url     = WooBuilder_Blocks::$url;
		$this->path    = WooBuilder_Blocks::$path;
		$this->version = WooBuilder_Blocks::$version;
	}

	/**
	 * Returns instance of current calss
	 * @return self Instance
	 */
	public static function instance() {
		if ( ! self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public static function is_admin_fse() {
		if ( null === self::$_is_fse ) {
			$screen = get_current_screen();
			self::$_is_fse = $screen && in_array( $screen->base, [ 'appearance_page_gutenberg-edit-site', 'site-editor' ] );
		}

		return self::$_is_fse;
	}

	public function register_fse_blocks() {
		register_block_type(
			'woobuilder/product-blocks-content',
			[
				'render_callback' => [ $this, "render_content_block" ],
				'supports'        => [],
			]
		);
		register_block_type(
			'woobuilder/product-notices',
			[
				'render_callback' => [ $this, "render_notices" ],
				'supports'        => [],
			]
		);
	}

	public function render_content_block( $args ) {
		global $post, $product;
		$product = wc_get_product( $post );

		ob_start();

		do_action( 'woocommerce_before_single_product' );
		?>
		<div class="product woobuilder">
			<?php do_action( 'woobuilder_render_product', $product, $post ); ?>
		</div>
		<?php do_action( 'woocommerce_after_single_product' );

		return ob_get_clean();
	}

	/**
	 * Add the block template objects to be used.
	 * @param array $query_result Array of template objects.
	 * @param array $query Optional. Arguments to retrieve templates.
	 * @param array $template_type wp_template or wp_template_part.
	 * @return array
	 */
	public function get_block_templates( $query_result, $query, $template_type ) {

		if ( 'wp_template' !== $template_type ) {
			return $query_result;
		}

		$slug__in = empty( $query['slug__in'] ) ? [ 'single-product' ] : $query['slug__in'];

		$woobk_template_posts = $this->get_block_template_from_db( $template_type );

		if ( in_array( 'single-product', $slug__in ) || 0 === strpos( $slug__in[0], 'single-product' ) ) {
			global $post;

			if ( ! is_admin() && $post ) {
				// Setup product data and scripts for frontend.
				wc_setup_product_data( $post );

				if ( WooBuilder_Blocks::enabled( $post->ID ) ) {
					$query_result[] = $this->block_template_result_from_file( [
						'slug'        => 'single-product',
						'path'        => "{$this->path}tpl/single-product.html",
						'title'       => 'Woobuilder Blocks Product',
						'description' => "Displays WooBuilder products built with blocks, uses 'WooBuilder content' block.",
						'source'      => 'Woobuilder',
						'type'        => $template_type,
						'post_type'   => [
							'product',
						]
					], $woobk_template_posts );
				} else {
					WooBuilder_Blocks_Public::instance()->flag_fse_template();
				}
			}
		}

		return $query_result;
	}

	function get_block_template_from_db( $template_type ) {
		$check_query_args = array(
			'post_type'      => $template_type,
			'posts_per_page' => - 1,
			'no_found_rows'  => true,
			'tax_query'      => array(
				array(
					'taxonomy' => 'wp_theme',
					'field'    => 'name',
					'terms'    => 'woobuilder',
				),
			),
		);

		$templates_query = new \WP_Query( $check_query_args );
		$templates       = [];
		foreach ( $templates_query->posts as $post ) {
			$templates[ $post->post_name ] = [
				'content' => $post->post_content,
				'author'  => $post->post_author,
				'id'      => $post->ID,
			];
		}

		return $templates;
	}

	public function block_template_result_from_file( $template_file, $db_templates, $template_type = 'wp_template' ) {

		if ( class_exists( 'Gutenberg_Block_Template' ) ) {
			$template = new Gutenberg_Block_Template();
		} else {
			$template = new WP_Block_Template();
		}

		$slug = $template_file['slug'];
		if ( ! empty( $template_file['o_slug'] ) ) {
			$slug = $template_file['o_slug'];
		}

		$source                   = $template_file['source'];
		$template->id             = $source . '//' . $template_file['slug'];
		$template->theme          = $source;
		$template->slug           = $template_file['slug'];
		$template->description    = isset( $template_file['description'] ) ? $template_file['description'] : '';
		$template->source         = 'plugin';
		$template->origin         = 'plugin';
		$template->type           = $template_type;
		$template->title          = ! empty( $template_file['title'] ) ? $template_file['title'] : $template_file['slug'];
		$template->status         = 'publish';
		$template->author         = 0;
		$template->has_theme_file = true;


		$template->is_custom = ! empty( $db_templates[ $slug ] );

		if ( $template->is_custom ) {
			$template_content = $db_templates[ $slug ]['content'];
			$template->author = $db_templates[ $slug ]['author'];
			$template->wp_id  = $db_templates[ $slug ]['id'];
			$template->source = 'custom';
		} else {
			$template_content = file_get_contents( $template_file['path'] );
		}
		$template->content = _inject_theme_attribute_in_block_template_content( $template_content );

		if ( 'wp_template' === $template_type && isset( $template_file['post_type'] ) ) {
			$template->post_types = $template_file['post_type'];
		}

		if ( 'wp_template_part' === $template_type && isset( $template_file['area'] ) ) {
			$template->area = $template_file['area'];
		}

		return $template;
	}

	public function get_block_file_template( $block_template, $id, $template_type ) {

		if ( $id === "Woobuilder//single-product" ) {
			return $this->block_template_result_from_file( [
				'slug'      => 'single-product',
				'path'      => "{$this->path}tpl/single-product.html",
				'title'     => 'Woobuilder Single Product',
				'source'    => 'Woobuilder',
				'type'      => $template_type,
				'post_type' => [
					'product',
				],
			], [] );
		}

		return $block_template;
	}

	public function render_notices() {
		ob_start();
		woocommerce_output_all_notices();
		return ob_get_clean();
	}
}
