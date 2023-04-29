<?php

class BWFAN_API_Get_Automation_Contact_Trail extends BWFAN_API_Base {

	public static $ins;

	public function __construct() {
		parent::__construct();
		$this->method       = WP_REST_Server::READABLE;
		$this->route        = '/automation/(?P<automation_id>[\\d]+)/trail/';
		$this->request_args = array(
			'tid' => array(
				'description' => __( 'Trail ID to retrieve', 'wp-marketing-automations-crm' ),
				'type'        => 'string',
			),
		);
	}

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	/** Customer journey Api call */
	public function process_api_call() {
		$tid = $this->get_sanitized_arg( 'tid' );
		$aid = empty( $this->get_sanitized_arg( 'automation_id' ) ) ? 0 : $this->get_sanitized_arg( 'automation_id' );

		/** If step id is 0 , event data to be returned */
		if ( empty( $tid ) ) {
			return $this->error_response( __( 'Invalid/ Empty trail ID provided', 'wp-marketing-automations-crm' ), null, 400 );
		}

		if ( empty( $aid ) ) {
			return $this->error_response( __( 'Invalid/ Empty Automation ID provided', 'wp-marketing-automations-crm' ), null, 400 );
		}

		/** Initiate automation object */
		$automation_obj = BWFAN_Automation_V2::get_instance( $aid );

		/** Check for automation exists */
		if ( ! empty( $automation_obj->error ) ) {
			return $this->error_response( [], $automation_obj->error );
		}

		/** Get trail data from DB */
		$trail = BWFAN_Model_Automation_Contact_Trail::get_trail( $tid );
		$trail = $this->maybe_alter_trail( $trail );

		/** Trail complete or active */
		$trail_completed = BWFAN_Model_Automation_Complete_Contact::is_contact_completed( $tid );

		/** Check for empty data */
		if ( empty( $trail ) || ! is_array( $trail ) ) {
			return $this->error_response( [], 'No data found' );
		}

		/** Get event slug, name and source*/
		$event_slug  = BWFAN_Model_Automations::get_event_name( $aid );
		$event_obj   = BWFAN_Core()->sources->get_event( $event_slug );
		$event_name  = ! empty( $event_obj ) ? $event_obj->get_name() : '';
		$source      = $event_obj->get_source();
		$source_name = BWFAN_Core()->sources->get_source( $source )->get_name();

		/** Set start array */
		$trail_data = [
			[
				'id'       => 'start',
				'type'     => 'start',
				'data'     => [
					'event'     => $event_slug,
					'nice_name' => $event_name,
					'source'    => $source_name,
					'date'      => ''
				],
				'hidden'   => false,
				'position' => [ 'x' => 250, 'y' => 25 ],
			]
		];
		$last_sid   = 'start';

		foreach ( $trail as $data ) {
			/** Set time in start node if not set */
			if ( isset( $trail_data[0]['data']['date'] ) && empty( $trail_data[0]['data']['date'] ) ) {
				$trail_data[0]['data']['date'] = date( 'Y-m-d H:i:s', $data['c_time'] );
			}
			/** Get data trail data by step type */
			$tdata = $this->get_trail_data_by_step_type( $data, $event_slug );

			/** Merge with trail main array */
			$trail_data = array_merge( $trail_data, $tdata );

			/** Set new link data */
			$trail_data[] = [
				'id'           => $last_sid . '-' . $data['sid'],
				'source'       => $last_sid,
				'target'       => $data['sid'],
				'sourceHandle' => '',
				'animated'     => false,
			];
			/** Check if conditional node and set last step connecting */
			if ( $data['type'] == BWFAN_Automation_Controller::$TYPE_CONDITIONAL ) {
				$last_sid = $data['sid'] . '-condi';
			} else {
				$last_sid = $data['sid'];
			}
		}

		/** Set end node */
		$trail_data[] = [
			'id'       => 'end',
			'type'     => 'end',
			'data'     => [
				'label' => 'End Automation',
			],
			'hidden'   => false,
			'position' => [ 'x' => 250, 'y' => 1100 ],
		];
		/** Set last node link with last step id  */
		$trail_data[] = [
			'id'           => $last_sid . '-end',
			'source'       => $last_sid,
			'target'       => 'end',
			'sourceHandle' => '',
			'animated'     => ! $trail_completed,
		];

		$this->response_code = 200;

		return $this->success_response( $trail_data, __( 'Automation contact data found', 'wp-marketing-automations-crm' ) );
	}

	/**
	 * Maybe alter trail if same steps found multiple times
	 *
	 * @param $trail
	 *
	 * @return array
	 */
	public function maybe_alter_trail( $trail ) {
		if ( empty( $trail ) ) {
			return [];
		}
		$new_trail = [];
		$step_key  = [];
		foreach ( $trail as $key => $single ) {
			if ( isset( $step_key[ $single['sid'] ] ) ) {
				unset( $new_trail[ $step_key[ $single['sid'] ] ] );
			}
			$step_key[ $single['sid'] ] = $key;
			$new_trail[ $key ]          = $single;
		}
		sort( $new_trail );

		return $new_trail;
	}

	/**
	 * Format step
	 *
	 * @param $data
	 * @param $event
	 *
	 * @return array|array[]
	 */
	public function get_trail_data_by_step_type( $data, $event ) {

		$status   = $data['status'];
		$response = isset( $data['data'] ) ? json_decode( $data['data'], true ) : '';
		$res_msg  = isset( $response['msg'] ) ? $response['msg'] : '';

		$trail_data = [];
		/** Set step node and data by type */
		switch ( absint( $data['type'] ) ) {
			case BWFAN_Automation_Controller::$TYPE_WAIT :
				$type_data  = isset( $data['step_data'] ) ? json_decode( $data['step_data'], true ) : [];
				$delay_data = isset( $type_data['sidebarData']['data'] ) ? $type_data['sidebarData']['data'] : [];
				$trail_data = [
					[
						'id'   => $data['sid'],
						'type' => 'wait',
						'data' => [
							'value'       => [
								'type'      => 1,
								'delayData' => $delay_data,
							],
							'date'        => date( 'Y-m-d H:i:s', $data['c_time'] ),
							'status'      => $status,
							'msg'         => $res_msg,
							'step_status' => isset( $data['step_status'] ) ? $data['step_status'] : 0
						],
					]
				];
				break;
			case BWFAN_Automation_Controller::$TYPE_ACTION :
				$action      = isset( $data['action'] ) ? json_decode( $data['action'], true ) : [];
				$action_slug = isset( $action['action'] ) ? $action['action'] : '';
				$res_msg     = ( empty( $res_msg ) && isset( $response['error_msg'] ) ) ? $response['error_msg'] : $res_msg;

				/** check for action slug */
				if ( empty( $action_slug ) ) {
					break;
				}
				/**Action object*/
				$action_obj = ! empty( $action_slug ) ? BWFAN_Core()->integration->get_action( $action_slug ) : '';
				/** check for action object */
				if ( ! $action_obj instanceof BWFAN_Action ) {
					break;
				}
				$integration_slug = $action_obj->get_integration_type();
				/**Get integration name*/
				$integration_name = BWFAN_Core()->integration->get_integration( $integration_slug )->get_name();
				$action_name      = $action_obj->get_name();
				$trail_data       = [
					[
						'id'   => $data['sid'],
						'type' => 'action',
						'data' => [
							'selected'    => $action_slug,
							'nice_name'   => $action_name,
							'integration' => $integration_name,
							'date'        => date( 'Y-m-d H:i:s', $data['c_time'] ),
							'status'      => $status,
							'msg'         => $res_msg,
							'step_status' => isset( $data['step_status'] ) ? $data['step_status'] : 0
						],
					]
				];
				break;
			case BWFAN_Automation_Controller::$TYPE_GOAL :
				$action    = isset( $data['action'] ) ? json_decode( $data['action'], true ) : [];
				$benchmark = isset( $action['benchmark'] ) ? $action['benchmark'] : '';
				/**Get event object */
				$event_obj  = BWFAN_Core()->sources->get_event( $benchmark );
				$event_name = ! empty( $event_obj ) ? $event_obj->get_name() : '';
				$source     = ! empty( $event_obj ) ? $event_obj->get_source() : '';
				/**Get source name */
				$source_name = BWFAN_Core()->sources->get_source( $source )->get_name();
				$trail_data  = [
					[
						'id'   => $data['sid'],
						'type' => 'benchmark',
						'data' => [
							'benchmark'   => $benchmark,
							'nice_name'   => $event_name,
							'source'      => $source_name,
							'date'        => date( 'Y-m-d H:i:s', $data['c_time'] ),
							'status'      => $status,
							'msg'         => $res_msg,
							'step_status' => isset( $data['step_status'] ) ? $data['step_status'] : 0
						],
					]
				];
				break;
			case BWFAN_Automation_Controller::$TYPE_CONDITIONAL :
				$type_data          = isset( $data['step_data'] ) ? json_decode( $data['step_data'], true ) : [];
				$sidebar_data       = isset( $type_data['sidebarData'] ) ? $type_data['sidebarData'] : [];
				$conditional_result = isset( $data['data'] ) ? json_decode( $data['data'], true ) : [];
				$direction          = isset( $conditional_result['msg'] ) && 1 === absint( $conditional_result['msg'] ) ? 'yes' : 'no';
				$trail_data         = [
					[
						'id'   => $data['sid'],
						'type' => 'conditional',
						'data' => [
							'sidebarValues' => $sidebar_data,
							'event'         => $event,
							'date'          => date( 'Y-m-d H:i:s', $data['c_time'] ),
							'status'        => $status,
							'msg'           => $res_msg,
							'step_status'   => isset( $data['step_status'] ) ? $data['step_status'] : 0
						],
					],
					[
						'id'   => $data['sid'] . '-condi',
						'type' => 'yesNoNode',
						'data' => [
							'direction' => $direction,
							'parent'    => $data['sid'],
							'date'      => date( 'Y-m-d H:i:s', $data['c_time'] ),
						]
					],
					[
						'id'           => $data['sid'] . '-condi-edge',
						'source'       => $data['sid'],
						'target'       => $data['sid'] . '-condi',
						'sourceHandle' => '',
						'animated'     => false,
					]
				];
				break;
			case BWFAN_Automation_Controller::$TYPE_EXIT :
				$trail_data = [
					[
						'id'   => $data['sid'],
						'type' => 'exit',
						'data' => [
							'date'        => date( 'Y-m-d H:i:s', $data['c_time'] ),
							'status'      => $status,
							'msg'         => $res_msg,
							'step_status' => isset( $data['step_status'] ) ? $data['step_status'] : 0
						],
					]
				];
				break;
			case BWFAN_Automation_Controller::$TYPE_JUMP :
				$trail_data = [
					[
						'id'   => $data['sid'],
						'type' => 'jump',
						'data' => [
							'date'        => date( 'Y-m-d H:i:s', $data['c_time'] ),
							'status'      => $status,
							'msg'         => $res_msg,
							'step_status' => isset( $data['step_status'] ) ? $data['step_status'] : 0
						],
					]
				];
				break;
		}

		return $trail_data;
	}

}

BWFAN_API_Loader::register( 'BWFAN_API_Get_Automation_Contact_Trail' );
