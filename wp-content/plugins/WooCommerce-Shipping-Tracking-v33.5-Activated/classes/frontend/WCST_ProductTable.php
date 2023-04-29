<?php 
class WCST_ProductTable
{
	public function __construct()
	{
		add_filter( 'woocommerce_cart_item_name', array(&$this, 'add_estimated_shipping_date_to_product'), 99, 3 ); 
		add_action( 'woocommerce_order_item_meta_end', array(&$this, 'add_tracking_data'), 99, 3 ); //Tracking data is added to the order items (Order details page)
	}
	function add_estimated_shipping_date_to_product( $url, $cart_item, $cart_item_key ) 
	{ 
		$options_controller = new WCST_Option();
		$estimated_shipping_info_cart_checkout_pages_automaic_display = $options_controller->get_general_options('estimated_shipping_info_cart_checkout_pages_automaic_display', 'no');
		$estimated_shipping_info_product_page_show_text_for_out_of_stock = $options_controller->get_general_options('estimated_shipping_info_product_page_show_text_for_out_of_stock');
		$estimated_shipping_info_product_page_show_text_for_out_of_stock = isset($estimated_shipping_info_product_page_show_text_for_out_of_stock) ? $estimated_shipping_info_product_page_show_text_for_out_of_stock : "yes";
		
		if($estimated_shipping_info_cart_checkout_pages_automaic_display == 'yes' && ( (function_exists('is_cart') && @is_cart()) || (function_exists('is_shop') && @is_shop()) ) )
		{
			wp_enqueue_style('wcst-product-table', WCST_PLUGIN_PATH.'/css/wcst_product-table.css');
			
			$product_id = $cart_item['variation_id'] != 0 ? $cart_item['variation_id'] : $cart_item['product_id'];
			$product = wc_get_product($product_id);
			
			if($estimated_shipping_info_product_page_show_text_for_out_of_stock == 'no' && (!$product->is_in_stock( ) || $product->is_on_backorder( ) || (WCST_Order::get_manage_stock($product) && isset($stock_quantity) && $stock_quantity < 1)))
			{
				return $url;
			}
		
			
			$estimated_date = do_shortcode('[wcst_show_estimated_date product_id="'.$product_id .'"]');
			
			if($estimated_date != "N/A" && $estimated_date != "")
			{
				$wpml_helper = new WCST_Wpml();
				$estimated_shipping_info_product_page_label = $options_controller->get_general_options('estimated_shipping_info_product_page_label');
				$estimated_shipping_info_product_page_label = isset($estimated_shipping_info_product_page_label[$wpml_helper->get_current_locale()]) ? $estimated_shipping_info_product_page_label[$wpml_helper->get_current_locale()] : __('Estimated shipping date:', 'woocommerce-shipping-tracking');
		
				$url .='<br/><span class="wcst_estimated_label">'.$estimated_shipping_info_product_page_label."</span> ".$estimated_date;
			}
		}
		return $url; 
	}
	function add_tracking_data($item_id, $item, $order)
	{
		global $wcst_order_model;
		$result = "";
		
		$tracking_data = $wcst_order_model->get_tracking_per_item($order,$item_id);
		if(empty($tracking_data))
			return $result;
		
		$options_controller = new WCST_Option();
		$hide_tracking_data_associated_to_product = $options_controller->get_general_options('hide_tracking_data_associated_to_product', false);
		if($hide_tracking_data_associated_to_product)
			return $result;
		
		wp_enqueue_style('wcst-order-datails-page', WCST_PLUGIN_PATH.'/css/wcst_order-details-page.css');
		
		
		foreach($tracking_data as $data)
		{
			$result .='<div class="wcst_tracking_data_wrapper" >';
			$result .='<span class="wcst_tracking_data_product_table_label">'.esc_html__( 'Tracking number: ', 'woocommerce-shipping-tracking' ).'</span><a href="'.$data["tracking_url"].'" target="_blank">'.$data["tracking_code"].'</a>';
			$result .='<br/><span class="wcst_tracking_data_product_table_label">'.esc_html__( 'Carrier: ', 'woocommerce-shipping-tracking' ).'</span>'.$data["company"];
			$result .='</div>';
		}
		
		echo $result;
	}
}
?>