<?php
/**
 * Created by PhpStorm.
 * User: Shramee Srivastav <shramee.srivastav@gmail.com>
 * Date: 27/4/15
 * Time: 5:36 PM
 */

/**
 * Storefront_Pro_Abstract
 * All classes except main extend this
 *
 * @class Storefront_Pro_Abstract
 * @version	1.0.0
 * @since 1.0.0
 * @package	Storefront_Pro
 */
abstract class Storefront_Pro_Abstract {

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $token;
	
	/*
	 * The plugin directory url.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $plugin_url;

	/*
	 * The plugin directory url.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $plugin_path;

	/**
	 * Constructor function.
	 *
	 * @param string $token
	 * @param string $url
	 * @param string $path
	 *
	 * @access  public
	 * @since   1.0.0
	 */
	public function __construct( $token, $path, $url ) {



		$this->token 	   = $token;
		$this->plugin_path = $path;
		$this->plugin_url  = $url;

		if ( method_exists( $this, 'init' ) ) {
			$this->init( func_get_args() );
		}
	}

	/**
	 * Gets the theme mod for customizer fields
	 *
	 * @param string $id
	 * @param mixed $default
	 * @return mixed Setting value
	 */
	public function get( $id, $default = null ){
		$return = get_theme_mod( $this->token . '-' . preg_replace( "/[^\w]+/", '-', strtolower( $id ) ), $default );
		if ( is_single() || is_page() ) {
			$post_meta = get_post_meta( get_the_ID(), 'pootle-page-customizer', true );
			if ( ! empty( $post_meta[ $id ] ) ) {
				$return = $post_meta[ $id ];
				$return = apply_filters( "post_meta_customize_setting_pootle-page-customizer[$id]", $return, $id );
			}
		}

		return apply_filters( "storefront_pro_filter_mod_$id", $return, $id );
	}

	/**
	 * Gets the theme mod for customizer fields
	 *
	 * @param string $id
	 * @param string $default
	 * @return string Setting value
	 */
	public function font_style( $style ){
		$s = explode( ',', $style );
		$css = '';
		if ( in_array( 'bold', $s ) ) {
			$css .= 'font-weight: bold;';
		} else {
			$css .= 'font-weight: normal;';
		}
		if ( in_array( 'italic', $s ) ) {
			$css .= 'font-style: italic;';
		} else {
			$css .= 'font-style: normal;';
		}
		if ( in_array( 'underline', $s ) ) {
			$css .= 'text-decoration: underline;';
		} else {
			$css .= 'text-decoration: none;';
		}
		if ( in_array( 'uppercase', $s ) ) {
			$css .= 'text-transform: uppercase;';
		} else {
			$css .= 'text-transform: none;';
		}

		return $css;
	}

	/**
	 * Hook for descendant class
	 * @return void
	 */
	public function init(){
		//For descendants
	}

} // End class