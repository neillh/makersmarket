<?php

class BWFAN_WC_Order_Data extends BWFAN_Merge_Tag {

	private static $instance = null;


	public function __construct() {
		$this->tag_name        = 'order_data';
		$this->tag_description = __( 'Order Data', 'wp-marketing-automations' );
		add_shortcode( 'bwfan_order_data', array( $this, 'parse_shortcode' ) );
		$this->priority     = 5;
		$this->support_date = true;
	}

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Show the html in popup for the merge tag.
	 */
	public function get_view() {
		$this->get_back_button();
		$this->data_key();
		if ( $this->support_fallback ) {
			$this->get_fallback();
		}

		$this->get_preview();
		$this->get_copy_button();
	}

	/**
	 * Parse the merge tag and return its value.
	 *
	 * @param $attr
	 *
	 * @return mixed|string|void
	 */
	public function parse_shortcode( $attr ) {
		if ( true === BWFAN_Merge_Tag_Loader::get_data( 'is_preview' ) ) {
			return $this->parse_shortcode_output( $this->get_dummy_preview( $attr ), $attr );
		}

		$return_value = '';
		$order_id     = BWFAN_Merge_Tag_Loader::get_data( 'order_id' );
		$item_key     = $attr['key'];

		if ( ! empty( $order_id ) ) {
			$return_value = get_post_meta( $order_id, $item_key, true );

			/** if value is date then checking format in attribute */
			$return_value = $this->get_date_value( $return_value, $attr );

			return $this->parse_shortcode_output( $return_value, $attr );
		}

		$order = BWFAN_Merge_Tag_Loader::get_data( 'wc_order' );
		if ( ! $order instanceof WC_Order ) {
			return $this->parse_shortcode_output( '', $attr );
		}

		$order_id = BWFAN_Woocommerce_Compatibility::get_order_id( $order );

		$return_value = get_post_meta( $order_id, $item_key, true );

		/** if value is date then checking format in attribute */
		$return_value = $this->get_date_value( $return_value, $attr );

		return $this->parse_shortcode_output( $return_value, $attr );
	}

	/**
	 * Get formatted date value
	 *
	 * @param $value
	 *
	 * @return false|string
	 */
	public function get_date_value( $value, $attr ) {
		if ( ! is_array( $attr ) || ! isset( $attr['type'] ) || 'date' !== $attr['type'] || ! isset( $attr['format'] ) || empty( $attr['format'] ) ) {
			return $value;
		}

		return $this->format_datetime( $value, $attr );
	}

	/**
	 * Show dummy value of the current merge tag.
	 *
	 * @return string
	 */
	public function get_dummy_preview( $attr ) {
		if ( ! is_array( $attr ) || ! isset( $attr['type'] ) || 'date' !== $attr['type'] || ! isset( $attr['format'] ) || empty( $attr['format'] ) ) {
			return __( 'Key value', 'wp-marketing-automations' );
		}

		return $this->format_datetime( date( 'Y-m-d H:i:s' ), $attr );
	}

	/**
	 * Return mergetag schema
	 *
	 * @return array[]
	 */
	public function get_setting_schema() {
		$formats      = $this->date_formats;
		$date_formats = [];
		foreach ( $formats as $data ) {
			if ( isset( $data['format'] ) ) {
				$date_time      = date( $data['format'] );
				$date_formats[] = [
					'value' => $data['format'],
					'label' => $date_time,
				];
			}
		}

		return [
			[
				'id'          => 'key',
				'label'       => __( 'Meta Key', 'wp-marketing-automations' ),
				'type'        => 'text',
				'class'       => '',
				'placeholder' => '',
				'hint'        => __( 'Input the correct meta key in order to get the data', 'wp-marketing-automations' ),
				'required'    => true,
				'toggler'     => array(),
			],
			[
				'id'          => 'type',
				'type'        => 'select',
				'options'     => [
					[
						'value' => '',
						'label' => 'Text',
					],
					[
						'value' => 'date',
						'label' => 'Date',
					]
				],
				'label'       => __( 'Select Type', 'wp-marketing-automations' ),
				"class"       => 'bwfan-input-wrapper',
				"required"    => false,
				'placeholder' => 'Select',
				'hint'        => __( 'Select value type', 'wp-marketing-automations' ),
			],
			[
				'id'          => 'format',
				'type'        => 'select',
				'options'     => $date_formats,
				'label'       => __( 'Select Date Format', 'wp-marketing-automations' ),
				"class"       => 'bwfan-input-wrapper',
				"placeholder" => 'Select',
				"required"    => false,
				"description" => "",
				'toggler'     => array(
					'fields'   => array(
						array(
							'id'    => 'type',
							'value' => 'date',
						),
					),
					'relation' => 'AND',
				)
			],
		];
	}
}

/**
 * Register this merge tag to a group.
 */
if ( bwfan_is_woocommerce_active() ) {
	BWFAN_Merge_Tag_Loader::register( 'wc_order', 'BWFAN_WC_Order_Data', null, 'Order' );
}