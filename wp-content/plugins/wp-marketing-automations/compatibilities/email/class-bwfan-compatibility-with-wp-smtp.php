<?php

/**
 * WP Mail SMTP
 * https://wordpress.org/plugins/wp-mail-smtp/
 */
if ( ! class_exists( 'BWFAN_Compatibility_With_WP_SMTP' ) ) {
	class BWFAN_Compatibility_With_WP_SMTP {

		public function __construct() {
			add_action( 'bwfan_before_send_email', array( $this, 'disable_force_email_settings' ) );
		}

		/**
		 * Disable force `from name` & 'from email' setting
		 *
		 * @return void
		 */
		public static function disable_force_email_settings() {
			/** WP SMTP force email setting **/
			$wp_smtp_settings = get_option( 'wp_mail_smtp' );
			if ( empty( $wp_smtp_settings ) || ! is_array( $wp_smtp_settings ) ) {
				return;
			}
			add_filter( 'wp_mail_smtp_options_get', function ( $value, $group, $key ) {
				if ( $group === 'mail' && in_array( strval( $key ), [ 'from_email_force', 'from_name_force' ], true ) ) {
					$value = false;
				}

				return $value;
			}, PHP_INT_MAX, 3 );
		}
	}

	if ( defined( 'WPMS_PLUGIN_VER' ) ) {
		new BWFAN_Compatibility_With_WP_SMTP();
	}
}
