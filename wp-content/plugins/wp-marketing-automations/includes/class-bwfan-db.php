<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class BWFAN_DB
 * @package Autonami
 * @author XlPlugins
 */
class BWFAN_DB {
	private static $ins = null;

	protected $tables_created = false;
	protected $method_run = [];

	/**
	 * BWFAN_DB constructor.
	 */
	public function __construct() {
		global $wpdb;
		$wpdb->bwfan_abandonedcarts      = $wpdb->prefix . 'bwfan_abandonedcarts';
		$wpdb->bwfan_automations         = $wpdb->prefix . 'bwfan_automations';
		$wpdb->bwfan_automationmeta      = $wpdb->prefix . 'bwfan_automationmeta';
		$wpdb->bwfan_tasks               = $wpdb->prefix . 'bwfan_tasks';
		$wpdb->bwfan_taskmeta            = $wpdb->prefix . 'bwfan_taskmeta';
		$wpdb->bwfan_task_claim          = $wpdb->prefix . 'bwfan_task_claim';
		$wpdb->bwfan_logs                = $wpdb->prefix . 'bwfan_logs';
		$wpdb->bwfan_logmeta             = $wpdb->prefix . 'bwfan_logmeta';
		$wpdb->bwfan_message_unsubscribe = $wpdb->prefix . 'bwfan_message_unsubscribe';
		$wpdb->bwfan_contact_automations = $wpdb->prefix . 'bwfan_contact_automations';

		/** v2 */
		$wpdb->bwfan_automation_contact          = $wpdb->prefix . 'bwfan_automation_contact';
		$wpdb->bwfan_automation_contact_claim    = $wpdb->prefix . 'bwfan_automation_contact_claim';
		$wpdb->bwfan_automation_contact_trail    = $wpdb->prefix . 'bwfan_automation_contact_trail';
		$wpdb->bwfan_automation_complete_contact = $wpdb->prefix . 'bwfan_automation_complete_contact';
		$wpdb->bwfan_automation_step             = $wpdb->prefix . 'bwfan_automation_step';

		add_action( 'plugins_loaded', [ $this, 'load_db_classes' ], 8 );

		add_action( 'admin_init', [ $this, 'version_1_0_0' ], 10 );
		add_action( 'admin_init', [ $this, 'db_update' ], 11 );
	}

	/**
	 * Return the object of current class
	 *
	 * @return null|BWFAN_DB
	 */
	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	/**
	 * Include all the DB Table files
	 */
	public static function load_db_classes() {
		$integration_dir = __DIR__ . '/db';
		foreach ( glob( $integration_dir . '/class-*.php' ) as $_field_filename ) {
			$file_data = pathinfo( $_field_filename );
			if ( isset( $file_data['basename'] ) && 'index.php' === $file_data['basename'] ) {
				continue;
			}
			require_once( $_field_filename );
		}
	}

	/**
	 * Version 1.0 update
	 */
	public function version_1_0_0() {
		if ( false !== get_option( 'bwfan_ver_1_0', false ) ) {
			return;
		}

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		global $wpdb;
		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}
		$max_index_length = 191;
		$db_errors        = [];

		$creationSQL = "CREATE TABLE {$wpdb->prefix}bwfan_automations (
 		  `ID` bigint(20) unsigned NOT NULL auto_increment,
 		  `source` varchar(60) NOT NULL,
 		  `event` varchar(60) NOT NULL,
 		  `status` tinyint(1) NOT NULL default 0 COMMENT '1 - Active 2 - Inactive',
 		  `priority` tinyint(3) NOT NULL default 0,
 		  `start` bigint(10) UNSIGNED NOT NULL,
 		  `v` tinyint(1) UNSIGNED NOT NULL default 1,
 		  `benchmark` varchar(150) NOT NULL,
 		  `title` varchar(255) NULL,
		  PRIMARY KEY (`ID`),
		  KEY `ID` (`ID`),
		  KEY `status` (`status`)
		) $collate;";
		dbDelta( $creationSQL );
		if ( ! empty( $wpdb->last_error ) ) {
			$db_errors[] = 'bwfan_automations - ' . $wpdb->last_error;
		}

		$creationSQL = "CREATE TABLE {$wpdb->prefix}bwfan_automationmeta (
		  `ID` bigint(20) unsigned NOT NULL auto_increment,
		  `bwfan_automation_id` bigint(20) unsigned NOT NULL default '0',
		  `meta_key` varchar(255) NULL,
		  `meta_value` longtext,
		  PRIMARY KEY (`ID`),
		  KEY `bwfan_automation_id` (`bwfan_automation_id`),
		  KEY `meta_key` (`meta_key`($max_index_length))
		) $collate;";
		dbDelta( $creationSQL );
		if ( ! empty( $wpdb->last_error ) ) {
			$db_errors[] = 'bwfan_automationmeta - ' . $wpdb->last_error;
		}

		$creationSQL = "CREATE TABLE {$wpdb->prefix}bwfan_message_unsubscribe (
		  	`ID` bigint(20) unsigned NOT NULL auto_increment,
			`recipient` varchar(255) default NULL,
			`mode` tinyint(1) NOT NULL COMMENT '1 - Email 2 - SMS' default 1,
			`c_date` datetime NOT NULL default '0000-00-00 00:00:00',
			`automation_id` bigint(20) unsigned default '0',
			`c_type` tinyint(1) NOT NULL default '1'  COMMENT '1 - Automation 2 - Broadcast 3 - Manual 4 - Form',
			`sid` bigint(20) unsigned NOT NULL default 0 COMMENT 'Step ID',
			PRIMARY KEY (`ID`),
			KEY `ID` (`ID`),
			KEY `recipient` (`recipient`($max_index_length)),
			KEY `mode` (`mode`),
			KEY `c_date` (`c_date`),
			KEY `automation_id` (`automation_id`),
			KEY `c_type` (`c_type`),
			KEY `sid` (`sid`)
		) $collate;";
		dbDelta( $creationSQL );
		if ( ! empty( $wpdb->last_error ) ) {
			$db_errors[] = 'bwfan_message_unsubscribe - ' . $wpdb->last_error;
		}

		$creationSQL = "CREATE TABLE {$wpdb->prefix}bwfan_abandonedcarts (
		  `ID` bigint(20) unsigned NOT NULL auto_increment,	
		  `email` varchar(32) NOT NULL,	  
		  `status` int(1) NOT NULL default 0,
		  `user_id` bigint(20) NOT NULL default 0,
		  `last_modified` datetime NOT NULL,
		  `created_time` datetime NOT NULL,
		  `items` longtext,
		  `coupons` longtext,
		  `fees` longtext,
		  `shipping_tax_total` varchar(32),
		  `shipping_total` varchar(32),
		  `total` varchar(32),
		  `total_base` varchar(32),
		  `token` varchar(32) NOT NULL,
		  `currency` varchar(8) NOT NULL,
		  `cookie_key` varchar(32) NOT NULL,
		  `checkout_data` longtext,
		  `order_id` bigint(20) NOT NULL,
		  `checkout_page_id` bigint(20) NOT NULL,
		  PRIMARY KEY (`ID`),
		  KEY `ID` (`ID`),
		  KEY `status` (`status`),
		  KEY `user_id` (`user_id`),
		  KEY `email` (`email`),
		  KEY `last_modified` (`last_modified`),
		  KEY `token` (`token`)
		) $collate;";
		dbDelta( $creationSQL );
		if ( ! empty( $wpdb->last_error ) ) {
			$db_errors[] = 'bwfan_abandonedcarts - ' . $wpdb->last_error;
		}

		$this->tables_created = true;

		$this->method_run[] = '1.0.0';

		do_action( 'bwfan_db_1_0_tables_created' );

		update_option( 'bwfan_ver_1_0', date( 'Y-m-d' ), true );

		/** Unique key to share in rest calls */
		$unique_key = md5( time() );
		update_option( 'bwfan_u_key', $unique_key, true );

		/** Update v1 automation status */
		update_option( 'bwfan_automation_v1', '0', true );

		/** Scheduling actions one-time */
		$this->schedule_actions();

		/** Auto global settings */
		if ( BWFAN_Plugin_Dependency::woocommerce_active_check() ) {
			$global_option = get_option( 'bwfan_global_settings', array() );

			$global_option['bwfan_ab_enable'] = true;
			update_option( 'bwfan_global_settings', $global_option, false );
		}

		/** Log if any mysql errors */
		if ( ! empty( $db_errors ) ) {
			BWFAN_Common::log_test_data( array_merge( [ __FUNCTION__ ], $db_errors ), 'db-creation-errors' );
		}
	}

	protected function schedule_actions() {
		$ins = BWFAN_Admin::get_instance();
		$ins->maybe_set_as_ct_worker();
		$ins->schedule_abandoned_cart_cron();
	}

	/**
	 * Create v1 automation related tables
	 *
	 * @return void
	 */
	public function db_create_v1_automation_tables() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		global $wpdb;
		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}
		$max_index_length = 191;
		$db_errors        = [];

		$creationSQL = "CREATE TABLE {$wpdb->prefix}bwfan_tasks (
		  `ID` bigint(20) unsigned NOT NULL auto_increment,
 		  `c_date` datetime NOT NULL default '0000-00-00 00:00:00',
 		  `e_date` bigint(12) NOT NULL,
 		  `automation_id` int(10) NOT NULL,
 		  `integration_slug` varchar(50) NULL,
 		  `integration_action` varchar(100) NULL,
 		  `status` int(1) NOT NULL default 0 COMMENT '0 - Pending 1 - Paused',
		  `claim_id` bigint(20) unsigned NOT NULL default 0,
		  `attempts` tinyint(1) unsigned NOT NULL default 0,
		  `priority` int(5) unsigned default 10,
		  PRIMARY KEY (`ID`),
		  KEY `ID` (`ID`),
		  KEY `e_date` (`e_date`),
		  KEY `automation_id` (`automation_id`),
		  KEY `status` (`status`),
		  KEY `claim_id` (`claim_id`)
		) $collate;";
		dbDelta( $creationSQL );
		if ( ! empty( $wpdb->last_error ) ) {
			$db_errors[] = 'bwfan_tasks - ' . $wpdb->last_error;
		}

		$creationSQL = "CREATE TABLE {$wpdb->prefix}bwfan_taskmeta (
		  `ID` bigint(20) unsigned NOT NULL auto_increment,
		  `bwfan_task_id` bigint(20) unsigned NOT NULL default '0',
		  `meta_key` varchar(255) NULL,
		  `meta_value` longtext,
		  PRIMARY KEY (`ID`),
		  KEY `bwfan_task_id` (`bwfan_task_id`),
		  KEY `meta_key` (`meta_key`($max_index_length))
		) $collate;";
		dbDelta( $creationSQL );
		if ( ! empty( $wpdb->last_error ) ) {
			$db_errors[] = 'bwfan_taskmeta - ' . $wpdb->last_error;
		}

		$creationSQL = "CREATE TABLE {$wpdb->prefix}bwfan_task_claim (
		  `claim_id` bigint(20) unsigned NOT NULL auto_increment,
		  `date_created_gmt` datetime NOT NULL default '0000-00-00 00:00:00',
		  PRIMARY KEY (`claim_id`),
		  KEY `date_created_gmt` (`date_created_gmt`)
		) $collate;";
		dbDelta( $creationSQL );
		if ( ! empty( $wpdb->last_error ) ) {
			$db_errors[] = 'bwfan_task_claim - ' . $wpdb->last_error;
		}

		$creationSQL = "CREATE TABLE {$wpdb->prefix}bwfan_logs (
		  `ID` bigint(20) unsigned NOT NULL auto_increment,
 		  `c_date` datetime NOT NULL default '0000-00-00 00:00:00',
 		  `e_date` bigint(12) NOT NULL,
 		  `status` int(1) NOT NULL default 0 COMMENT '0 - Failed 1 - Success',
 		  `integration_slug` varchar(50) default NULL,
 		  `integration_action` varchar(100) default NULL,
 		  `automation_id` int(10) NOT NULL,
		  PRIMARY KEY (`ID`),
		  KEY `ID` (`ID`),
		  KEY `status` (`status`),
		  KEY `automation_id` (`automation_id`)
		) $collate;";
		dbDelta( $creationSQL );
		if ( ! empty( $wpdb->last_error ) ) {
			$db_errors[] = 'bwfan_logs - ' . $wpdb->last_error;
		}

		$creationSQL = "CREATE TABLE {$wpdb->prefix}bwfan_logmeta (
		  `ID` bigint(20) unsigned NOT NULL auto_increment,
		  `bwfan_log_id` bigint(20) unsigned NOT NULL default '0',
		  `meta_key` varchar(255) default NULL,
		  `meta_value` longtext,
		  PRIMARY KEY (`ID`),
		  KEY `bwfan_log_id` (`bwfan_log_id`),
		  KEY `meta_key` (`meta_key`($max_index_length))
		) $collate;";
		dbDelta( $creationSQL );
		if ( ! empty( $wpdb->last_error ) ) {
			$db_errors[] = 'bwfan_logmeta - ' . $wpdb->last_error;
		}

		$creationSQL = "CREATE TABLE {$wpdb->prefix}bwfan_contact_automations (
		  `ID` bigint(20) unsigned NOT NULL auto_increment,	
		  `contact_id` bigint(20) NOT NULL,
		  `automation_id` bigint(20) NOT NULL,
		  `time` bigint(12) NOT NULL,
		  PRIMARY KEY (`ID`),
		  KEY `contact_id` (`contact_id`),
		  KEY `automation_id` (`automation_id`)
		) $collate;";
		dbDelta( $creationSQL );
		if ( ! empty( $wpdb->last_error ) ) {
			$db_errors[] = 'bwfan_contact_automations - ' . $wpdb->last_error;
		}

		/** Update v1 automation status */
		update_option( 'bwfan_automation_v1', '1', true );

		/** Log if any mysql errors */
		if ( ! empty( $db_errors ) ) {
			BWFAN_Common::log_test_data( array_merge( [ __FUNCTION__ ], $db_errors ), 'db-creation-errors' );
		}
	}

	/**
	 * Perform DB updates or once occurring updates
	 */
	public function db_update() {
		$db_changes = array(
			'2.0.10.1' => '2_0_10_1',
			'2.0.10.2' => '2_0_10_2',
			'2.0.10.3' => '2_0_10_3',
			'2.0.10.4' => '2_0_10_4',
			'2.0.10.5' => '2_0_10_5',
			'2.0.10.6' => '2_0_10_6',
			'2.0.10.7' => '2_0_10_7',
			'2.0.10.8' => '2_0_10_8',
			'2.4.1'    => '2_4_1',
		);
		$db_version = get_option( 'bwfan_db', '2.0' );

		foreach ( $db_changes as $version_key => $version_value ) {
			if ( version_compare( $db_version, $version_key, '<' ) ) {
				$function_name = 'db_update_' . $version_value;
				$this->$function_name( $version_key );
			}
		}
	}

	/**
	 * @param $version_key
	 *
	 * @return void
	 */
	public function db_update_2_0_10_1( $version_key ) {
		global $wpdb;
		$collate = '';
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}
		$db_errors = [];

		if ( ! is_array( $this->method_run ) || ! in_array( '1.0.0', $this->method_run, true ) ) {
			/** Add new columns in bwfan_automations table */
			$query = "ALTER TABLE {$wpdb->prefix}bwfan_automations
		    ADD COLUMN `start` bigint(10) UNSIGNED NOT NULL,
		    ADD COLUMN `v` tinyint(1) UNSIGNED NOT NULL default 1,
		    ADD COLUMN `benchmark` varchar(150) NOT NULL,
			ADD COLUMN `title` varchar(255) NULL;";
			$wpdb->query( $query );
			if ( ! empty( $wpdb->last_error ) ) {
				$db_errors[] = 'bwfan_automations alter table - ' . $wpdb->last_error;
			}
		}

		/** Create automation step table */
		$creationSQL = "CREATE TABLE {$wpdb->prefix}bwfan_automation_step (
			`ID` bigint(10) UNSIGNED NOT NULL auto_increment,
			`aid` bigint(10) UNSIGNED NOT NULL ,
			`type` tinyint(1) UNSIGNED NOT NULL default 1 COMMENT '1 - Wait | 2 - Action | 3 - Goal | 4 - Conditional | 5 - Exit',
			`action` varchar(255) NULL,
			`status` tinyint(1) NOT NULL default 0 COMMENT '1 - Active | 2 - Draft | 3 - Deleted',
			`data` longtext,
			`created_at` datetime NOT NULL default '0000-00-00 00:00:00',
			`updated_at` datetime NOT NULL default '0000-00-00 00:00:00',
			PRIMARY KEY (`ID`),
			KEY `aid` (`aid`),
			KEY `type` (`type`)
		) $collate;";
		dbDelta( $creationSQL );
		if ( ! empty( $wpdb->last_error ) ) {
			$db_errors[] = 'bwfan_automation_step - ' . $wpdb->last_error;
		}

		$creationSQL = "CREATE TABLE {$wpdb->prefix}bwfan_automation_contact (
		  `ID` bigint(20) unsigned NOT NULL auto_increment,	
		  `cid` bigint(20) unsigned NOT NULL,
		  `aid` bigint(10) unsigned NOT NULL,
 		  `event` varchar(120) NOT NULL,
		  `c_date` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Start Date',
		  `e_time` bigint(12) unsigned NOT NULL,
		  `status` tinyint(1) UNSIGNED NOT NULL default 1 COMMENT '1 - Active | 2 - Failed | 3 - Paused | 4 - Waiting | 5 - Terminate | 6 - Retry',
		  `last` bigint(10) UNSIGNED NOT NULL default 0,
		  `last_time` bigint(12) UNSIGNED NOT NULL,
		  `data` longtext,
		  `claim_id` bigint(20) UNSIGNED NOT NULL default 0,
		  `attempts` tinyint(1) UNSIGNED NOT NULL default 0,
		  `trail` varchar(40) NULL COMMENT 'Trail ID',
		  PRIMARY KEY (`ID`),
		  KEY `ID` (`ID`),
		  KEY `cid` (`cid`),
		  KEY `aid` (`aid`),
		  KEY `e_time` (`e_time`),
		  KEY `status` (`status`),
		  KEY `claim_id` (`claim_id`)
		) $collate;";
		dbDelta( $creationSQL );
		if ( ! empty( $wpdb->last_error ) ) {
			$db_errors[] = 'bwfan_automation_contact - ' . $wpdb->last_error;
		}

		/** Create automation contact complete table */
		$creationSQL = "CREATE TABLE {$wpdb->prefix}bwfan_automation_complete_contact(
		  `ID` bigint(20) unsigned NOT NULL auto_increment,	
		  `cid` bigint(20) unsigned NOT NULL,
		  `aid` bigint(10) unsigned NOT NULL,
 		  `event` varchar(120) NOT NULL,
		  `s_date` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Start Date',
		  `c_date` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Completion Date',
		  `data` longtext,
		  `trail` varchar(40) NULL COMMENT 'Trail ID',
		  PRIMARY KEY (`ID`),
		  KEY `ID` (`ID`),
		  KEY `cid` (`cid`),
		  KEY `aid` (`aid`),
		  KEY `c_date` (`c_date`)
		) $collate;";
		dbDelta( $creationSQL );
		if ( ! empty( $wpdb->last_error ) ) {
			$db_errors[] = 'bwfan_automation_complete_contact - ' . $wpdb->last_error;
		}

		/** Create automation contact claim table */
		$creationSQL = "CREATE TABLE {$wpdb->prefix}bwfan_automation_contact_claim(
			`ID` bigint(20) UNSIGNED NOT NULL auto_increment,
			`created_at` datetime NOT NULL default '0000-00-00 00:00:00',
			PRIMARY KEY (`ID`)
		) $collate;";
		dbDelta( $creationSQL );
		if ( ! empty( $wpdb->last_error ) ) {
			$db_errors[] = 'bwfan_automation_contact_claim - ' . $wpdb->last_error;
		}

		/** automation contact trail table */
		$creationSQL = "CREATE TABLE {$wpdb->prefix}bwfan_automation_contact_trail (
			`ID` bigint(20) UNSIGNED NOT NULL auto_increment,
			`tid` varchar(40) NOT NULL COMMENT 'Trail ID',
			`cid` bigint(12) UNSIGNED NOT NULL COMMENT 'Contact ID',
			`aid` bigint(10) UNSIGNED NOT NULL COMMENT 'Automation ID',
			`sid` bigint(10) UNSIGNED NOT NULL COMMENT 'Step ID',
			`c_time` bigint(12) UNSIGNED NOT NULL,
			`status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1 - Success | 2 - Wait | 3 - Failed | 4 - Skipped',
			`data` varchar(255) NULL,
			PRIMARY KEY (`ID`),
			KEY `ID` (`ID`),
			KEY `tid` (`tid`(40)),
			KEY `cid` (`cid`),
			KEY `sid` (`sid`),
			KEY `status` (`status`)
		) $collate;";
		dbDelta( $creationSQL );
		if ( ! empty( $wpdb->last_error ) ) {
			$db_errors[] = 'bwfan_automation_contact_trail - ' . $wpdb->last_error;
		}

		$this->method_run[] = $version_key;

		/** Updating version key */
		update_option( 'bwfan_db', $version_key, true );

		/** Log if any mysql errors */
		if ( ! empty( $db_errors ) ) {
			BWFAN_Common::log_test_data( array_merge( [ __FUNCTION__ ], $db_errors ), 'db-creation-errors' );
		}
	}

	/**
	 * @param $version_key
	 *
	 * @return void
	 */
	public function db_update_2_0_10_2( $version_key ) {
		if ( is_array( $this->method_run ) && in_array( '2.0.10.1', $this->method_run, true ) ) {
			update_option( 'bwfan_db', $version_key, true );
			$this->method_run[] = $version_key;

			return;
		}

		global $wpdb;
		$collate = '';
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}
		$db_errors = [];

		/** Drop next column */
		$drop_col = "ALTER TABLE {$wpdb->prefix}bwfan_automation_contact DROP COLUMN `next`";
		$wpdb->query( $drop_col );
		if ( ! empty( $wpdb->last_error ) ) {
			$db_errors[] = 'bwfan_automation_contact drop call - ' . $wpdb->last_error;
		}

		/** event column added */
		$creationSQL = "CREATE TABLE {$wpdb->prefix}bwfan_automation_contact (
		  `ID` bigint(20) unsigned NOT NULL auto_increment,	
		  `cid` bigint(20) unsigned NOT NULL,
		  `aid` bigint(10) unsigned NOT NULL,
 		  `event` varchar(120) NOT NULL,
		  `c_date` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Start Date',
		  `e_time` bigint(12) unsigned NOT NULL,
		  `status` tinyint(1) UNSIGNED NOT NULL default 1 COMMENT '1 - Active | 2 - Failed | 3 - Paused | 4 - Waiting | 5 - Terminate | 6 - Retry',
		  `last` bigint(10) UNSIGNED NOT NULL default 0,
		  `last_time` bigint(12) UNSIGNED NOT NULL,
		  `data` longtext,
		  `claim_id` bigint(20) UNSIGNED NOT NULL default 0,
		  `attempts` tinyint(1) UNSIGNED NOT NULL default 0,
		  `trail` varchar(40) NULL COMMENT 'Trail ID',
		  PRIMARY KEY (`ID`),
		  KEY `ID` (`ID`),
		  KEY `cid` (`cid`),
		  KEY `aid` (`aid`),
		  KEY `e_time` (`e_time`),
		  KEY `status` (`status`),
		  KEY `claim_id` (`claim_id`)
		) $collate;";
		dbDelta( $creationSQL );
		if ( ! empty( $wpdb->last_error ) ) {
			$db_errors[] = 'bwfan_automation_contact - ' . $wpdb->last_error;
		}

		/** event column added */
		$creationSQL = "CREATE TABLE {$wpdb->prefix}bwfan_automation_complete_contact(
		  `ID` bigint(20) unsigned NOT NULL auto_increment,	
		  `cid` bigint(20) unsigned NOT NULL,
		  `aid` bigint(10) unsigned NOT NULL,
 		  `event` varchar(120) NOT NULL,
		  `s_date` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Start Date',
		  `c_date` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Completion Date',
		  `data` longtext,
		  `trail` varchar(40) NULL COMMENT 'Trail ID',
		  PRIMARY KEY (`ID`),
		  KEY `ID` (`ID`),
		  KEY `cid` (`cid`),
		  KEY `aid` (`aid`),
		  KEY `c_date` (`c_date`)
		) $collate;";
		dbDelta( $creationSQL );
		if ( ! empty( $wpdb->last_error ) ) {
			$db_errors[] = 'bwfan_automation_complete_contact - ' . $wpdb->last_error;
		}

		$this->method_run[] = $version_key;

		/** Updating version key */
		update_option( 'bwfan_db', $version_key, true );

		/** Log if any mysql errors */
		if ( ! empty( $db_errors ) ) {
			BWFAN_Common::log_test_data( array_merge( [ __FUNCTION__ ], $db_errors ), 'db-creation-errors' );
		}
	}

	/**
	 * @param $version_key
	 *
	 * @return void
	 */
	public function db_update_2_0_10_3( $version_key ) {
		if ( is_array( $this->method_run ) && in_array( '2.0.10.1', $this->method_run, true ) ) {
			update_option( 'bwfan_db', $version_key, true );
			$this->method_run[] = $version_key;

			return;
		}

		global $wpdb;
		$collate = '';
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}
		$db_errors = [];

		if ( ! is_array( $this->method_run ) || ! in_array( '2.0.10.2', $this->method_run, true ) ) {
			/** Alter bwfan_automation_complete_contact table */
			$query = "ALTER TABLE {$wpdb->prefix}bwfan_automation_complete_contact
    		CHANGE `trail` `trail` VARCHAR(40) NULL COMMENT 'Trail ID',
		    ADD COLUMN `event` varchar(120) NOT NULL;";
			$wpdb->query( $query );
			if ( ! empty( $wpdb->last_error ) ) {
				$db_errors[] = 'bwfan_automation_complete_contact alter table - ' . $wpdb->last_error;
			}

			/** Alter bwfan_automation_contact table */
			$query = "ALTER TABLE {$wpdb->prefix}bwfan_automation_contact
    		CHANGE `trail` `trail` VARCHAR(40) NULL COMMENT 'Trail ID',
		    ADD COLUMN `last_time` bigint(12) UNSIGNED NOT NULL;";
			$wpdb->query( $query );
			if ( ! empty( $wpdb->last_error ) ) {
				$db_errors[] = 'bwfan_automation_contact alter table - ' . $wpdb->last_error;
			}
		}

		/** automation contact trail table */
		$creationSQL = "CREATE TABLE {$wpdb->prefix}bwfan_automation_contact_trail (
			`ID` bigint(20) UNSIGNED NOT NULL auto_increment,
			`tid` varchar(40) NOT NULL COMMENT 'Trail ID',
			`cid` bigint(12) UNSIGNED NOT NULL COMMENT 'Contact ID',
			`aid` bigint(10) UNSIGNED NOT NULL COMMENT 'Automation ID',
			`sid` bigint(10) UNSIGNED NOT NULL COMMENT 'Step ID',
			`c_time` bigint(12) UNSIGNED NOT NULL,
			`status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1 - Success | 2 - Wait | 3 - Failed | 4 - Skipped',
			`data` varchar(255) NULL,
			PRIMARY KEY (`ID`),
			KEY `ID` (`ID`),
			KEY `tid` (`tid`(40)),
			KEY `cid` (`cid`),
			KEY `sid` (`sid`),
			KEY `status` (`status`)
		) $collate;";
		dbDelta( $creationSQL );
		if ( ! empty( $wpdb->last_error ) ) {
			$db_errors[] = 'bwfan_automation_contact_trail - ' . $wpdb->last_error;
		}

		$this->method_run[] = $version_key;

		/** Updating version key */
		update_option( 'bwfan_db', $version_key, true );

		/** Log if any mysql errors */
		if ( ! empty( $db_errors ) ) {
			BWFAN_Common::log_test_data( array_merge( [ __FUNCTION__ ], $db_errors ), 'db-creation-errors' );
		}
	}

	/**
	 * @param $version_key
	 *
	 * @return void
	 */
	public function db_update_2_0_10_4( $version_key ) {
		if ( is_array( $this->method_run ) && in_array( '2.0.10.1', $this->method_run, true ) ) {
			update_option( 'bwfan_db', $version_key, true );
			$this->method_run[] = $version_key;

			return;
		}

		/** Marking option key autoload false */
		$global_option             = get_option( 'bwfan_global_settings', array() );
		$global_option['2_0_10_4'] = 1;
		update_option( 'bwfan_global_settings', $global_option, false );

		$this->method_run[] = $version_key;

		/** Updating version key */
		update_option( 'bwfan_db', $version_key, true );
	}

	/**
	 * @param $version_key
	 *
	 * @return void
	 */
	public function db_update_2_0_10_5( $version_key ) {
		if ( ( is_array( $this->method_run ) && in_array( '2.0.10.1', $this->method_run, true ) ) || ! class_exists( 'BWFCRM_Contact' ) ) {
			update_option( 'bwfan_db', $version_key, true );
			$this->method_run[] = $version_key;

			return;
		}

		global $wpdb;

		/** Automation complete contact */
		$query = "SELECT MAX(`ID`) FROM `{$wpdb->prefix}bwfan_automation_complete_contact`";

		$max_completed_aid = $wpdb->get_var( $query );
		if ( intval( $max_completed_aid ) > 0 ) {
			update_option( 'bwfan_max_automation_completed', $max_completed_aid );
			if ( ! bwf_has_action_scheduled( 'bwfan_store_automation_completed_ids' ) ) {
				bwf_schedule_recurring_action( time() + 60, 120, 'bwfan_store_automation_completed_ids' );
			}
		}

		/** Automation contact */
		$query = "SELECT MAX(`ID`) FROM `{$wpdb->prefix}bwfan_automation_contact`";

		$max_active_aid = $wpdb->get_var( $query );
		if ( intval( $max_active_aid ) > 0 ) {
			update_option( 'bwfan_max_active_automation', $max_active_aid );
			if ( ! bwf_has_action_scheduled( 'bwfan_store_automation_active_ids' ) ) {
				bwf_schedule_recurring_action( time(), 120, 'bwfan_store_automation_active_ids' );
			}
		}

		/** Updating version key */
		update_option( 'bwfan_db', $version_key, true );
	}

	/**
	 * @param $version_key
	 *
	 * @return void
	 */
	public function db_update_2_0_10_6( $version_key ) {
		if ( is_array( $this->method_run ) && in_array( '1.0.0', $this->method_run, true ) ) {
			update_option( 'bwfan_db', $version_key, true );
			$this->method_run[] = $version_key;

			return;
		}

		global $wpdb;
		$db_errors = [];

		/** Automation contact */
		$query = $wpdb->prepare( "SELECT MIN(`ID`) FROM `{$wpdb->prefix}bwfan_automations` WHERE `v` = %d", 1 );

		$automation_v1 = $wpdb->get_var( $query );
		$automation_v1 = ( 0 === intval( $automation_v1 ) ) ? '0' : '1';
		update_option( 'bwfan_automation_v1', $automation_v1, true );

		/** Updating version key */
		update_option( 'bwfan_db', $version_key, true );

		/** Log if any mysql errors */
		if ( ! empty( $db_errors ) ) {
			BWFAN_Common::log_test_data( array_merge( [ __FUNCTION__ ], $db_errors ), 'db-creation-errors' );
		}
	}

	/**
	 * @param $version_key
	 *
	 * @return void
	 */
	public function db_update_2_0_10_7( $version_key ) {
		BWFAN_Recipe_Loader::get_recipes_array( true );

		/** Updating version key */
		update_option( 'bwfan_db', $version_key, true );
	}

	/**
	 * @param $version_key
	 *
	 * @return void
	 */
	public function db_update_2_0_10_8( $version_key ) {
		if ( is_array( $this->method_run ) && in_array( '1.0.0', $this->method_run, true ) ) {
			update_option( 'bwfan_db', $version_key, true );
			$this->method_run[] = $version_key;

			return;
		}

		global $wpdb;

		$query  = "DESCRIBE {$wpdb->prefix}bwfan_message_unsubscribe";
		$result = $wpdb->get_results( $query, ARRAY_A ); // phpcs:disable WordPress.DB.PreparedSQL
		if ( ! empty( $result ) ) {
			$cols = array_column( $result, 'Field' );
			if ( in_array( 'sid', $cols, true ) ) {
				update_option( 'bwfan_db', $version_key, true );
				$this->method_run[] = $version_key;

				return;
			}
		}

		$collate = '';
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}
		$max_index_length = 191;
		$db_errors        = [];

		/** Add sid column in message unsubscribe table */
		$creationSQL = "CREATE TABLE {$wpdb->prefix}bwfan_message_unsubscribe (
			`ID` bigint(20) unsigned NOT NULL auto_increment,
			`recipient` varchar(255) default NULL,
			`mode` tinyint(1) NOT NULL COMMENT '1 - Email 2 - SMS' default 1,
			`c_date` datetime NOT NULL default '0000-00-00 00:00:00',
			`automation_id` bigint(20) unsigned default '0',
			`c_type` tinyint(1) NOT NULL default '1'  COMMENT '1 - Automation 2 - Broadcast 3 - Manual 4 - Form',
			`sid` bigint(20) unsigned NOT NULL default 0 COMMENT 'Step ID',
			PRIMARY KEY (`ID`),
			KEY `ID` (`ID`),
			KEY `recipient` (`recipient`($max_index_length)),
			KEY `mode` (`mode`),
			KEY `c_date` (`c_date`),
			KEY `automation_id` (`automation_id`),
			KEY `c_type` (`c_type`),
			KEY `sid` (`sid`)
		  ) $collate;";
		dbDelta( $creationSQL );
		if ( ! empty( $wpdb->last_error ) ) {
			$db_errors[] = 'bwfan_message_unsubscribe - ' . $wpdb->last_error;
		}

		/** Updating version key */
		update_option( 'bwfan_db', $version_key, true );

		/** Log if any mysql errors */
		if ( ! empty( $db_errors ) ) {
			BWFAN_Common::log_test_data( array_merge( [ __FUNCTION__ ], $db_errors ), 'db-creation-errors' );
		}
	}

	public function db_update_2_4_1( $version_key ) {
		if ( is_array( $this->method_run ) && in_array( '1.0.0', $this->method_run, true ) ) {
			update_option( 'bwfan_db', $version_key, true );
			$this->method_run[] = $version_key;

			return;
		}

		global $wpdb;

		$query  = "SELECT count(*) AS `count` FROM `{$wpdb->prefix}bwf_actions` WHERE `hook` IN ('bwfan_run_midnight_cron', 'bwfan_5_minute_worker', 'bwfan_run_midnight_connectors_sync')";
		$result = $wpdb->get_var( $query ); // phpcs:disable WordPress.DB.PreparedSQL
		if ( ! empty( $result ) ) {
			/** Delete the rows */
			$query = "DELETE FROM `{$wpdb->prefix}bwf_actions` WHERE `hook` IN ('bwfan_run_midnight_cron', 'bwfan_5_minute_worker', 'bwfan_run_midnight_connectors_sync')";
			$wpdb->query( $query );
		}

		/** Updating version key */
		update_option( 'bwfan_db', $version_key, true );
	}
}

if ( class_exists( 'BWFAN_DB' ) ) {
	BWFAN_Core::register( 'db', 'BWFAN_DB' );
}
