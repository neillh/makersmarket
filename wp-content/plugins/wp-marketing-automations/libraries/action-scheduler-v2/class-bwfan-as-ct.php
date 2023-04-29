<?php

class BWFAN_AS_V2 {
	private static $instance;

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		global $wpdb;
		$wpdb->bwfan_automations                 = $wpdb->prefix . 'bwfan_automations';
		$wpdb->bwfan_automationmeta              = $wpdb->prefix . 'bwfan_automationmeta';
		$wpdb->bwfan_automation_step             = $wpdb->prefix . 'bwfan_automation_step';
		$wpdb->bwfan_automation_contact          = $wpdb->prefix . 'bwfan_automation_contact';
		$wpdb->bwfan_automation_contact_claim    = $wpdb->prefix . 'bwfan_automation_contact_claim';
		$wpdb->bwfan_automation_complete_contact = $wpdb->prefix . 'bwfan_automation_complete_contact';
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Override the action store with our own
	 *
	 * @param string $class
	 *
	 * @return string
	 */
	public function set_store_class( $class ) {
		return BWFAN_AS_V2_Action_Store::class;
	}

	/**
	 * Override the logger with our own
	 *
	 * @param string $class
	 *
	 * @return string
	 */
	public function set_logger_class( $class ) {
		return BWFAN_AS_V2_Log_Store::class;
	}

	public function change_data_store() {
		/** Removing all action data store change filter and then assign ours */
		remove_all_filters( 'action_scheduler_store_class' );
		add_filter( 'action_scheduler_store_class', [ $this, 'set_store_class' ], 999999, 1 );

		/** Removing all log data store change filter and then assign ours */
		remove_all_filters( 'action_scheduler_logger_class' );
		add_filter( 'action_scheduler_logger_class', [ $this, 'set_logger_class' ], 999999, 1 );

		/** Removing all AS memory exceeds filter */
		remove_all_filters( 'action_scheduler_memory_exceeded' );
		add_filter( 'action_scheduler_memory_exceeded', [ $this, 'check_memory_exceeded' ], 1000000, 1 );
	}

	/**
	 * Override memory exceeded filter value
	 *
	 * @param $memory_exceeded
	 *
	 * @return bool
	 */
	public function check_memory_exceeded( $memory_exceeded ) {
		if ( true === $memory_exceeded ) {
			return $memory_exceeded;
		}

		$ins = BWF_AS::instance();

		return $ins->validate_time_breach();
	}
}
