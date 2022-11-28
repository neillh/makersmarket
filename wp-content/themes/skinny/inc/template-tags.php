<?php
/**
 * Custom template tags for this theme.
 *
 * This file is for custom template tags only and it should not contain
 * functions that will be used for filtering or adding an action.
 *
 * @package Skinny\Template_Tags
 */

namespace Skinny;

use function Skinny\WooCommerce\mobile_account_link;
use function Skinny\WooCommerce\shop_header;
use function Skinny\WooCommerce\mobile_cart;
use function Skinny\WooCommerce\header_cart;
use function Skinny\WooCommerce\account_link;

/**
 * Display the site branding section, which includes a logo
 * from Customizer (if set) or site title and description.
 *
 * @param array $args {
 *   Optional. An array of arguments.
 *
 *   @type boolean $description Whether to show the Site Description. Default is true.
 * }
 * @return void
 */
function display_site_branding( $args = array() ) {
	echo "<div class='header__title-inner' itemscope itemtype='https://schema.org/Organization'>"; // phpcs:ignore
	site_branding( $args );
	echo '</div>';
}

/**
 * Render the site branding or the logo.
 *
 * @param array $args Optional arguments.
 *
 * @return void
 */
function site_branding( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'description' => true,
		)
	);

	if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
		the_custom_logo();
	} else {
		$blog_name        = get_bloginfo( 'name' );
		$blog_description = get_bloginfo( 'description' );

		if ( ! empty( $blog_name ) ) {
			echo '<a class="site-title-link" href="' . esc_url( home_url( '/' ) ) . '" itemprop="url">';
			printf(
				'<%1$s class="site-title">' . esc_html( $blog_name ) . '</%1$s>',
				'h2'
			);
			echo '</a>';
		}

		if ( true === $args['description'] && ! empty( $blog_description ) ) :
			echo '<span class="site-description">' . esc_html( $blog_description ) . '</span>';
		endif;
	}
}

/**
 * Display the header search toggle button.
 *
 * @return void
 */
function search_toggle() {
	?>

	<button
		id="header__search-toggle"
		class="header__search-toggle"
		type="button"
		>
		<div class="search-toggle-icon">
			<p><?php esc_html_e( 'Search', 'skinny' ); ?></p>
		</div>
		<span class="screen-reader-text"><?php esc_html_e( 'Search Toggle', 'skinny' ); ?></span>
	</button>

	<?php
}


/**
 * Display the footer widget regions.
 *
 * @return void
 */
function footer_widgets() {
	$columns = absint( skinny_get_thememod( 'footer_widget_cols' ) );

	if ( is_active_sidebar( 'footer-area' ) ) :
		?>
		<div class="block footer-widgets col-<?php echo esc_attr( $columns ); ?>">
			<?php dynamic_sidebar( 'footer-area' ); ?>
		</div>
		<?php
	endif;
}

/**
 * Display single post publish date/categories meta.
 *
 * @return void
 */
function posted_on() {
	$time_string = '<time class="entry-date published" datetime="%1$s"><meta itemprop="datePublished" content="%5$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date updated" datetime="%3$s"><meta itemprop="dateModified" content="%6$s">%4$s</time>';
	}

	$time_string = sprintf(
		$time_string,
		esc_attr( get_the_date( 'M d Y' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'M d Y' ) ),
		esc_html( get_the_modified_date() ),
		esc_attr( get_the_date( 'Y-m-d' ) ),
		esc_attr( get_the_modified_date( 'Y-m-d' ) )
	);

	$posted_on = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';

	$posted_in = sprintf(
	/* translators: %s: post author. */
		esc_html_x( 'in %s', 'post categories', 'skinny' ),
		'<span class="categories"><span itemprop="articleSection">' . get_the_category_list( esc_html__( ', ', 'skinny' ) ) . '</span></span>'
	);

	echo '<span class="posted-on">' . wp_kses_post( $posted_on ) . '</span><span class="posted-in"> ' . wp_kses_post( $posted_in ) . '</span>'; // phpcs:ignore
}

/**
 * Display single post tags if defined.
 *
 * @return void
 */
function post_tags() {
	/* translators: used between list items, there is a space after the comma */
	$tags_list = get_the_tag_list();
	if ( $tags_list ) {
		/* translators: 1: list of tags. */
		printf(
			'<div class="tags-links"><span>%1$s</span> %2$s</div>',
			esc_html__( 'Tags:&nbsp;', 'skinny' ),
			wp_kses_post( $tags_list )
		);
	}
}

/**
 * Display site container classes.
 *
 * @return void
 */
function site_container_class( $content_part = '' ) {
	$classes = 'mx-auto px-large ';
	if ( 'singular' === $content_part ) {
		$classes .= 'container-singular';
	} elseif ( 'singular-content' === $content_part ) {
		$classes .= 'container-singular-content';
	} else {
		$classes .= 'container';
	}
	return $classes;
}

/**
 * Display Searchbar content.
 *
 * @retun void
 */
function searchbar_content() {
	if ( ! skinny_get_thememod( 'search_btn_toggle' ) && ! is_customize_preview() ) {
		return;
	}
	?>
	<div class="search__modal">
		<div class="search__modal-inner">
			<button id="search__close-btn" type="button">
				<span class="screen-reader-text"><?php esc_html_e( 'Search Close Toggle', 'skinny' ); ?></span>
			</button>
			<?php get_template_part( 'template-parts/search', 'form' ); ?>
		</div>
	</div>
	<?php
}
add_action( 'skinny_before_header', __NAMESPACE__ . '\searchbar_content', 10 );

/**
 * Display blog header.
 *
 * @return void
 */
function blog_header() {
	if ( is_singular() ) {
		return;
	}

	// Use the fallback image value from Theme options.
	$image               = skinny_get_thememod( 'blog_header_background' );
	$image               = $image ? 'style=background-image:url(\'' . esc_url( $image ) . '\')' : '';
	$blog_homepage_title = single_post_title( '', false );

	if ( is_home() ) :
		printf(
			'<div class="blog-header" %1$s><div class="blog-header__cover"></div><div class="blog-header__inner %2$s"><h1 class="blog-title">%3$s</h1></div></div>',
			esc_attr( $image ),
			esc_attr( site_container_class() ),
			$blog_homepage_title ? esc_html( $blog_homepage_title ) : esc_html__( 'Blog', 'skinny' )
		);
	elseif ( is_archive() ) :
		printf(
			'<div class="archive-header" %1$s><div class="archive-header__cover"></div><div class="archive-header__inner %2$s"><h1 class="archive-title">%3$s</h1><div class="archive-description">%4$s</div></div></div>',
			esc_attr( $image ),
			esc_attr( site_container_class() ),
			wp_kses( get_the_archive_title(), wp_kses_allowed_html() ),
			wp_kses( get_the_archive_description(), wp_kses_allowed_html() )
		);
	elseif ( is_search() ) :
		printf(
			'<div class="archive-header" %1$s><div class="archive-header__cover"></div><div class="archive-header__inner %2$s"><h2 class="archive-title">%3$s%4$s</h2></div></div>',
			esc_attr( $image ),
			esc_attr( site_container_class() ),
			esc_html__( 'Search results for: ', 'skinny' ),
			get_search_query()
		);
	else :
		printf(
			'<div class="archive-header"><div class="archive-header__cover"></div><div class="archive__header-inner %1$s"><h1 class="archive-title">%2$s</h1></div>',
			esc_attr( site_container_class() ),
			esc_html__( 'Archives', 'skinny' )
		);
	endif;
}

function custom_header() {
	if ( skinny_is_woocommerce_activated() && ( is_cart() || is_checkout() || is_account_page() ) ) {
		shop_header();
	} else {
		blog_header();
	}
}

/**
 * Display featured images.
 *
 * @return void
 */
function featured_image() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	$image_string = '<div class="entry-thumbnail"><a href="%1$s">%2$s</a></div>';

	if ( is_single() ) {
		$image_string = '<div class="entry-thumbnail">%2$s</div>';
	}

	printf(
		wp_kses_post( $image_string ),
		esc_url( get_permalink() ),
		get_the_post_thumbnail(
			null,
			'skinny-post-thumbnail',
			array(
				'alt' => the_title_attribute(
					array(
						'echo' => false,
					)
				),
			)
		)
	);
}

/**
 * Mobile menu.
 */
function mobile_menu() {
	$menu_class       = has_nav_menu( 'primary' ) ? 'mobile-menu list-reset' : 'mobile-menu list-reset unset';
	$hide_quick_links = ! skinny_get_thememod( 'account_btn' ) && ! skinny_get_thememod( 'color_scheme_toggle' ) ? 'display-none' : '';
	?>
	<span id="mobile__menu-toggle" class="mobile__menu-toggle">
		<span class="menu-item"></span>
		<span class="menu-item"></span>
		<span class="menu-item"></span>
	</span>
	<div id="header__mobile-navigation" class="header__mobile-navigation" aria-label="<?php esc_attr_e( 'Vertical', 'skinny' ); ?>" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
		<div class="header__mobile-navigation-inner">
			<?php
			if ( skinny_is_woocommerce_activated() && ( is_customize_preview() || skinny_get_thememod( 'cart_widget_toggle' ) ) ) {
				mobile_cart();
			}
			?>

			<div class="quick-links <?php echo esc_attr( $hide_quick_links ); ?>">
				<?php
				if ( skinny_is_woocommerce_activated() && ( is_customize_preview() || skinny_get_thememod( 'account_btn' ) ) ) {
					mobile_account_link();
				}

				if ( is_customize_preview() || skinny_get_thememod( 'color_scheme_toggle' ) ) {
					color_scheme_toggle();
				}
				?>
			</div>
			<?php

			wp_nav_menu(
				array(
					'menu_id'        => 'mobile-menu',
					'menu_class'     => $menu_class,
					'theme_location' => 'primary',
				)
			);

			if ( is_customize_preview() || skinny_get_thememod( 'search_btn_toggle' ) ) {
				get_search_form();
			}
			?>
		</div>
	</div>
	<?php
}

/**
 * Display Color scheme toggle.
 *
 * @return void
 */
function color_scheme_toggle() {
	$color_scheme = skinny_get_thememod( 'site_color_scheme' );
	$btn_class    = is_customize_preview() ? 'header__color-scheme-toggle disabled' : 'header__color-scheme-toggle';
	?>
	<button
		id="header__color-scheme-toggle"
		class="<?php echo esc_attr( $btn_class ); ?>"
		type="button"
		data-color-scheme="<?php echo esc_attr( $color_scheme ); ?>"
	>
		<div class="scheme-toggle-icon">
		</div>
		<span class="screen-reader-text"><?php esc_html_e( 'Color Scheme Toggle', 'skinny' ); ?></span>
	</button>

	<?php
}

/**
 * Displays site header.
 *
 * @return void
 */
function display_site_header() {
	$site_header_variation = skinny_get_thememod( 'header_layout_variations' );
	$menu_class            = has_nav_menu( 'primary' ) ? 'primary-menu list-reset' : 'primary-menu list-reset unset';
	?>
	<div class="header__inner flex
	<?php
	printf(
		'%1$s %2$s-header',
		esc_attr( site_container_class() ),
		esc_attr( $site_header_variation )
	);
	?>
	">

			<div class="header__title flex-nowrap">

				<?php display_site_branding(); ?>

				<?php mobile_menu(); ?>
			</div>

				<nav id="header__navigation" class="header__navigation" aria-label="<?php esc_attr_e( 'Horizontal', 'skinny' ); ?>" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">

					<div class="header__navigation-inner">
						<?php
						wp_nav_menu(
							array(
								'menu_class'     => $menu_class,
								'theme_location' => 'primary',
							)
						);
						?>
					</div>

				</nav>

			<div class="header__extras">
				<?php
				if ( is_customize_preview() || skinny_get_thememod( 'search_btn_toggle' ) ) {
					search_toggle();
				}
				if ( skinny_is_woocommerce_activated() && ( is_customize_preview() || skinny_get_thememod( 'cart_widget_toggle' ) ) ) {
					header_cart();
				}
				if ( skinny_is_woocommerce_activated() && ( is_customize_preview() || skinny_get_thememod( 'account_btn' ) ) ) {
					account_link();
				}
				if ( is_customize_preview() || skinny_get_thememod( 'color_scheme_toggle' ) ) {
					color_scheme_toggle();
				}
				?>
			</div>
		</div>
	<?php
}

/**
 * Display a Re-usable CTA Block.
 *
 * @return void
 */
function display_footer_cta() {
	$block_id = skinny_get_thememod( 'footer_cta_block' );
	if ( ! skinny_post_exists( $block_id ) ) {
		return;
	}

	if ( is_front_page() && skinny_get_thememod( 'footer_cta_homepage_toggle' ) || is_single() && skinny_get_thememod( 'footer_cta_single_posts_toggle' ) || ( is_home() || is_archive() ) && skinny_get_thememod( 'footer_cta_blog_archives_toggle' ) || skinny_is_woocommerce_activated() && ( is_shop() || is_product() || is_tax( get_object_taxonomies( 'product' ) ) ) ) {

		if ( skinny_is_woocommerce_activated() && ( is_shop() || is_tax( get_object_taxonomies( 'product' ) ) ) && ! skinny_get_thememod( 'footer_cta_shop_archives_toggle' ) || skinny_is_woocommerce_activated() && is_product() && ! skinny_get_thememod( 'footer_cta_single_products_toggle' ) ) {
			return;
		}

		$cta_block = get_post( $block_id );
		echo wp_kses_post( $cta_block->post_content );
	}
}

/**
 * Displays pagination.
 *
 * @param object $the_query WPQuery Object.
 * @return void
 */
function get_pagination( $the_query ) {
	if ( 1 === absint( $the_query->max_num_pages ) ) {
		return;
	}

	$big = 999999999;
	?>
	<div class="content-pagination <?php echo esc_attr( site_container_class() ); ?>">
		<div class="pagination-wrapper">
			<?php
			echo wp_kses_post(
				paginate_links(
					array(
						'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
						'format'    => '?paged=%#%',
						'current'   => max( 1, get_query_var( 'paged' ) ),
						'total'     => $the_query->max_num_pages,
						'prev_text' => __( 'Previous', 'skinny' ),
						'next_text' => __( 'Next', 'skinny' ),
					)
				)
			);
			?>
		</div>
	</div>
	<?php
}
