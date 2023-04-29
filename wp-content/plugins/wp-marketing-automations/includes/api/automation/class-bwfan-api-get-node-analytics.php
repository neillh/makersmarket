<?php

class BWFAN_API_Get_Node_Analytics extends BWFAN_API_Base {

	public static $ins;

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function __construct() {
		parent::__construct();
		$this->method       = WP_REST_Server::READABLE;
		$this->route        = '/automation/(?P<automation_id>[\\d]+)/analytics/(?P<step_id>[\\d]+)';
		$this->request_args = array(
			'automation_id' => array(
				'description' => __( 'Automation ID', 'wp-marketing-automations-crm' ),
				'type'        => 'integer',
			),
			'step_id'       => array(
				'description' => __( 'Step ID ', 'wp-marketing-automations-crm' ),
				'type'        => 'integer',
			),
		);
	}

	public function process_api_call() {
		$automation_id = $this->get_sanitized_arg( 'automation_id' );
		$step_id       = $this->get_sanitized_arg( 'step_id' );
		$mode          = $this->get_sanitized_arg( 'mode' );
		$mode          = ! empty( $mode ) ? $mode : 'email';

		if ( empty( $automation_id ) || empty( $step_id ) ) {
			return $this->error_response( __( 'Invalid / Empty automation ID provided', 'wp-marketing-automations-crm' ), null, 400 );
		}

		/** Initiate automation object */
		$automation_obj = BWFAN_Automation_V2::get_instance( $automation_id );

		/** Check for automation exists */
		if ( ! empty( $automation_obj->error ) ) {
			return $this->error_response( [], $automation_obj->error );
		}

		if ( ! class_exists( 'BWFAN_Model_Engagement_Tracking' ) || ! method_exists( 'BWFAN_Model_Engagement_Tracking', 'get_automation_step_analytics' ) ) {
			return $this->error_response( [], '' );
		}

		$data = BWFAN_Model_Engagement_Tracking::get_automation_step_analytics( $automation_id, $step_id );

		if ( empty( $data ) || ! is_array( $data ) ) {
			return $this->error_response( [], "No data found." );
		}

		$open_rate        = isset( $data['open_rate'] ) ? number_format( $data['open_rate'], 2 ) : 0;
		$click_rate       = isset( $data['click_rate'] ) ? number_format( $data['click_rate'], 2 ) : 0;
		$revenue          = isset( $data['revenue'] ) ? floatval( $data['revenue'] ) : 0;
		$unsubscribes     = isset( $data['unsbuscribers'] ) ? absint( $data['unsbuscribers'] ) : 0;
		$conversions      = isset( $data['conversions'] ) ? absint( $data['conversions'] ) : 0;
		$sent             = isset( $data['sent'] ) ? absint( $data['sent'] ) : 0;
		$open_count       = isset( $data['open_count'] ) ? absint( $data['open_count'] ) : 0;
		$click_count      = isset( $data['click_count'] ) ? absint( $data['click_count'] ) : 0;
		$contacts_count   = isset( $data['contacts_count'] ) ? absint( $data['contacts_count'] ) : 1;
		$rev_per_person   = empty( $contacts_count ) || empty( $revenue ) ? 0 : number_format( $revenue / $contacts_count, 2 );
		$unsubscribe_rate = empty( $contacts_count ) || empty( $unsubscribes ) ? 0 : ( $unsubscribes / $contacts_count ) * 100;

		/** Tile for sms */
		if ( 'sms' === $mode ) {
			$tiles = [
				[
					'label' => 'Sent',
					'value' => $sent,
				],
				[
					'label' => 'Click Rate',
					'value' => $click_rate . '% (' . $click_count . ')',
				]
			];
		} else {
			/** Get click rate from total opens */
			$click_to_open_rate = ( empty( $click_count ) || empty( $open_count ) ) ? 0 : number_format( ( $click_count / $open_count ) * 100, 2 );

			$tiles = [
				[
					'label' => 'Sent',
					'value' => $sent,
				],
				[
					'label' => 'Open Rate',
					'value' => $open_rate . '% (' . $open_count . ')',
				],
				[
					'label' => 'Click Rate',
					'value' => $click_rate . '% (' . $click_count . ')',
				],
				[
					'label' => 'Click to Open Rate',
					'value' => $click_to_open_rate . '%',
				]
			];
		}

		if ( bwfan_is_woocommerce_active() ) {

			$currency_symbol = get_woocommerce_currency_symbol();
			$revenue         = html_entity_decode( $currency_symbol . $revenue );
			$rev_per_person  = html_entity_decode( $currency_symbol . $rev_per_person );

			$revenue_tiles = [
				[
					'label' => 'Revenue',
					'value' => $revenue . ' (' . $conversions . ')',
				],
				[
					'label' => 'Revenue/ Person',
					'value' => $rev_per_person,
				]
			];

			$tiles = array_merge( $tiles, $revenue_tiles );
		}

		$tiles[] = [
			'label' => 'Unsubscribe Rate',
			'value' => number_format( $unsubscribe_rate, 2 ) . '% (' . $unsubscribes . ')',
		];


		$automation_data = [
			'status' => true,
			'data'   => [
				'analytics' => [
					// [
					// 	'date' => '2021-10-01 00:00:00',
					// 	'view' => [
					// 		'label' => 'View',
					// 		'value' => 12,
					// 	],
					// 	'conversion' => [
					// 		'label' => 'Conversion',
					// 		'value' => 2,
					// 	]
					// ],
					// [
					// 	'date' => '2021-10-11 00:00:00',
					// 	'view' => [
					// 		'label' => 'View',
					// 		'value' => 5,
					// 	],
					// 	'conversion' => [
					// 		'label' => 'Conversion',
					// 		'value' => 1,
					// 	]
					// ],
				],
				'tile'      => $tiles,
			]
		];

		if ( ! $automation_data['status'] ) {
			return $this->error_response( ! empty( $automation_data['message'] ) ? $automation_data['message'] : __( 'Automation not found with provided ID', 'wp-marketing-automations-crm' ), null, 400 );
		} else {
			$automation = $automation_data['data'];
		}

		$this->response_code = 200;

		return $this->success_response( $automation, ! empty( $automation_data['message'] ) ? $automation_data['message'] : __( 'Automation step analytics found', 'wp-marketing-automations-crm' ) );
	}

}

BWFAN_API_Loader::register( 'BWFAN_API_Get_Node_Analytics' );