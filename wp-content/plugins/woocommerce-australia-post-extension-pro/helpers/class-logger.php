<?php
namespace AustraliaPost\Helpers;


class Logger {

	public static $logger;
	const LOG_FILENAME = 'wpruby-australia-post';

	public static function log( $message )
	{
		if (!self::is_enabled()) {
			return;
		}

		if (is_object($message) || is_array($message)) {
			$message = json_encode($message);
		}


		$logger = wc_get_logger();

		$log_entry = sprintf('==== WPRuby Australia Post Log Start [%s] ====' . PHP_EOL, date('d/m/Y H:i:s'));
		$log_entry .=  $message . PHP_EOL;
		$log_entry .= '==== WPRuby Australia Post Log End ====' . PHP_EOL . PHP_EOL;

		$logger->debug( $log_entry, [ 'source' => self::LOG_FILENAME ] );

	}


	/**
	 * @return bool
	 */
	public static function is_enabled()
	{
		if ( !class_exists( 'WC_Logger' )) {
			return false;
		}

		if (Utilities::get_method_setting('debug_mode') !== 'yes') {
			return false;
		}

		return true;
	}
}
