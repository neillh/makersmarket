<?php

final class BWFAN_WC_Product_Refunded extends BWFAN_Event {
	private static $instance = null;

	public $order_id = null;
	public $order = null;
	public $refund_id = null;

	private function __construct() {
		$this->optgroup_label         = esc_html__( 'Orders', 'wp-marketing-automations' );
		$this->event_name             = esc_html__( 'Order Refunded', 'wp-marketing-automations' );
		$this->event_desc             = esc_html__( 'This event runs when an order is refunded.', 'wp-marketing-automations' );
		$this->event_merge_tag_groups = array( 'bwf_contact', 'wc_order', 'wc_order_refund' );
		$this->event_rule_groups      = array(
			'wc_order',
			'bwf_contact_segments',
			'bwf_contact',
			'bwf_contact_fields',
			'bwf_contact_user',
			'bwf_contact_wc',
			'bwf_contact_geo',
			'bwf_engagement',
			'bwf_broadcast'
		);
		$this->support_lang           = true;
		$this->priority               = 15.3;
		$this->v2                     = true;
	}

	public function load_hooks() {
		add_action( 'woocommerce_order_refunded', array( $this, 'process' ), 10, 2 );
	}

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Set up rules data
	 *
	 * @param $value
	 */
	public function pre_executable_actions( $value ) {
		BWFAN_Core()->rules->setRulesData( $this->event_automation_id, 'automation_id' );
		BWFAN_Core()->rules->setRulesData( $this->order, 'wc_order' );
		BWFAN_Core()->rules->setRulesData( BWFAN_Common::get_bwf_customer( $this->order->get_billing_email(), $this->order->get_user_id() ), 'bwf_customer' );
	}

	/**
	 * Registers the tasks for current event.
	 *
	 * @param $automation_id
	 * @param $integration_data
	 * @param $event_data
	 */
	public function register_tasks( $automation_id, $integration_data, $event_data ) {
		if ( ! is_array( $integration_data ) ) {
			return;
		}
		$data_to_send = $this->get_event_data();

		$this->create_tasks( $automation_id, $integration_data, $event_data, $data_to_send );
	}

	public function get_event_data() {
		$data_to_send                        = [];
		$data_to_send['global']['order_id']  = $this->order_id;
		$data_to_send['global']['refund_id'] = $this->refund_id;

		$this->order                     = $this->order instanceof WC_Order ? $this->order : wc_get_order( $this->order_id );
		$data_to_send['global']['email'] = $this->order instanceof WC_Order ? $this->order->get_billing_email() : '';
		$data_to_send['global']['phone'] = $this->order instanceof WC_Order ? $this->order->get_billing_phone() : '';

		$order_lang = '';
		if ( class_exists( 'woocommerce_wpml' ) ) {
			$order_lang = get_post_meta( $this->order_id, 'wpml_language', true );
		}

		if ( ! empty( $order_lang ) ) {
			$data_to_send['global']['language'] = $order_lang;
		}

		return $data_to_send;
	}

	public function get_email_event() {
		if ( $this->order instanceof WC_Order ) {
			return $this->order->get_billing_email();
		}

		if ( ! empty( absint( $this->order_id ) ) ) {
			$order = wc_get_order( absint( $this->order_id ) );

			return $order instanceof WC_Order ? $order->get_billing_email() : false;
		}

		return false;
	}

	public function get_user_id_event() {
		if ( $this->order instanceof WC_Order ) {
			return $this->order->get_user_id();
		}

		if ( ! empty( absint( $this->order_id ) ) ) {
			$order = wc_get_order( absint( $this->order_id ) );

			return $order instanceof WC_Order ? $order->get_user_id() : false;
		}

		return false;
	}

	/**
	 * Make the required data for the current event and send it asynchronously.
	 *
	 * @param $order_id
	 */
	public function process( $order_id, $refund_id ) {
		$data              = $this->get_default_data();
		$data['order_id']  = $order_id;
		$data['refund_id'] = $refund_id;

		$this->send_async_call( $data );
	}

	/**
	 * Make the view data for the current event which will be shown in task listing screen.
	 *
	 * @param $global_data
	 *
	 * @return false|string
	 */
	public function get_task_view( $global_data ) {
		ob_start();
		$order = wc_get_order( $global_data['order_id'] );
		if ( $order instanceof WC_Order ) {
			?>
            <li>
                <strong><?php esc_html_e( 'Order:', 'wp-marketing-automations' ); ?> </strong>
                <a target="_blank" href="<?php echo get_edit_post_link( $global_data['order_id'] ); //phpcs:ignore WordPress.Security.EscapeOutput
				?>"><?php echo '#' . esc_html( $global_data['order_id'] . ' ' . $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ); ?></a>
            </li>
		<?php } ?>
        <li>
            <strong><?php esc_html_e( 'Email:', 'wp-marketing-automations' ); ?> </strong>
			<?php esc_html_e( $global_data['email'] ); ?>
        </li>
		<?php
		return ob_get_clean();
	}

	public function validate_event_data_before_executing_task( $data ) {
		return $this->validate_order( $data );
	}

	/**
	 * Set global data for all the merge tags which are supported by this event.
	 *
	 * @param $task_meta
	 */
	public function set_merge_tags_data( $task_meta ) {
		$get_data = BWFAN_Merge_Tag_Loader::get_data();
		$set_data = [];
		if ( ! isset( $get_data['wc_order_id'] ) || $get_data['wc_order_id'] !== $task_meta['global']['order_id'] ) {
			$set_data['wc_order_id'] = $task_meta['global']['order_id'];
			$set_data['email']       = $task_meta['global']['email'];
			$set_data['wc_order']    = wc_get_order( $task_meta['global']['order_id'] );
		}

		BWFAN_Merge_Tag_Loader::set_data( $set_data );
	}

	/**
	 * Capture the async data for the current event.
	 */
	public function capture_async_data() {
		$order_id  = BWFAN_Common::$events_async_data['order_id'];
		$refund_id = BWFAN_Common::$events_async_data['refund_id'];
		$order     = wc_get_order( $order_id );

		$this->order_id  = $order_id;
		$this->order     = $order;
		$this->refund_id = $refund_id;

		$this->run_automations();
	}

	/**
	 * Get old wc orders.
	 *
	 * @param $automation_meta
	 *
	 * @return stdClass|WC_Order[]
	 */
	public function get_event_records( $automation_meta ) {
		$query_args = array(
			'post_type'      => 'shop_order',
			'orderby'        => 'date',
			'order'          => 'asc',
			'posts_per_page' => - 1,
			'post_status'    => 'wc-refunded',
			'return'         => 'ids',
		);

		if ( ! is_null( $this->display_count ) ) {
			$query_args['posts_per_page'] = $this->display_count;
		}
		if ( ! is_null( $this->page ) ) {
			$query_args['page'] = $this->page;
		}
		if ( ! is_null( $this->offset ) ) {
			$query_args['offset'] = $this->offset;
		}

		$query_args['date_query'] = array(
			array(
				'after'     => array(
					'year'  => $this->from_year,
					'month' => $this->from_month,
					'day'   => $this->from_day,
				),
				'before'    => array(
					'year'  => $this->to_year,
					'month' => $this->to_month,
					'day'   => $this->to_day,
				),
				'inclusive' => true,
			),
		);

		return wc_get_orders( $query_args );
	}

	/**
	 * Run automations on all the old records of the current event.
	 * This function is used in sync process.
	 *
	 * @param $orders
	 */
	public function process_event_records( $orders ) {
		if ( empty( $orders ) ) {
			return;
		}

		foreach ( $orders as $order_id ) {
			$this->sync_start_time ++;
			// make the tasks from here
			$this->order_id = $order_id;
			$this->order    = wc_get_order( $order_id );
			$this->run_automations();

			$this->offset ++;
			$this->processed ++;

			$data = array(
				'offset'    => $this->offset,
				'processed' => $this->processed,
			);
			$this->update_sync_record( $this->sync_id, $data );
		}
	}

	/**
	 * Capture the async data for the current event.
	 * @return array|bool
	 */
	public function capture_v2_data( $automation_data ) {
		$this->order_id  = BWFAN_Common::$events_async_data['order_id'];
		$this->refund_id = BWFAN_Common::$events_async_data['refund_id'];
		$this->order     = wc_get_order( $this->order_id );

		$automation_data['order_id']  = $this->order_id;
		$automation_data['refund_id'] = $this->refund_id;

		return $automation_data;
	}
}

/**
 * Register this event to a source.
 * This will show the current event in dropdown in single automation screen.
 */
if ( bwfan_is_woocommerce_active() ) {
	return 'BWFAN_WC_Product_Refunded';
}
