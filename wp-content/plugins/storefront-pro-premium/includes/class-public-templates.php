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
 * @version  1.0.0
 * @since 1.0.0
 * @package  Storefront_Pro
 */
class Storefront_Pro_Public_Templates extends Storefront_Pro_Abstract {

	public function init_templates() {

		// Post archives
		add_filter( 'home_template', array( $this, 'blog_layout' ) );
		add_filter( 'archive_template', array( $this, 'blog_layout' ) );
		add_filter( 'search_template', array( $this, 'blog_layout' ) );

		// Single post
		add_filter( 'single_template', array( $this, 'post_layout' ) );

		// Home
		add_action( 'wp', array( $this, 'header_hero_init' ), 10 );

		add_filter( 'storefront_homepage_content_styles', array( $this, 'shop_homepage_content_styles' ) );
	}

	public function shop_homepage_content_styles( $styles ) {
		if ( function_exists( 'is_shop' ) && is_shop() ) {
			$image = get_the_post_thumbnail_url( wc_get_page_id( 'shop' ) );
			$styles['background-image'] = 'url(' . $image . ')';
		}
		return $styles;
	}

	/**
	 * Filters the blog template
	 * @return string Template path
	 */
	function blog_layout( $template ) {
		$layout = $this->get( 'blog-layout' );
		$dir    = dirname( __FILE__ );

		if ( ! empty( $layout ) && file_exists( "$dir/template/home-{$layout}.php" ) ) {
			global $sfp_blog_grid, $sfp_blog_across, $sfp_blog_down;
			if ( is_home() ) {
				remove_action( 'storefront_sidebar', 'storefront_get_sidebar' );
				Storefront_Pro_Public::$desktop_css .= '.storefront-pro-active #primary.content-area{ width: 100%; margin: auto; }';
			}
			$sfp_blog_across = $sfp_blog_grid[0];
			$sfp_blog_down   = $sfp_blog_grid[1];

			return "$dir/template/home-{$layout}.php";
		} else {
			return $template;
		}
	}

	/**
	 * Filters the blog template
	 * @return string Template path
	 */
	function post_layout( $template ) {
		$layout = get_option( 'sfp_post_layout' );
		$dir    = dirname( __FILE__ );

		if ( is_singular( 'post' ) && ! empty( $layout ) && file_exists( "$dir/template/single-{$layout}.php" ) ) {
			return "$dir/template/single-{$layout}.php";
		}

		return $template;
	}

	/**
	 * Output home hero section
	 */
	public function header_hero_init() {
		if ( is_page() ) {

			$use_home_hero = is_front_page() || is_page_template( 'template-homepage.php' );
			$header_hero   = $use_home_hero ? $this->get( 'home-header' ) : $this->get( 'sitewide-header' );
			if ( $header_hero && ( $use_home_hero || has_post_thumbnail( get_the_ID() ) ) ) {
				add_action( 'wp_head', array( $this, 'header_hero_styles' ), 10 );

				if ( ! $use_home_hero || ! $this->get( 'home-header-position' ) ) {
					$page_custo_filter = [ $this, 'header_hero_options_filter', ];
					add_filter( 'storefront_pro_filter_mod_header-over-content', $page_custo_filter, 10, 2 );
					add_filter( 'storefront_pro_filter_mod_header-over-content-color', $page_custo_filter, 10, 2 );
				}
				remove_action( 'homepage', 'storefront_homepage_content', 10 );
				remove_action( 'storefront_page', 'storefront_page_header', 10 );
				add_action( 'storefront_page', array( $this, 'page_header' ), 10 );

				add_action( 'storefront_before_content', array( $this, "header_hero_$header_hero" ), 9 );
			}
		} elseif ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) ) {
			$this->wc_header_hero_init();
		}
	}

	private function wc_header_hero_init() {
		$header_hero = is_shop() ? $this->get( 'shop-header' ) : $this->get( 'shop-cat-header' );
		$method = "shop_header_$header_hero";
		if ( method_exists( $this, $method ) ) {
			add_action( 'storefront_before_content', array( $this, $method ), 9 );
		}
	}

	public function page_header() {
		?>
		<header class="entry-header">
			<?php
			the_title( '<h1 class="entry-title">', '</h1>' );
			?>
		</header><!-- .entry-header -->
		<?php
	}

	public function header_hero_styles() {
		include 'template/header-hero-styles.php';
	}

	/**
	 * Output home hero section
	 */
	public function header_hero_options_filter( $val, $key ) {
		$vals = [
			'header-over-content'       => '1',
			'header-over-content-color' => 'rgba(0,0,0,0)',
		];
		if ( isset( $vals[ $key ] ) ) {
			return $vals[ $key ];
		}
	}

	/**
	 * Output home hero section
	 */
	public function header_hero_image() {
		include 'template/header-hero-image.php';
	}

	/**
	 * Output home hero video
	 */
	public function header_hero_video() {
		include 'template/header-hero-video.php';
	}

	/**
	 * Output home hero posts slider
	 */
	public function header_hero_slider() {
		wp_enqueue_script( 'flexslider' );
		wp_enqueue_style( 'sfp-flexslider-styles' );
		include 'template/header-hero-slider.php';
	}

	/**
	 * Output home hero products slider
	 */
	public function header_hero_products() {
		wp_enqueue_script( 'flexslider' );
		wp_enqueue_style( 'sfp-flexslider-styles' );
		include 'template/header-hero-products.php';
	}

	public function excerpt_length() {
		return 50;
	}

	public function shop_header_cat_img() {
		global $wp_query;

		// get the query object
		$cat = $wp_query->get_queried_object();

		// get the thumbnail id using the queried category term_id
		if ( is_shop() ) {
			$image = get_the_post_thumbnail_url( wc_get_page_id( 'shop' ) );
		} else {
			$thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
			$image = wp_get_attachment_url( $thumbnail_id );
		}

		if ( ! $image ) {
			return;
		}

		$height = $this->get( 'shop-header-height' );
		?>
		<style>
			#masthead {
				margin-bottom: 0;
			}
			#category-header-img {
				height: <?php echo $height ? $height . 'px' : '50vh' ?>;
				min-height: 120px;
				background: center/cover;
				box-sizing: content-box;
			}
		</style>
		<div id="category-header-img" style="background-image: url(<?php echo $image ?>);"></div>
		<?php
	}

	public function shop_header_cat_img_parallax() {
		?>

		<style>
			div#category-header-img {
				background: fixed center/cover;
			}
		</style>

		<?php $this->shop_header_cat_img() ?>

		<?php
	}

	public function shop_header_feat_prods() {
		include 'template/shop-header-slider.php';
	}
}