<?php
require_once 'class-primary-nav-styles.php';

/**
 * Storefront_Pro_Header_Nav Class
 *
 * @class Storefront_Pro_Header_Nav
 * @version	1.0.0
 * @since 1.0.0
 * @package	Storefront_Pro
 */
class Storefront_Pro_Header_Nav extends Storefront_Pro_Primary_Navigation {

	protected $css = '';
	protected $logo_url;

	/**
	 * Enqueue CSS and custom styles.
	 * @since  1.0.0
	 * @return string CSS
	 */
	public function styles() {
		$t = &$this;

		add_action( 'storefront_header', 'storefront_pro_menu_hamburger', 1 );
		add_action( 'storefront_header', 'storefront_pro_menu_hamburger_close_full_width', 999 );

		$t->css .= "\n/*Primary navigation*/\n";
		$t->primary_nav_style( $t->get( 'nav-style' ) );
		$t->primary_nav_typo();
		$t->submenu_animation( $t->get( 'pri-nav-dd-animation' ) );

		$t->mobile_nav();

		//Add search icon and maybe logo in nav
		add_filter( 'wp_nav_menu_items', array( $this, 'logo_in_nav' ), 10, 2 );

		$t->css .= '.storefront-pro-active #masthead { background-color:' . $t->get( 'header-bg-color' ) . ';}';

		$t->css .= "\n/*Secondary navigation*/\n";
		$t->secondary_nav_typo();

		return $this->css;
	}

	/**
	 * Primary nav typography
	 */
	public function secondary_nav_typo() {
		$t = &$this;
		$css = &$t->css;
		$css .= '.storefront-pro-active nav.secondary-navigation {' .
		        'background-color:' . $t->get( 'sec-nav-bg-color' ) . ';' .
		        '}';
		$css .= '.storefront-pro-active nav.secondary-navigation a {font-family:' . $t->get( 'sec-nav-font' ) . ';}';
		$css .=
			'.storefront-pro-active nav.secondary-navigation ul,' .
			'.storefront-pro-active nav.secondary-navigation a,' .
			'.storefront-pro-active nav.secondary-navigation a:hover {' .
			'font-size:' . $t->get( 'sec-nav-text-size' ) . 'px;' .
			'letter-spacing:' . $t->get( 'sec-nav-letter-spacing' ) . 'px;' .
			'color:' . $t->get( 'sec-nav-text-color', '#fff' ) . ';' .
			$t->font_style( $t->get( 'sec-nav-font-style' ) ) .
			'}';

		$css .= '.storefront-pro-active nav.secondary-navigation ul li.current_page_item a,' .
		        '.storefront-pro-active nav.secondary-navigation ul li.current_page_item a:hover {' .
		        'color:' . $t->get( 'sec-nav-active-link-color' ) . ';' .
		        '}';
		$css .= '.storefront-pro-active nav.secondary-navigation ul ul li a,' .
		        '.storefront-pro-active nav.secondary-navigation ul ul li a:hover {' .
		        'color:' . $t->get( 'sec-nav-dd-text-color' ) . ';' .
		        '}';

		$css .= '.storefront-pro-active nav.secondary-navigation ul.menu ul {' .
		        'background-color:' . $t->get( 'sec-nav-dd-bg-color' ) . ';' .
		'}';
	}

	/**
	 * Display Secondary Navigation
	 * @since  1.0.0
	 * @return void
	 */
	function secondary_navigation() {
		$container = $container_close = '';
		if ( ! $this->get( 'sec-nav-full' ) ) {
			$container = '<div class="col-full">';
			$container_close = '</div>';
		}
		?>
		<nav class="secondary-navigation " role="navigation" aria-label="<?php _e( 'Secondary Navigation', 'storefront' ); ?>">
			<?php
			echo $container;
			do_action( 'storefront_pro_in_sec_nav' );
			if ( 'right' == $this->get( 'align-social-info' ) ) {
				?> <style> .storefront-pro-active .secondary-nav-menu { float: left; } </style> <?php
				wp_nav_menu( array(
					'theme_location' => 'secondary', 'fallback_cb' => '', 'container_class' => 'secondary-nav-menu',
				) );
				echo $this->sec_nav_icons( 'right' );
			} else {
				echo $this->sec_nav_icons( $this->get( 'align-social-info' ) );
				wp_nav_menu( array(
					'theme_location' => 'secondary', 'fallback_cb' => '', 'container_class'	=> 'secondary-nav-menu',
				) );
			}
			echo $container_close;
			?>
		</nav><!-- #site-navigation -->
		<?php
	}

	public function sec_nav_icons( $float = 'left' ) {
		$phone = $this->get( 'phone-num' );
		$email = $this->get( 'email' );
		$pinterest  = $this->get( 'pinterest' );
		$youtube    = $this->get( 'youtube' );

		if ( 'center' == $float ) {
			$return = "<div style='float:left;position:relative;left:50%' ><div style='position: relative;left: -50%;' class='social-info'>";
		} elseif ( 'right' == $float ) {
			$return = "<div style='float:right;' class='social-info'>";
		} else {
			$return = "<div style='float:left;' class='social-info'>";
		}

		if ( ! empty( $phone ) ) {
			$return .= "<a href='tel:$phone' class='contact-info'><i class='fas fa-phone'></i>$phone</a>";
		}

		if ( ! empty( $email ) ) {
			$return .= "<a class='contact-info' href='mailto:{$email}'><i class='fas fa-envelope'></i>$email</a> ";
		}

		$this->sec_nav_icons_social( $return );

		if ( $pinterest ) { $return .= "<a target='_blank' href='$pinterest'><i class='fab fa-pinterest'></i></a>"; }

		if ( $youtube ) { $return .= "<a target='_blank' href='$youtube'><i class='fab fa-youtube'></i></a>"; }

		if ( 'center' == $float ) {
			$return .= "</div>";
		}

		return $return . '</div>';
	}

	public function sec_nav_icons_social( &$ret ) {
		$facebook   = $this->get( 'facebook' );
		$whatsapp   = $this->get( 'whatsapp' );
		$whatsapp_number = str_replace( [ '-', '+', '(', ')', ' ' ], '', $whatsapp );
		$twitter    = $this->get( 'twitter' );
		$googleplus = $this->get( 'googleplus' );
		$linkedin   = $this->get( 'linkedin' );
		$instagram  = $this->get( 'instagram' );

		if ( $facebook )   { $ret .= "<a target='_blank' href='$facebook'><i class='fab fa-facebook'></i></a>"; }

		if ( $twitter )    { $ret .= "<a target='_blank' href='$twitter'><i class='fab fa-twitter'></i></a>"; }

		if ( $whatsapp )    { $ret .= "<a target='_blank' href='https://wa.me/$whatsapp_number'><i class='fab fa-whatsapp'></i></a>"; }

		if ( $googleplus ) { $ret .= "<a target='_blank' href='$googleplus'><i class='fab fa-google-plus'></i></a>"; }

		if ( $linkedin )   { $ret .= "<a target='_blank' href='$linkedin'><i class='fab fa-linkedin'></i></a>"; }

		if ( $instagram )   { $ret .= "<a target='_blank' href='$instagram'><i class='fab fa-instagram'></i></a>"; }
	}

	public function mobile_nav() {
		$css = &Storefront_Pro_Public::$mobile_css;
		$css .= '#site-navigation a.menu-toggle, .storefront-pro-active .site-header-cart .cart-contents {';
		$css .= 'color: ' . $this->get( 'mob-menu-icon-color', '#000' ) . ';';
		$css .= '}';
		$css .= '.menu-toggle:after, .menu-toggle:before, .menu-toggle span:before {';
		$css .= 'background-color: ' . $this->get( 'mob-menu-icon-color', '#000' ) . ';';
		$css .= '}';
		$css .= '.storefront-pro-active .menu-toggle {';
		$css .= 'color: ' . $this->get( 'mob-menu-icon-color', '#000' ) . ';';
		$css .= '}';
		$css .= '#site-navigation .handheld-navigation{';
		$css .= 'background-color: ' . $this->get( 'mob-menu-bg-color' ) . ';';
		$css .= '}';
		$css .= '#site-navigation .handheld-navigation li a, button.dropdown-toggle {';
		$css .= 'color: ' . $this->get( 'mob-menu-font-color' ) . ';';
		$css .= '}';
	}
} // End class