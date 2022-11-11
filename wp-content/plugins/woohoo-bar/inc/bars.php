<?php

class WooHoo_Bar_Bars {

	/** @var string Bars to render */
	protected $bars_html = '';

	protected $bar_id = 0;

	public function __construct() {
		add_action( 'init', array( $this, 'bar_block' ) );
		add_action( 'init', array( $this, 'post_types' ), 4 );
		add_action( 'init', array( $this, 'maybe_flush_rewrite_rules' ), 20 );
		add_action( 'pre_get_posts', array( $this, 'exclude_bars_from_prod_cat_pages' ) );
		add_action( 'woocommerce_taxonomy_objects_product_cat', array( $this, 'product_cat_enable_woohoo_bar' ) );
		add_action( 'wp_head', array( $this, 'prepare_bars' ) );
		add_action( 'wp_footer', array( $this, 'footer_bars' ) );
	}

	public function bar_block() {
		register_block_type(
			'woohoo-bar/bar',
			[ 'render_callback' => [ $this, 'render_bar_block' ] ]
		);
	}

	public function post_types() {
		$labels = array(
			'name'                  => _x( 'Woohoo bars', 'Post type general name', 'woohoo_bar' ),
			'singular_name'         => _x( 'Woohoo bar', 'Post type singular name', 'woohoo_bar' ),
			'menu_name'             => _x( 'Woohoo bars', 'Admin Menu text', 'woohoo_bar' ),
			'name_admin_bar'        => _x( 'Woohoo bar', 'Add New on Toolbar', 'woohoo_bar' ),
			'add_new'               => __( 'Add New', 'woohoo_bar' ),
			'add_new_item'          => __( 'Add New Woohoo bar', 'woohoo_bar' ),
			'new_item'              => __( 'New Woohoo bar', 'woohoo_bar' ),
			'edit_item'             => __( 'Edit Woohoo bar', 'woohoo_bar' ),
			'view_item'             => __( 'View Woohoo bar', 'woohoo_bar' ),
			'all_items'             => __( 'All Woohoo bars', 'woohoo_bar' ),
			'search_items'          => __( 'Search Woohoo bars', 'woohoo_bar' ),
			'parent_item_colon'     => __( 'Parent Woohoo bars:', 'woohoo_bar' ),
			'not_found'             => __( 'No Woohoo bars found.', 'woohoo_bar' ),
			'not_found_in_trash'    => __( 'No Woohoo bars found in Trash.', 'woohoo_bar' ),
			'featured_image'        => _x( 'Woohoo bar Cover Image', '"Featured Image" phrase for this post type. Added in 4.3', 'woohoo_bar' ),
			'archives'              => _x( 'Woohoo bar archives', 'The post type archive label used in nav menus. Default “Post Archives". Added in 4.4', 'woohoo_bar' ),
			'insert_into_item'      => _x( 'Insert into Woohoo bar', '"Insert into post" phrase (used when inserting media into a post). Added in 4.4', 'woohoo_bar' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this Woohoo bar', '"Uploaded to this post" phrase (used when viewing media attached to a post). Added in 4.4', 'woohoo_bar' ),
			'filter_items_list'     => _x( 'Filter Woohoo bars list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list". Added in 4.4', 'woohoo_bar' ),
			'items_list_navigation' => _x( 'Woohoo bars list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation". Added in 4.4', 'woohoo_bar' ),
			'items_list'            => _x( 'Woohoo bars list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list"/"Pages list". Added in 4.4', 'woohoo_bar' ),
		);

		register_post_type( 'woohoo_bar', [
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => false,
			'rewrite'            => array( 'slug' => 'woohoo_bar' ),
			'menu_icon'          => 'dashicons-archive',
			'supports'           => array( 'title', 'editor', 'custom-fields', ),
			'show_in_rest'       => true,
			'template_lock'      => 'all',
			'template'           => [
				[ 'woohoo-bar/bar', ],
			]
		] );

		$post_meta_args = [
			'show_in_rest' => true,
			'single'       => true,
			'type'         => 'string',
		];

		register_post_meta( 'woohoo_bar', 'woohoo_bar_display', $post_meta_args );
		register_post_meta( 'woohoo_bar', 'woohoo_bar_position', $post_meta_args );
	}

	public function maybe_flush_rewrite_rules() {
		if ( ! get_option( 'woohoo_bar_flushed_rewrite_rules' ) ) {
			flush_rewrite_rules();
			update_option( 'woohoo_bar_flushed_rewrite_rules', 1 );
		}
	}

	public function product_cat_enable_woohoo_bar( $post_types ) {
		$post_types[] = 'woohoo_bar';
		return $post_types;
	}

	public function exclude_bars_from_prod_cat_pages( WP_Query $query ) {
		if ( $query->is_main_query() && $query->is_tax( 'product_cat' ) ) {
			$tax = get_taxonomy( 'product_cat' );
			$post_types = $tax->object_type;
			unset( $post_types[ array_search( 'woohoo_bar', $post_types ) ] );
			$query->set( 'post_type', $post_types );
		}
	}

	protected function everywhere_bars() {
		return get_posts( [
			'post_type' => 'woohoo_bar',
			'meta_query' => array(
				array(
					'key'     => 'woohoo_bar_display',
					'value'   => 'everywhere',
				),
			),
		] );
	}

	protected function product_cat_bars( $cats ) {
		if ( $cats && ! is_wp_error( $cats ) ) {
			if ( ! is_string( $cats[0] ) ) {
				$cats = wp_list_pluck( $cats, 'slug' );
			}

			return get_posts( [
				'post_type' => 'woohoo_bar',
				'meta_query' => array(
					array(
						'key'     => 'woohoo_bar_display',
						'value'   => 'categories',
					),
				),
				'tax_query' => array(
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'slug',
						'terms'    => $cats,
					),
				),
			] );
		}
	}

	protected function render_bar( WP_Post $bar, $add_for_output ) {
		$bar_html = apply_filters( 'the_content', $bar->post_content );

		if ( $add_for_output ) {
			$this->bars_html .= $bar_html;
		}

		return $bar_html;
	}

	public function render_bar_block( $attributes, $content ) {
		$atr = wp_parse_args( $attributes, [
			'bar_position' => '',
			'show_on'      => '',
			'close'        => '',
		] );
		$this->bar_id++;
		$theme_compat = 'mra mla col-full fusion-row container section-inner wrap ast-container';
		$classes = "woohoo-bar-$atr[bar_position] $atr[show_on]";
		$bar_html = "<div id='woohoo-bar-$this->bar_id' class='woohoo-bar-wrap $classes $theme_compat'>";
		$bar_html .= $atr['close'] ? '<span class="woohoo-bar-close-btn">&#10005;</span>' : '';
		$bar_html .= $content;
		$bar_html .= '</div>';

		return $bar_html;
	}

	public function prepare_bars() {
		$active_bars = $this->everywhere_bars();

		if ( is_singular( array( 'product' ) ) ) {
			global $post;
			$category_bars = $this->product_cat_bars( get_the_terms( $post, 'product_cat' ) );
			$active_bars = array_merge( $active_bars, $category_bars );
		} else if ( is_tax( 'product_cat' ) ) {
			/** @var WP_Term $tax */
			$tax = get_queried_object();
			$category_bars = $this->product_cat_bars( [ $tax ] );
			$active_bars = array_merge( $active_bars, $category_bars );
		}

		foreach ( $active_bars as $bar ) {
			$this->render_bar( $bar, 'add_for_output' );
		}
	}

	public function footer_bars() {
		echo $this->bars_html;
		wp_enqueue_script( "woohoo-bar-front" );
	}

}