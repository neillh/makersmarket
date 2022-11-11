<?php

/**
 * Storefront Pro Blocks public class
 */
class Storefront_Pro_Blocks_Public {

	/** @var Storefront_Pro_Blocks_Public Instance */
	private static $_instance = null;

	/* @var string $token Plugin token */
	public $token;

	/* @var string $url Plugin root dir url */
	public $url;

	/* @var string $path Plugin root dir path */
	public $path;

	/* @var string $version Plugin version */
	public $version;

	/** @var array Product variations info */
	protected $product_variations_json;

	/** @var WP_Query */
	protected $query;

	protected $storefront_blocks_on_page = false;
	protected $a2c_notice;
	private $block_index = 0;

	/**
	 * Constructor function.
	 * @access  private
	 * @since   1.0.0
	 */
	private function __construct() {
		$this->token   = Storefront_Pro_Blocks::$token;
		$this->url     = Storefront_Pro_Blocks::$url;
		$this->path    = Storefront_Pro_Blocks::$path;
		$this->version = Storefront_Pro_Blocks::$version;
	}

	public static function theme_wc_styles() {
		$wc_styles_18t = '/inc/woocommerce/css/woocommerce.css';
		$wc_styles_sf  = '/assets/css/woocommerce/woocommerce.css';

		if ( file_exists( get_template_directory() . $wc_styles_18t ) ) {
			wp_enqueue_style( 'wc-18t', get_template_directory_uri() . $wc_styles_18t );
		} else if ( file_exists( get_template_directory() . $wc_styles_sf ) ) {
			wp_enqueue_style( 'wc-sf', get_template_directory_uri() . $wc_styles_sf );
		} else if ( class_exists( 'WC_Frontend_Scripts' ) ) {
			$enqueue_styles = WC_Frontend_Scripts::get_styles();
			if ( $enqueue_styles ) {
				foreach ( $enqueue_styles as $handle => $args ) {
					if ( ! isset( $args['has_rtl'] ) ) {
						$args['has_rtl'] = false;
					}
					wp_enqueue_style( $handle, $args['src'], $args['deps'], $args['version'], $args['media'] );
				}
			}
		}
	}

	public function maybe_redirect() {
		$qry_obj = get_queried_object();

		$url = '';

		if ( $qry_obj instanceof WP_Post_Type && $qry_obj->name === 'product' ) {
			$page_id = get_option( 'sfbk_custom_shop_page' );

			if ( $page_id ) {
				$url = get_the_permalink( $page_id );
			}
		}
		if ( $qry_obj instanceof WP_Term && $qry_obj->taxonomy == 'product_cat' ) {
			$url = get_term_meta( $qry_obj->term_id, 'sfpbk_redir_url', 'single' );
		}

		if ( $url ) {
			header( "HTTP/1.1 301 Moved Permanently" );
			header( "Location: $url" );
		}

	}

	public function track_recent_products() {
		/** @var WP_Query $wp_query */
		global $wp_query;

		if ( $wp_query->is_singular( 'product' ) ) {
			$recent_products = [];
			$just_viewed     = get_the_ID();

			if ( ! empty ( $_COOKIE['sfbk_rec_prods'] ) ) {
				$recent_products = explode( ',', $_COOKIE['sfbk_rec_prods'] );
				$already_viewed  = array_search( '' . $just_viewed, $recent_products );
				if ( false !== $already_viewed ) {
					unset( $recent_products[ $already_viewed ] );
				}
			}

			if ( 11 < count( $recent_products ) ) {
				unset( $recent_products[0] );
			}

			array_unshift( $recent_products, get_the_ID() );

			setcookie( 'sfbk_rec_prods', implode( ',', $recent_products ), time() + MONTH_IN_SECONDS, '/' );
		}
	}

	/**
	 * Registers dynamic blocks
	 */
	public function register_blocks() {

		$this->_register_block_type( 'wc-filter-category', [ $this, 'sfp_filter_category_renderer' ] );
		$this->_register_block_type( 'wc-category-grid', [ $this, 'sfp_blocks_categories_renderer' ] );
		$this->_register_block_type( 'wc-category-square-grid', [ $this, 'sfp_blocks_category_square_renderer' ] );

		$this->_register_block_type( 'wc-products-slider', [ $this, 'sfp_blocks_slider_renderer' ] );

		$this->_register_block_type( 'wc-products-flip', [ $this, 'sfp_blocks_flip_renderer' ] );
		$this->_register_block_type( 'wc-products-grid', [ $this, 'sfp_blocks_products_renderer' ] );
		$this->_register_block_type( 'wc-products-sliding-titles-grid', [
			$this,
			'sfp_blocks_products_sliding_tiles_renderer'
		], [ 'script' => 'wc-add-to-cart' ] );
		$this->_register_block_type( 'wc-products-square-grid', [ $this, 'sfp_blocks_products_square_renderer' ] );
		$this->_register_block_type( 'wc-products-masonry', [ $this, 'sfp_blocks_products_masonry_renderer' ] );
		$this->_register_block_type( 'wc-product-cards', [ $this, 'sfp_blocks_product_cards_renderer' ] );
		$this->_register_block_type( 'wc-products-carousel', [ $this, 'sfp_blocks_products_carousel_renderer' ] );
		$this->_register_block_type( 'wc-products-normal-grid', [ $this, 'sfp_blocks_products_normal_renderer' ] );
		$this->_register_block_type( 'wc-products-viewed-heading', [ $this, 'sfp_blocks_products_viewed_heading' ] );
		$this->_register_block_type( 'wc-products-list', [ $this, 'sfp_blocks_products_list_renderer' ] );
		$this->_register_block_type( 'wc-product-hero', [ $this, 'sfp_blocks_product_hero_renderer' ] );
		$this->_register_block_type( 'wc-sale-countdown', [ $this, 'sfp_blocks_sale_countdown' ] );
		$this->_register_block_type( 'wc-archive-title', [
			$this,
			'sfp_blocks_archive_title'
		], [ 'style' => 'woocommerce-general' ] );
		$this->_register_block_type( 'wc-archive-image', [
			$this,
			'sfp_blocks_archive_image'
		], [ 'style' => 'woocommerce-general' ] );
		$this->_register_block_type( 'wc-archive-description', [
			$this,
			'sfp_blocks_archive_description'
		], [ 'style' => 'woocommerce-general' ] );
		$this->_register_block_type( 'wc-breadcrumbs', [
			$this,
			'sfp_blocks_breadcrumbs'
		], [ 'style' => 'woocommerce-layout' ] );
		$this->_register_block_type( 'wc-sorting', [ $this, 'sfp_blocks_sorting' ], [ 'style' => 'woocommerce-layout' ] );
		$this->_register_block_type( 'wc-results-count', [
			$this,
			'sfp_blocks_results_count'
		], [ 'style' => 'woocommerce-layout' ] );
		$this->_register_block_type( 'wc-pagination', [
			$this,
			'sfp_blocks_pagination'
		], [ 'style' => 'woocommerce-layout' ] );
		$this->_register_block_type( 'mini-cart', [ $this, 'sfp_blocks_mini_cart' ], [ 'style' => 'woocommerce-layout' ] );
	}

	/**
	 * @param $id
	 * @param callable $cb
	 * @param $block_args
	 * @return false|WP_Block_Type
	 */
	private function _register_block_type( $id, $cb, $block_args = [] ) {

		$defaults = [
			'render_callback' => $cb,
			'editor_style'    => 'sfp-blocks-admin',
			'script'          => 'sfp-blocks-front',
			'style'           => 'sfp-blocks-front',
		];

		$block_args = wp_parse_args( $block_args, $defaults );

		return register_block_type( "sfp-blocks/$id", $block_args );
	}

	public function maybe_register_table_block() {
		if ( ! WP_Block_Type_Registry::get_instance()->is_registered( 'sfp-blocks/wc-products-table' ) ) {
			$this->_register_block_type( 'wc-products-table', [ $this, 'sfp_blocks_products_table_renderer' ] );
		}
	}

	/**
	 * Adds front end stylesheet and js
	 * @action wp_enqueue_scripts
	 */
	public function init_scripts_register() {
		$url = $this->url;
		if ( 1 !== version_compare( Caxton::$version, '1.27.0' ) ) {
			$caxton_assets = Caxton::$url . 'assets';
			// Register caxton scripts
			wp_register_style( 'caxton-front', "{$caxton_assets}/front.css", [], Caxton::$version );
			wp_register_script( 'caxton-utils', "{$caxton_assets}/caxton-utils.min.js", [ 'jquery' ], Caxton::$version, 'in_footer' );
		}

		if ( ! wp_style_is( 'caxton-flexslider', 'registered' ) ) {
			wp_register_style( 'caxton-flexslider', Caxton::$url . 'assets/flexslider.css' );
		}

		if ( ! wp_style_is( 'woocommerce-layout' ) ) {
			WC_Frontend_Scripts::load_scripts();
		}

		// Front
		wp_register_style( 'sfp-blocks-front', $url . 'assets/front.css' );

		wp_register_script( 'sfp-blocks-front', $url . 'assets/front.js', [
			'caxton-utils',
			'jquery',
		], $this->version, 'in_footer' );

		// Admin

		wp_register_style( 'sfp-blocks-admin', $url . 'assets/admin.css', [ 'caxton-flexslider' ] );
		wp_register_script( 'sfp-blocks-admin', $url . 'assets/admin.js', [ 'caxton-utils' ] );
	}

	/**
	 * Adds front end stylesheet and js
	 * @action wp_enqueue_scripts
	 */
	public function enqueue() {
		wp_enqueue_style( "sfp-blocks-front" );
		wp_enqueue_script( "sfp-blocks-front" );
	}

	public function wp_footer() {
		if ( $this->product_variations_json ) {
			wp_localize_script( "$this->token-js", 'sfpbkProdutVariations', $this->product_variations_json );
		}
	}

	public function support_terms_order( $orderby, $args ) {
		if ( ! empty( $args['include'] ) ) {
			$terms_order = is_array( $args['include'] ) ? implode( ',', $args['include'] ) : $args['include'];
			$orderby     = "FIELD(t.term_id,$terms_order)";
		}

		return $orderby;
	}

	public function maybe_clear_shop_content() {
		if ( $this->storefront_blocks_on_page && is_shop() ) {
			?>
			<style>
				.woocommerce-products-header {padding: 0 !important;}

				.woocommerce-products-header ~ * {display: none;}

				.ast-woo-shop-archive #primary {float: none;width: 100%;border: none;padding: 0}

				.ast-woo-shop-archive #secondary {display: none;}
			</style>
			<?php
			if ( ! empty( $GLOBALS['woocommerce_loop'] ) ) {
				$GLOBALS['woocommerce_loop']['total'] = 0;
			}
			remove_all_actions( 'woocommerce_before_main_content' );
			remove_all_actions( 'woocommerce_archive_description' );
			remove_all_actions( 'woocommerce_before_shop_loop' );
			remove_all_actions( 'woocommerce_shop_loop' );
			remove_all_actions( 'woocommerce_after_shop_loop' );
			remove_all_actions( 'woocommerce_no_products_found' );
			remove_all_actions( 'woocommerce_after_main_content' );
			remove_all_actions( 'woocommerce_sidebar' );
//			add_action( 'woocommerce_before_main_content', 'storefront_before_content', 10 );
//			add_action( 'woocommerce_after_main_content', 'storefront_after_content', 10 );
		}
	}

	public function sfp_blocks_category_square_renderer( $attr ) {
		$this->storefront_blocks_on_page = true;

		$attr['max_items'] = $attr['Grid_rows'] * $attr['Grid_columns'];

		if ( ! empty( $attr['Categories to show'] ) ) {
			$attr['include'] = $attr['Categories to show'];
		}

		$grid_data = $this->block_category_grid( $attr, 'data' );

		$label_classes = $this->label_classes( $attr );
		$className     = "sfp-blocks-categories sfbk-squares sfbk-gcols-{$attr['Grid_columns']} $label_classes";
		if ( ! empty( $attr['Full width'] ) ) {
			$className .= ' vw-100';
		}
		if ( ! empty( $attr['Drop shadow'] ) ) {
			$className .= ' sfpbk-item-shadow';
		}
		if ( ! empty( $attr['Overlay'] ) ) {
			$className .= " $attr[Overlay]";
		}

		$parentClass = 'sfp-bk-categories-square';
		if ( ! empty( $attr['on-tablet'] ) ) {
			$className .= " {$attr['on-tablet']}";
		}
		if ( ! empty( $attr['on-mobile'] ) ) {
			if ( $attr['on-mobile'] == '1' ) {
				$parentClass .= ' sfp-on-mobile';
			} else {
				$className .= " {$attr['on-mobile']}";
			}
		}

		$shadow_color = "rgba({$attr['Text Glow/Shadow']},{$attr['Shadow Strength']})";

		$style =
			"margin:{$attr['Grid top/bottom margin']}px {$attr['Grid right/left margin']}px;" .
			"letter-spacing:{$attr['Letter Spacing']}px;grid-gap:{$attr['Grid gap']}px;font-family:{$attr['Font']};" .
			"font-size:{$attr['Font size']}px;color:{$attr['Text color']};" .
			"text-shadow: {$attr['Shadow position']} {$attr['Shadow Blur']}px {$shadow_color};";

		return "<div class='$parentClass'><div class='$className' style=\"$style\">" .
					 implode( '', $grid_data['items'] ) . '</div></div>';
	}

	// region Utility functions

	public function block_category_grid( $attrs, $data = false ) {
		$attrs = wp_parse_args( $attrs, [
			'max_items' => 12
		] );

		$grid_items = [];
		$num_items  = 0;

		$all_categories = self::prod_cats( $attrs );

		foreach ( $all_categories as $cat ) {
			if ( $cat->category_parent == 0 ) {
				$image = wp_get_attachment_url( get_term_meta( $cat->term_id, 'thumbnail_id', true ) );
				if ( $image && $attrs['max_items'] > $num_items ) {
					$num_items ++;
					$grid_items[] = [
						'link'  => get_term_link( $cat->slug, 'product_cat' ),
						'image' => $image,
						'label' => empty( $attrs['Hide titles'] ) && empty( $attrs['Hide_titles'] ) ? $cat->name : '',
					];
				}
			}
		}

		return self::create_fancy_grid( $grid_items, $data );
	}

	public static function prod_cats( $args = [] ) {
		$args = wp_parse_args( $args, array(
			'taxonomy'   => 'product_cat',
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => false
		) );

		unset( $args['_wpnonce'] );
		unset( $args['woocommerce-login-nonce'] );

		if ( ! empty( $args['include'] ) ) {
			$args['orderby'] = 'include';
			$args['include'] = explode( ',', $args['include'] );
		}

		return get_terms( $args );
	}

	public static function create_fancy_grid( $items, $data = false ) {
		return Storefront_Blocks_Grid::fancy( $items, $data );
	}

	public function label_classes( $attr ) {
		if ( isset( $attr['Label Alignment'] ) ) {
			return $attr['Label Alignment'] . ' ' . $attr['Label Position'];
		}

		return '';
	}

	// endregion Utility functions

	public function sfp_blocks_categories_renderer( $attr ) {
		$this->storefront_blocks_on_page = true;

		$attr['include'] = $attr['Categories to show'];

		$grid_data = $this->block_category_grid( $attr, 'data' );

		$label_classes = $this->label_classes( $attr );
		$className     = "$grid_data[class] $label_classes";
		if ( ! empty( $attr['Full width'] ) ) {
			$className .= ' vw-100';
		}
		if ( ! empty( $attr['Overlay'] ) ) {
			$className .= " $attr[Overlay]";
		}

		$parentClass = 'sfp-bk-categories-masonry';
		if ( ! empty( $attr['on-tablet'] ) ) {
			$className .= " {$attr['on-tablet']}";
		}

		if ( ! empty( $attr['on-tablet'] ) ) {
			$className .= " {$attr['on-tablet']}";
		}
		if ( ! empty( $attr['on-mobile'] ) ) {
			if ( $attr['on-mobile'] == '1' ) {
				$parentClass .= ' sfp-on-mobile';
			} else {
				$className .= " {$attr['on-mobile']}";
			}
		}

		$text_shadow = $this->text_shadow( $attr );

		$style = "grid-auto-rows:{$attr['Grid row height']}px;margin:{$attr['Grid top/bottom margin']}px {$attr['Grid right/left margin']}px;letter-spacing:{$attr['Letter Spacing']}px;
grid-gap:{$attr['Grid gap']}px;font-family:{$attr['Font']};
font-size:{$attr['Font size']}px;color:{$attr['Text color']};$text_shadow";

		return "<div class='$parentClass'><div class='$className' style=\"$style\">" .
					 implode( '', $grid_data['items'] ) . '</div></div>';
	}

	public function text_shadow( $attr ) {
		if ( ! empty ( $attr['Shadow Strength'] ) ) {
			return "text-shadow: {$attr['Shadow position']} {$attr['Shadow Blur']}px rgba({$attr['Text Glow/Shadow']},{$attr['Shadow Strength']})";
		}

		return '';
	}

	public function sfp_filter_category_renderer( $attr ) {
		$this->storefront_blocks_on_page = true;

		$attr['include'] = $attr['Categories to show'];

		$filters = $this->block_filter_category( $attr );

		$className = 'sfbk-filter-category-wrap';
		if ( ! empty( $attr['Full width'] ) ) {
			$className .= ' vw-100';
		}

		$style =
			"letter-spacing:{$attr['Letter Spacing']}px;font-family:{$attr['Font']};font-size:{$attr['Font size']}px;" .
			"color:{$attr['Text color']};";

		return "<div class='$className' style=\"$style\">$filters</div>";
	}

	public function block_filter_category( $attrs, $data = false ) {
		$attrs = wp_parse_args( $attrs, [
			'max_items' => 12
		] );

		$all_categories = self::prod_cats( $attrs );

		$html = '';

		$active_cats = [];
		if ( isset( $_GET['sfp-cat'] ) ) {
			$active_cats = explode( ',', $_GET['sfp-cat'] );
		}

		if ( isset( $attrs['Filter_display'] ) ) {
			$attrs['Filter display'] = $attrs['Filter_display'];
		}

		if ( isset( $attrs['Multiple_categories'] ) ) {
			$attrs['Multiple categories'] = $attrs['Multiple_categories'];
		}

		if ( isset( $attrs['Active_background_color'] ) ) {
			$attrs['Active background color'] = $attrs['Active_background_color'];
		}

		if ( isset( $attrs['Active_text_color'] ) ) {
			$attrs['Active text color'] = $attrs['Active_text_color'];
		}

		if ( isset( $attrs['Background_color'] ) ) {
			$attrs['Background color'] = $attrs['Background_color'];
		}

		if ( isset( $attrs['Text_color'] ) ) {
			$attrs['Text color'] = $attrs['Text_color'];
		}

		/** @var WP_Term $cat */
		foreach ( $all_categories as $cat ) {
			$image   = wp_get_attachment_url( get_term_meta( $cat->term_id, 'thumbnail_id', true ) );
			$classes = $attrs['Filter display'];
			$styles  = '';
			$url     = $this->category_filter_url(
				empty( $attrs['Multiple categories'] ) ?
					[] : $active_cats,
				$cat->term_id );
			if ( in_array( $cat->term_id, $active_cats ) ) {
				$classes .= ' active';
				$styles  = "background:{$attrs['Active background color']};color:{$attrs['Active text color']};";
			} else {
				$styles = '';
			}

			$html         .= "<li><a href='$url' style='$styles' class='$classes'>$cat->name</a></li>";
			$grid_items[] = [
				'link'  => get_term_link( $cat->slug, 'product_cat' ),
				'image' => $image,
				'label' => $cat->name,
			];
		}

		$styles = "--sfbk-bg:{$attrs['Background color']};--sfbk-clr:{$attrs['Text color']};";

		$wrapper_class = 'sfbk-category-filter w-100 overflow-auto';
		if ( ! empty( $attrs['Horizontal slider'] ) ) {
			$wrapper_class .= ' sfbk-category-filter-slider o-0';
		}

		return "<div class='$wrapper_class' style='$styles'>" .
					 "<ul class='slides flex flex-wrap list ml0 pl0 justify-$attrs[Alignment]'>$html</ul>" .
					 '</div>';
	}

	public function category_filter_url( $active_cats, $toggle ) {

		$index = array_search( $toggle, $active_cats );

		if ( false === $index ) {
			$active_cats[] = $toggle;
		} else {
			unset( $active_cats[ $index ] );
		}

		return add_query_arg( [
			'sfp-cat' => implode( ',', $active_cats ),
		] );
	}

	public function sfp_blocks_products_renderer( $attr ) {
		$this->storefront_blocks_on_page = true;

		if ( ! empty( $attr['Products to show'] ) ) {
			$attr['post__in'] = $attr['Products to show'];
		} else if ( ! empty( $attr['Product Category'] ) ) {
			$attr['prod_cat'] = $attr['Product Category'];
		}

		$grid_data = $this->block_products_grid( $attr );

		$label_classes = $this->label_classes( $attr );
		$className     = "$grid_data[class] $label_classes";
		if ( ! empty( $attr['Full width'] ) ) {
			$className .= ' vw-100';
		}
		if ( ! empty( $attr['Overlay'] ) ) {
			$className .= " $attr[Overlay]";
		}

		$parentClass = 'sfp-bk-products-masonry';
		if ( ! empty( $attr['on-tablet'] ) ) {
			$className .= " {$attr['on-tablet']}";
		}
		if ( ! empty( $attr['on-mobile'] ) ) {
			if ( $attr['on-mobile'] == '1' ) {
				$parentClass .= ' sfp-on-mobile';
			} else {
				$className .= " {$attr['on-mobile']}";
			}
		}

		$text_shadow = $this->text_shadow( $attr );

		$style = "grid-auto-rows:{$attr['Grid row height']}px;letter-spacing:{$attr['Letter Spacing']}px;
grid-gap:{$attr['Grid gap']}px;font-family:{$attr['Font']};
font-size:{$attr['Font size']}px;color:{$attr['Text color']};$text_shadow";

		return
			"<div class='$parentClass' style='margin:{$attr['Grid top/bottom margin']}px {$attr['Grid right/left margin']}px;'><div class='$className' style=\"$style\">" .
			implode( '', $grid_data['items'] ) . "</div>$grid_data[pagination]</div>";
	}

	public function block_products_grid( $attrs ) {
		$attrs = wp_parse_args( $attrs, [
			'max_items' => 12
		] );

		$attrs['max_items'] = empty( $attrs['max_items'] ) ? 12 : $attrs['max_items'];

		$grid_items = [];

		$posts = self::prods( $attrs );

		foreach ( $posts as $post ) {
			/** @var  WP_Post $post */
			$image = get_the_post_thumbnail_url( $post->ID, 'medium_large' );
			$label = '';
			if ( empty( $attrs['Hide titles'] ) && empty( $attrs['Hide_titles'] ) ) {
				$label = get_the_title( $post->ID );
				if ( ! empty( $attrs['Show Price'] ) || ! empty( $attrs['Show_Price'] ) ) {
					$product = wc_get_product( $post );
					$label   .= ' &ndash; ' . $product->get_price_html();
				}
			}
			if ( ! empty( $attrs['Show Description'] ) || ! empty( $attrs['Show_Description'] ) ) {
				$label .= '<div class="sfpbk-grid-desc">' . strip_tags( get_the_excerpt( $post ) ) . '</div>';
			}
			$grid_items[] = [
				'link'  => get_the_permalink( $post->ID ),
				'image' => $image,
				'label' => $label,
			];
		}

		if ( empty( $attrs['items_data'] ) ) {
			$data = self::create_fancy_grid( $grid_items, 'data' );
		} else {
			$data = [ 'items' => $grid_items ];
		}
		$data['pagination'] = $this->maybe_paginate( $attrs );

		return $data;
	}

	public static function prods( $args = [ 'max_items' => 0 ], $return_query = false ) {

		$defaults = [
			'post_type'      => 'product',
			'numberposts'    => $args['max_items'],
			'posts_per_page' => $args['max_items'],
			'orderby'        => 'date',
			'order'          => 'DESC',
			'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1
		];

		if ( ! empty( $args['inherit_global_query'] ) ) {
			/** @var $wp_query WP_Query */
			global $wp_query;
			$defaults = wp_parse_args( $defaults, $wp_query->query );
		} else {
			if ( ! empty( $args['featured_products_only'] ) && $args['featured_products_only'] != 'undefined' ) {
				$args['tax_query'] = [
					[
						'taxonomy' => 'product_visibility',
						'field'    => 'slug',
						'terms'    => 'featured',
					],
				];
			}

			if ( ! empty( $args['post__in'] ) ) {

				// We got post IDs, set sorting params accordingly
				if ( strstr( $args['post__in'], 'recent-products' ) ) {
					if ( ! empty( $_COOKIE['sfbk_rec_prods'] ) ) {
						$args['post__in'] = str_replace( 'recent-products', $_COOKIE['sfbk_rec_prods'], $args['post__in'] );
					} else {
						$args['post__in'] = str_replace( 'recent-products', '0', $args['post__in'] );
					}
				}

				$args['post__in'] = explode( ',', $args['post__in'] );

				$defaults['orderby'] = 'post__in';
				unset( $defaults['order'] );
			} else if ( ! empty( $_GET['sfp-cat'] ) ) {
				$active_cats       = explode( ',', $_GET['sfp-cat'] );
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'product_cat',
						'terms'    => $active_cats,
						'field'    => 'term_id',
						'operator' => 'AND',
					)
				);
			} else if ( ! empty( $args['prod_cat'] ) ) {
				$active_cats       = explode( ',', $args['prod_cat'] );
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'product_cat',
						'terms'    => $active_cats,
						'field'    => 'term_id',
					)
				);
			} else if ( ! empty( $args['prod_tag'] ) ) {
				$active_tags       = explode( ',', $args['prod_tag'] );
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'product_tag',
						'terms'    => $active_tags,
						'field'    => 'term_id',
					)
				);
			}
		}

		$args = wp_parse_args( $args, $defaults );

		self::instance()->query = new WP_Query( $args );

		if ( $return_query ) {
			return self::instance()->query;
		}

		return self::instance()->query->get_posts();
	}

	/**
	 * Storefront Pro Blocks public class instance
	 * @return Storefront_Pro_Blocks_Public instance
	 */
	public static function instance() {
		if ( null == self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Adds pagination when set in attributes
	 * @param array $attr Block attributes
	 * @return string Pagination html
	 */
	public function maybe_paginate( $attr ) {

		if ( ! empty( $attr['Pagination'] ) ) {

			// Modify global query
			$qry_bkp             = $GLOBALS['wp_query'];
			$GLOBALS['wp_query'] = $this->query;

			$posts_pagination = paginate_links( [
				'prev_text' => '&#x2190;',
				'next_text' => '&#x2192;',
			] );

			if ( 'boxes' === $attr['Pagination'] ) {
				$posts_pagination = str_replace( 'page-numbers current', 'sfp-page-link button', $posts_pagination );
				$posts_pagination = str_replace( 'page-numbers', 'sfp-page-link', $posts_pagination );
			} else {
				$posts_pagination = str_replace( 'page-numbers', 'sfp-page-link', $posts_pagination );
			}

			// Restore global query
			$GLOBALS['wp_query'] = $qry_bkp;

			return "<nav class='sfp-pagination sfp-pagination-$attr[Pagination]'>$posts_pagination</nav>";
		}
	}

	public function maybe_process_product_action() {
		if ( isset( $_POST['sfpbk-pt-act-nonce'] ) ) {
			if ( wp_verify_nonce( $_POST['sfpbk-pt-act-nonce'], 'sfpbk-pt-action' ) ) {
				if ( $_POST['sfpbk-pt-prods'] ) {

					if ( empty( $_POST['sfpbk-pt-variation'] ) ) {
						$_POST['sfpbk-pt-variation'] = [];
					}

					$this->a2c_notice = $_POST['action'] === 'quote' ? $this->product_action_request_quote() : $this->product_action_add_to_cart();
				} else {
					$this->a2c_notice = "<div class='woocommerce-error'>Couldn't find products to add.</div>";
				}
			} else {
				$this->a2c_notice = '<div class="woocommerce-error">Sorry, nonce validation failed.</div>';
			}
		}
	}

	public function product_action_request_quote() {
		$emails = WC_Emails::instance();

		/** @var SFPBK_Request_Quote_Email $quote_email */
		$quote_email = $emails->get_emails()['SFPBK_Request_Quote_Email'];

		return $quote_email->trigger();

	}

	public function product_action_add_to_cart() {
		$added = [];
		foreach ( $_POST['sfpbk-pt-prods'] as $prod => $qty ) {
			$qty = wc_stock_amount( wp_unslash( $qty ) );
			if ( $qty ) {

				if ( isset( $_POST['sfpbk-pt-variation'][ $prod ] ) ) {
					$attributes = $_POST['sfpbk-pt-variation'][ $prod ];
					/** @var WC_Product_Variable $product */
					$product              = wc_get_product( $prod );
					$available_variations = $product->get_available_variations();

					foreach ( $available_variations as $variation ) {
						if ( $attributes == $variation['attributes'] ) {
							$var_id = $variation['variation_id'];
							break;
						}
					}

					if ( empty( $var_id ) ) {
						wc_add_notice(
							'Product ' . $product->get_title() . ' could not be added with selected options ' .
							wc_format_list_of_items( array_values( $attributes ) ) . '.', 'error' );
					} else {
						$a2c_result = wc()->cart->add_to_cart( $prod, $qty, $var_id, $attributes );
					}
				} else {
					$a2c_result = wc()->cart->add_to_cart( $prod, $qty );
				}
				if ( $a2c_result ) {
					$added[ $prod ] = $qty;
				}
			}
		}
		if ( $added ) {
			wc_add_to_cart_message( $added );
		}

		return '';
	}

	/**
	 * @param WC_Product_Variable $product
	 * @return string Variation select box
	 */
	public function maybe_output_variation_options( $variation_dropdowns ) {
		$ret = '';
		foreach ( $variation_dropdowns as $select_field ) {
			$ret .= $select_field;
		}

		return $ret;
	}

	public function sfp_blocks_products_table_renderer( $attr ) {
		$this->storefront_blocks_on_page = true;

		if ( $attr['Products to show'] ) {
			$attr['post__in'] = $attr['Products to show'];
		} else if ( $attr['Product Category'] ) {
			$attr['prod_cat'] = $attr['Product Category'];
		} else if ( $attr['Product Tags'] ) {
			$attr['prod_tag'] = $attr['Product Tags'];
		}

		$table_html = $this->block_products_table( $attr, 'data' );

		$className = "product-table-block-products-table-block";
		if ( ! empty( $attr['Full width'] ) ) {
			$className .= ' vw-100';
		}

		$style = "letter-spacing:{$attr['Letter Spacing']}px;font-family:{$attr['Font']};
font-size:{$attr['Font size']}px;color:{$attr['Text color']};";

		return "<div class='$className' style='$style'>$table_html</div>";
	}

	public function block_products_table( $attrs, $data = false ) {
		$attrs = wp_parse_args( $attrs, [
			'max_items' => 12
		] );

		$show_props = [
			'Circle images',
			'Add to cart',
			'Request quote',
			'Show Filters',
			'Show Description',
			'Show Stock Status',
			'Show Rating',
			'Show Price',
		];

		$block_id = $this->gen_id();

		foreach ( $show_props as $show_prop ) {
			$show_prop_ = str_replace( ' ', '_', $show_prop );
			if ( isset( $attrs[ $show_prop_ ] ) ) {
				$attrs[ $show_prop ] = $attrs[ $show_prop_ ];
			}
		}

		$prods_action = [];
		if ( $attrs['Add to cart'] ) {
			$prods_action[] = 'cart';
		}
		if ( ! empty( $attrs['Request quote'] ) ) {
			$prods_action[] = 'quote';
		}

		$posts = self::prods( $attrs );

		if ( empty( $attrs['Columns'] ) ) {
			$attrs['Columns'] = 'img,name';
			if ( $attrs['Show Description'] ) {
				$attrs['Columns'] .= ',description';
			}
			if ( $attrs['Show Stock Status'] ) {
				$attrs['Columns'] .= ',stock';
			}
			if ( $attrs['Show Rating'] ) {
				$attrs['Columns'] .= ',rating';
			}
			if ( $attrs['Show Price'] ) {
				$attrs['Columns'] .= ',price';
			}
		}

		$attrs['Columns'] = explode( ',', $attrs['Columns'] );

		$thead = '<tr class="sfpbk-pt-head">';
		$thead .= $this->output_table_headers( $attrs['Columns'] );
		if ( $prods_action ) {
			$thead .= '<th class="sfpbk-pt-qty tr">Qty</th>';
		}
		$thead .= '</tr>';

		$num_cols = substr_count( $thead, '<th' );

		$filters_data = [
			'product_cat' => [],
			'product_tag' => [],
			'pa_color'    => [],
		];

		$filters_labels = [
			'product_cat' => 'Categories',
			'product_tag' => 'Tags',
			'pa_color'    => 'Colors',
		];

		$rows = '';

		/** @var  WP_Post $post */
		foreach ( $posts as $post ) {
			$row_classes         = $tr_attr = '';
			$product             = wc_get_product( $post );
			$pid                 = $product->get_id();
			$variation_dropdowns = $this->get_variations_data( $product );

			$columns = $this->product_row( $attrs, $product, $variation_dropdowns );
			$tr_attr .= "data-product-id='$pid'";

			if ( $prods_action ) {
				$columns .=
					'<td class="tr">' .
					implode( '', $variation_dropdowns ) .
					"<label><input type='checkbox'><i class='sfpbk-chkbx'></i><input type='number' name='sfpbk-pt-prods[$pid]' value=''></label>" . '</td>';
			}

			if ( ! empty( $attrs['Show Filters'] ) ) {
				$data_terms = '';
				foreach ( $filters_data as $tax => $tax_data ) {

					$terms = get_the_terms( $post, $tax );
					$data  = [];

					if ( $terms ) {
						foreach ( $terms as $term ) {
							/** @var WP_Term $term */
							$data[ $term->slug ] = $term->name;
							$data_terms          .= "|$tax:{$term->slug}";
						}
						$filters_data[ $tax ] = $data;
					}
				}

				$tr_attr .= " data-terms='$data_terms|'";
			}

			$rows .= "<tr class='$row_classes' $tr_attr>$columns</tr>";
		}

		$tfoot = "<tr class='sfpbk-no-products-label'><th colspan='$num_cols'>" . __( 'No products found', 'woocommerce' ) . '</th></tr>';

		$table_format = "<table class='product-table-block-products-table'>%s</table>";

		if ( ! empty( $attrs['Show Filters'] ) ) {
			$filters = '';
			foreach ( $filters_data as $tax => $terms ) {
				if ( ! $terms || count( $terms ) < 2 ) {
					continue;
				}
				$filters .= "<select class='sfpbk-pt-filter' data-tax='$tax'>";
				$filters .= "<option value=''>All {$filters_labels[$tax]}</option>";
				foreach ( $terms as $term => $term_name ) {
					$filters .= "<option value='$term'>$term_name</option>";
				}
				$filters .= '</select>';
			}
			$table_format = $filters . $table_format;
		}

		if ( $prods_action ) {

			$cart  = "<button name='action' value='cart'>" . __( 'Add to Cart', 'woocommerce' ) . '</button> ';
			$quote = "<a href='#$block_id' class='button'>" . __( 'Request quote', 'woocommerce' ) . '</a>';
			$tfoot .= "<tr class='sfpbk-pt-action'><td colspan='$num_cols' class='tr'>" .
								( in_array( 'cart', $prods_action ) ? $cart : '' ) .
								( in_array( 'quote', $prods_action ) ? $quote : '' ) .
								'</td></tr>';

			$table_format =
				'<form action="#" class="product-table-block-products-table-form relative" method="post">' .
				$this->maybe_output_a2c_notice() . // This may add a notice
				wp_nonce_field( 'sfpbk-pt-action', 'sfpbk-pt-act-nonce', 0, 0 ) .
				$table_format .
				( in_array( 'quote', $prods_action ) ? $this->quote_dialog() : '' ) .
				'</form>';
		}

		return

			'<div id="' . $block_id . '" class="sfpbk-product-table-wrap">' .
			sprintf( $table_format, "$thead $rows $tfoot" ) . '</div>';
	}

	/**
	 * Generates an incremented unique ID  based on index
	 * @param string $prefix
	 * @param string $suffix
	 * @return string
	 */
	protected function gen_id( $prefix = 'sfpbk-block-', $suffix = '' ) {
		$index = $this->block_index ++;

		return "{$prefix}$index{$suffix}";
	}

	private function output_table_headers( $cols ) {
		$thead   = '';
		$headers = Storefront_Pro_Blocks::table_columns();

		foreach ( $cols as $col ) {
			$thead .= "<th class='sfpbk-pt-$col'>{$headers[$col]}</th>";
		}

		return $thead;
	}

	/**
	 * @param WC_Product $product
	 * @return array Select fields for attributes
	 */
	private function get_variations_data( $product ) {
		$variation_dropdowns = [];
		if ( method_exists( $product, 'get_available_variations' ) ) {
			/** @var WC_Product_Variable $product */
			$pid                  = $product->get_id();
			$available_variations = $product->get_available_variations();
			$attributes           = $product->get_variation_attributes();

			$product->get_default_attributes();

			if ( ! empty( $available_variations ) ) {

				$this->product_variations_json[ $pid ] = [
					'title' => $product->get_title(),
					'vars'  => [],
					'price' => $product->get_price_html(),
				];
				$ordered_attributes                    = [];

				foreach ( $attributes as $attribute_name => $options ) {
					$ordered_attributes[] = "attribute_$attribute_name";
					ob_start();
					wc_dropdown_variation_attribute_options(
						[
							'options'          => $options,
							'attribute'        => $attribute_name,
							'product'          => $product,
							'id'               => "sfpbk-pt-$pid-$attribute_name",
							'name'             => "sfpbk-pt-variation[$pid][attribute_$attribute_name]",
							'show_option_none' => ucfirst( str_replace( [ 'pa_', '_', '-' ], [ '', ' ', ' ' ], $attribute_name ) ),
						]
					);
					$variation_dropdowns[ $attribute_name ] = ob_get_clean();
				}


				foreach ( $available_variations as $variation ) {
					$var_attrs = [];
					foreach ( $ordered_attributes as $oattr ) {
						$var_attrs[] = empty( $variation['attributes'][ $oattr ] ) ? '[^,]*' : $variation['attributes'][ $oattr ];
					}
					$var_attr_id = implode( ',', $var_attrs );

					$this->product_variations_json[ $pid ]['vars'][ $var_attr_id ] = [
						'id'                => $variation['variation_id'],
						'in_stock'          => $variation['is_in_stock'],
						'availability_html' => $variation['availability_html'],
						'attributes'        => implode( ',', $var_attrs ),
						'price'             => $variation['price_html'],
					];
				}
				$this->product_variations_json[ $pid ]['attrs'] = $ordered_attributes;
			}
		}

		return $variation_dropdowns;
	}

	/**
	 * @param array $attrs
	 * @param WC_Product $product
	 * @param array $variation_dropdowns
	 * @return string
	 */
	private function product_row( $attrs, $product, &$variation_dropdowns ) {
		$col_calbacks = [
			'img'         => function () use ( $product, $attrs ) {
				return '<td class="f0">' .
							 get_the_post_thumbnail( $product->get_id(), 'thumbnail', [
								 'class' => $attrs['Circle images'] ? 'br-100' : '',
							 ] ) .
							 '</td>';
			},
			'name'        => function () use ( $product ) {
				return '<td><a href="' . $product->get_permalink() . '">' . $product->get_title() . '</a></td>';
			},
			'description' => function () use ( $product ) {
				return '<td>' . $product->get_short_description() . '</td>';
			},
			'stock'       => function () use ( $product ) {
				$stock_labels = [
					'outofstock'  => 'Out of stock',
					'onbackorder' => 'On backorder',
					'instock'     => 'In stock',
				];

				return '<td>' . $stock_labels[ $product->get_stock_status() ] . '</td>';
			},
			'rating'      => function () use ( $product ) {
				return '<td>' . $this->star_rating( $product->get_average_rating() ) . '</td>';
			},
			'price'       => function () use ( $product ) {
				return '<td class="prod-table-price">' . $product->get_price_html() . '</td>';
			},
			'tax'         => function ( $col_info ) use ( $product, &$variation_dropdowns ) {
				$tax = $col_info[1];
				if ( ! empty( $variation_dropdowns[ $tax ] ) ) {
					$terms = $variation_dropdowns[ $tax ];
					unset( $variation_dropdowns[ $tax ] );
				} else {
					$terms = get_the_term_list( $product->get_id(), $tax, '', ', ', '' );
				}

				return '<td>' . $terms . '</td>';
			},
		];

		$cols = $attrs['Columns'];
		$row  = '';

		foreach ( $cols as $col ) {
			$col = explode( ':', $col );
			if ( is_callable( $col_calbacks[ $col[0] ] ) ) {
				$row .= $col_calbacks[ $col[0] ]( $col );
			}
		}

		return $row;

	}

	private function star_rating( $average_rating ) {
		$html = "<div class='product-star-rating' title='Rated $average_rating out of 5'>";
		for ( $i = 0; $i < 5; $i ++ ) {
			if ( $average_rating - $i >= .75 ) {
				$html .= '<span class="fas fa-star"></span>';
			} else if ( $average_rating - $i > 0 ) {
				$html .= '<span class="fas fa-star-half-alt"></span>';
			} else {
				$html .= '<span class="far fa-star"></span>';
			}
		}
		$html .= '</div>';

		return $html;
	}

	private function maybe_output_a2c_notice() {
		if ( $this->a2c_notice ) {
			echo $this->a2c_notice;
			$this->a2c_notice = '';
		}
	}

	public function quote_dialog() {
		ob_start();
		?>
		<div class="absolute--fill sfpbk-quote-dialog">
			<a class="absolute--fill" onclick="location.hash = '_'"></a>
			<div class="sfpbk-fields relative">
				<input required name="requester_name" type="text" placeholder="Full name">
				<input required name="requester_email" type="email" placeholder="Email">
				<textarea name="requester_message" placeholder="Message"></textarea>
				<button name='action' value='quote'><?php _e( 'Send request for quote', 'woocommerce' ) ?></button>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	public function sfp_blocks_products_square_renderer( $attr ) {
		$this->storefront_blocks_on_page = true;

		$attr['max_items'] = $attr['Grid_rows'] * $attr['Grid_columns'];

		if ( ! empty( $attr['Products to show'] ) ) {
			$attr['post__in'] = $attr['Products to show'];
		} else if ( ! empty( $attr['Product Category'] ) ) {
			$attr['prod_cat'] = $attr['Product Category'];
		}

		$grid_data = $this->block_products_grid( $attr );

		$label_classes = $this->label_classes( $attr );
		$className     = "sfp-blocks-products sfbk-squares sfbk-gcols-{$attr['Grid_columns']} $label_classes";
		if ( ! empty( $attr['Full width'] ) ) {
			$className .= ' vw-100';
		}
		if ( ! empty( $attr['Drop shadow'] ) ) {
			$className .= ' sfpbk-item-shadow';
		}
		if ( ! empty( $attr['Overlay'] ) ) {
			$className .= " $attr[Overlay]";
		}

		$parentClass = 'sfp-bk-products-square';
		if ( ! empty( $attr['on-tablet'] ) ) {
			$className .= " {$attr['on-tablet']}";
		}
		if ( ! empty( $attr['on-mobile'] ) ) {
			if ( $attr['on-mobile'] == '1' ) {
				$parentClass .= ' sfp-on-mobile';
			} else {
				$className .= " {$attr['on-mobile']}";
			}
		}

		$shadow_color = "rgba({$attr['Text Glow/Shadow']},{$attr['Shadow Strength']})";

		$style =
			"letter-spacing:{$attr['Letter Spacing']}px;grid-gap:{$attr['Grid gap']}px;font-family:{$attr['Font']};" .
			"font-size:{$attr['Font size']}px;color:{$attr['Text color']};" .
			"text-shadow: {$attr['Shadow position']} {$attr['Shadow Blur']}px {$shadow_color};";

		return
			"<div class='$parentClass' style='margin:{$attr['Grid top/bottom margin']}px {$attr['Grid right/left margin']}px;'><div class='$className' style=\"$style\">" .
			implode( '', $grid_data['items'] ) . "</div>$grid_data[pagination]</div>";
	}

	public function sfp_blocks_products_sliding_tiles_renderer( $attr ) {
		$this->storefront_blocks_on_page = true;

		if ( ! empty( $attr['Products to show'] ) ) {
			$attr['post__in'] = $attr['Products to show'];
		} else if ( ! empty( $attr['Product Category'] ) ) {
			$attr['prod_cat'] = $attr['Product Category'];
		}

		$items_layout = $this->products_sliding_tiles( $attr );

		$className = "sfbk-sliding-tiles sfp-blocks-products border-box cf vw-100";

		$parentClass = 'sfbk-sliti-wrapper';

		$style = '';

		return "<div class='$parentClass' style=''><div class='$className' style='$style'>$items_layout</div></div>";
	}

	public function products_sliding_tiles( $attr ) {
		global $product;

		add_filter( 'woocommerce_product_add_to_cart_text', [ $this, 'sliti_a2c_button_text' ] );

		$attr['max_items'] = $attr['Grid_rows'] * 2;

		$items = self::prods( $attr );

		$groups = array_chunk( $items, ceil( count( $items ) / 2 ) );

		$classes = [
			'sfbk-sliti-col-normal',
			'sfbk-sliti-col-reverse',
		];

		$parent_css =
			"outline-color:$attr[outline_color];" .
			"--sliti-btn-clr:$attr[a2c_color];" .
			"--sliti-tile-clr:$attr[tile_color];";

		$title_css =
			"color:$attr[title_color];font-family:$attr[title_font];font-size:" .
			$this->responsive_size( $attr['title_size'], 1.7, 0.25 );
		$price_css =
			"color: $attr[price_color];font-family: $attr[price_font];font-size:" .
			$this->responsive_size( $attr['price_size'], 1.7, 0.25 );

		ob_start();

		$product_bkp = $product;

		foreach ( $groups as $key => $posts ) {
			echo "<div style='$parent_css' class='sfbk-sliti-col {$classes[$key]}'>";

			if ( $key ) {
				$posts = array_merge( $posts, $posts );
			}

			foreach ( $posts as $post ) {
				/** @var  WP_Post $post */
				$product = wc_get_product( $post );
				$a       = "<a href='" . get_the_permalink( $post->ID ) . "'>";
				$a_      = "</a>";

				echo "<div class='sfbk-sliti-item'>";
				echo "<div class='sfbk-sliti-item-content'>";
				echo $a . get_the_post_thumbnail(
						$post->ID,
						! empty( $attr['small_images'] ) ?
							'woocommerce_thumbnail' :
							'woocommerce_single'
					) . $a_;
				echo
					"<h2 class='sfbk-sliti-title' style='$title_css;'>$a" .
					get_the_title( $post->ID ) . "$a_</h2>";
				echo
					"<div class='sfbk-sliti-price' style='$price_css'>$a" .
					$product->get_price_html() . "$a_</div>";
				echo "</div>";
				if ( $attr['show_a2c'] ) {
					echo '<div class="sliti-add-to-cart">';
					echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M96 0C107.5 0 117.4 8.19 119.6 19.51L121.1 32H541.8C562.1 32 578.3 52.25 572.6 72.66L518.6 264.7C514.7 278.5 502.1 288 487.8 288H170.7L179.9 336H488C501.3 336 512 346.7 512 360C512 373.3 501.3 384 488 384H159.1C148.5 384 138.6 375.8 136.4 364.5L76.14 48H24C10.75 48 0 37.25 0 24C0 10.75 10.75 0 24 0H96zM128 464C128 437.5 149.5 416 176 416C202.5 416 224 437.5 224 464C224 490.5 202.5 512 176 512C149.5 512 128 490.5 128 464zM512 464C512 490.5 490.5 512 464 512C437.5 512 416 490.5 416 464C416 437.5 437.5 416 464 416C490.5 416 512 437.5 512 464z"/></svg>';
					woocommerce_template_loop_add_to_cart();
					echo '</div>';
				}
				echo "</div>";
			}

			echo "</div>";
		}
		remove_filter( 'woocommerce_product_add_to_cart_text', [ $this, 'sliti_a2c_button_text' ] );

		$product = $product_bkp;

		return ob_get_clean();
	}

	/**
	 * @param number $value
	 * @param number $px_mult
	 * @param number $vw_mult
	 * @return void
	 */
	public function responsive_size( $value, $px_mult = 2, $vw_mult = .25 ) {
		return 'calc( ' . $value * $px_mult . 'px + ' . $value * $vw_mult . 'vw )';
	}

	public function sliti_a2c_button_text() {
		return '';
	}

	public function sfp_blocks_products_masonry_renderer( $attr ) {
		$this->storefront_blocks_on_page = true;

		if ( ! empty( $attr['Products to show'] ) ) {
			$attr['post__in'] = $attr['Products to show'];
		} else if ( ! empty( $attr['Product Category'] ) ) {
			$attr['prod_cat'] = $attr['Product Category'];
		}

		$grid_data = $this->block_products_masonry( $attr );

		$label_classes = $this->label_classes( $attr );
		$className     = "sfp-blocks-products-masonry sfbk-mcols-$attr[columns] $label_classes";
		if ( ! empty( $attr['Full width'] ) ) {
			$className .= ' vw-100';
		}
		if ( ! empty( $attr['Drop shadow'] ) ) {
			$className .= ' sfpbk-item-shadow';
		}
		if ( ! empty( $attr['Overlay'] ) ) {
			$className .= " $attr[Overlay]";
		}

		$parentClass = 'sfp-bk-products-masonry';
		if ( ! empty( $attr['on-tablet'] ) ) {
			$className .= " {$attr['on-tablet']}";
		}
		if ( ! empty( $attr['on-mobile'] ) ) {
			if ( $attr['on-mobile'] == '1' ) {
				$parentClass .= ' sfp-on-mobile';
			} else {
				$className .= " {$attr['on-mobile']}";
			}
		}

		$shadow_color = "rgba({$attr['Text Glow/Shadow']},{$attr['Shadow Strength']})";

		$style =
			"letter-spacing:{$attr['Letter Spacing']}px;--items-gap:{$attr['Gap']}px;font-family:{$attr['Font']};" .
			"font-size:{$attr['Font size']}px;color:{$attr['Text color']};" .
			"text-shadow: {$attr['Shadow position']} {$attr['Shadow Blur']}px {$shadow_color};";

		$parent_styles = "margin:{$attr['Grid top/bottom margin']}px {$attr['Grid right/left margin']}px;";

		return
			"<div class='$parentClass' style='$parent_styles'>" .
			"<div class='$className' style='$style'>" .
			implode( '', $grid_data['items'] ) . "</div>$grid_data[pagination]</div>";
	}

	public function block_products_masonry( $attr ) {

		$attr['items_data'] = true;
		$grid_data          = $this->block_products_grid( $attr );


		foreach ( $grid_data['items'] as $k => $item ) {
			$grid_data['items'][ $k ] =
				"<div class='sfp-bk-masonry-item'>" .
				"<a href='$item[link]'>" .
				"<img src='$item[image]'>" .
				"<span>$item[label]</span>" .
				"</a></div>";
		}

		return $grid_data;
	}

	public function sfp_blocks_product_cards_renderer( $attr ) {
		$this->storefront_blocks_on_page = true;

		$attr['max_items'] = $attr['Grid_rows'] * $attr['Grid_columns'];

		if ( ! empty( $attr['Products to show'] ) ) {
			$attr['post__in'] = $attr['Products to show'];
		} else if ( ! empty( $attr['Product Category'] ) ) {
			$attr['prod_cat'] = $attr['Product Category'];
		}

		$grid_data = $this->block_products_cards( $attr );

		$label_classes = $this->label_classes( $attr );
		$className     = "sfp-blocks-products sfbk-cards sfbk-gcols-{$attr['Grid_columns']} $label_classes";
		if ( ! empty( $attr['Full width'] ) ) {
			$className .= ' vw-100';
		}
		if ( ! empty( $attr['Drop shadow'] ) ) {
			$className .= ' sfpbk-item-shadow';
		}
		if ( ! empty( $attr['Overlay'] ) ) {
			$className .= " $attr[Overlay]";
		}
		if ( ! empty( $attr['Cards animation'] ) ) {
			$className .= " {$attr['Cards animation']}";
		}

		$parentClass = 'sfp-bk-products-square';
		if ( ! empty( $attr['on-tablet'] ) ) {
			$className .= " {$attr['on-tablet']}";
		}
		if ( ! empty( $attr['on-mobile'] ) ) {
			if ( $attr['on-mobile'] == '1' ) {
				$parentClass .= ' sfp-on-mobile';
			} else {
				$className .= " {$attr['on-mobile']}";
			}
		}

		$shadow_color = "rgba({$attr['Text Glow/Shadow']},{$attr['Shadow Strength']})";

		$style =
			"letter-spacing:{$attr['Letter Spacing']}px;grid-gap:{$attr['Grid gap']}px;font-family:{$attr['Font']};" .
			"font-size:{$attr['Font size']}px;color:{$attr['Text color']};" .
			"text-shadow: {$attr['Shadow position']} {$attr['Shadow Blur']}px {$shadow_color};";

		return
			"<div class='$parentClass' style='margin:{$attr['Grid top/bottom margin']}px {$attr['Grid right/left margin']}px;'><div class='$className' style=\"$style\">" .
			implode( '', $grid_data['items'] ) . "</div>$grid_data[pagination]</div>";
	}

	public function block_products_cards( $attrs ) {
		$attrs = wp_parse_args( $attrs, [
			'max_items' => 12
		] );

		$attrs['max_items'] = empty( $attrs['max_items'] ) ? 12 : $attrs['max_items'];

		$grid_items = [];

		$posts = self::prods( $attrs );

		$data = [ 'items' => [] ];

		foreach ( $posts as $post ) {
			$product = wc_get_product( $post );
			/** @var  WP_Post $post */
			$image = get_the_post_thumbnail_url( $post->ID, 'woocommerce_single' );
			$label = '';
			if ( empty( $attrs['Hide titles'] ) && empty( $attrs['Hide_titles'] ) ) {
				$label = get_the_title( $post->ID );
				if ( ! empty( $attrs['Show Price'] ) || ! empty( $attrs['Show_Price'] ) ) {
					$label .= ' &ndash; ' . $product->get_price_html();
				}
			}
			if ( ! empty( $attrs['Show Description'] ) || ! empty( $attrs['Show_Description'] ) ) {
				$label .= '<div class="sfpbk-grid-desc">' . strip_tags( get_the_excerpt( $post ) ) . '</div>';
			}

			$link = get_the_permalink( $post->ID );

			$card_images_class = 'sfbk-card-images';
			$image             = "<img src='$image'>";
			$image2            = '';
			$gal_images        = $product->get_gallery_image_ids();

			if ( $gal_images ) {
				$card_images_class .= " $card_images_class-2";
				$image2            = wp_get_attachment_image_url( $gal_images[0], 'woocommerce_single' );
				$image2            = "<div class='sfbk-card-image-2' style='background-image:url($image2)'></div>";
			};
			$data['items'][] =
				"<div class='sfbk-product-card'>" .
				"<div class='$card_images_class'><a href='$link'>{$image}{$image2}</a></div>" .
				"<div><a href='$link'>$label</a></div>" .
				"</div>";
		}

		$data['pagination'] = $this->maybe_paginate( $attrs );

		return $data;
	}

	public function sfp_blocks_slider_renderer( $attr ) {
		$this->storefront_blocks_on_page = true;

		if ( ! empty( $attr['Products to show'] ) ) {
			$attr['post__in'] = $attr['Products to show'];
		} else if ( ! empty( $attr['Product Category'] ) ) {
			$attr['prod_cat'] = $attr['Product Category'];
		}


		$slides = $this->block_products_slides( $attr );

		$className   = "sfp-blocks-products caxton-slider caxton-slider-pending-setup {$attr['Text alignment']}";
		$wrapClasses = "caxton-slider-wrap";
		if ( ! empty( $attr['Full width'] ) ) {
			$wrapClasses .= ' vw-100';
		}

		$text_shadow = $this->text_shadow( $attr );

		$style = "margin:{$attr['Top/Bottom margin']}px {$attr['Right/Left margin']}px;font-family:{$attr['Font']};
font-size:{$attr['Font size']}px;color:{$attr['Text color']};min-height: {$attr['Slider height']}px;$text_shadow";

		return
			"<div class='$wrapClasses'>" .
			"<div class='$className' style=\"$style\">" . $slides . '</div>' .
			'</div>';

	}

	public function block_products_slides( $attrs ) {
		$attrs = wp_parse_args( $attrs, [
			'max_items' => 12
		] );

		$attrs['max_items'] = min( $attrs['max_items'], 5 );

		$items     = [];
		$num_items = 0;

		$posts = self::prods( $attrs );

		foreach ( $posts as $post ) {
			/** @var  WP_Post $post */
			$product = wc_get_product( $post );
			$image   = get_the_post_thumbnail_url( $post->ID, 'large' );

			ob_start();
			?>
			<li class="caxton-slide" style="background-image: url('<?php echo $image ?>')">
				<div class="summary entry-summary flex-caption">
					<div class="col-full">
						<h2 class="product_title entry-title"><?php echo $post->post_title ?></h2>
						<p class="price"><?php $product->get_price_html() ?></p>
						<div class="woocommerce-product-details__short-description">
							<p><?php echo $post->post_excerpt; ?></p>
						</div>
						<?php
						if ( ! empty( $attrs['Show Price'] ) || ! empty( $attrs['Show_Price'] ) ) {
							$product = wc_get_product( $post );
							echo $product->get_price_html();
						}
						?>
						<a href="<?php echo get_the_permalink( $post ) ?>" data-quantity="1"
							 data-product_id="<?php echo $post->ID ?>"
							 class="button alt product_type_simple add_to_cart_button ajax_add_to_cart"
							 rel="nofollow"><?php _e( 'Add to cart', 'woocommerce' ) ?></a>
					</div>
				</div>
			</li>
			<?php
			$items[] = ob_get_clean();
		}

		return '<ul class="slides">' . implode( '', $items ) . '</ul>';
	}

	public function sfp_blocks_flip_renderer( $attr ) {
		$this->storefront_blocks_on_page = true;

		if ( ! empty( $attr['Products to show'] ) ) {
			$attr['post__in'] = $attr['Products to show'];
		} else if ( ! empty( $attr['Product Category'] ) ) {
			$attr['prod_cat'] = $attr['Product Category'];
		}


		$slides = $this->block_products_flip( $attr );

		$className   = "sfp-blocks-products caxton-slider caxton-slider-pending-setup {$attr['Text alignment']}";
		$wrapClasses = "caxton-slider-wrap sfbk-flip-wrap";
		if ( ! empty( $attr['Full width'] ) ) {
			$wrapClasses .= ' vw-100';
		}

		$text_shadow = $this->text_shadow( $attr );

		$style = "margin:{$attr['Top/Bottom margin']}px {$attr['Right/Left margin']}px;font-family:{$attr['Font']};
font-size:{$attr['Font size']}px;color:{$attr['Text color']};min-height: {$attr['Slider height']}px;$text_shadow";

		return
			"<div class='$wrapClasses'>" .
			"<div class='$className' style=\"$style\">" . $slides . '</div>' .
			'</div>';

	}

	public function block_products_flip( $attrs ) {
		$attrs = wp_parse_args( $attrs, [
			'max_items' => 12
		] );

		$attrs['max_items'] = min( $attrs['max_items'], 5 );

		$items     = [];
		$num_items = 0;

		$posts = self::prods( $attrs );

		foreach ( $posts as $post ) {
			/** @var  WP_Post $post */
			$product = wc_get_product( $post );
			$image   = get_the_post_thumbnail_url( $post->ID, 'large' );

			ob_start();
			?>
			<li class="caxton-slide sfbk-flip-slide items-stretch">
				<div class="sfbk-flip-img" style="background-image: url('<?php echo $image ?>')"></div>
				<div class="sfbk-flip-content">
					<div class="col-full">
						<h2 class="product_title entry-title"><?php echo $post->post_title ?></h2>
						<p class="price"><?php $product->get_price_html() ?></p>
						<div class="woocommerce-product-details__short-description">
							<p><?php echo $post->post_excerpt; ?></p>
						</div>
						<?php
						if ( ! empty( $attrs['Show Price'] ) || ! empty( $attrs['Show_Price'] ) ) {
							$product = wc_get_product( $post );
							echo '<div class="sfbk-flip-price mb4 f3">' . $product->get_price_html() . '</div>';
						}
						?>
						<a href="<?php echo get_the_permalink( $post ) ?>" class="button alt">
							<?php _e( 'Read more', 'woocommerce' ) ?></a>
					</div>
				</div>
			</li>
			<?php
			$items[] = ob_get_clean();
		}

		return '<ul class="slides sfbk-flip">' . implode( '', $items ) . '</ul>';
	}

	public function sfp_blocks_products_carousel_renderer( $attr ) {
		$this->storefront_blocks_on_page = true;

		if ( ! empty( $attr['Products to show'] ) ) {
			$attr['post__in'] = $attr['Products to show'];
		} else if ( ! empty( $attr['Product Category'] ) ) {
			$attr['prod_cat'] = $attr['Product Category'];
		}

		$carousel = $this->block_products_carousel( $attr );

		$className   = 'sfp-blocks-products caxton-slider caxton-carousel caxton-carousel-pending-setup tc';
		$wrapClasses = "caxton-slider-wrap";
		if ( ! empty( $attr['Full width'] ) ) {
			$wrapClasses .= ' vw-100';
		}

		$text_shadow = $this->text_shadow( $attr );

		$style = "margin:{$attr['Top/Bottom margin']}px {$attr['Right/Left margin']}px;font-family:{$attr['Font']};
font-size:{$attr['Font size']}px;color:{$attr['Text color']};$text_shadow";

		return
			"<div class='$wrapClasses'>" .
			"<div class='$className' style=\"$style\">" . $carousel . '</div>' .
			'</div>';

	}

	public function block_products_carousel( $attrs ) {
		$attrs = wp_parse_args( $attrs, [
			'max_items' => 20
		] );

		$items = [];

		$posts = self::prods( $attrs );

		foreach ( $posts as $post ) {
			/** @var  WP_Post $post */
			$product = wc_get_product( $post );
			$image   = get_the_post_thumbnail_url( $post->ID, 'medium_large' );

			ob_start();
			?>
			<li class="caxton-slide">
				<img src="<?php echo $image ?>">
				<div class="summary">
					<h3 class="product_title entry-title"><?php echo $post->post_title ?></h3>
					<?php
					if ( ! empty( $attrs['Show Price'] ) || ! empty( $attrs['Show_Price'] ) ) {
						$product = wc_get_product( $post );
						echo '<p class="price">' . $product->get_price_html() . '</p>';
					}
					?>
					<a href="<?php echo get_the_permalink( $post ) ?>" data-quantity="1" data-product_id="<?php echo $post->ID ?>"
						 class="button alt product_type_simple add_to_cart_button ajax_add_to_cart"
						 rel="nofollow"><?php _e( 'Add to cart', 'woocommerce' ) ?></a>
					<?php /* PRODUCT META ?>
						<div class="product_meta">
							<?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woocommerce' ) . ' ', '</span>' ); ?>
							<?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ) . ' ', '</span>' ); ?>
						</div>
				<?php /**/ ?>
				</div>
			</li>
			<?php
			$items[] = ob_get_clean();
		}

		return '<ul class="slides">' . implode( '', $items ) . '</ul>';
	}

	public function sfp_blocks_products_normal_renderer( $attr ) {
		$this->storefront_blocks_on_page = true;

		if ( ! empty( $attr['Products to show'] ) ) {
			$attr['post__in'] = $attr['Products to show'];
		} else if ( ! empty( $attr['Product Category'] ) ) {
			$attr['prod_cat'] = $attr['Product Category'];
		}

		$products = $this->block_normal_products_grid( $attr );

		$className =
			'sfp-blocks-products ' . $attr['Text Alignment'] . ' ' . $attr['Hide price'] . ' ' .
			$attr['Hide add to cart button'] . ' ' . $attr['Hide product title'] . '';
		if ( ! empty( $attr['Full width'] ) ) {
			$className .= ' vw-100';
		}

		$shadow_color = "rgba({$attr['Text Glow/Shadow']},{$attr['Shadow Strength']})";

		$style =
			"margin:{$attr['Grid top/bottom margin']}px {$attr['Grid right/left margin']}px;" .
			"letter-spacing:{$attr['Letter Spacing']}px;font-family:{$attr['Font']};" .
			"font-size:{$attr['Font size']}px;color:{$attr['Text color']};" .
			"text-shadow: {$attr['Shadow position']} {$attr['Shadow Blur']}px {$shadow_color};";

		return "<div class='$className' style=\"$style\">$products</div>";
	}

	public function block_normal_products_grid( $attr ) {
		WC()->frontend_includes();

		$attr['max_items'] = $attr['Grid_rows'] * $attr['Grid_columns'];

		if ( ! empty( $attr['Products to show'] ) ) {
			$attr['post__in'] = $attr['Products to show'];
		} else if ( ! empty( $attr['Product Category'] ) ) {
			$attr['prod_cat'] = $attr['Product Category'];
		}

		$query = self::prods( $attr, 'query' );

		if ( empty( $attr['Grid_columns'] ) ) {
			$attr['Grid_columns'] = 3;
		}
		if ( empty( $GLOBALS['woocommerce_loop'] ) ) {
			$GLOBALS['woocommerce_loop'] = [ 'columns' => 3 ];
		}

		$temp_cols                              = $GLOBALS['woocommerce_loop']['columns'];
		$GLOBALS['woocommerce_loop']['columns'] = $attr['Grid_columns'];

		ob_start();

		echo "<div class='woocommerce columns-$attr[Grid_columns]'>";

		if ( ! empty( $attr['show_qty'] ) ) {
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_single_add_to_cart', 10 );
		}
		if ( ! empty( $attr['show_excerpt'] ) ) {
			add_action( 'woocommerce_after_shop_loop_item_title', [ $this, 'block_normal_grid_show_excerpt' ], 0 );
		}

		woocommerce_product_loop_start();
		while ( $query->have_posts() ) {
			$query->the_post();
			wc_get_template_part( 'content', 'product' );
		}
		woocommerce_product_loop_end();

		wp_reset_postdata();

		$GLOBALS['woocommerce_loop']['columns'] = $temp_cols;

		echo '</div>';

		if ( ! empty( $attr['show_qty'] ) ) {
			add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_single_add_to_cart', 10 );
		}
		if ( ! empty( $attr['show_excerpt'] ) ) {
			remove_action( 'woocommerce_after_shop_loop_item_title', [ $this, 'block_normal_grid_show_excerpt' ], 0 );
		}

		echo $this->maybe_paginate( $attr );

		return ob_get_clean();
	}

	public function sfp_blocks_products_viewed_heading( $attr, $content ) {
		if ( empty ( $_COOKIE['sfbk_rec_prods'] ) ) {
			return '';
		}

		return $content;
	}

	public function sfp_blocks_products_list_renderer( $attr ) {
		$this->storefront_blocks_on_page = true;

		if ( ! empty( $attr['Products to show'] ) ) {
			$attr['post__in'] = $attr['Products to show'];
		} else if ( ! empty( $attr['Product Category'] ) ) {
			$attr['prod_cat'] = $attr['Product Category'];
		}

		$products = $this->block_products_list( $attr );

		$className =
			'sfp-blocks-products ' . $attr['Text Alignment'] . ' ' . $attr['Hide price'] . ' ' .
			$attr['Hide add to cart button'] . ' ' . $attr['Hide product title'] . '';
		if ( ! empty( $attr['Full width'] ) ) {
			$className .= ' vw-100';
		}

		$shadow_color = "rgba({$attr['Text Glow/Shadow']},{$attr['Shadow Strength']})";

		$style =
			"margin:{$attr['Grid top/bottom margin']}px {$attr['Grid right/left margin']}px;" .
			"letter-spacing:{$attr['Letter Spacing']}px;font-family:{$attr['Font']};" .
			"font-size:{$attr['Font size']}px;color:{$attr['Text color']};" .
			"text-shadow: {$attr['Shadow position']} {$attr['Shadow Blur']}px {$shadow_color};";

		return "<div class='$className' style=\"$style\">$products</div>";
	}

	public function block_products_list( $attr ) {
		global $post;

		WC()->frontend_includes();

		if ( ! empty( $attr['Products to show'] ) ) {
			$attr['post__in'] = $attr['Products to show'];
		} else if ( ! empty( $attr['Product Category'] ) ) {
			$attr['prod_cat'] = $attr['Product Category'];
		}

		$query = self::prods( $attr, 'query' );

		ob_start();

		echo "<div class='sfpbk-products-list'>";

		woocommerce_product_loop_start( false );

		while ( $query->have_posts() ) {
			$query->the_post();
			?>
			<div class="sfpbk-list-product">
				<div class="sfpbk-list-product-img">
					<?php echo the_post_thumbnail( 'woocommerce_thumbnail' ) ?>
				</div>
				<div class="sfpbk-list-product-summary">
					<?php
					woocommerce_template_loop_rating();
					woocommerce_template_loop_product_title();
					woocommerce_template_single_price();
					?>
					<div class="product-desc">
						<?php
						echo apply_filters( 'woocommerce_short_description', $post->post_excerpt );
						?>
					</div>
					<?php
					if ( empty( $attr['Hide add to cart button'] ) ) {
						woocommerce_template_loop_add_to_cart();
					}
					if ( ! empty( $attr['Show view product button'] ) || ! empty( $attr['Show_view_product_button'] ) ) {
						?>
						<a href="<?php the_permalink(); ?>" class="button alt"><?php _e( 'View product', 'woocommerce' ) ?></a>
						<?php
					}
					?>
				</div>
			</div>
			<?php
		}

		woocommerce_product_loop_end( false );

		wp_reset_postdata();

		echo '</div>';

		echo $this->maybe_paginate( $attr );

		return ob_get_clean();
	}

	public function sfp_blocks_sale_countdown( $attr, $content = '' ) {
		return str_replace( '%content%', $this->sale_countdown( $attr ), $content );
	}

	public function sale_countdown( $attr ) {
		$this->storefront_blocks_on_page = true;

		if ( empty( $attr['ending'] ) ) {
			return;
		}

		$ending = strtotime( $attr['ending'] );
		$diff   = $ending - time();

		if ( ! $diff || $diff < 5 ) {
			return '<div></div>';
		}

		ob_start();

		echo "<div class='sfpbk-sale_countdown flex justify-center tc' data-date-end='$ending'>";

		$days = floor( $diff / ( 60 * 60 * 24 ) );

		$hours = floor( $diff % ( 60 * 60 * 24 ) / ( 60 * 60 ) );

		$minutes = floor( $diff % ( 60 * 60 ) / 60 );

		$seconds = floor( $diff % 60 );

		$format =
			'<div class="sfpbk-timr sfpbk-timr-%1$s br1 ph4 pv2 ma1 bg-black-30">' .
			'<div class="sfpbk-timr-number-%1$s sfpbk-timr-number">%3$s</div>' .
			'<div class="sfpbk-timr-label">%4$s</div>' .
			'</div>';

		echo $days ? sprintf( $format, 'days', $days * 100 / 31, $days, _n( 'day', 'days', $days ) ) : '';

		echo sprintf( $format, 'hours', $hours * 100 / 24, $hours, _n( 'hour', 'hours', $hours ) );

		echo sprintf( $format, 'minutes', $minutes * 100 / 60, $minutes, _n( 'minute', 'minutes', $minutes ) );

		echo sprintf( $format, 'seconds', $seconds * 100 / 60, $seconds, _n( 'second', 'seconds', $seconds ) );

		echo '</div>';

		return ob_get_clean();

	}

	public function sfp_blocks_product_hero_renderer( $attr ) {
		$this->storefront_blocks_on_page = true;

		if ( ! empty( $attr['Product'] ) ) {
			$attr['post__in'] = $attr['Product'];
		} else if ( ! empty( $attr['Product Category'] ) ) {
			$attr['prod_cat'] = $attr['Product Category'];
		}

		$product = $this->product_hero( $attr );


		return Caxton_Public::processTemplate( $attr['tpl'], $product );
	}

	public function product_hero( $attr ) {

		$attr['max_items'] = 1;

		$query = self::prods( $attr, 'query' );

		if ( empty( $attr['Grid_columns'] ) ) {
			$attr['Grid_columns'] = 3;
		}

		$query->the_post();

		global $product;

		/** @var WC_Product $product */
		$product = wc_get_product( get_the_ID() );

		if ( ! $product ) {
			return [];
		}

		$attachment_id = $product->get_image_id();
		$thumbs        = $product->get_gallery_image_ids();

		$product_data = [
			'title'   => get_the_title(),
			'id'      => get_the_ID(),
			'excerpt' => apply_filters( 'the_excerpt', get_the_excerpt() ),
			'content' => apply_filters( 'the_content', get_the_content() ),

			'thumb-img'          => wp_get_attachment_image( $attachment_id, 'shop_single' ),
			'thumb'              => wp_get_attachment_image_url( $attachment_id, 'large' ),
			'thumb-shop_single'  => wp_get_attachment_image_url( $attachment_id, 'shop_single' ),
			'thumb-medium_large' => wp_get_attachment_image_url( $attachment_id, 'medium_large' ),
			'thumb-full'         => wp_get_attachment_image_url( $attachment_id, 'full' ),

			'cats'  => get_the_term_list( $product->get_id(), 'product_cat' ),
			'tags'  => get_the_term_list( $product->get_id(), 'product_tag' ),
			'price' => $product->get_price_html(),
		];

		foreach ( $thumbs as $i => $thumb ) {
			$i ++;
			$product_data["gallery$i"]       = wp_get_attachment_image_url( $thumb, 'shop_single' );
			$product_data["gallery$i-large"] = wp_get_attachment_image_url( $thumb, 'large' );
		}

		for ( $i = 1; $i < 5; $i ++ ) {
			if ( empty( $product_data["gallery$i"] ) ) {
				$product_data["gallery$i"] = $product_data["gallery$i-large"] = '';
			}
		}

		ob_start();
		wc_get_template( 'single-product/meta.php' );
		$product_data['meta'] = ob_get_clean();

		if ( $product->is_on_sale() ) {
			$product_data['sale'] = '<span class="onsale">' . esc_html__( 'Sale', 'woocommerce' ) . '</span>';
		}

		ob_start();
		woocommerce_template_single_add_to_cart();
		$product_data['a2c'] = ob_get_clean();

		wp_reset_postdata();

		return $product_data;
	}

	public function block_normal_grid_show_qty() {

	}

	public function block_normal_grid_show_excerpt() {
		echo '<div class="sfpbk-prod-excerpt">';
		the_excerpt();
		echo '</div>';
	}

	public function sfp_blocks_archive_title( $attr ) {
		$content = $this->archive_title( $attr );
		$style   = $this->block_font_styles( $attr );

		return "<div class='sfp-blocks-archive-title' style=\"$style\">$content</div>";
	}

	public function archive_title( $attr ) {
		return woocommerce_page_title( false );
	}

	private function block_font_styles( $attr ) {

		$css = '';
		if ( ! empty( $attr['Text Glow/Shadow'] ) ) {
			$css = "text-shadow: {$attr['Shadow position']} {$attr['Shadow Blur']}px rgba({$attr['Text Glow/Shadow']},{$attr['Shadow Strength']});";
		}
		$styles = [
			'Font'       => 'font-family:%s;',
			'Font size'  => 'font-size:%spx;',
			'Text color' => 'color:%s;',
		];

		foreach ( $styles as $field => $fcss ) {
			if ( ! empty( $attr[ $field ] ) ) {
				$css .= sprintf( $fcss, "{$attr[$field]}" );
			}
		}

		return "$css";
	}

	public function sfp_blocks_archive_image( $attr ) {
		$content = $this->archive_image( $attr );

		if ( ! $content ) {
			return '';
		}

		$className = "sfp-blocks-archive-image $attr[Align]";

		$style = "";

		return "<div class='$className' style=\"$style\">$content</div>";
	}

	public function archive_image( $attr ) {
		if ( is_product_taxonomy() ) {
			$term         = get_queried_object();
			$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );

			if ( $thumbnail_id ) {
				return wp_get_attachment_image( $thumbnail_id );
			}
		}
	}

	public function sfp_blocks_archive_description( $attr ) {
		$content = $this->archive_description( $attr );
		$style   = $this->block_font_styles( $attr );

		return "<div class='sfp-blocks-archive-description' style=\"$style\">$content</div>";
	}

	public function archive_description( $attr ) {
		if ( is_product_taxonomy() ) {
			$term = get_queried_object();

			if ( $term && ! empty( $term->description ) ) {
				return '<div class="term-description">' . wc_format_content( wp_kses_post( $term->description ) ) . '</div>';
			}
		}
	}

	public function sfp_blocks_breadcrumbs( $attr ) {
		$content = $this->breadcrumbs( $attr );
		$style   = $this->block_font_styles( $attr );

		return "<div class='sfp-block sfp-block-breadcrumbs' style=\"$style\">$content</div>";
	}

	public function breadcrumbs( $attr ) {
		ob_start();
		woocommerce_breadcrumb();

		return ob_get_clean();
	}

	public function sfp_blocks_sorting( $attr ) {
		$content = $this->sorting( $attr );
		$style   = $this->block_font_styles( $attr );

		return "<div class='sfp-block sfp-block-sorting' style=\"$style\">$content</div>";
	}

	public function sorting( $attr ) {
		ob_start();
		woocommerce_catalog_ordering();

		return ob_get_clean();
	}

	public function sfp_blocks_results_count( $attr ) {
		$content = $this->results_count( $attr );
		$style   = $this->block_font_styles( $attr );

		return "<div class='sfp-block sfp-block-results-count' style=\"$style\">$content</div>";
	}

	public function results_count( $attr ) {
		ob_start();
		woocommerce_result_count();

		return ob_get_clean();
	}

	public function sfp_blocks_pagination( $attr ) {
		$content = $this->pagination( $attr );
		$style   = $this->block_font_styles( $attr );

		return "<div class='sfp-block sfp-block-pagination' style=\"$style\">$content</div>";
	}

	public function pagination( $attr ) {
		ob_start();
		woocommerce_pagination();

		return ob_get_clean();
	}

	public function sfp_blocks_mini_cart( $attr ) {
		$content = $this->mini_cart( $attr );
		$style   = $this->block_font_styles( $attr );
		$classes = 'sfp-block sfp-block-mini-cart';

		return "<div class='$classes' style=\"$style\">$content</div>";
	}

	public function mini_cart( $attr ) {
		ob_start();
		if ( is_admin() || ! WC()->cart ) {
			$subtotal = wp_kses_post( wc_price( '17.98' ) );
			$count = 2;
		} else {
			$subtotal = wp_kses_post( WC()->cart->get_cart_subtotal() );
			$count = WC()->cart->get_cart_contents_count();
		}

		$content_classes = 'sfp-block-mini-cart-content';
		if ( ! empty( $attr['popup_position'] ) ) {
			$content_classes .= " sfp-block-mini-cart-$attr[popup_position]";
		}

		?>
		<a class="sfp-block-mini-cart-link" href="<?php echo esc_url( wc_get_cart_url() ); ?>"
			 title="<?php esc_attr_e( 'View your shopping cart', 'sfp-blocks' ); ?>">
			<?php echo $subtotal; ?>
			<span class="count">
			<?php
			/* translators: %d: number of items in cart */
			echo wp_kses_data( sprintf(
				_n( '%d item', '%d items', $count, 'sfp-blocks' ),
				$count
			) );
			?>
			</span>
		</a>
		<ul class='<?php echo $content_classes; ?>'>
			<li>
				<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
			</li>
		</ul>
		<?php
		return ob_get_clean();
	}

	public function sfp_blocks_shop_controls( $attr ) {
		$content = $this->shop_controls( $attr );
		$style   = $this->block_font_styles( $attr );

		return "<div class='sfp-block sfp-block-shop-controls' style=\"$style\">$content</div>";
	}

	public function shop_controls( $attr ) {
		ob_start();

		return ob_get_clean();
	}
}

