<?php

/**
 * WooBuilder blocks public class
 */
class WooBuilder_Blocks_Public {

	/** @var WooBuilder_Blocks_Public Instance */
	private static $_instance = null;

	/* @var string $token Plugin token */
	public $token;

	/* @var string $url Plugin root dir url */
	public $url;

	/* @var string $path Plugin root dir path */
	public $path;

	/* @var string $version Plugin version */
	public $version;
	/** @var string Gallery image size */
	protected $_gallery_image_size;
	/** @var string[] Videos to add to gallery */
	protected $videos = [];
	/** @var string[] Video options */
	protected $video_options = [
		'first_video_url',
		'first_video_file',
		'first_video_thumb',
		'last_video_url',
		'last_video_file',
		'last_video_thumb',
	];
	private $product_description;
	private $rendering_woobuilder;
	private $add_to_cart_text;
	private $stock_status;
	private $product_context;
	private $should_enqueue_block_scripts;

	/** @var $fse_template_active bool Whether FSE theme is active */
	private $fse_template_active;

	/**
	 * Constructor function.
	 * @access  private
	 * @since   1.0.0
	 */
	private function __construct() {
		$this->token   = WooBuilder_Blocks::$token;
		$this->meta_keys   = WooBuilder_Blocks::meta_keys();
		$this->url     = WooBuilder_Blocks::$url;
		$this->path    = WooBuilder_Blocks::$path;
		$this->version = WooBuilder_Blocks::$version;
	}

	/**
	 * WooBuilder blocks public class instance
	 * @return WooBuilder_Blocks_Public instance
	 */
	public static function instance() {
		if ( null == self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	// region WooBuilder product frontend setup

	public function setup_product_render() {
		add_action( 'woobuilder_render_product', [ $this, 'render_init' ], 0 );
		add_action( 'woobuilder_render_product', [ $this, 'apply_product_settings' ] );
		add_action( 'woobuilder_render_product', 'the_content' );
		add_action( 'woobuilder_render_product', [ $this, 'render_finish' ], 99 );
		add_action( 'woobuilder_render_product', [ wc()->structured_data, 'generate_product_data' ] );
	}

	public function render_init() {
		$this->rendering_woobuilder = true;
	}

	public function render_finish() {
		$this->rendering_woobuilder = false;
	}


	/**
	 * Sets up WooBuilder for single product when enabled.
	 */
	public function maybe_setup_woobuilder_product() {
		if ( WooBuilder_Blocks::enabled() ) {

			if ( function_exists( 'gencwooc_single_product_loop' ) && has_action( 'genesis_loop', 'gencwooc_single_product_loop' ) ) {
				remove_action( 'genesis_loop', 'gencwooc_single_product_loop' );
				add_action( 'genesis_loop', [ $this, 'gencwooc_single_product_template' ] );
			}
			// Priority more than storefront pro 999
			add_filter( 'wc_get_template_part', array( $this, 'wc_get_template_part' ), 1001, 3 );
		}
	}

	public function gencwooc_single_product_template() {
		/**
		 * woocommerce_before_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
		?>

		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>

			<?php wc_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>

		<?php
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	}

	/**
	 * Adds front end stylesheet and js
	 * @since 1.0.0
	 */
	public function wc_get_template_part( $template, $slug, $name ) {
		if (
			'content' == $slug &&
			'single-product' == $name
		) {
			return dirname( __FILE__ ) . '/tpl/single-product.php';
		}

		return $template;
	}

	/**
	 * Removes description tab to avoid potential recursion.
	 *
	 * @param array $tabs
	 *
	 * @return array
	 */
	public function product_tabs( $tabs ) {

		if ( ! $this->fse_template_active ) {
			if ( $this->product_description ) {
				$tabs['description'] = array(
					'title'    => __( 'Description', 'woocommerce' ),
					'priority' => 10,
					'callback' => [ $this, 'product_description_tab' ],
				);
			} else {
				unset( $tabs['description'] );
			}
		}
		return $tabs;
	}

	public function product_description_tab() {
		echo $this->product_description;
	}

	// endregion WooBuilder product frontend setup

	public function product_actions() {
		if ( isset( $_POST['woobk-nonce'] ) ) {
			if ( wp_verify_nonce( $_POST['woobk-nonce'], 'woobk-action' ) ) {
				if ( $_POST['sfpbk-pt-prods'] ) {

					if ( empty( $_POST['sfpbk-pt-variations'] ) ) {
						$_POST['sfpbk-pt-variations'] = [];
					}

					if ( $_POST['action'] === 'quote' ) {
						$this->product_action_request_quote();
					}
				}
			}
		}
	}

	public function product_action_request_quote() {
		$emails = WC_Emails::instance();

		/** @var SFPBK_Request_Quote_Email $quote_email */
		$quote_email = $emails->get_emails()['SFPBK_Request_Quote_Email'];

		wc_add_notice( strip_tags( $quote_email->trigger() ) );

	}

	public function init() {
		$this->register_blocks();
		$this->register_meta();
		$this->setup_product_render();
	}

	public function apply_product_settings() {

		$id = apply_filters( 'woobuilder_blocks_rendered_post_id', get_the_ID() );

		$bg_color = get_post_meta( $id, 'woobk_bg_color', 'single' );
		$bg_image = get_post_meta( $id, 'woobk_bg_image', 'single' );
		$bg_parallax = get_post_meta( $id, 'woobk_bg_parallax', 'single' );
		$bg_gradient = get_post_meta( $id, 'woobk_bg_gradient', 'single' );
		$display_header = get_post_meta( $id, 'woobk_hide_header', 'single' );
		$display_sidebar = get_post_meta( $id, 'woobk_hide_sidebar', 'single' );
		$display_footer = get_post_meta( $id, 'woobk_hide_footer', 'single' );
		$this->add_to_cart_text = get_post_meta( $id, 'woobk_add_to_cart_text', 'single' );

		$css = '';

		if ( 'hide' === $display_header ) {
			$css .= '#main-header, #masthead, #header, #site-header, .site-header, .tc-header{ display: none; }';
		}

		$css .= ".content-bg, body.content-style-unboxed .site {background: none!important;}";
		$css .= "html body {min-height: 100vh;";
		if ( ! empty( $bg_color ) ) {
			$css .= "background-color : {$bg_color} !important;";
		}
		if ( ! empty( $bg_image ) ) {
			$css .= "background: center/cover url({$bg_image}) !important;";
		}

		if ( ! empty( $bg_parallax ) ) {
			$css .= "background-attachment: fixed !important;";
		}
		if ( ! empty( $bg_gradient ) ) {
			$css .= "background-image: {$bg_gradient} !important;";
		}
		$css .= "}";
		$css .= "html body.admin-bar {min-height: calc(100vh - 32px);}";


		if ( 'hide' === $display_sidebar ) {
			$css .= "aside, .sidebar, .side-bar, #secondary {display : none !important;}";
			$css .= "#content, .content, .content-area { width : 100% !important;}";
			$css .= ".ast-right-sidebar #primary, .ast-left-sidebar #primary{border:none; padding : 0!important}";
		}

		if ( 'hide' === $display_footer ) {
			$css .= '.colophon, #footer, #main-footer, #site-footer, .site-footer{ display: none; }';
		}

		echo "<style id='woobuilder-blocks-meta-settings'>$css</style>";
	}

	public function register_meta() {
		$post_meta_args = [
			'show_in_rest' => true,
			'single'       => true,
			'type'         => 'string',
		];

		foreach ( $this->meta_keys as $meta_key ) {
			register_post_meta( 'product', $meta_key, $post_meta_args );
		}
	}

	public function register_blocks() {
		$blocks = WooBuilder_Blocks::blocks();

		foreach ( $blocks as $block ) {
			register_block_type(
				str_replace( '_', '-', "woobuilder/$block" ),
				[
					'render_callback' => [ $this, "render_$block" ],
					'supports'        => [],
				]
			);
		}
	}

	public function enable_rest_taxonomy( $args ) {
		$args['show_in_rest'] = true;

		return $args;
	}

	private function openWrap( $props, $class, $tag = 'div', $style = '' ) {

		$pre = '';

		if ( ! empty( $props['className'] ) ) {
			$class .= " $props[className]";
		}

		if ( ! empty( $props['text_align'] ) ) {
			$style .= "text-align:{$props['text_align']};";
		}

		if ( ! empty( $props['font_size'] ) ) {
			$style .= "font-size:{$props['font_size']}px;";
		}
		if ( ! empty( $props['font'] ) ) {
			$props['font'] = stripslashes( $props['font'] );
			$style         .= "font-family:{$props['font']};";
		}
		if ( ! empty( $props['text_color'] ) ) {
			$style .= "color:{$props['text_color']};";
		}
		if ( ! empty( $props['woobuilder_style'] ) ) {
			$class .= " woobuilder-style-$props[woobuilder_style]";
		}

		if ( $style ) {
			$style = 'style="' . $style . '"';
		}

		$this->openedWrapTag = $tag;

		if ( ! empty( $props['product_id'] ) || $this->fse_template_active ) {
			$pre .= '<div class="single-product woocommerce woocommerce-page"><div class="product woobuilder">';
		}

		return "$pre<$tag class='woobuilder-block woobuilder-$class' $style>";
	}

	public function closeWrap( $props ) {
		$ret = '';

		$ret .= "</{$this->openedWrapTag}>";
		$this->openedWrapTag = '';

		if ( ! empty( $props['product_id'] ) || $this->fse_template_active ) {
			$ret .= '</div></div>';
		}

		return $ret;
	}


	public function render_title( $props ) {
		if ( ! empty( $props['product_id'] ) ) {
			$this->setup_product_context( $props['product_id'] );
		}

		$render =
			$this->openWrap( $props, 'title entry-title', 'h1' ) .
			get_the_title() .
			$this->closeWrap( $props );

		$this->reset_product_context();

		return $render;
	}

	public function render_rating( $props ) {
		global $product;

		if ( ! empty( $props['product_id'] ) ) {
			$this->setup_product_context( $props['product_id'] );
		}

		if ( ! $product ) {
			return '';
		}

		ob_start();
		echo $this->openWrap( $props, 'rating' );
		$rating_count = $product->get_rating_count();
		$review_count = $product->get_review_count();
		$average      = $product->get_average_rating();
		?>
		<div class="woobuilder-product-rating">
			<?php echo wc_get_rating_html( $average, $rating_count ); ?>
			<?php if ( $rating_count > 0 && comments_open() ) : ?>
				<a href="#reviews" class="woobuilder-review-link" rel="nofollow">
				(<?php printf( _n( '%s customer review', '%s customer reviews', $review_count, 'woocommerce' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ); ?>
				)</a><?php endif ?>
		</div>
		<?php
		echo $this->closeWrap( $props );

		$this->reset_product_context();
		return ob_get_clean();
	}

	public function render_add_to_cart( $props ) {
		global $product;

		if ( ! empty( $props['product_id'] ) ) {
			$this->setup_product_context( $props['product_id'] );
		}

		if ( ! $product ) {
			return '';
		}

		ob_start();

		$class = "add-to-cart";

		if ( ! empty( $props['attributes_layout'] ) ) {
			$class .= " woobk-attr-lay-$props[attributes_layout]";
		}

		echo $this->openWrap( $props, $class );
		if ( ! empty( $props['product_id'] ) ) {
			woocommerce_template_loop_add_to_cart();
		} else {
			woocommerce_template_single_add_to_cart();
		}
		echo $this->closeWrap( $props );

		$this->reset_product_context();
		return ob_get_clean();
	}

	public function render_add_to_cart_sticky( $props ) {
		global $product;

		if ( ! empty( $props['product_id'] ) ) {
			$this->setup_product_context( $props['product_id'] );
		}

		if ( ! $product ) {
			return '';
		}

		$style = "background-color:$props[bg_colour];" .
						 "--woobk-btn-color:$props[button_colour];--woobk-btn-text:$props[button_text];";

		$classes = 'add-to-cart-sticky';

		if ( ! empty( $props['show_on_scroll'] ) ) {
			$classes .= ' woobk-a2cs-show-on-scroll';
		}

		ob_start();

		echo $this->openWrap( $props, $classes, 'div', $style );

		echo '<div class="woobk-a2cs-wrap ">';
		echo '<div class="woobk-a2cs-img">';
		echo get_the_post_thumbnail( $product->get_id(), 'post-thumbnail' );
		echo '</div>';

		echo '<div class="woobk-a2cs-info">';
		the_title( '<h3 class="woobk-a2cs-title">', '</h3>' );
		echo $product->get_price_html();
		echo '</div>';
		echo '<div class="woobk-a2cs-cart">';
		woocommerce_template_single_add_to_cart();
		echo '</div>';
		echo '</div>';

		echo $this->closeWrap( $props );

		$this->reset_product_context();
		return ob_get_clean();
	}

	public function render_cover( $props, $content = '' ) {
		global $product;

		if ( ! empty( $props['product_id'] ) ) {
			$this->setup_product_context( $props['product_id'] );
		}

		if ( ! $product ) {
			return '';
		}

		ob_start();

		$parallax = empty( $props['Parallax'] ) ? '' : 'background-attachment:fixed;';
		$full     = empty( $props['Full width'] ) ? '' : ' vw-100';

		echo $this->openWrap(
			$props, "cover-wrap bg-center ph4 cover flex flex-column justify-center relative $full $props[BlockAlignment]", 'div',
			'min-height:' . $props['Min height'] . 'px;' . $parallax .
			'background-image:url(' . get_the_post_thumbnail_url( $product->get_id(), 'large' ) . '")'
		);

		echo $content;

		echo $this->closeWrap( $props );

		$this->reset_product_context();
		return ob_get_clean();
	}

	public function render_wc_hook( $props ) {
		if ( ! empty( $props['product_id'] ) ) {
			$this->setup_product_context( $props['product_id'] );
		}

		$hook = $props['hook'];

//		die( "$hook $this->rendering_woobuilder" );

		if ( ! $this->rendering_woobuilder ) {
			return '';
		}

		$tpl_hooks = [
			'woocommerce_before_single_product_summary' => [
				'woocommerce_show_product_sale_flash',
				'woocommerce_show_product_images',
			],
			'woocommerce_single_product_summary'        => [
				'woocommerce_template_single_title',
				'woocommerce_template_single_rating',
				'woocommerce_template_single_price',
				'woocommerce_template_single_excerpt',
				'woocommerce_template_single_add_to_cart',
				'woocommerce_template_single_meta',
				'woocommerce_template_single_sharing',
				[ wc()->structured_data, 'generate_product_data' ],

			],
			'woocommerce_after_single_product_summary'  => [
				'woocommerce_output_product_data_tabs',
				'woocommerce_upsell_display',
				'woocommerce_output_related_products',
			],
		];

		$unhooked = [];

		if ( ! empty( $tpl_hooks[ $hook ] ) ) {
			foreach ( $tpl_hooks[ $hook ] as $tpl_hook ) {
				$action_prio = has_action( $hook, $tpl_hook );
				if ( $action_prio ) {
					$unhooked[] = [ $tpl_hook, $action_prio ];
					remove_action( $hook, $tpl_hook, $action_prio );
				}
			}
		}
		ob_start();

		do_action( $hook );

		foreach ( $unhooked as $tpl_hook ) {
			add_action( $hook, $tpl_hook[0], $tpl_hook[1] );
		}

		$this->reset_product_context();
		return ob_get_clean();
	}

	public function render_stock_countdown( $props ) {
		global $product;

		if ( ! empty( $props['product_id'] ) ) {
			$this->setup_product_context( $props['product_id'] );
		}

		if ( ! $product ) {
			return '';
		}

		$stock = $product->get_stock_quantity();

		if ( ! $stock ) {
			return '';
		}

		$max     = max( $stock, $props['max'] );
		$percent = $stock / $max * 100;

		ob_start();
		echo $this->openWrap( $props, 'stock-countdown' );
		echo "<style>.woobuilder.product p.stock{display:none}</style>";

		echo "<div class='woobk-stock-countdown-bar' style='background:$props[track_color];'>" .
				 "<div class='woobk-stock-countdown-bar-left' style='background:$props[active_color];width:$percent%;'></div>" .
				 "</div>";
		if ( $stock > 1 ) {
			printf( $props['message'], '' . $stock );
		} else {
			echo $props['message1'];
		}
		echo $this->closeWrap( $props );

		$this->reset_product_context();
		return ob_get_clean();
	}

	public function render_sale_counter( $props ) {
		global $product;

		if ( ! empty( $props['product_id'] ) ) {
			$this->setup_product_context( $props['product_id'] );
		}

		if ( ! $product ) {
			return '';
		}

		/** @var WC_Product $product */
		// Declare and define two dates
		$date1 = strtotime( $product->get_date_on_sale_to() );
		$diff  = $date1 - time();

		if ( ! $diff || $diff < 5 ) {
			return '<div></div>';
		}

		$props = wp_parse_args( $props, [
			'active_color' => '#555',
			'track_color'  => '#ddd',
			'track_width'  => '2',
		] );

		ob_start();

		echo $this->openWrap( $props, 'sale_counter_wrap' );
		echo "<div class='woobuilder-sale_counter' data-date-end='$date1'>";

		$days = floor( $diff / ( 60 * 60 * 24 ) );

		$hours = floor( $diff % ( 60 * 60 * 24 ) / ( 60 * 60 ) );

		$minutes = floor( $diff % ( 60 * 60 ) / 60 );

		$seconds = floor( $diff % 60 );

		$r      = 15.9154; // 100/2PI
		$center = $r + $props['track_width'] / 2;

		$width = 2 * $center;


		$circle_attrs = "cx=$center cy=$center r='{$r}' stroke-width='{$props['track_width']}' " .
										"style='transform-origin:50%% 50%%;transform:rotate(-90deg);' fill='none'";

		$format =
			'<div class="woob-timr woob-timr-%1$s">' .
			"<svg viewBox='0 0 $width $width'>" .
			"<circle $circle_attrs stroke='{$props['track_color']}' />" .
			"<circle $circle_attrs stroke='{$props['active_color']}' class='woob-timr-arc-%1\$s' />" .
			'</svg>' .
			'<div class="woob-timr-number-%1$s woob-timr-number">%3$s</div>' .
			'<div class="woob-timr-label">%4$s</div>' .
			'</div>';
		$sep    = '<div class="woob-timr-sep"></div>';

		echo $days ? sprintf( $format, 'days', $days * 100 / 31, $days, _n( 'day', 'days', $days ) ) . $sep : '';

		echo sprintf( $format, 'hours', $hours * 100 / 24, $hours, _n( 'hour', 'hours', $hours ) ) . $sep;

		echo sprintf( $format, 'minutes', $minutes * 100 / 60, $minutes, _n( 'minute', 'minutes', $minutes ) ) . $sep;

		echo sprintf( $format, 'seconds', $seconds * 100 / 60, $seconds, _n( 'second', 'seconds', $seconds ) );

		echo '</div>';
		echo $this->closeWrap( $props );

		$this->reset_product_context();
		return ob_get_clean();
	}

	public function render_related_products( $props ) {
		global $product;

		if ( ! $product || is_admin() ) {
			return '';
		}

		ob_start();
		echo $this->openWrap( $props, 'related_products' );
		woocommerce_related_products();
		echo $this->closeWrap( $props );

		$this->reset_product_context();
		return ob_get_clean();
	}

	public function render_upsell_products( $props ) {
		global $product;

		if ( ! empty( $props['product_id'] ) ) {
			$this->setup_product_context( $props['product_id'] );
		}

		if ( ! $product || is_admin() ) {
			return '';
		}

		ob_start();
		echo $this->openWrap( $props, 'upsell_products' );
		woocommerce_upsell_display();
		echo $this->closeWrap( $props );

		$this->reset_product_context();
		return ob_get_clean();
	}

	public function render_product_price( $props ) {
		global $product;

		if ( ! empty( $props['product_id'] ) ) {
			$this->setup_product_context( $props['product_id'] );
		}

		if ( ! $product ) {
			return '';
		}

		$render =
			$this->openWrap( $props, 'price' ) .
			$product->get_price_html() .
			$this->closeWrap( $props );

		$this->reset_product_context();

		return $render;
	}

	public function render_excerpt( $props ) {
		global $product, $post;

		if ( ! empty( $props['product_id'] ) ) {
			$this->setup_product_context( $props['product_id'] );
		}

		if ( ! $product ) {
			return '';
		}

		$short_description = apply_filters( 'woocommerce_short_description', $post->post_excerpt );

		$render = $this->openWrap( $props, 'excerpt' ) . $short_description . $this->closeWrap( $props );

		$this->reset_product_context();

		return $render;
	}

	public function render_meta( $props ) {
		global $product;

		if ( ! empty( $props['product_id'] ) ) {
			$this->setup_product_context( $props['product_id'] );
		}

		if ( ! $product ) {
			return '';
		}

		ob_start();
		echo $this->openWrap( $props, 'meta' );

		do_action( 'woocommerce_product_meta_start' );

		$metadata = '';
		$sku      = $product->get_sku();
		if ( $sku ) {
			$metadata .= "<span class='woobuilder-sku'>" . __( 'SKU:', 'woocommerce' ) . " $sku</span> ";
		}
		$metadata .= wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woocommerce' ) . ' ', '</span> ' );
		$metadata .= wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ) . ' ', '</span> ' );
		echo apply_filters( 'woobuilder_product_meta', $metadata );

		do_action( 'woocommerce_product_meta_end' );

		echo $this->closeWrap( $props );

		$this->reset_product_context();
		return ob_get_clean();
	}

	public function render_reviews( $props ) {
		global $product;

		if ( ! empty( $props['product_id'] ) ) {
			$this->setup_product_context( $props['product_id'] );
		}

		if ( ! $product ) {
			return '';
		}

		ob_start();
		echo $this->openWrap( $props, 'reviews' );
		comments_template();
		echo $this->closeWrap( $props );

		$this->reset_product_context();
		return ob_get_clean();
	}

	public function woocommerce_gallery_image_size( $size ) {
		if ( $this->_gallery_image_size ) {
			return $this->_gallery_image_size;
		}

		return $size;
	}

	public function render_images( $props ) {
		global $product;

		$css = 'z-index: 1;position: relative;';

		if ( ! empty( $props['product_id'] ) ) {
			$this->setup_product_context( $props['product_id'] );
		}

		$this->videos = [];

		if ( ! empty( $props['img_size'] ) ) {
			$this->_gallery_image_size = $props['img_size'];
		}

		foreach ( $this->video_options as $video_option ) {
			if ( ! empty( $props[ $video_option ] ) ) {
				$this->videos[ $video_option ] = $props[ $video_option ];
			}
		}

		if ( $this->videos ) {
			add_filter( 'woocommerce_single_product_image_thumbnail_html', [ $this, 'add_gallery_video' ] );
			add_action( 'woocommerce_product_thumbnails', [ $this, 'woocommerce_product_thumbnails' ], 25 );
		}

		if ( ! $product ) {
			return '';
		}

		$css .= ! empty( $props['img_radius'] ) ? '--img_radius:' . $props['img_radius'] . 'px;' : '';
		if ( ! empty( $props['width'] ) ) {
			$css .= "width:$props[width]px;";
		}
		if ( ! empty( $props['alignment'] ) ) {
			$css .= "float:$props[alignment];";
		} else {
			$css .= 'margin:auto;';
		}

		ob_start();
		echo "<div style='$css'>";
		echo $this->openWrap( $props, 'images', 'div' );
		add_action( 'woocommerce_gallery_image_size', [ $this, 'woocommerce_gallery_image_size' ], 999 );
		woocommerce_show_product_images();
		remove_action( 'woocommerce_gallery_image_size', [ $this, 'woocommerce_gallery_image_size' ], 999 );
		echo $this->closeWrap( $props );
		echo '</div>';

		$this->reset_product_context();
		return ob_get_clean();
	}

	public function render_images_carousel( $props ) {
		global $product;

		if ( ! empty( $props['product_id'] ) ) {
			$this->setup_product_context( $props['product_id'] );
		}

		if ( ! $product || ! function_exists( 'wc_get_gallery_image_html' ) ) {
			return '';
		}

		ob_start();
		echo $this->openWrap( $props, 'images_carousel flexslider o-0' );
		$slide_attachments = $product->get_gallery_image_ids();
		array_splice( $slide_attachments, 0, 0, + $product->get_image_id() );
		?>
		<ul class="slides">
			<?php
			if ( ! empty( $this->videos['first_video_url'] ) ) {
				echo "<li>" . $this->gallery_video_html( $this->videos['first_video_url'] ) . '</li>';
			}
			if ( ! empty( $this->videos['first_video_file'] ) ) {
				echo "<li>" . $this->gallery_video_html5( $this->videos['first_video_file'] ) . '</li>';
			}

			if ( $slide_attachments ) {
				foreach ( $slide_attachments as $attachment ) {
					echo '<li>';
					echo wp_get_attachment_image( $attachment, 'large' );
					echo '</li>';
				}
			}

			if ( ! empty( $this->videos['last_video_url'] ) ) {
				echo "<li>" . $this->gallery_video_html( $this->videos['last_video_url'] ) . '</li>';
			}
			if ( ! empty( $this->videos['last_video_file'] ) ) {
				echo "<li>" . $this->gallery_video_html5( $this->videos['last_video_file'] ) . '</li>';
			}
			?>
		</ul>
		<div class="woobuilder-images_carousel-navigation">
			<a href="#" class="flex-prev"></a>
			<a href="#" class="flex-next"></a>
		</div>
		<?php
		echo $this->closeWrap( $props );

		$this->reset_product_context();
		return ob_get_clean();
	}

	public function reset_product_context( $context_set = null ) {
		global $product;

		if ( ! $context_set ) {
			$context_set = $this->product_context;
		}

		if ( $context_set ) {
			$this->product_context = null;
			wp_reset_postdata();
			$product = wc_get_product();
		}
	}

	public function setup_product_context( $id = false ) {
		global $product;

		$this->should_enqueue_block_scripts = true;

		$this->product_context = $id;

		$query = new WP_Query( [
			'p' => $id,
			'post_type' => 'product',
		] );

		if ( $query->have_posts() ) {
			$query->the_post();
			$product = wc_get_product();
		}
	}

	private function gallery_video_html( $video_url ) {
		global $wp_embed;

		return
			"<div class='aspect-ratio--4x3 relative woobk-video'>" .
			$wp_embed->autoembed( $video_url ) .
			"</div>";
	}

	private function gallery_video_html5( $video_url ) {
		global $wp_embed;

		return "<div class='aspect-ratio--4x3 relative woobk-video'>" .
					 "<video controls><source src='$video_url' type='video/mp4'></video>" .
					 "</div>";
	}

	public function render_request_quote( $props ) {
		global $product;

		if ( ! empty( $props['product_id'] ) ) {
			$this->setup_product_context( $props['product_id'] );
		}

		if ( ! $product ) {
			return '';
		}

		$pid = $product->get_id();

		ob_start();
		echo $this->openWrap( $props, 'request_quote' );

		echo '<form action="#" method="post" class="cart flex items-center">';

		if ( method_exists( $product, 'get_available_variations' ) ) {
			$variations = $product->get_available_variations();
			if ( $variations ) {
				echo '<select name="sfpbk-pt-variations[' . $product->get_id() . ']">';
				foreach ( $variations as $var ) {
					$label = array_map( function ( $itm ) {
						return str_replace( '-', ' ', $itm );
					}, $var['attributes'] );
					$label = ucfirst( implode( ', ', $label ) );
					echo "<option value='$var[variation_id]'>$label</option>";
				}
				echo '</select>';
			}

		}

		echo
			wp_nonce_field( 'woobk-action', 'woobk-nonce', 0, 0 ) .
			"<div class='quantity'><input required class='qty' type='number' name='sfpbk-pt-prods[$pid]' value='1'></div>" .
			"<a href='#woobk-quote-dialog' class='button'>" . __( 'Request quote', 'woobuilder-blocks' ) . '</a>';

		$this->request_quote_dialog();

		echo '</form>';
		echo $this->closeWrap( $props );

		$this->reset_product_context();
		return ob_get_clean();
	}

	public function request_quote_dialog() {
		?>
		<div class="absolute--fill" id="woobk-quote-dialog">
			<a class="absolute--fill" href="#_">&nbsp;</a>
			<div class="woobk-fields relative">
				<input required name="requester_name" type="text" placeholder="<?php _e( 'Full name', 'woobuilder-blocks' ) ?>">
				<input required name="requester_email" type="email" placeholder="<?php _e( 'Email', 'woobuilder-blocks' ) ?>">
				<textarea name="requester_message" placeholder="<?php _e( 'Message', 'woobuilder-blocks' ) ?>"></textarea>
				<button name='action' value='quote'><?php _e( 'Send request for quote', 'woobuilder-blocks' ) ?></button>
			</div>
		</div>
		<?php
	}

	public function render_long_description( $props, $content ) {

		return apply_filters( 'woobuilder_long_description', $content );
	}

	public function render_tabs( $props ) {
		global $product;

		if ( ! empty( $props['product_id'] ) ) {
			$this->setup_product_context( $props['product_id'] );
		}

		$props = wp_parse_args( $props, [
			'layout' => '',
		] );
//
//		echo '<pre>';
//		print_r( $props );
//		echo '</pre>';


		if ( ! $product ) {
			return '';
		}

		if ( ! has_action( 'woocommerce_product_tabs', array( $this, 'product_tabs' ) ) ) {
			add_filter( 'woocommerce_product_tabs', array( $this, 'product_tabs' ), 99, 3 );
		}

		$this->product_description = nl2br( $props['desc'] );
		ob_start();

		// For the horizontal layout of WooBuilder: Product tabs
		if ( $props['layout'] == 'hrzntl-tabs' ) {
			echo $this->openWrap( $props, 'htabs' );
		} else {
			echo $this->openWrap( $props, 'tabs' );
			if ( $props['layout'] == 'accordion') {
				add_action( 'woocommerce_locate_template', array( $this, 'product_tabs_accordion' ), 999, 3 );
			}
		}
		woocommerce_output_product_data_tabs();
		echo $this->closeWrap( $props );

		$this->reset_product_context();
		return ob_get_clean();
	}

	public function product_tabs_accordion( $file, $name ) { // Mars
		if ( 'single-product/tabs/tabs.php' == $name ) {
			return dirname( __FILE__ ) . '/tpl/accordion-tabs.php';
		}

		return $file;
	}

	/**
	 * Adds front end stylesheet and js
	 * @action wp_enqueue_scripts
	 */
	public function enqueue() {
		global $post;

		if ( ! $this->should_enqueue_block_scripts && ( ! $post || $post->post_type != 'product' ) ) {
			return;
		}

		$token = $this->token;
		$url   = $this->url;
		$ver   = $this->version;

		wp_enqueue_style( $token . '-css', $url . '/assets/front.css', null, $ver );
		wp_enqueue_script( $token . '-utils', $url . '/assets/utils.js', [ 'jquery' ], $ver );
	}

	public function flag_fse_template() {
		$this->fse_template_active = true;
		$this->should_enqueue_block_scripts = true;
	}

	public function maybe_woobk_scripts() {

		if ( ! $this->should_enqueue_block_scripts ) {
			return;
		}

		$this->enqueue();

		if ( current_theme_supports( 'wc-product-gallery-zoom' ) ) {
			wp_enqueue_script( 'zoom' );
		}
		if ( current_theme_supports( 'wc-product-gallery-slider' ) ) {
			wp_enqueue_script( 'flexslider' );
		}
		wp_enqueue_script( 'wc-single-product' );
		wp_enqueue_style( 'woocommerce-general' );
	}

	public function add_gallery_video( $html ) {
		remove_filter( 'woocommerce_single_product_image_thumbnail_html', [ $this, 'add_gallery_video' ] );

		$thumb = ! empty( $this->videos['first_video_thumb'] ) ?
			$this->videos['first_video_thumb'] :
			"$this->url/assets/img/vid-icon.svg?first_video_url";

		if ( ! empty( $this->videos['first_video_url'] ) ) {
			$html = "<div data-thumb='$thumb' class='woocommerce-product-gallery__image'>" .
							$this->gallery_video_html( $this->videos['first_video_url'] ) .
							'</div>' . $html;
		}
		if ( ! empty( $this->videos['first_video_file'] ) ) {
			$html = "<div data-thumb='$thumb' class='woocommerce-product-gallery__image'>" .
							$this->gallery_video_html5( $this->videos['first_video_file'] ) .
							'</div>' . $html;
		}

		return $html;
	}

	public function woocommerce_product_thumbnails( $html ) {
		$thumb = ! empty( $this->videos['last_video_thumb'] ) ?
			$this->videos['last_video_thumb'] :
			"$this->url/assets/img/vid-icon.svg?last_video_url";

		if ( ! empty( $this->videos['last_video_url'] ) ) {
			echo "<div data-thumb='$thumb' class='woocommerce-product-gallery__image'>" .
					 $this->gallery_video_html( $this->videos['last_video_url'] ) .
					 '</div>';
		}
		if ( ! empty( $this->videos['last_video_file'] ) ) {
			echo "<div data-thumb='$thumb' class='woocommerce-product-gallery__image'>" .
					 $this->gallery_video_html5( $this->videos['last_video_file'] ) .
					 '</div>';
		}
	}

	public function replace_add_to_cart_text( $html ) {
		if ( $this->add_to_cart_text ) {
			$html = $this->add_to_cart_text;
		}
		return $html;
	}

	public function out_of_stock_message( $msg ) {
		global $product;

		if ( $product ) {
			$out_of_stock_text = get_post_meta( $product->get_id(), 'woobk_out_of_stock_text', 'single' );
			if ( $out_of_stock_text ) {
				return $out_of_stock_text;
			}
		}
		return $msg;
	}

	public function get_availability( $avail, $product ) {
		if ( 'out-of-stock' === $avail['class'] ) {
			$out_of_stock_text = get_post_meta( $product->get_id(), 'woobk_out_of_stock_text', 'single' );
			if ( $out_of_stock_text ) {
				$avail['availability'] = $out_of_stock_text;
			}
		} else if ( 'available-on-backorder' === $avail['class'] ) {
			$on_backorder_text = get_post_meta( $product->get_id(), 'woobk_on_back_order_text', 'single' );
			if ( $on_backorder_text ) {
				$avail['availability'] = $on_backorder_text;
			}
		}
		return $avail;
	}

	public function append_stock_status( $option, $term, $attribute, $product ) {
		if ( empty( $this->stock_status[$option] ) ) {
			return $option;
		}
		$stock_status = $this->stock_status[$option];
		$product_id = $product->get_id();
		switch( $stock_status ) {
			case 'onbackorder':
				$on_back_order_text = get_post_meta( $product_id, 'woobk_on_back_order_text', 'single' );
				if ( $on_back_order_text ) {
					$option .= ' - ' . $on_back_order_text;
				}

				break;
			case 'outofstock':
				$out_of_stock_text = get_post_meta( $product_id, 'woobk_out_of_stock_text', 'single' );
				if ( $out_of_stock_text ) {
					$option .= ' - ' . $out_of_stock_text;
				}
				break;

		}
		return $option;
	}

	public function prepare_variations_map( $args ) {
		foreach ($args['product']->get_visible_children() as $variationId) {
			$variation = new WC_Product_Variation($variationId);
			$attribute = $variation->get_variation_attributes();
			$this->stock_status[reset( $attribute )] = $variation->get_stock_status();
		}
		return $args;
	}

	public function redirect_thankyou_page( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order->has_status( 'failed' ) ) {
			$url = '';
			foreach ($order->get_items() as $item) { //gets the first redirect page
				$product_id = $item->get_data()['product_id'];
				$page_id = get_post_meta( $product_id, 'woobk_thankyou_page', 'single' );
				if (! $page_id ) {
					//get_permalink() will return something even if $page_id is null
					//so we don't try it if there's no $page_id
					continue;
				}
				$url = get_permalink($page_id);
				break;
			}
			if ( $url ) {
				wp_safe_redirect( $url );
			}
		}

		return $order_id;
	}
}