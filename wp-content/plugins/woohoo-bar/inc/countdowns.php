<?php

class WooHoo_Bar_Countdowns {
	public function __construct() {
		add_action( 'init', array( $this, 'register_blocks' ) );
		add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );
	}

	/**
	 * Register REST API endpoints
	 */
	public function rest_api_init() {
		register_rest_route( 'woohoo_bar/v1', '/fixed-time-countdown', array(
			'methods'  => 'GET',
			'permission_callback' => [$this, 'rest_routes_permission'],
			'callback' => [ $this, 'api_countdown' ],
		) );
		register_rest_route( 'woohoo_bar/v1', '/regular-countdown', array(
			'methods'  => 'GET',
			'permission_callback' => [$this, 'rest_routes_permission'],
			'callback' => [ $this, 'api_countdown' ],
		) );
		register_rest_route( 'woohoo_bar/v1', '/evergreen-countdown', array(
			'methods'  => 'GET',
			'permission_callback' => [$this, 'rest_routes_permission'],
			'callback' => [ $this, 'api_countdown' ],
		) );
	}

	public function rest_routes_permission() {
		return true;
		return is_user_logged_in();
	}

	public function api_countdown() {

		if ( empty( $_GET['ending'] ) && empty( $_GET['endtime'] ) && empty( $_GET['hours'] ) && empty( $_GET['minutes'] ) ) {
			return [
				'html' => '<div class="notice notice-error">Countdown time is required.</div>'
			];
		}
		return [ 'html' => $this->render_countdown( $_GET ) ];
	}

	public function register_blocks() {
		register_block_type(
			'woohoo-bar/fixed-time-countdown',
			[ 'render_callback' => [ $this, 'countdown' ] ]
		);
		register_block_type(
			'woohoo-bar/regular-countdown',
			[ 'render_callback' => [ $this, 'countdown' ] ]
		);
		register_block_type(
			'woohoo-bar/evergreen-countdown',
			[ 'render_callback' => [ $this, 'countdown' ] ]
		);
	}

	/**
	 * Countdown
	 * @param $attr
	 * @param string $content
	 * @return string|string[]
	 */
	public function countdown( $attr, $content = '' ) {

		return str_replace( '%content%', $this->render_countdown( $attr ), $content );
	}

	/**
	 * Renders countdown html
	 * @param $attr
	 * @return false|string|void
	 */
	public function render_countdown( $attr ) {
		$this->storefront_blocks_on_page = true;

		if ( ! empty( $attr['hours'] ) || ! empty( $attr['minutes'] ) ) {
			$hours = $attr['hours'] ? $attr['hours'] : 0;
			$hours += $attr['minutes'] ? $attr['minutes'] / 60 : 0;
			$seconds = $hours * 3600;

			if ( ! empty( $_COOKIE['woobarTime' . $seconds] ) ) {
				$seconds = $_COOKIE[ 'woobarTime' . $seconds ] - time();
			}

			return $this->render_countdown_numbers( $seconds, "data-time-duration='$seconds'", $attr );

		} else if ( ! empty( $attr['endtime'] ) ) {
			$ending = get_gmt_from_date( $attr['endtime'], 'U' );
//			$ending = strtotime( $attr['endtime'] );
			if ( $ending < time() ) {
				$ending += 24 * 60 * 60;
			}
			$diff = $ending - time();
		} else if ( ! empty( $attr['ending'] ) ) {
			$ending = get_gmt_from_date( $attr['ending'], 'U' );
//			$ending = strtotime( $attr['ending'] );
			$diff  = $ending - time();
		} else {
			return;
		}

		return $this->render_countdown_numbers( $diff, "data-time-end='$ending'", $attr );
	}

	public function render_countdown_numbers( $diff, $html_attrs = '', $attr = [] ) {
		ob_start();

		$diff = max( 0, $diff );

		if ( ! empty( $attr['finish'] ) ) {
			$html_attrs .= " data-finish='$attr[finish]'";
		}

		echo "<div class='woohoo-bar-countdown-counter flex justify-center tc' $html_attrs>";

		$days = floor( $diff / ( 60 * 60 * 24 ) );
		$hours = floor( $diff % (60 * 60 * 24) / ( 60 * 60 ) );
		$minutes = floor( $diff % (60 * 60) / 60 );
		$seconds = floor( $diff % 60 );

		$format =
			'<div class="woohoo-bar-timr woohoo-bar-timr-%1$s br1 ph2 pv1 ma1 bg-black-30">' .
			'<span class="woohoo-bar-timr-number-%1$s woohoo-bar-timr-number">%3$s</span>' .
			'<span class="ml1 woohoo-bar-timr-label">%4$s</span>' .
			'</div>';

		echo $days ? sprintf( $format, 'days', $days * 100 / 31, $days, _n( 'day', 'days', $days ) ) : '';

		echo $hours ? sprintf( $format, 'hours', $hours * 100 / 24, $hours, _n( 'hour', 'hours', $hours ) ) : '';

		echo sprintf( $format, 'minutes', $minutes * 100 / 60, $minutes, _n( 'minute', 'minutes', $minutes ) );

		echo sprintf( $format, 'seconds', $seconds * 100 / 60, $seconds, _n( 'second', 'seconds', $seconds ) );

		echo '</div>';

		return ob_get_clean();
	}
}