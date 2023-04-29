<?php 
class WCST_ProductPage
{
	public function __construct()
	{
		 add_action('init', array(&$this, 'init'));	

		add_action('wp_ajax_wcst_load_estimation_date', array(&$this, 'ajax_display_estimated_date'));
		add_action('wp_ajax_nopriv_wcst_load_estimation_date', array(&$this, 'ajax_display_estimated_date'));		 
	}
	public function ajax_display_estimated_date()
	{
		if(isset($_POST['product_id']))
		{
			echo $this->render_estimated_shipping_date($_POST['product_id'], true);
		}
		wp_die();
	}
	public function init()
	{
		$options_controller = new WCST_Option();
		$estimated_shipping_info_product_page_positioning = $options_controller->get_general_options('estimated_shipping_info_product_page_positioning', 'none');
		if($estimated_shipping_info_product_page_positioning != "none")
			 add_action($estimated_shipping_info_product_page_positioning, array(&$this, 'render_estimated_shipping_date'));
	}
	public function render_estimated_shipping_date($product_id = null, $is_ajax = false)
	{
		global $post;
		$product_id = $product_id != null ? $product_id : $post->ID;
		$product = wc_get_product($product_id);
		
		
		
		if(!$is_ajax)
		{
			wp_enqueue_script('wcst-product-page', WCST_PLUGIN_PATH.'/js/wcst-frontend-product-page.js', array('jquery'));
			$translation_array = array(
					'wcst_ajax_url' => admin_url('admin-ajax.php'),
					'wcst_loading_message' => esc_html__('Loading...', 'woocommerce-shipping-tracking'),
					'is_simple' => $product->is_type('simple') ? 'true' : 'false',
					'product_id' => $product_id //??
				);
			wp_localize_script( 'wcst-product-page', 'wcst', $translation_array );
		}
		
		if(!$product->is_type('simple') && !$is_ajax)
		{
			echo '<div class="wcst_estimated_date_container"></div>';
			return;
		}
		
		$options_controller = new WCST_Option();
		$wpml_helper = new WCST_Wpml();
		$estimated_shipping_info_product_page_label = $options_controller->get_general_options('estimated_shipping_info_product_page_label');
		$estimated_shipping_info_product_page_label = isset($estimated_shipping_info_product_page_label[$wpml_helper->get_current_locale()]) ? $estimated_shipping_info_product_page_label[$wpml_helper->get_current_locale()] : esc_html__('Estimated shipping date:', 'woocommerce-shipping-tracking');
		$estimated_shipping_info_product_page_show_text_for_out_of_stock = $options_controller->get_general_options('estimated_shipping_info_product_page_show_text_for_out_of_stock');
		$estimated_shipping_info_product_page_show_text_for_out_of_stock = isset($estimated_shipping_info_product_page_show_text_for_out_of_stock) ? $estimated_shipping_info_product_page_show_text_for_out_of_stock : "yes";
		$stock_quantity = $product->get_stock_quantity( );
		
		if($estimated_shipping_info_product_page_show_text_for_out_of_stock == 'no' && (!$product->is_in_stock( ) || $product->is_on_backorder( ) || (WCST_Order::get_manage_stock($product) && isset($stock_quantity) && $stock_quantity < 1)))
		{
			return;
		}
		else
		{
			if(!$is_ajax);
				echo '<div class="wcst_estimated_date_container">';
			echo '<span class="wcst_estimated_label">'.$estimated_shipping_info_product_page_label."</span> ".do_shortcode('[wcst_show_estimated_date product_id="'.$product_id.'"]');
			if(!$is_ajax)
				echo '</div>';	
		}
	}
}
?>