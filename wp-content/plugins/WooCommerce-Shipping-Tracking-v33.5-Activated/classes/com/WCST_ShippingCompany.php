<?php 
class WCST_ShippingCompany
{
	public function __construct()
	{
		//ajax
		add_action('wp_ajax_nopriv_wcst_get_tracking_info', array(&$this, 'ajax_get_tracking_info'));
		add_action('wp_ajax_wcst_get_tracking_info', array(&$this, 'ajax_get_tracking_info'));
	}
	public function get_company_name_by_id($id)
	{
		$shipping_companies = WCST_AdminMenu::get_shipping_companies_list();
		$custom_companies = get_option( 'wcst_user_defined_companies');
		foreach( $shipping_companies as $k => $v )
		{
			if ($k == $id) 
				return $v;
		}
		//Custom companies
		if(isset($custom_companies) && is_array($custom_companies))
			foreach( $custom_companies as $index => $custom_company )
			{
				if ($index == $id) 
					return $custom_company['name'];
			}
		
		return null; 
	}
	
	public function get_predefined_couriers_list_from_csv()
	{
		//17track $result = array_map('str_getcsv', file(WCST_PLUGIN_ABS_PATH.'/included_companies/17track_carriers.csv'));
		//17 track unset($result[0]);
		$result = $this->get_aftership_couriers_list_from_csv();
		return $result;
	}
	public function get_aftership_couriers_list_from_csv()
	{
		$result = array_map('str_getcsv', file(WCST_PLUGIN_ABS_PATH.'/included_companies/after_ship_couriers.csv'));
		unset($result[0]);
		return $result;
	}
	public function get_selected_predefined_companies($only_slug = false)
	{
		$option_model = new WCST_Option();
		$result = array();
		$company_list = $this->get_predefined_couriers_list_from_csv();
		$options = $option_model->get_option();
		
		foreach($company_list as $company_data)
			if( wcst_get_value_if_set($options, array($company_data[0]), false) == "1")
				$result[$company_data[0]] = $only_slug ? $company_data[0]: $company_data[1];
		
		return $result;
	}
	public function get_all_selected_comanies()
	{
		$option_model = new WCST_Option();
		$company_id = isset($atts['company_id']) && $atts['company_id'] != "" ? $atts['company_id'] : false;
		$shipping_companies = WCST_AdminMenu::get_shipping_companies_list();
		$custom_companies = get_option( 'wcst_user_defined_companies');
		$options = $option_model->get_option();
		
		$result = array('custom_companies' => array(), 'default_companies' => array());
		//Default companies
		foreach( (array)$shipping_companies as $k => $v )
		{
			if (isset($options[$k]) == '1') 
			{
				$result['default_companies'][$k]['name'] = $v;
			}
			
		}
		//Custom companies
		foreach( (array)$custom_companies as $index => $custom_company )
		{
			if (isset($options[$index]) == '1' && isset($custom_company['name']) && $custom_company['name'] != "") //$options[$index] means that the company can be showed.
			{
				$result['custom_companies'][$index]['name'] = $custom_company['name']; 
			}
		}
		
		return $result;
	}
	public function ajax_get_tracking_info()
	{
		global $wcst_shortcodes;
		$option_model = new WCST_Option();
		$tracking_code = isset($_POST['tracking_code']) ? $_POST['tracking_code'] : 'none';
		$tracking_company_id = isset($_POST['tracking_company_id']) ? $_POST['tracking_company_id'] : 'none';
		if($tracking_code != 'none')
		{
			if($tracking_company_id  == 'track_in_site')
			{
				$options_controller = new WCST_Option();
				$options = $options_controller->get_general_options();
				$aftership_api_key = isset($options['aftership_api_key']) && isset($options['aftership_api_key']) ? $options['aftership_api_key'] : "";
				
				//to restore the old selection: $aftership_api_preselected_companies = isset($options['aftership_api_preselected_companies']) ? $options['aftership_api_preselected_companies'] : array();
				$aftership_api_preselected_companies = $this->get_selected_predefined_companies(true);
				
				$after_shipping_tracker = new WCST_AfterShip($aftership_api_key);
				echo $after_shipping_tracker->render_tracking_info_box(array('tacking_code'=>$tracking_code, 'preselected_companies'=>$aftership_api_preselected_companies));
			}
			else 
			{
				$info = WCST_shipping_companies_url::get_company_url(stripslashes( $tracking_company_id ), stripslashes($tracking_code ));
				echo $info['urltrack'];
			}
		}
		wp_die();
	}
}
?>