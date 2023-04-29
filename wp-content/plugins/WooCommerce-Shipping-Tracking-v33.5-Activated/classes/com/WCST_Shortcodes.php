<?php 
class WCST_Shortcodes
{
	public function __construct()
	{
		add_shortcode( 'wcst_show_estimated_date', array(&$this, 'display_estimated_date' ));
		add_shortcode( 'wcst_tracking_form', array(&$this, 'display_tracking_form' ));
	}
	
	public function display_estimated_date($atts)
	{
		$parameters = shortcode_atts( array(
        'product_id' => get_the_ID(),
			), $atts );
			
		if(!isset($parameters['product_id']))
			return "";
		
		global $wcst_product_model,$wcst_time_model;
		
		$product = wc_get_product($parameters['product_id']); 
		
		$stock_quantity = $product->get_stock_quantity( );
		
		$options_controller = new WCST_Option();
		$wpml_helper = new WCST_Wpml();
		$options = $options_controller->get_general_options();
		$estimated_shipping_info_out_of_stock = isset($options['estimated_shipping_info_out_of_stock']) && isset($options['estimated_shipping_info_out_of_stock'][$wpml_helper->get_current_locale()]) ? $options['estimated_shipping_info_out_of_stock'][$wpml_helper->get_current_locale()] : esc_html__('Out of stock, date unavailable', 'woocommerce-shipping-tracking');
		$is_in_backorder = $product->backorders_allowed() && isset($stock_quantity) && $stock_quantity < 1;
		$estimated_shipping_info_product_page_show_text_for_out_of_stock = $options_controller->get_general_options('estimated_shipping_info_product_page_show_text_for_out_of_stock');
		$estimated_shipping_info_product_page_show_text_for_out_of_stock = isset($estimated_shipping_info_product_page_show_text_for_out_of_stock) ? $estimated_shipping_info_product_page_show_text_for_out_of_stock : "yes";
		
		
		if( ($estimated_shipping_info_product_page_show_text_for_out_of_stock == 'yes' && ($is_in_backorder || $product->is_on_backorder( ))) || !$product->is_in_stock( ) || (WCST_Order::get_manage_stock($product) != 'no' && isset($stock_quantity) && $stock_quantity < 1))
		{
			ob_start();
			echo $estimated_shipping_info_out_of_stock;
			return ob_get_clean();
		}
		
		$estimated_date = $wcst_time_model->get_available_date($wcst_product_model->get_estimation_shippment_rule($parameters['product_id']));	
		
		ob_start();
			echo '<span class="wcst_estimated_text">';
			echo $estimated_date;
			echo '</span>';
		return ob_get_clean();
	}
	public function display_tracking_form($atts)
	{
		global $wcst_shipping_company_model;
		$options = new WCST_Option();
		$redirection_method = $options->get_option('wcst_general_options','tracking_form_redirect_method', 'same_page');
		$shipping_companies = $wcst_shipping_company_model->get_all_selected_comanies();
		$company_id = isset($atts['company_id']) && $atts['company_id'] != "" ? $atts['company_id'] : 'none';
		$button_classes = isset($atts['button_classes']) && $atts['button_classes'] != "" ? $atts['button_classes'] : "";
		$track_in_site  = isset($atts['track_in_site']) && $atts['track_in_site'] == 'true' ? true : false;
		$tracking_code  = isset($atts['tracking_code'])? $atts['tracking_code'] : "";
		if($track_in_site)
			$company_id = 'track_in_site';
		
		wp_register_script('wcst-shortcode-tracking-input', WCST_PLUGIN_PATH.'/js/wcst-shortcode-tracking-input.js', array( 'jquery' ));
		wp_enqueue_style('wcst-shortcode-style', WCST_PLUGIN_PATH.'/css/wcst_shortcode.css');
		wp_enqueue_style( 'wcst-timeline', WCST_PLUGIN_PATH.'/css/timeline/wcst-timeline.css');
		
		$js_options = array('tracking_code_empty_error'=> esc_html__('Please fill the tracking code input field.','woocommerce-shipping-tracking'),
							'ajaxurl' => admin_url('admin-ajax.php'),
							'redirection_method' => $redirection_method
							);
							
		wp_localize_script('wcst-shortcode-tracking-input','wcst', $js_options);
		wp_enqueue_script('wcst-shortcode-tracking-input');
		
		ob_start();
		if(file_exists ( get_theme_file_path()."/woocommerce-shipping-tracking/template/shortcode_tracking_input.php" ))
				include get_theme_file_path()."/woocommerce-shipping-tracking/template/shortcode_tracking_input.php";
			else
				include WCST_PLUGIN_ABS_PATH."template/shortcode_tracking_input.php";
		return ob_get_clean();
	}
	
	public static function check_if_conditional_no_tracking_url_text_has_to_be_removed($text, $remove_conditional_text = true)
	{
		$start_string = "[if_has_tracking_url]";
		$end_string = "[/if_has_tracking_url]";
		
		$start = strpos($text, $start_string);
		$end = strpos($text, $end_string);
		if($start == true && $end == true)
		{
			if($remove_conditional_text)
			{
				$offset = strlen($end_string);
				$offset_second = substr ( $text , ($end + $offset) + 1, 2) == "br" ? 6 : 0; //<br /> = 6
				$result = substr_replace ($text, "", $start, ($end + $offset) - $start + $offset_second);
				
				return substr($result,0,1) == "\n" ? "a" : $result;
			}
			else
				return str_replace(array($start_string, $end_string), "", $text);
		}
		return $text;
	}
	public function display_tracking_info_box($params)
	{
		if(!isset($params['tacking_code']) || $params['tacking_code'] == "")
			return "";
		
		wp_enqueue_style( 'wcst-timeline', WCST_PLUGIN_PATH.'/css/timeline/wcst-timeline.css');
		wp_register_script('wcst-shipping-tracking-in-site-tracking', WCST_PLUGIN_PATH.'/js/wcst-shipping-tracking-insite.js', array( 'jquery' ));
		$options = array(
			'ajax_url' => admin_url('admin-ajax.php')
		);
		wp_localize_script( 'wcst-shipping-tracking-in-site-tracking', 'wcsts_in_site_tracking', $options );
		wp_enqueue_script( 'wcst-shipping-tracking-in-site-tracking' );
		
		ob_start();
		?>
		<div class="wcst-in-site-shipping-tracking-container" data-tracking-code="<?php echo $params['tacking_code']; ?>">
			<p class="wcst-in-site-loading-text"><?php esc_html_e( 'Loading shipping info, this could take few seconds. Please wait...', 'woocommerce-shipping-tracking' ) ?></p>
			<div class="wcst_in_site_shipping_info_loading" style="background-image: url('<?php echo WCST_PLUGIN_PATH;?>/img/loader.gif');"></div>
		</div>
		<?php
		$data = ob_get_contents();
		ob_end_clean();
		
		return $data;
	}
}
?>