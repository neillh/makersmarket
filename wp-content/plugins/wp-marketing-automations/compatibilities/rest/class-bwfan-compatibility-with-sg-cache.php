<?php

/**
 * SiteGround Optimizer
 *
 * https://wordpress.org/plugins/sg-cachepress/
 */
if ( ! class_exists( 'BWFAN_Compatibility_With_SG_Cache' ) ) {
	class BWFAN_Compatibility_With_SG_Cache {

		public function __construct() {
			add_filter( 'option_siteground_optimizer_excluded_urls', array( $this, 'exclude_autonami_endpoint_option' ), 100 );
			add_filter( 'default_option_siteground_optimizer_excluded_urls', array( $this, 'exclude_autonami_endpoint_option_default' ), 100 );
		}

		/**
		 * Exclude Autonami and WooFunnels endpoints from SiteGround cache
		 *
		 * @param $value
		 *
		 * @return mixed
		 */
		public function exclude_autonami_endpoint_option( $value ) {
			$value[] = "/wp-json/autonami-admin/*";
			$value[] = "/wp-json/woofunnels/*";

			return $value;
		}

		/**
		 * Exclude Autonami and WooFunnels endpoints from SiteGround cache
		 * Passing default arguments if none value set
		 *
		 * @param $default
		 *
		 * @return mixed
		 */
		public function exclude_autonami_endpoint_option_default( $default ) {
			$default[] = "/wp-json/autonami-admin/*";
			$default[] = "/wp-json/woofunnels/*";

			return $default;
		}
	}

	if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
		new BWFAN_Compatibility_With_SG_Cache();
	}
}
