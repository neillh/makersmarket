<?php
/**
 * Storefront_Pro_Footer_Styles Class
 *
 * @class Storefront_Pro_Footer_Styles
 * @version	1.0.0
 * @since 1.0.0
 * @package	Storefront_Pro
 */
class Storefront_Pro_Footer_Styles extends Storefront_Pro_Abstract {

	protected $css = "\n/* Footer Styles */";

	public function init() {
		add_filter( 'storefront_footer_widget_columns', array( $this, 'widget_columns' ) );

		remove_action( 'storefront_footer', 'storefront_credit', 20 );

		add_action( 'storefront_footer', array( $this, 'credit' ), 20 );
	}

	/**
	 * Enqueue CSS and custom styles.
	 * @since  1.0.0
	 * @return string CSS
	 */
	public function styles() {
		$this->footer_typography();
		$this->layout();
		$this->mobile_footer();
		return $this->css;
	}

	/**
	 * Sets footer typography css
	 */
	protected function footer_typography() {
		$t = &$this;
		$css = &$this->css;
		$t->get( 'footer-wid-header-text-size' );
		$grad_colors = get_theme_mod( 'storefront_footer_gradient_color1' ) . ',' . get_theme_mod( 'storefront_footer_gradient_color2' );
		$bg_img = $this->get( 'footer-bg-image' );

		$css = '.storefront-pro-active .site-footer {';

		$css .= $bg_img ? "background: url($bg_img);" : '';

		if ( ! in_array( $grad_colors, array( '#f0f0f0,#f0f0f0', ',' ) ) ) {

			$css .=
				sprintf(
					'background: -webkit-linear-gradient(%1$s), url(%2$s);' .
					'background: -o-linear-gradient(%1$s), url(%2$s);' .
					'background: -moz-linear-gradient(%1$s), url(%2$s);' .
					'background: linear-gradient(to %1$s, url(%2$s));',
					$grad_colors, $bg_img
				);
		}
		$css .='}';

		$css .= '.storefront-pro-active .site-footer * {' .
		        'font-size:' . $t->get( 'footer-wid-font-size' ) . 'px;' .
		        $t->font_style( $t->get( 'footer-wid-font-style' ) ) .
		        'color:' . $t->get( 'footer-wid-color' ) . ';' .
		        '}';

		$css .= '.storefront-pro-active .site-footer  .widget-title,' .
		        '.storefront-pro-active .site-footer  h3 {' .
		        'font-size:' . $t->get( 'footer-wid-header-font-size' ) . 'px;' .
		        $t->font_style( $t->get( 'footer-wid-header-font-style' ) ) .
		        'color:' . $t->get( 'footer-wid-header-color' ) . ';' .
		        '}';

		$css .= '.storefront-pro-active .site-footer a {' .
		        'color:' . $t->get( 'footer-wid-link-color' ) . ';' .
		        '}';

		$css .= '.storefront-pro-active .site-footer .footer-widgets li:before {' .
		        'color:' . $t->get( 'footer-wid-bullet-color' ) . ';' .
		        '}';
	}

	public function widget_columns( $areas ) {
		$layout = $this->get( 'typo-footer-layout' );
		if ( $layout ) {
			return is_numeric( $layout ) ? $layout : count( explode( '-', $layout ) );
		}
		return $areas;
	}

	protected function layout() {
		$t = &$this;
		$css = &Storefront_Pro_Public::$desktop_css;
		$layout = $t->get( 'typo-footer-layout' );
		$class_prefix = '.footer-widget-';
		$selector_prefix = '.storefront-pro-active .footer-widgets ';
		if ( $layout && ! is_numeric( $layout ) ) {
			$sizes = explode( '-', $layout );
			$cols = count( $sizes );
			$available_width = 100 - ( ( $cols - 1 ) * 4.347826087 );
			for( $i = 0; $i < $cols; $i++ ) {
				$fraction = explode( '_', $sizes[ $i ] );
				$width = ( $fraction[0] / $fraction[1] ) * $available_width;
				$class = $class_prefix . ( $i + 1 );
				$css .= $selector_prefix . $class . ' { '
				. "width: {$width}%; }";
			}
		}
	}

	public function credit() {
		$footer_text = $this->get( 'footer-custom-text' );
		?>
		<div class="site-info">
			<?php
			if ( empty( $footer_text ) ) {
				echo esc_html(apply_filters('storefront_copyright_text', $content = '&copy; ' . get_bloginfo('name') . ' ' . date('Y')));
				if (apply_filters('storefront_credit_link', true)) { ?>
					<br/> <?php printf(__('%1$s designed by %2$s.', 'storefront'), 'Storefront', '<a href="http://www.woothemes.com" alt="Premium WordPress Themes & Plugins by WooThemes" title="Premium WordPress Themes & Plugins by WooThemes" rel="designer">WooThemes</a>');
				}
			} else {
				echo $footer_text;
			}
			?>
		</div><!-- .site-info -->
		<?php
	}

	private function mobile_footer() {
		$css = &$this->css;
		$css .= '.storefront-handheld-footer-bar ul li.search .site-search, .storefront-pro-active .site-footer .storefront-handheld-footer-bar ul li > a {';
		$css .= 'background-color: ' . $this->get( 'mob-footer-bg-color' ) . ';';
		$css .= 'color: ' . $this->get( 'mob-footer-font-color' ) . '!important;';
		$css .= '}';
		$css .= '.storefront-pro-active .storefront-handheld-footer-bar ul li.cart .count {';
		$css .= 'color: ' . $this->get( 'mob-footer-bg-color' ) . ';';
		$css .= 'border-color: ' . $this->get( 'mob-footer-bg-color' ) . ';';
		$css .= 'background: ' . $this->get( 'mob-footer-font-color' ) . ';';
		$css .= '}';

		if ( $this->get( 'mob-footer-hide-myac' ) ) {
			$css .= '.storefront-pro-active .storefront-handheld-footer-bar .my-account { display: none; }';
		}
		if ( $this->get( 'mob-footer-hide-search' ) ) {
			$css .= '.storefront-pro-active .storefront-handheld-footer-bar .search { display: none; }';
		}
		if ( $this->get( 'mob-footer-hide-cart' ) ) {
			$css .= '.storefront-pro-active .storefront-handheld-footer-bar .cart { display: none; }';
		}
	}
} // End class
