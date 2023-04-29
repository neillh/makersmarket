<?php 
$order_detail_message_additional = "";
$messages = $options_model->get_messages();

if($order_meta['_wcst_order_trackurl'][0] != 'NOTRACK')
{
	$shipping_traking_num = $order_meta['_wcst_order_trackno'][0];
	$original_shipping_traking_num = $order_meta['_wcst_order_trackno'][0];
	if(strpos($shipping_traking_num, "###") !== false)
	{
		$split_result = explode('###', $shipping_traking_num);
		$shipping_traking_num = implode(", ",$split_result);
	}
	if(strpos($shipping_traking_num, ",") !== false)
	{
		$split_result = explode(',', $shipping_traking_num);
		$shipping_traking_num = implode(", ",$split_result);
	}
	
	$dispatch_date = isset($order_meta['_wcst_order_dispatch_date'][0]) ? $order_meta['_wcst_order_dispatch_date'][0] : esc_html__( 'N/A', 'woocommerce' ) ;
	$dispatch_date = $wcst_time_model->format_data($dispatch_date);
	$custom_text = isset($order_meta['_wcst_custom_text'][0]) ? $order_meta['_wcst_custom_text'][0] : "";
	$shipping_company_name = $order_meta['_wcst_order_trackurl'][0] == 'NOTRACK' ? "" : $shipping_company_name;
	$associated_products = wcst_get_value_if_set($order_meta, '_wcst_associated_product', array());
	
	$order_detail_message = (!isset($messages['wcst_order_details_page_message']) || $messages['wcst_order_details_page_message'] == "") ? nl2br($default_message):nl2br($messages['wcst_order_details_page_message']);
	
	$order_detail_message = str_replace("[shipping_company_name]", $shipping_company_name, $order_detail_message);
	$order_detail_message = str_replace("[url_track]", $urltrack, $order_detail_message);
	//conditional
	$order_detail_message = WCST_Shortcodes::check_if_conditional_no_tracking_url_text_has_to_be_removed($order_detail_message, $urltrack == "");
			
	$order_detail_message = str_replace("[tracking_number]", $shipping_traking_num, $order_detail_message);
	$order_detail_message = str_replace("[dispatch_date]", $dispatch_date, $order_detail_message);
	$order_detail_message = str_replace("[custom_text]", $custom_text, $order_detail_message);
	$order_detail_message = str_replace("[order_url]", $order_details_page_url, $order_detail_message);
	$order_detail_message = str_replace("[track_shipping_in_site]", $wcst_shortcodes->display_tracking_info_box(array('tacking_code'=>$original_shipping_traking_num)), $order_detail_message);
	
	//associated products 
	$associated_products_names = array();
	foreach($order->get_items() as $order_item)
	{
		if(in_array($order_item->get_id(), $associated_products))
		{
			$attributes = $wcst_product_model->get_order_variation_attribute_value_and_name($order_item->get_product());
			$associated_products_names[] = $order_item->get_name()." ".$attributes;
		}
	}
	
	$order_detail_message = str_replace("[associated_products]", implode(", ", $associated_products_names), $order_detail_message);
}
else 
	$order_detail_message = "";

//Additional shipping companies
if($order_meta_additional_shippings)
{
	foreach($order_meta_additional_shippings as $additional_shipping)
	{
		if($additional_shipping['_wcst_order_trackurl'] == 'NOTRACK')
			continue;
			
		$order_detail_message_additional .= (!isset($messages['wcst_order_details_page_additional_shippings']) || $messages['wcst_order_details_page_additional_shippings'] == "") ? nl2br($default_message_additional):nl2br($messages['wcst_order_details_page_additional_shippings']);
		
		$urltrack = $additional_shipping['_wcst_order_trackno'];
		$original_urltrack = $additional_shipping['_wcst_order_trackno'];
		if(strpos($urltrack, "###") !== false)
		{
			$split_result = explode('###', $urltrack);
			$urltrack = implode(", ",$split_result);
		}
		if(strpos($urltrack, ",") !== false)
		{
			$split_result = explode(',', $urltrack);
			$urltrack = implode(", ",$split_result);
		}
		$dispatch_date = isset($additional_shipping['_wcst_order_dispatch_date']) ? $additional_shipping['_wcst_order_dispatch_date'] : "" ;
		$dispatch_date = $wcst_time_model->format_data($dispatch_date);
		$shipping_company_name =  $additional_shipping['_wcst_order_trackname'];
		$shipping_traking_num = $additional_shipping['_wcst_order_track_http_url'];
		$custom_text = isset($additional_shipping['_wcst_custom_text']) ? $additional_shipping['_wcst_custom_text'] : "";
		$associated_products = wcst_get_value_if_set($additional_shipping, array('_wcst_associated_product'), array());
		
		$order_detail_message_additional = str_replace("[additional_shipping_company_name]", $shipping_company_name, $order_detail_message_additional);
		$order_detail_message_additional = str_replace("[additional_shipping_tracking_number]", $urltrack, $order_detail_message_additional);
		$order_detail_message_additional = str_replace("[additional_shipping_url_track]", $shipping_traking_num, $order_detail_message_additional);
		$order_detail_message_additional = str_replace("[additional_order_url]", $order_details_page_url, $order_detail_message_additional);
		$order_detail_message_additional = str_replace("[additional_track_shipping_in_site]", $wcst_shortcodes->display_tracking_info_box(array('tacking_code'=>$original_urltrack)), $order_detail_message_additional);
		
		//conditional
		$order_detail_message_additional = WCST_Shortcodes::check_if_conditional_no_tracking_url_text_has_to_be_removed($order_detail_message_additional, $shipping_traking_num == "");
		
		$order_detail_message_additional = isset($dispatch_date) && $dispatch_date != "" && !empty($dispatch_date) ? str_replace("[additional_dispatch_date]", $dispatch_date, $order_detail_message_additional) : str_replace("[additional_dispatch_date]", "", $order_detail_message_additional);
		$order_detail_message_additional = isset($dispatch_date) && $dispatch_date != "" && !empty($dispatch_date) ? str_replace("[dispatch_date]", $dispatch_date, $order_detail_message_additional) : str_replace("[dispatch_date]", "", $order_detail_message_additional);
		$order_detail_message_additional = isset($custom_text) && $custom_text != "" && !empty($custom_text) ? str_replace("[additional_custom_text]", $custom_text, $order_detail_message_additional) : str_replace("[additional_custom_text]", "", $order_detail_message_additional);
	
		//associated products 
		$associated_products_names = array();
		foreach($order->get_items() as $order_item)
		{
			if(in_array($order_item->get_id(), $associated_products))
			{
				$attributes = $wcst_product_model->get_order_variation_attribute_value_and_name($order_item->get_product());
				$associated_products_names[] = $order_item->get_name()." ".$attributes;
			}
		}
		$order_detail_message_additional = str_replace("[additional_associated_products]", implode(", ", $associated_products_names), $order_detail_message_additional);
	}
}
echo '<div class="tracking-box">';
echo $order_detail_message.$order_detail_message_additional;
echo '</div>';
?>
	
				