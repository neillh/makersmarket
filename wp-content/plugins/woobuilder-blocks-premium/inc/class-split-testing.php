<?php

class WooBuilder_Blocks_Split_Testing {
	/** @var self Instance */
	private static $_instance;

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

	protected function __construct() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'editor_enqueue' ), 7 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), 5 );
		add_action( 'woocommerce_add_to_cart', array( $this, 'log_a2c_conversion' ), 10, 2 );
		add_action( 'wp_ajax_woobk_stest_clear', array( $this, 'woobk_stest_clear' ) );
		add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );
	}

	function woobk_stest_clear() {
		// Maybe use $_POST['winner'] at some point.
		if ( ! isset( $_POST['test_data'] ) ) {
			return false;
		}

		$test        = explode( '--', $_POST['test_data'] );

		$test_name = $test[1];
		$pid       = $test[0];

		$active_tests = $this->get_post_tests( $pid );

		unset( $active_tests[ $test_name ] );

		$this->update_post_tests( $pid, $active_tests );

		setcookie( "woobk-stest-conv-$pid", '', time() - 3600, '/' );

		return true;

	}

	function rest_api_init() {
		register_rest_route( 'woob-stest/v1', '/impression', array(
			'permission_callback' => '__return_true', // Public endpoint
			'methods' => 'GET, POST',
			'callback' => [ $this, 'log_impression' ],
		) );
	}

	private function get_post_tests( $post, $tests_active = [] ) {
		$tests = get_post_meta( $post, "woob-stests", 'single' );

		$tests = $tests ? $tests : [];

		if ( $tests_active ) {
			foreach ( $tests_active as $test_name => $active ) {
				if ( empty( $tests[ $test_name ] ) ) {
					$tests[ $test_name ] = [
						$active => [
							'impressions' => 0,
							'conversions' => 0,
						]
					];
				} else if ( empty( $tests[ $test_name ][ $active ] ) ) {
					$tests[ $test_name ][ $active ] = [
						'impressions' => 0,
						'conversions' => 0,
					];
				}
			}
		}
		return $tests;
	}

	private function update_post_tests( $post, $tests ) {
		return update_post_meta( $post, "woob-stests", $tests );
	}

	public function log( $post, $tests_active, $what = 'impressions' ) {

		$tests = $this->get_post_tests( $post, $tests_active );

		$what = in_array( $what, [ 'conversions' ] ) ? $what : 'impressions';

		foreach ( $tests_active as $test_name => $active ) {
			$tests[ $test_name ][ $active ][ $what ] ++;
		}

		$this->update_post_tests( $post, $tests );
		return true;
	}

	public function log_impression() {

		if ( empty( $_POST['tests_data'] ) || empty( $_POST['pid'] ) ) {
			return false;
		}

		$tests_data   = json_decode( stripslashes( $_POST['tests_data'] ), 'assoc_array' );
		$pid          = $_POST['pid'];
		$active_tests = $this->get_active_tests( $pid );

		foreach ( $tests_data as $test_name => $active_test ) {

			// Active test cookie
			$active_tests[ $test_name ] = $active_test;
		}
		// Active test log impression
		$this->log( $pid, $active_tests );
		setcookie( "woobk-stest-$pid", json_encode( $active_tests ), time() + DAY_IN_SECONDS, '/' );

		return true;
	}

	public function get_active_tests( $pid ) {
		if ( ! empty( $_COOKIE["woobk-stest-$pid"] ) ) {
			$active_tests = json_decode( stripslashes( $_COOKIE["woobk-stest-$pid"] ), 'assoc_array' );
			return $active_tests ? $active_tests : [];
		}

		return [];
	}

	public function log_a2c_conversion( $item_id, $pid ) {
		$active_tests = $this->get_active_tests( $pid );
		if ( $active_tests ) {
			$this->log( $pid, $active_tests, 'conversions' );
			$active_tests = $this->get_active_tests( $pid );
			setcookie( "woobk-stest-$pid", '', time() - 3600, '/' );
			setcookie( "woobk-stest-conv-$pid", json_encode( $active_tests ), time() + DAY_IN_SECONDS, '/' );
		}
	}

	public function enqueue() {
		global $post;

		$url = WooBuilder_Blocks::$url;
		wp_enqueue_script( 'woob-stest-js', $url . '/assets/split-front.js' );
		wp_enqueue_style( 'woob-stest-css', $url . '/assets/split-front.css' );

		wp_localize_script(
			'woob-stest-js',
			'wbkSplitTesting',
			[
				'restApiUrl' => site_url( 'wp-json/woob-stest/v1/impression' ),
			]
		);
	}

	public function editor_enqueue() {
		global $post;

		if ( ! $post || $post->post_type != 'product' ) { // Perhaps make this a filter later
			return;
		}

		$url = WooBuilder_Blocks::$url;

		wp_enqueue_script( 'woob-stest-js', $url . '/assets/split-admin.js', [ 'caxton', 'wp-edit-post' ] );
		wp_enqueue_style( 'woob-stest-css', $url . '/assets/split-admin.css' );

		wp_localize_script(
			'woob-stest-js',
			'wbkSplitTestingData',
			[
				'post' => $post->ID,
/*				'stats' => [
					'My test' => [
						[
							'impressions' => 151,
							'conversions' => 25,
						],
						[
							'impressions' => 142,
							'conversions' => 23,
						],
					]
				],*/
				'stats' => $this->get_post_tests( $post->ID ),
			]
		);

	}
}

WooBuilder_Blocks_Split_Testing::instance();