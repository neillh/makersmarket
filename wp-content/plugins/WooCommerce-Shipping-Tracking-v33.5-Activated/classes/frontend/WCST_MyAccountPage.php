<?php 
class WCST_MyAccountPage
{
	public function __construct()
	{
		$theme_version = wcst_get_file_version( get_template_directory() . '/woocommerce/myaccount/my-account.php' );
		
		add_action( 'woocommerce_account_content', array( &$this,'add_shipping_tracking_buttons'),99 );
		add_action('woocommerce_my_account_my_orders_column_order-number', array( &$this, 'alter_order_number_column' ) );
	}
	
	public function alter_order_number_column($order)
	{
		?>
		<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" data-wcst-id="<?php echo WCST_Order::get_id($order); ?>">
			<?php echo _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number(); ?>
		</a>
		<?php 
	}
	function add_shipping_tracking_buttons()
	{
		global $wp, $wcst_order_model;
		$options_controller = new WCST_Option();
		$wpml_helper = new WCST_Wpml();
			
		$can_render = false;
		if ( did_action( 'woocommerce_account_content' ) ) 
		{
			  if(isset($wp->query_vars) && is_array($wp->query_vars))
				foreach ( $wp->query_vars as $key => $value ) 
				{
					if($key == get_option('woocommerce_myaccount_orders_endpoint'))
						$can_render = true;
				}
		}
		else
			$can_render = true;
		
		if(!$can_render)
			return false;
		
		if($options_controller->get_general_options('disable_track_shipment_buttons', false) != false)
			return;
		
		if(!get_current_user_id())
			return;
			
		$options = $options_controller->get_general_options();
		
		$orders = $wcst_order_model->get_orders_by_user_id(get_current_user_id());
		$tracking_shipment_button = isset($options['tracking_shipment_button']) && isset($options['tracking_shipment_button'][$wpml_helper->get_current_locale()]) ? $options['tracking_shipment_button'][$wpml_helper->get_current_locale()] : esc_html__('Track shipment #%s', 'woocommerce-shipping-tracking');
		$track_urls = array();
		
		foreach($orders as $order)
		{
			$order_meta = $wcst_order_model->get_order_meta($order->ID ); 
			$order_meta_additional_shippings = array();
			if(isset($order_meta['_wcst_additional_companies']))
			{
				$order_meta_additional_shippings = is_string($order_meta['_wcst_additional_companies'][0]) ? unserialize(array_shift($order_meta['_wcst_additional_companies'])) : $order_meta['_wcst_additional_companies'];
			}
				
			$track_urls[$order->ID] = array();
			if(isset($order_meta['_wcst_order_trackno']) && isset($order_meta['_wcst_order_trackurl']) && isset($order_meta['_wcst_order_trackname']) && strlen ($order_meta['_wcst_order_trackno'][0]) > 0)
			{
				 array_push($track_urls[$order->ID], $order_meta['_wcst_order_track_http_url'][0]);
			}
			foreach($order_meta_additional_shippings as $additional)
			{
				array_push($track_urls[$order->ID], $additional['_wcst_order_track_http_url']);
			}
			if(empty($track_urls[$order->ID]))
				$track_urls[$order->ID] = "false";
		}
		wp_enqueue_style('wcst-order-table', WCST_PLUGIN_PATH.'/css/wcst_order_table.css');
		
		$js_order_to_url = array();
		foreach($track_urls as $order_id => $url_array) 
		{
			if($url_array === 'false')
				$js_order_to_url[$order_id] =  $url_array;
			else
			{
				if(!isset($js_order_to_url[$order_id]))
					$js_order_to_url[$order_id] = array();
				foreach($url_array as $url)
					$js_order_to_url[$order_id][] = $url;
			}
		}

		$js_options =  array('order_to_url' => $js_order_to_url,
							 'tracking_shipment_button' => addslashes ($tracking_shipment_button),
							 'wc_version' => WC_VERSION
							);
		wp_register_script('wcst-frontend-order-table', WCST_PLUGIN_PATH.'/js/wcst-frontend-order-table.js', array('jquery'));
		wp_localize_script( 'wcst-frontend-order-table', 'wcst', $js_options );
		wp_enqueue_script( 'wcst-frontend-order-table' );
		
	}
}
?>