<?php

namespace AustraliaPost\Core;

use AustraliaPost\Extensions\Business\API\Business;
use AustraliaPost\Extensions\Business\Endpoints\Create_Shipments_Endpoint;
use WC_Order;

class Tracking {
	/**
	 * The single instance of the class.
	 *
	 * @var Tracking
	 * @since 2.1.1
	 */
	protected static $_instance = null;

	/**
	 * @return Tracking
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}


	private $tracking_url;

	/**
	 * Class constructor.
	 * @since 2.0.0
	 *
	 * @uses hook()
	 *
	 */
	public function __construct() {
		$this->tracking_url = 'https://auspost.com.au/parcels-mail/track.html#/track?id={tracking_number}';

        add_action( 'add_meta_boxes', [$this, 'adding_tracking_metabox'] );
        add_action( 'save_post', [$this, 'process_tracking_metabox'] );

		add_action( 'woocommerce_order_details_after_order_table', [$this, 'add_tracking_details_in_customer_account' ] );
		add_action( 'woocommerce_email_order_meta', [ $this, 'add_tracking_details_in_customer_account'] );
		add_action( 'woocommerce_email_order_meta', [ $this, 'add_tracking_ids_to_email_body_from_shipments'] );
		add_filter( 'woocommerce_my_account_my_orders_actions', [ $this, 'add_track_button_action' ], 10, 2 );

	}

	/**
	 * To add the tracking metabox in the order's page.
	 *
	 * @param object $post The object of the post.
	 *
	 * @since 2.0.0
	 *
	 * @uses add_meta_box()
	 */
	public function adding_tracking_metabox( $post ) {
		add_meta_box( 'aupost-tracking', __( 'Australia Post Tracking', 'woocommerce-australia-post-pro' ), array(
			$this,
			'render_tracking_metabox'
		), 'shop_order', 'side' );
	}

	/**
	 * Display the HTML of the tracking metabox
	 *
	 * @param object $order The object of the order.
	 *
	 * @since 2.0.0
	 *
	 * @uses get_post_meta()
	 */
	public function render_tracking_metabox( $order ) {
		$order_tracking_number = get_post_meta( $order->ID, '_aupost_order_tracking_number', true );
		$order_shipping_date   = get_post_meta( $order->ID, '_aupost_order_shipping_date', true ); ?>
        <p class="form-field form-field-wide">
            <label for="aupost_tracking_number"><?php _e( 'Tracking number', 'woocommerce-australia-post-pro' ); ?>
                :</label>
            <input id="aupost_tracking_number" class="widefat" type="text" name="order_tracking_number"
                   value="<?php echo $order_tracking_number; ?>">
        </p>
        <p class="form-field form-field-wide">
            <label for="aupost_shipping_date"><?php _e( 'Shipping date', 'woocommerce-australia-post-pro' ); ?>:</label>
            <input id="aupost_shipping_date" class="widefat date-picker hasDatepicker date-picker-field" type="date"
                   name="order_shipping_date" value="<?php echo $order_shipping_date; ?>">
        </p>
        <small style="text-align: justify">If you have Labels Pro, and generated a label, you do not have to add the tracking number here. This is only for labels purchased off the plugin.</small>
		<?php
	}

	/**
	 * Save the tracking metabox form.
	 *
	 * @param integer $order_id The ID number of the order.
	 *
	 * @since 2.0.0
	 *
	 * @uses update_post_meta()
	 */
	public function process_tracking_metabox( $order_id ) {
		if ( isset( $_POST['order_tracking_number'] ) ) {
			update_post_meta( intval( $order_id ), '_aupost_order_tracking_number', sanitize_text_field( $_POST['order_tracking_number'] ) );
		}
		if ( isset( $_POST['order_shipping_date'] ) ) {
			update_post_meta( intval( $order_id ), '_aupost_order_shipping_date', sanitize_text_field( $_POST['order_shipping_date'] ) );
		}
	}

	/**
	 * Used to add the tracking info into the Order details page, and the order's completed email.
	 *
	 * @param WC_Order $order The order object.
	 *
	 * @since 2.0.0
	 *
	 * @uses get_post_meta()
	 */
	public function add_tracking_details_in_customer_account( $order ) {
		$order_id = ( method_exists( $order, 'get_id' ) ) ? $order->get_id() : $order->id;

		$order_tracking_number = get_post_meta( $order_id, '_aupost_order_tracking_number', true );
		$order_shipping_date   = get_post_meta( $order_id, '_aupost_order_shipping_date', true );
		if ( $order_tracking_number === '' ) {
			$order_tracking_number = (isset($_POST['order_tracking_number']))? $_POST['order_tracking_number']: '';
		}

		if ( $order_shipping_date === '' ) {
			$order_shipping_date = (isset($_POST['order_shipping_date']))? $_POST['order_shipping_date']: '';
		}
		$time_stamp = strtotime( $order_shipping_date );
		if ( false !== $time_stamp ) {
			$order_shipping_date = date( 'l jS \of F Y', $time_stamp );
		}

		if ( $order_tracking_number != '' && $order_shipping_date != '' ) {
			echo sprintf( __( 'Your order was shipped on %s via Australia Post. Tracking number is (%s).' ), $order_shipping_date, $order_tracking_number );
			echo $this->trackingUrl($order_tracking_number, __( 'Click here to track your order', 'woocommerce-australia-post-pro' ));
			echo '<br>';
			echo '<br>';
		}

		if (class_exists(Business::class)){
			$this->add_tracking_events_to_account_page($order);
		}
	}

	public function add_tracking_ids_to_email_body_from_shipments($order)
	{
		if (!class_exists(Business::class)) {
		    return;
        }

		$shipments = json_decode($order->get_meta(Create_Shipments_Endpoint::LABELS_SHIPMENTS_META_KEY), true);

		if (!$shipments) {
			return;
		}

		$tracking_numbers = [];
		foreach ($shipments as $shipment) {
			if (!isset($shipment['items'])) continue;
			foreach ($shipment['items'] as $item) {
				$tracking_numbers[] = $item['tracking_details']['article_id'];
			}
		}

		if (count($tracking_numbers) === 0) {
		    return;
		}

		?>
        <h3>Tracking Numbers:</h3>
        <ul>
            <?php foreach ($tracking_numbers as $trackingNumber): ?>
	            <li><?php echo $this->trackingUrl($trackingNumber, $trackingNumber); ?></li>
            <?php endforeach; ?>
        </ul>
        <br>
        <?php

	}
	/**
	 * @param WC_Order $order
	 */
	private function add_tracking_events_to_account_page($order)
    {
        $shipments = json_decode($order->get_meta(Create_Shipments_Endpoint::LABELS_SHIPMENTS_META_KEY), true);

	    if (!$shipments) {
		    return;
	    }

	    $tracking_numbers = [];
	    foreach ($shipments as $shipment){
	        if (!isset($shipment['items'])) continue;
	        foreach ($shipment['items'] as $item) {
		        $tracking_numbers[] = $item['tracking_details']['article_id'];
	        }
	    }

		$tracking_info = (new Business([]))->get_tracking_info(implode(',', $tracking_numbers));
		if (! isset($tracking_info["tracking_results"])) {
			return;
		}

		foreach ($tracking_info["tracking_results"] as $index => $result):
			$status = $result['status'];
			$events = $result['trackable_items'][0]["events"];
			if (! is_array($events) || empty($events)) {
				return;
			} ?>
            <br>
            <h3>Shipment #<?php echo $index + 1; ?></h3>
            <p><strong>Status:</strong> <?php echo $status; ?></p>
            <table>
                <thead>
                <tr>
                    <th><?php _e('Date', 'woocommerce-australia-post-pro') ?></th>
                    <th><?php _e('Location', 'woocommerce-australia-post-pro') ?></th>
                    <th><?php _e('Description', 'woocommerce-australia-post-pro') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?php echo date('d M Y h:ia', strtotime($event['date'])); ?></td>
                        <td><?php if (isset($event['location'])): echo $event['location']; endif ?></td>
                        <td><?php echo $event['description']; ?> </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php
		endforeach;

	}

	/**
	 * Used to add (Track) button to the table of the orders table at My Account page.
	 *
	 * @param array $actions The list of actions.
	 * @param object $order The order object.
	 *
	 * @return array  $actions     The modified list of actions.
	 * @uses get_post_meta()
	 * @uses woocommerce_my_account_my_orders_actions
	 * @since 2.0.0
	 *
	 */
	public function add_track_button_action( $actions, $order ) {
		$order_id              = ( method_exists( $order, 'get_id' ) ) ? $order->get_id() : $order->id;
		$order_tracking_number = get_post_meta( $order_id, '_aupost_order_tracking_number', true );
		$order_shipping_date   = get_post_meta( $order_id, '_aupost_order_shipping_date', true );
		if ( $order_tracking_number != '' && $order_shipping_date != '' ) {
			$tracking_url = str_replace( '{tracking_number}', $order_tracking_number, $this->tracking_url );

			$actions['track'] = array(
				'url'  => trim($tracking_url),
				'name' => __( 'Track', 'woocommerce-australia-post-pro' ),
			);
		}
		return $actions;
	}

	private function trackingUrl($trackingNumber, $text)
    {
	    $tracking_url = str_replace( '{tracking_number}', trim($trackingNumber), $this->tracking_url );
	    return sprintf( ' <a target="_blank" href="%s">%s.</a>', trim($tracking_url), $text );
    }

}
