<?php 

class WCST_AdminMenu
{
	var $aftership_url = "http://track.aftership.com/%s";
	var $trackingmore_url = "http://track.trackingmore.com/choose-en-%s.html";
	function __construct() {
	}

	public static function get_shipping_companies_list($return_only_slug = false)
	{
		global $wcst_shipping_company_model;
		/* 
		 Old method
		include WCST_PLUGIN_ABS_PATH.'included_companies/WCST_shipping_companies_list.php';
		*/
		
		//New method
		$shipping_companies = array();
		$data = $wcst_shipping_company_model->get_predefined_couriers_list_from_csv();
		
		foreach($data as $tmp_company)
			$shipping_companies[$tmp_company[0]] = $return_only_slug ? $tmp_company[1] : $tmp_company[1];
		//
		
		
		ksort($shipping_companies); 		
		return apply_filters('wcst_getting_shipping_companies', $shipping_companies);
	}

	public function render_page() 
	{
		
		$tab_to_render = isset($_REQUEST['page']) ? $_REQUEST['page']:'wcst-shipping-companies';
		
		if($tab_to_render == 'wcst-add-custom-shipping-company')
			$this->render_add_custom_companies_tab();
		else if($tab_to_render == 'wcst-edit-messages')
			$this->render_edit_messages_tab();
		else if($tab_to_render == 'wcst-delivery-extra-fields')
			$this->render_delivery_date_time_tab();
		else if($tab_to_render == 'wcst-general-options')
			$this->render_general_options_tab();
		else
			$this->render_general_tab();
	}
	public static function get_default_message()
	{
		$default_message =  '<h4>Your Order has been shipped via [shipping_company_name].</h4>';
		$default_message .=	'<strong>Tracking #</strong>[tracking_number]';
		$default_message .=	'<br/>';
		$default_message .=	'<a class="wcst_tracking_link" href="[url_track]" target="_blank" ><strong>CLICK HERE</strong></a> to track your shipment.<br/>';
		$default_message .=	'Dispatched on: [dispatch_date] <br/>';
		$default_message .=	'[custom_text] <br/>';
		return $default_message;
	}
	public static function get_default_message_additional_shippings()
	{
		$default_message =  '<br/>';
		$default_message .=  'Company name: [additional_shipping_company_name]<br/>';
		$default_message .=  '<strong>Tracking #</strong>[additional_shipping_tracking_number]';
		$default_message .=	'<br/>';
		$default_message .=	'[if_has_tracking_url]<a class="wcst_tracking_link" href="[additional_shipping_url_track]" target="_blank" ><strong>CLICK HERE</strong></a> to track your shipment.[/if_has_tracking_url]<br/>';
		$default_message .=	'Dispatched on: [additional_dispatch_date] <br/>';
		$default_message .=	'[additional_custom_text]';
		return $default_message;
	}
	private function update_custom_companies()
	{
		$companies = array();
		if(isset($_REQUEST['wcst_custom_shipping_company']))
		{
			
			foreach($_REQUEST['wcst_custom_shipping_company'] as $company)
			{
				if(isset($company['name']) )
				{
					if(isset($company['disable_tracking_url']))
						array_push($companies, array("name"=> $company['name'], "url"=>"", "disable_tracking_url" => true,  "enable_aftership"=>false,"enable_trackingmore"=>false));
					else if(isset($company['enable_trackingmore']))
						array_push($companies, array("name"=> $company['name'], "url"=>$this->trackingmore_url, "disable_tracking_url" => false,"enable_aftership"=>false,"enable_trackingmore"=>true));
					else if(isset($company['enable_aftership']))
						array_push($companies, array("name"=> $company['name'], "url"=>$this->aftership_url, "disable_tracking_url" => false,"enable_aftership"=>true,"enable_trackingmore"=>false));
					else
						array_push($companies, array("name"=> $company['name'], "url"=> $company['url'], "disable_tracking_url" => false,  "enable_aftership"=>false,"enable_trackingmore"=>false));
				}
			}
			update_option( 'wcst_user_defined_companies', $companies );
		}
		else
		{
			delete_option( 'wcst_user_defined_companies');
		}
	}
	private function render_edit_messages_tab()
	{
		$options = new WCST_Option();
		$wpml = new WCST_Wpml();
		
		if(isset($_POST['wcst_template_messages']))
			$options->save_messages($_POST['wcst_template_messages']);
		//$messages = get_option( 'wcst_template_messages');
		$messages = $options->get_messages();
		
		$default_message = WCST_AdminMenu::get_default_message();
		$default_message_additional_shippings = WCST_AdminMenu::get_default_message_additional_shippings();
		
		
		$active_notification_text_before_tracking_info = (!isset($messages['wcst_active_notification_text_before_tracking_info']) || $messages['wcst_active_notification_text_before_tracking_info'] == "") ? "":$messages['wcst_active_notification_text_before_tracking_info'];
		$mail_message = (!isset($messages['wcst_mail_message']) || $messages['wcst_mail_message'] == "") ? $default_message:$messages['wcst_mail_message'];
		$mail_additional_snippet = (!isset($messages['wcst_mail_message_additional_shippings']) || $messages['wcst_mail_message_additional_shippings'] == "") ? $default_message_additional_shippings:$messages['wcst_mail_message_additional_shippings'];
	
	
		$order_details_page_message =  (!isset($messages['wcst_order_details_page_message']) || $messages['wcst_order_details_page_message'] == "" )? $default_message:$messages['wcst_order_details_page_message'];
		$order_additional_snippet = (!isset($messages['wcst_order_details_page_additional_shippings']) || $messages['wcst_order_details_page_additional_shippings'] == "") ? $default_message_additional_shippings:$messages['wcst_order_details_page_additional_shippings'];
	
		wp_enqueue_style( 'wcst-toggle', WCST_PLUGIN_PATH.'/css/com-toggle.css');
		wp_enqueue_style( 'wcst-common', WCST_PLUGIN_PATH.'/css/wcst_common.css');
		wp_enqueue_style( 'wcst-admin', WCST_PLUGIN_PATH.'/css/wcst_options.css');
		ob_start();
		?>
		
		<div class="wrap white-box">
		<?php if($wpml->is_wpml_active()):?>
			<small class="wcst_notice"><strong><?php esc_html_e('NOTE:', 'woocommerce-shipping-tracking');?></strong> <?php esc_html_e('WPML Detected! to translate following texts simply select the language you desire from the upper WPML language selector, edit texts and save!', 'woocommerce-shipping-tracking');?> </small>
		<?php endif; ?>
		<form action="" method="post" >
				<div class="input_fields_wrap">
				<h4 class="wcst_no_margin_top"><?php esc_html_e('Instructions', 'woocommerce-shipping-tracking');?></h4>
				<p>
					<?php wcst_html_escape_allowing_special_tags(__('For the <strong>MAIN</strong> shipping company you can use the following <strong>[shipping_company_name]</strong>, <strong>[tracking_number]</strong>, <strong>[custom_text]</strong>, <strong>[dispatch_date]</strong>, <strong>[url_track]</strong>,  <strong>[order_url]</strong>, <strong>[associated_products]</strong> and <strong>[track_shipping_in_site] *</strong> shortocodes to display into the messages the Shipping company name, the tracking number and the tracking url.', 'woocommerce-shipping-tracking'));?>
					<br/><br/>
					<?php wcst_html_escape_allowing_special_tags(__('For <strong>ADDITIONAL</strong> shipping companies you can use the following <strong>[additional_shipping_company_name]</strong>, <strong>[additional_shipping_tracking_number]</strong>, <strong>[additional_shipping_url_track]</strong>, <strong>[additional_custom_text]</strong>, <strong>[additional_dispatch_date]</strong>, <strong>[additional_order_url]</strong>, <strong>[additional_associated_products]</strong> and <strong>[additional_shipping_track_in_site]*</strong> shortcodes.', 'woocommerce-shipping-tracking'));?>
					<br/><br/>
					<?php wcst_html_escape_allowing_special_tags(__('* <strong>[track_shipping_in_site]</strong> and <strong>[additional_track_shipping_in_site]</strong> shortcodes can be use only for <strong>Order details messages</strong>. Those shortcode will render a special area tha will display tracking info directly on site. This <strong>requires</strong> you have entered a valid AfterShip Api Key in the <strong>General optiosn & Texts</strong> menu (click on that menu to have more info on how to get it, it is jus a 2 step operation and it is free!).','woocommerce-shipping-tracking'));?>
				</p>
				<div id="wcst_description_note">
					<span id="wcst_description_note_title"><?php esc_html_e('NOTE', 'woocommerce-shipping-tracking');?></span>
					<?php wcst_html_escape_allowing_special_tags(__("If you are using a <strong>Custom company</strong> for which you have <strong>Disabled the tracking url</strong>, use the conditional <strong>[if_has_tracking_url] [/if_has_tracking_url]</strong> shortcode to wrap the message part you won't to display in case there is no tracking url. This is useful when generating auotomatic tracking links.", 'woocommerce-shipping-tracking'));?>
					<br>
					<?php esc_html_e("Exmple:", 'woocommerce-shipping-tracking');?>
					<pre> Custom message [if_has_tracking_url]&lt;a href="[additional_shipping_url_track]" target="_blank"&gt;&lt;strong&gt;CLICK HERE&lt;/strong&gt;&lt;/a&gt; to track your shipment.[/if_has_tracking_url]</pre>
					<?php esc_html_e("In this way the tracking link will not be generated if the current company has not any tracking url associated.", 'woocommerce-shipping-tracking');?>
				</div>
				<h2 class="wcst_section_title" ><?php esc_html_e('Email', 'woocommerce-shipping-tracking');?></h2>
					<h4 class="margin-top"><?php esc_html_e('Main shipping company message', 'woocommerce-shipping-tracking');?></h4>
					<?php wp_editor($mail_message, "wcst_mail_message", array("textarea_name" => "wcst_template_messages[wcst_mail_message]", 'media_buttons' => false, 'teeny'=> true) ) ?>
					
					<h4 class="margin-top"><?php esc_html_e('Additional companies message.', 'woocommerce-shipping-tracking');?></h4>
					<p><?php esc_html_e('In case of one or more additional shippings the plugin will show the following message per each additional shipping.','woocommerce-shipping-tracking'); ?></p>
					<?php wp_editor($mail_additional_snippet, "wcst_mail_message_additional_shippings", array("textarea_name" => "wcst_template_messages[wcst_mail_message_additional_shippings]", 'media_buttons' => false) ) ?>
					
					<h4 class="margin-top"><?php esc_html_e('Active notification: Text showed before tracking info ', 'woocommerce-shipping-tracking');?></h4>
					<p><?php wcst_html_escape_allowing_special_tags(__('This text will be showed before the tracking info messages setted in the previous options. You can use the following shortcodes: <strong>[order_id]</strong>, <strong>[billing_first_name]</strong>, <strong>[billing_last_name]</strong>, <strong>[shipping_first_name]</strong>, <strong>[shipping_last_name]</strong>, <strong>[formatted_billing_address]</strong>, <strong>[formatted_shipping_address]</strong>.', 'woocommerce-shipping-tracking'));?></p>
					<?php wp_editor($active_notification_text_before_tracking_info, "wcst_active_notification_text_before_tracking_info", array("textarea_name" => "wcst_template_messages[wcst_active_notification_text_before_tracking_info]", 'media_buttons' => false) ) ?>
					
					<h4 class="margin-top"><?php esc_html_e('Email message preview', 'woocommerce-shipping-tracking');?></h4>
					<div class="preview_box"><?php echo nl2br($mail_message)."<br><br>".nl2br($mail_additional_snippet);?></div>
				
					<h4 class="margin-top"><?php esc_html_e('Active notification email message preview', 'woocommerce-shipping-tracking');?></h4>
					<div class="preview_box"><?php echo nl2br($active_notification_text_before_tracking_info).nl2br($mail_message)."<br><br>".nl2br($mail_additional_snippet);?></div>
				
				<h2 class="wcst_section_title"><?php esc_html_e('Order details page', 'woocommerce-shipping-tracking');?></h2>				
					<h4 class="margin-top"><?php esc_html_e('Main shipping company message', 'woocommerce-shipping-tracking');?></h4>
					<?php wp_editor($order_details_page_message, "wcst_order_details_page_message", array("textarea_name" => "wcst_template_messages[wcst_order_details_page_message]", 'media_buttons' => false) ) ?>
					
					<h4 class="margin-top"><?php esc_html_e('Additional companies message (in case of one or more additional shippings. Will be rendered one per additional shippings)', 'woocommerce-shipping-tracking');?></h4>
					<?php wp_editor($order_additional_snippet, "wcst_order_details_page_additional_shippings", array("textarea_name" => "wcst_template_messages[wcst_order_details_page_additional_shippings]", 'media_buttons' => false) ) ?>
					
					
					<h4 class="margin-top"><?php esc_html_e('Order detail page message preview', 'woocommerce-shipping-tracking');?></h4>
					<div class="preview_box"><?php echo nl2br($order_details_page_message)."<br><br>".nl2br($order_additional_snippet);?></div>
				</div>
				<p class="submit">
					<input type="submit" value="Save Changes" class="button-primary" name="Submit">
				</p>
		</form>
		</div>
		<?php
		echo ob_get_clean();
	}
	private function render_add_custom_companies_tab()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
			$this->update_custom_companies();
		
		$custom_companies = get_option( 'wcst_user_defined_companies');
		$counter  = 0;
		
		wp_register_script('wcst-custom-companies', WCST_PLUGIN_PATH.'/js/wcst-admin-custom-companies.js' ,array('jquery'));
		
		wp_enqueue_style( 'wcst-toggle', WCST_PLUGIN_PATH.'/css/com-toggle.css');
		wp_enqueue_style( 'wcst-common', WCST_PLUGIN_PATH.'/css/wcst_common.css');
		wp_enqueue_style( 'wcst-admin', WCST_PLUGIN_PATH.'/css/wcst_options.css');
		
		ob_start();
		?>
		
		<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') echo '<div id="message" class="updated"><p>' . __('Saved successfully.', 'woocommerce-shipping-tracking') . '</p></div>'; ?>
		<div class="wcst_wrap white-box">
		
			<h2 class="wcst_section_title wcst_small_margin_top"><?php esc_html_e('Custom defined shipping companies', 'woocommerce-shipping-tracking');?></h2>
			<h4><b><?php esc_html_e('Add or remove custom shipping companies:', 'woocommerce-shipping-tracking');?></b></h4>
			<p><b><?php esc_html_e('NOTE:', 'woocommerce-shipping-tracking');?></b>
				<?php wcst_html_escape_allowing_special_tags(__('You can create a special URL using the <b>%s</b> string in the address as placeholder for the tracking code. For example: <i>"http://www.shipping-company.com/?tracking-code:%s"</i><br/>In this way the WCST plugin will include the tracking code directly in the url. Use <i>%post_code</i> and <i>%country_code</i> as place holder for the order postcode and country code (shipping country code).', 'woocommerce-shipping-tracking'));?>
			<p>
			<p>
				<?php wcst_html_escape_allowing_special_tags(__('In case the URL takes more than one code, use multiple %s. Example: <i>http://www.courriersite.com?tracking_code=%s&item_code=%s</i>', 'woocommerce-shipping-tracking'));?>
			</p>
			<br>
			<form method="post" >
				<div class="input_fields_wrap">
				<button class="add_field_button button-primary"><?php esc_html_e('Add one more Company', 'woocommerce-shipping-tracking');?></button>
				<?php if($custom_companies && is_array($custom_companies)):
						foreach($custom_companies as $company): 
						$company['enable_aftership'] = isset($company['enable_aftership']) ? $company['enable_aftership'] : false;
						$company['enable_trackingmore'] = isset($company['enable_trackingmore']) ? $company['enable_trackingmore'] : false;
						?>
						<div class="input_box" >
							<label><?php esc_html_e('Shipping Company Name:', 'woocommerce-shipping-tracking'); ?> </label>
							<input type="text"  class="wcst_company_name_input" value="<?php echo $company['name']; ?>" name="wcst_custom_shipping_company[<?php echo $counter ?>][name]" placeholder="ex. DHL, UPS, ..." required></input>
							<br/>
							<label class="wcst_label"><?php esc_html_e('Set a shipping Company Tracking URL:', 'woocommerce-shipping-tracking'); ?> </label>
							<input  class="wcst_tracking_url_input" id="wcst_tracking_url_input_<?php echo $counter ?>" value="<?php echo $company['url']; ?>"  type="text" size="80" name="wcst_custom_shipping_company[<?php echo $counter ?>][url]" placeholder="http://www.ups.com?tracking=%s" required></input>
							<button class="remove_field button-secondary"><?php esc_html_e('Remove company', 'woocommerce-shipping-tracking');?></button>
							
							<h4 class="wcst_title_label"><?php esc_html_e('Extra options', 'woocommerce-shipping-tracking'); ?></h4> 
							<div class="wcst_extra_options_container">
								<label class="wcst_label wcst_very_samll_margin_top"><?php esc_html_e('Disable the tracking url', 'woocommerce-shipping-tracking'); ?> </label>
								<p><?php esc_html_e('The plugin will not generate any tracking url for this company.', 'woocommerce-shipping-tracking'); ?></p>
								<input class="wcst_disable_tracking_url" id="wcst_disable_tracking_url_checkbox_<?php echo $counter ?>" type="checkbox" value="true" data-id="<?php echo $counter ?>" name="wcst_custom_shipping_company[<?php echo $counter ?>][disable_tracking_url]" <?php if($company['disable_tracking_url']) echo 'checked="checked"';?>><?php esc_html_e('Disable tracking url', 'woocommerce-shipping-tracking'); ?></input><br/>
								
								<div  id="wcst_extra_tracking_services_box_<?php echo $counter ?>">
									<label class="wcst_label"><?php esc_html_e('Use a 3rd party tracking service to track the shipping', 'woocommerce-shipping-tracking'); ?> </label>
									<input class="wcst_aftership_checkbox" id="wcst_aftership_checkbox_<?php echo $counter ?>" type="checkbox" value="true" data-id="<?php echo $counter ?>" name="wcst_custom_shipping_company[<?php echo $counter ?>][enable_aftership]" <?php if($company['enable_aftership']) echo 'checked="checked"';?>><?php esc_html_e('Use Aftership service to track order', 'woocommerce-shipping-tracking'); ?></input><br/>
									<input class="wcst_trackingmore_checkbox" id="wcst_trackingmore_checkbox_<?php echo $counter ?>" type="checkbox" value="true" data-id="<?php echo $counter ?>" name="wcst_custom_shipping_company[<?php echo $counter ?>][enable_trackingmore]" <?php if($company['enable_trackingmore']) echo 'checked="checked"';?>><?php esc_html_e('Use TrackingMore service to track order', 'woocommerce-shipping-tracking'); ?></input>
								</div>
							</div>
						</div>
				<?php $counter++; endforeach; else: ?>
					<div class="input_box">
						<label><?php esc_html_e('Shipping Company Name:', 'woocommerce-shipping-tracking'); ?> </label>
						<input type="text" class="wcst_company_name_input" name="wcst_custom_shipping_company[0][name]" placeholder="ex. DHL, UPS, ..." required></input>
						<br/>
						<label class="wcst_label"><?php esc_html_e('Shipping Company Tracking URL:', 'woocommerce-shipping-tracking'); ?> </label>
						<input class="wcst_tracking_url_input" id="wcst_tracking_url_input_0" type="text" size="80" name="wcst_custom_shipping_company[0][url]" placeholder="http://www.ups.com?tracking=%s" required></input>
						<button class="remove_field button-secondary"><?php esc_html_e('Remove field', 'woocommerce-shipping-tracking');?></button>
						
						<h4 class="wcst_title_label"><?php esc_html_e('Extra options', 'woocommerce-shipping-tracking'); ?></h4> 
						<div class="wcst_extra_options_container">
							<label class="wcst_label wcst_very_samll_margin_top"><?php esc_html_e('Disable the tracking url', 'woocommerce-shipping-tracking'); ?> </label>
							<span class="wcst_disable_tracking_url_info"><?php esc_html_e('The plugin will not generate any tracking url for this company.', 'woocommerce-shipping-tracking'); ?></span>
							<input class="wcst_disable_tracking_url" id="wcst_disable_tracking_url_checkbox_0" type="checkbox" value="true" data-id="0" name="wcst_custom_shipping_company[<?php echo $counter ?>][disable_tracking_url]" ><?php esc_html_e('Disable tracking url', 'woocommerce-shipping-tracking'); ?></input><br/>
							
							<div  id="wcst_extra_tracking_services_box_0">
								<label class="wcst_label"><?php esc_html_e('Use a 3rd party tracking service to track the shipping', 'woocommerce-shipping-tracking'); ?> </label>
								<input class="wcst_aftership_checkbox" id="wcst_aftership_checkbox_0" type="checkbox" value="true" data-id="0" name="wcst_custom_shipping_company[0][enable_aftership]"><?php esc_html_e('Use Aftership service to track order', 'woocommerce-shipping-tracking'); ?></input><br/>
								<input class="wcst_trackingmore_checkbox" id="wcst_trackingmore_checkbox_0" type="checkbox" value="true" data-id="0" name="wcst_custom_shipping_company[0][enable_trackingmore]"><?php esc_html_e('Use TrackingMore service to track order', 'woocommerce-shipping-tracking'); ?></input>
							</div>
						</div>
					</div>
				<?php endif ?>
				</div>
				
				<p class="submit">
					<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes', 'woocommerce-shipping-tracking'); ?>" />
				</p>
			</form>
		</div>
		<?php
		echo ob_get_clean();
		
		$js_options = array('counter' => $counter,
							'company_name_txt' => esc_html__('Shipping Company Name:', 'woocommerce-shipping-tracking'),
							'shipping_url_txt' => esc_html__('Shipping Company URL:', 'woocommerce-shipping-tracking'),
							'remove_field_txt' => esc_html__('Remove field', 'woocommerce-shipping-tracking'),
							'extra_options_txt' => esc_html__('Extra options', 'woocommerce-shipping-tracking'),
							'disable_tracking_url_txt' => esc_html__('Disable the tracking url', 'woocommerce-shipping-tracking'),
							'tracking_generation_txt' => esc_html__('The plugin will not generate any tracking url for this company.', 'woocommerce-shipping-tracking'),
							'use_3rd_party_text' => esc_html__('Use a 3rd party tracking service to track the shipping', 'woocommerce-shipping-tracking'),
							'use_aftership_service_text' =>esc_html__('Use Aftership service to track order', 'woocommerce-shipping-tracking'),
							'use_trackingmore_service_text' => esc_html__('Use TrackingMore service to track order', 'woocommerce-shipping-tracking')
							
							);
		wp_localize_script( 'wcst-custom-companies', 'wcst',$js_options);
		wp_enqueue_script('wcst-custom-companies');
	}
	private function render_general_tab()
	{
		$options = get_option( 'wcst_options' );
		$custom_companies = get_option( 'wcst_user_defined_companies');
		$shipping_companies = WCST_AdminMenu::get_shipping_companies_list();
		$favorite = isset($options['favorite']) ? $options['favorite'] : -1;
		
		wp_enqueue_style( 'wcst-toggle', WCST_PLUGIN_PATH.'/css/com-toggle.css');
		wp_enqueue_style( 'wcst-common', WCST_PLUGIN_PATH.'/css/wcst_common.css');
		wp_enqueue_style( 'wcst-admin', WCST_PLUGIN_PATH.'/css/wcst_options.css');
		ob_start();
		?>
		<div class="wrap white-box">
			<h2><?php esc_html_e('Select Shipping Companies used to ship products', 'woocommerce-shipping-tracking');?></h2>
			<form action="options.php" method="post"  style="padding-left:20px">
			<?php settings_fields('wcst_shipping_companies_group'); ?> 
			<table cellpadding="10px">
			
			<?php if( ($favorite > -1 && count($options) > 1) || ( $favorite < 0 && !empty($options) && count($options) > 0)): ?>
				<h2 class="wcst_section_title"><?php esc_html_e('Select default company', 'woocommerce-shipping-tracking');?></h2>
				<p><?php esc_html_e('Optionally you can select a company that will be the company that will be already selected when editing an order. First enable one or more predefined/custom companies, save and then select a default one (if you need it!).', 'woocommerce-shipping-tracking');?></p>
				<select name="wcst_options[favorite]">
				<option value="NOTRACK" <?php if($favorite === 'NOTRACK') echo 'selected="selected"'; ?>> <?php esc_html_e('No Tracking', 'woocommerce-shipping-tracking'); ?></option>
				<?php 
				if(isset( $shipping_companies) && is_array( $shipping_companies))
					foreach( $shipping_companies as $k => $v )
					{
						
						if (isset($options[$k]) == '1') 
						{
							echo '<option value="'.$k.'" ';
							if ($favorite === $k) {
								echo 'selected="selected"';
							}
							echo '>'.$v.'</option>';  
						}
						
					}
					//Custom companies
					if(isset( $custom_companies) && is_array( $custom_companies))
					foreach( $custom_companies as $index => $custom_company )
					{
						if (isset($options[$index]) == '1') 
						{
							echo '<option value="'.$index.'" ';
							if ($favorite === (string)$index) 
							{
								echo ' selected="selected"';
							}
							echo '>'.$custom_company['name'].'</option>';  
						}
					}
				?>
				</select>
			<?php endif; ?>
			
			<h2 class="wcst_section_title"><?php esc_html_e('User defined Companies list:', 'woocommerce-shipping-tracking');?></h2>
			<p><?php wcst_html_escape_allowing_special_tags( sprintf(__('You can add new ones clicking on the <a href="%s" target="_blank">Custom companies</a> menu link.', 'woocommerce-shipping-tracking'), admin_url('admin.php?page=wcst-add-custom-shipping-company')));?></p>
			<?php
					//Custom companies
					$i = 0;
					if(isset( $custom_companies) && is_array( $custom_companies))
						foreach( $custom_companies as $index => $custom_company )
						{
							if($i%5==0){
								echo '<tr>';
							}
								
							$checked = '';
								
							if(1 == isset($options[$index])){
								$checked = "checked='checked'";
							}
										
							echo "<td><td class='forminp'>
									<input type='checkbox' name='wcst_options[$index]' id='$index' value='1' $checked />
								</td>
								<td scope='row'><label for='$index' >".$custom_company['name']."<br/>(ID: ".$index.")</label></td>
								</td>";
							$i++;
							if($i%5==0){
								echo '</tr>';
							}
						}
					if($i%5!=0){
						echo '</tr>';
					}						
			?>
			</table>
			<h2 class="wcst_section_title"><?php esc_html_e('Already defined Companies list:', 'woocommerce-shipping-tracking');?></h2>
			<p><?php esc_html_e('Enable the ones that will be used to ship products.', 'woocommerce-shipping-tracking');?></p>
				
			
				<table cellpadding="10px">
				<?php
										
					$i = 0;
					if(isset( $shipping_companies) && is_array( $shipping_companies))
					foreach( $shipping_companies as $k => $v )
					{
						
						if($i%5==0){
							echo '<tr>';
						}
							
						$checked = '';
							
						if(1 == isset($options[$k])){
							$checked = "checked='checked'";
						}
									
						echo "<td><td class='forminp'>
								<input type='checkbox' name='wcst_options[$k]' id='$k' value='1' $checked />
							</td>
							<td scope='row'><label for='$k' >$v<br/>(ID: $k)</label></td>
							</td>";
								
						$i++;
						if($i%5==0){
							echo '</tr>';
						}
					}
					if($i%5!=0){
						echo '</tr>';
					}
						
				?>
				</table>
				
			
				<p class="submit">
					<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes', 'woocommerce-shipping-tracking'); ?>" />
				</p>
			</form>
		</div>
		<?php
		echo ob_get_clean();
	}	
	public function render_delivery_date_time_tab()
	{
		$options = new WCST_Option();
		$wpml = new WCST_Wpml();
		if(isset($_POST['wcst_checkout_options']))
			$options->save_checkout_options($_POST['wcst_checkout_options']);
		
		$messages_and_options = $options->get_checkout_options();
		
		wp_enqueue_style( 'wcst-toggle', WCST_PLUGIN_PATH.'/css/com-toggle.css');
		wp_enqueue_style( 'wcst-common', WCST_PLUGIN_PATH.'/css/wcst_common.css');
		wp_enqueue_style( 'wcst-admin', WCST_PLUGIN_PATH.'/css/wcst_options.css');	
		
		wp_enqueue_script('wcst-delivery-date-configurator', WCST_PLUGIN_PATH.'/js/wcst-admin-delivery-date-options-page.js', array('jquery'));
		?>
		
		<div class="wrap white-box">
		<?php if($wpml->is_wpml_active()):?>
			<small class="wcst_notice"><strong><?php esc_html_e('NOTE:', 'woocommerce-shipping-tracking');?></strong> <?php esc_html_e('WPML Detected! to translate following texts simply select the language you desire from the upper WPML language selector, edit texts and save!', 'woocommerce-shipping-tracking');?> </small>
		<?php endif; ?>
				
			<form method="post" >
				<h2 class="wcst_section_title wcst_small_margin_top"><?php esc_html_e('General', 'woocommerce-shipping-tracking');?></h2>
				
				
				<div class="wcst_option_group wcst_full">
					<h4><?php esc_html_e('Order details editing', 'woocommerce-shipping-tracking');?></h2>
					<p><?php esc_html_e('By default, once the delivery date and time has been setted it cannot be edited. Would you like your customer to be able to edit date and time?', 'woocommerce-shipping-tracking');?></p>
				
					<?php  $selected = isset($messages_and_options['options']['order_details_page_re_edit_datetime']) ? " checked='checked' " : " "; ?>
					<label class="wcst-switch">
					  <input type="checkbox" class="wcst_toggle" name="wcst_checkout_options[options][order_details_page_re_edit_datetime]" value="true" <?php esc_html_e($selected); ?>>
					  <span class="wcst-slider"></span>
					</label>	
				</div>
				
				<h2 class="wcst_section_title"><?php esc_html_e('Delivery date and time', 'woocommerce-shipping-tracking');?></h2>
				<div class="wcst_option_group wcst_half">
					<p><?php esc_html_e('If enabled, a date and time selector is displayed  to allow the customer to set an optional date and/or time when receive the items', 'woocommerce-shipping-tracking');?></p>
					<input type="checkbox" id="wcst_date_range" name="wcst_checkout_options[options][date_range]" <?php if(isset($messages_and_options['options']['date_range'])) echo 'checked="checked"'; ?>><?php esc_html_e('Display date range selector', 'woocommerce-shipping-tracking'); ?></input><br/>
					<div id="wcst_just_one_date_field">
						<input type="checkbox"  name="wcst_checkout_options[options][just_one_date_field]" <?php if(isset($messages_and_options['options']['just_one_date_field'])) echo 'checked="checked"'; ?>><?php esc_html_e('Display just one date selector', 'woocommerce-shipping-tracking'); ?></input><br/>
					</div>
				</div>
				
				<div class="wcst_option_group wcst_half">
				<h4><?php esc_html_e('Visibility', 'woocommerce-shipping-tracking');?></h4>
				<p><?php esc_html_e('Set where the date (and eventually the time) selector has to be displayed.', 'woocommerce-shipping-tracking');?></p>
				
					<p>
						<input type="checkbox" name="wcst_checkout_options[options][show_on_checkout_page]" <?php if(isset($messages_and_options['options']['show_on_checkout_page'])) echo 'checked="checked"'; ?>><?php esc_html_e('Show date selector on Checkout page', 'woocommerce-shipping-tracking'); ?></input><br/>
						<input type="checkbox" name="wcst_checkout_options[options][show_on_order_details_page]" <?php if(isset($messages_and_options['options']['show_on_order_details_page'])) echo 'checked="checked"'; ?>><?php esc_html_e('Show date selector on Order details page', 'woocommerce-shipping-tracking'); ?></input><br/><br/>
					</p>
				</div>
				
				<div class="wcst_option_group wcst_half">
					<h4><?php esc_html_e('Time ranges', 'woocommerce-shipping-tracking');?></h4>
					<input type="checkbox" name="wcst_checkout_options[options][time_range]" <?php if(isset($messages_and_options['options']['time_range'])) echo 'checked="checked"'; ?>><?php esc_html_e('Display time range (you can restrict selection using the following starting and ending hours and minutes boxes)', 'woocommerce-shipping-tracking'); ?></input><br/><br/>
					<label><?php esc_html_e('Start hour & minute', 'woocommerce-shipping-tracking');?></label>
					<input type="number" name="wcst_checkout_options[options][time_range_start_hour]" min="0" step="1" max="24" value="<?php if(isset($messages_and_options['options']['time_range_start_hour'])) echo $messages_and_options['options']['time_range_start_hour']; else echo 0;?>"></input>
					<input type="number" name="wcst_checkout_options[options][time_range_start_minute]" min="0" step="1" max="59" value="<?php if(isset($messages_and_options['options']['time_range_start_minute'])) echo $messages_and_options['options']['time_range_start_minute'];  else echo 0;?>"></input><br/>
					<label><?php esc_html_e('End hour & minute', 'woocommerce-shipping-tracking');?></label>
					<input type="number" name="wcst_checkout_options[options][time_range_end_hour]" min="0" step="1" max="24" value="<?php if(isset($messages_and_options['options']['time_range_end_hour'])) echo $messages_and_options['options']['time_range_end_hour']; else echo 23;?>"></input>
					<input type="number" name="wcst_checkout_options[options][time_range_end_minute]" min="0" step="1" max="59" value="<?php if(isset($messages_and_options['options']['time_range_end_minute'])) echo $messages_and_options['options']['time_range_end_minute']; else echo 59;?>" ></input><br/><br/><br/>
					
					
					<input type="checkbox" name="wcst_checkout_options[options][time_secondary_range]" <?php if(isset($messages_and_options['options']['time_secondary_range'])) echo 'checked="checked"'; ?>><?php esc_html_e('Display secondary time range (will be displayed only in previous option has been checked. You can restrict selection using the following starting and ending hours and minutes boxes)', 'woocommerce-shipping-tracking'); ?></input><br/><br/>
					<label><?php esc_html_e('Start hour & minute', 'woocommerce-shipping-tracking');?></label>
					<input type="number" name="wcst_checkout_options[options][time_secondary_range_start_hour]" min="0" step="1" max="24" value="<?php if(isset($messages_and_options['options']['time_secondary_range_start_hour'])) echo $messages_and_options['options']['time_secondary_range_start_hour']; else echo 0;?>"></input>
					<input type="number" name="wcst_checkout_options[options][time_secondary_range_start_minute]" min="0" step="1" max="59" value="<?php if(isset($messages_and_options['options']['time_secondary_range_start_minute'])) echo $messages_and_options['options']['time_secondary_range_start_minute']; else echo 0;?>"></input><br/>
					<label><?php esc_html_e('End hour & minute', 'woocommerce-shipping-tracking');?></label>
					<input type="number" name="wcst_checkout_options[options][time_secondary_range_end_hour]" min="0" step="1" max="24" value="<?php if(isset($messages_and_options['options']['time_secondary_range_end_hour'])) echo $messages_and_options['options']['time_secondary_range_end_hour']; else echo 23;?>"></input>
					<input type="number" name="wcst_checkout_options[options][time_secondary_range_end_minute]" min="0" step="1" max="59" value="<?php if(isset($messages_and_options['options']['time_secondary_range_end_minute'])) echo $messages_and_options['options']['time_secondary_range_end_minute']; else echo 59;?>"></input><br/><br/>
				</div>
				
				<div class="wcst_option_group wcst_half">
					<h4><?php esc_html_e('Dates to exclude', 'woocommerce-shipping-tracking');?></h4>
					<input type="button" class="button-primary button" value="<?php esc_html_e('Add new', 'woocommerce-shipping-tracking');?>" onclick="wcst_addRow('wcst_excluded_date_table')" />

					<input type="button" class="button-primary button" value="<?php esc_html_e('Remove selected', 'woocommerce-shipping-tracking');?>" onclick="wcst_deleteRow('wcst_excluded_date_table')" />

					<table id="wcst_excluded_date_table" width="350px" border="1">
						 <tr>
							<th></th>
							<th><?php esc_html_e('Day', 'woocommerce-shipping-tracking');?></th>
							<th><?php esc_html_e('Month', 'woocommerce-shipping-tracking');?></th>
						  </tr>
					<?php if(isset($messages_and_options['options']['delivery_date_to_exclude'])):
								foreach($messages_and_options['options']['delivery_date_to_exclude'] as $index => $date_to_exclude): ?>
							<tr>
							<td><input type="checkbox" /></td>
							<td><input type="number" step="1" min="1" max="31" value="<?php echo $date_to_exclude['day']; ?>" name="wcst_checkout_options[options][delivery_date_to_exclude][<?php echo $index; ?>][day]"/></td>
							<td><input type="number" step="1" min="1" max="12" value="<?php echo $date_to_exclude['month']; ?>" name="wcst_checkout_options[options][delivery_date_to_exclude][<?php echo $index; ?>][month]"/></td>
						</tr>
					<?php endforeach;
						endif; ?>
					</table>
				</div>
				
				<h2 class="wcst_section_title"><?php esc_html_e('Title, labels and description', 'woocommerce-shipping-tracking');?></h2>
				<div class="wcst_option_group wcst_half">
					<p>
						<label><?php esc_html_e('Title', 'woocommerce-shipping-tracking'); ?></label>
						<input class="wcst_checkout_tab_input" type="text" name="wcst_checkout_options[messages][title]" value="<?php if(isset($messages_and_options['messages']['title'])) echo $messages_and_options['messages']['title']; ?>" placeholder="<?php esc_html_e('Ex.: Additional shipping delivery info', 'woocommerce-shipping-tracking'); ?>"></input>
					<p/>
					
					<p><?php esc_html_e('Will be visible only if one of the previews option has been selected.', 'woocommerce-shipping-tracking');?></p>
					<p>
						<label><?php esc_html_e('Date range label', 'woocommerce-shipping-tracking'); ?></label>
						<input class="wcst_checkout_tab_input" type="text" name="wcst_checkout_options[messages][date_range]" value="<?php if(isset($messages_and_options['messages']['date_range'])) echo $messages_and_options['messages']['date_range']; ?>" placeholder="<?php esc_html_e('Ex.: Select a start and end period.', 'woocommerce-shipping-tracking'); ?>"></input>
					</p>
					<p>
						<label><?php esc_html_e('Time range label', 'woocommerce-shipping-tracking'); ?></label>
						<input class="wcst_checkout_tab_input" type="text" name="wcst_checkout_options[messages][time_range]" value="<?php if(isset($messages_and_options['messages']['time_range'])) echo $messages_and_options['messages']['time_range']; ?>" placeholder="<?php esc_html_e('Ex.: Select a start and end time period.', 'woocommerce-shipping-tracking'); ?>"></input>
					</p>
					<p>
						<label><?php esc_html_e('Seconday time range', 'woocommerce-shipping-tracking'); ?></label>
						<input class="wcst_checkout_tab_input" type="text" name="wcst_checkout_options[messages][time_secondary_range]" value="<?php if(isset($messages_and_options['messages']['time_secondary_range'])) echo $messages_and_options['messages']['time_secondary_range']; ?>" placeholder="<?php esc_html_e('Ex.: Select a secondary start and end time period.', 'woocommerce-shipping-tracking'); ?>"></input>
					</p>
					<p>
						<label><?php esc_html_e('Description', 'woocommerce-shipping-tracking'); ?></label>
						<textarea class="wcst_checkout_tab_textarea" name="wcst_checkout_options[messages][note]" placeholder="<?php esc_html_e('Ex.: Type a preferead Date and time to receive you items, we will do the best to respect your needings.', 'woocommerce-shipping-tracking'); ?>"><?php if(!empty($messages_and_options['messages']['note'])) echo $messages_and_options['messages']['note']; ?></textarea>
					</p>
				</div>
				<p class="submit">
					<input  name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes', 'woocommerce-shipping-tracking'); ?>" />
				</p>
			</fom>
		</div>
		<?php
	}
	public function render_general_options_tab()
	{
		global $wcst_shipping_company_model;
		$options_controller = new WCST_Option();
		$wpml_helper = new WCST_Wpml();
		if(isset($_POST['wcst_general_options']))
			$options_controller->save_general_options($_POST['wcst_general_options']); //update_option('wcst_general_options', $_POST['wcst_general_options']);
		
		//$options = get_option('wcst_general_options');
		$options = $options_controller->get_general_options();
		$date_format = isset($options['date_format']) ? $options['date_format'] : "dd/mm/yyyy";
		$dispatch_date_automatic_fill_with_today_date = isset($options['dispatch_date_automatic_fill_with_today_date']) ? $options['dispatch_date_automatic_fill_with_today_date'] : "no";
		$order_details_page_positioning = isset($options['order_details_page_positioning']) ? $options['order_details_page_positioning'] : "woocommerce_order_details_after_order_table";
		$estimated_shipping_info_product_page_positioning = isset($options['estimated_shipping_info_product_page_positioning']) ? $options['estimated_shipping_info_product_page_positioning'] : "none";
		$estimated_shipping_info_product_page_show_text_for_out_of_stock = isset($options['estimated_shipping_info_product_page_show_text_for_out_of_stock']) ? $options['estimated_shipping_info_product_page_show_text_for_out_of_stock'] : "yes";
		$estimated_shipping_info_cart_checkout_pages_automaic_display = isset($options['estimated_shipping_info_cart_checkout_pages_automaic_display']) ? $options['estimated_shipping_info_cart_checkout_pages_automaic_display'] : "no";
		$redirect_method = isset($options['tracking_form_redirect_method']) ? $options['tracking_form_redirect_method'] : "same_page";
		$enable_bulk_import = isset($options['enable_bulk_import']) ? $options['enable_bulk_import'] : "no";
		$admin_order_details_autofocus = isset($options['admin_order_details_autofocus']) ? $options['admin_order_details_autofocus'] : "no";
		$enable_bulk_import_time_interval = isset($options['enable_bulk_import_time_interval']) ? $options['enable_bulk_import_time_interval'] : "daily";
		$bulk_import_merge_data = isset($options['bulk_import_merge_data']) ? $options['bulk_import_merge_data'] == 'yes' : false;
		$enable_bulk_import_csv_file_path = isset($options['enable_bulk_import_csv_file_path']) ? $options['enable_bulk_import_csv_file_path'] : "";
		$estimated_shipping_info_product_page_label = isset($options['estimated_shipping_info_product_page_label']) && isset($options['estimated_shipping_info_product_page_label'][$wpml_helper->get_current_locale()]) ? $options['estimated_shipping_info_product_page_label'][$wpml_helper->get_current_locale()] : esc_html__('Estimated shipping date:', 'woocommerce-shipping-tracking');;
		$estimated_shipping_info_out_of_stock = isset($options['estimated_shipping_info_out_of_stock']) && isset($options['estimated_shipping_info_out_of_stock'][$wpml_helper->get_current_locale()]) ? $options['estimated_shipping_info_out_of_stock'][$wpml_helper->get_current_locale()] : esc_html__('Out of stock, date unavailable', 'woocommerce-shipping-tracking');
		$tracking_shipment_button = isset($options['tracking_shipment_button']) && isset($options['tracking_shipment_button'][$wpml_helper->get_current_locale()]) ? $options['tracking_shipment_button'][$wpml_helper->get_current_locale()] : esc_html__('Track shipment #%s', 'woocommerce-shipping-tracking');;
		$active_notification_email_subject = isset($options['active_notification_email_subject']) && isset($options['active_notification_email_subject'][$wpml_helper->get_current_locale()]) ? $options['active_notification_email_subject'][$wpml_helper->get_current_locale()] : esc_html__('Your products have been shipped', 'woocommerce-shipping-tracking');
		$active_notification_email_heading = isset($options['active_notification_email_heading']) && isset($options['active_notification_email_heading'][$wpml_helper->get_current_locale()]) ? $options['active_notification_email_heading'][$wpml_helper->get_current_locale()] : get_bloginfo('name');
		$active_notification_from_name = isset($options['active_notification_from_name']) && isset($options['active_notification_from_name'][$wpml_helper->get_current_locale()]) ? $options['active_notification_from_name'][$wpml_helper->get_current_locale()] : "";
		$active_notification_from_email_address = isset($options['active_notification_from_email_address']) && isset($options['active_notification_from_email_address'][$wpml_helper->get_current_locale()]) ? $options['active_notification_from_email_address'][$wpml_helper->get_current_locale()] : "";
		//
		$aftership_api_key = isset($options['aftership_api_key']) ? $options['aftership_api_key'] : "";
		$aftership_api_preselected_companies = isset($options['aftership_api_preselected_companies']) ? $options['aftership_api_preselected_companies'] : array();
		$estimated_shipping_report_info_on_order_details = isset($options['estimated_shipping_report_info_on_order_details']) && isset($options['estimated_shipping_report_info_on_order_details']) ? $options['estimated_shipping_report_info_on_order_details'] : false;
		$disable_track_shipment_buttons = isset($options['disable_track_shipment_buttons']) && isset($options['disable_track_shipment_buttons']) ? $options['disable_track_shipment_buttons'] : false;
		$hide_tracking_data_associated_to_product = wcst_get_value_if_set($options, 'hide_tracking_data_associated_to_product', false);
		$disable_utf8_encoding = isset($options['disable_utf8_encoding']) && isset($options['disable_utf8_encoding']) ? $options['disable_utf8_encoding'] : false;
		$csv_separator = $options_controller->get_csv_separator();
		
	
		wp_enqueue_style( 'wcst-toggle', WCST_PLUGIN_PATH.'/css/com-toggle.css');
		wp_enqueue_style( 'wcst-common', WCST_PLUGIN_PATH.'/css/wcst_common.css');
		wp_enqueue_style( 'wcst-admin', WCST_PLUGIN_PATH.'/css/wcst_options.css');		
		
		wp_enqueue_script('wcst-admin-options-page', WCST_PLUGIN_PATH.'/js/wcst-admin-options-page.js', array('jquery'));
		
		$order_statuses = wc_get_order_statuses();
		?>
		<div class="wrap white-box">
				
			<form action="" method="post" > 
			<?php settings_fields('wcst_general_options_group'); ?> 
				
				<h2  class="wcst_section_title wcst_small_margin_top"><?php esc_html_e('System', 'woocommerce-shipping-tracking');?></h2>
				<div class="wcst_option_group wcst_half">
					<h4><?php esc_html_e('Email options', 'woocommerce-shipping-tracking');?></h4>
					<p>
						<?php esc_html_e('By default tracking info are displayed in every woocommerce outgoing email only if the Order status is completed. Select which in which order status(es) would you like to include the info', 'woocommerce-shipping-tracking'); ?>
					<p/>
						
						<?php foreach($order_statuses as $order_status => $order_status_name):
							$order_status = str_replace("wc-", "", $order_status); ?>
							<input class="" type="checkbox" name="wcst_general_options[email_options][show_tracking_info_by_order_statuses][<?php echo $order_status; ?>]" value="true" <?php  if($options_controller->get_email_show_tracking_info_by_order_status($order_status)) echo 'checked="checked"';?> ><?php echo $order_status_name; ?></input>
						<?php endforeach; ?>
						
					
				</div>
				
				<div class="wcst_option_group wcst_half"> 
					<h4 ><?php esc_html_e('Custom statuses', 'woocommerce-shipping-tracking');?></h4>
					<p><?php esc_html_e('In case you are using custom statuses email, please enter the status codes comma separated for which you want to embed shipping tracking info', 'woocommerce-shipping-tracking');?></p>
					<input type="text" class="wcst_text_input" name="wcst_general_options[email_options][show_tracking_info_by_order_statuses][custom_statuses]" placeholder="wc-shipping,wc-waiting,wc-delivered" value="<?php if(isset($options['email_options']['show_tracking_info_by_order_statuses']['custom_statuses'])) echo $options['email_options']['show_tracking_info_by_order_statuses']['custom_statuses']; ?>"></input>
				</div>
				
				<div class="wcst_option_group wcst_half">
					<h4 ><?php esc_html_e('Autofocus on admin order detail page', 'woocommerce-shipping-tracking');?></h4>
					<p><?php esc_html_e('Main tracking code field will be automatically autofocused', 'woocommerce-shipping-tracking'); ?></p>
					<select name="wcst_general_options[admin_order_details_autofocus]">
						<option value="no" <?php if($admin_order_details_autofocus == "no") echo 'selected="selected"';?>><?php esc_html_e('No', 'woocommerce-shipping-tracking');?></option>
						<option value="yes" <?php if($admin_order_details_autofocus == "yes") echo 'selected="selected"';?>><?php esc_html_e('Yes', 'woocommerce-shipping-tracking');?></option>
					</select>
				</div>
				
				
				<div class="wcst_option_group wcst_half">
					<h4 ><?php esc_html_e('Disable UTF-8 encoding for the estimated shipping data texts', 'woocommerce-shipping-tracking');?></h4>
					<p><?php esc_html_e('In some installation the UTF-8 installation may corrupt the estimated shipping data text. Enable the following option to disable the encoding.', 'woocommerce-shipping-tracking'); ?></p>
					<label><?php esc_html_e('Disable UTF-8', 'woocommerce-shipping-tracking'); ?></label>
					<?php  $selected = $disable_utf8_encoding ? " checked='checked' " : " "; ?>
					<label class="wcst-switch">
					  <input type="checkbox" class="wcst_toggle" name="wcst_general_options[disable_utf8_encoding]" value="true" <?php esc_html_e($selected); ?>>
					  <span class="wcst-slider"></span>
					</label>	
				</div>	
				
				<div class="wcst_option_group wcst_half">
					<h4 ><?php esc_html_e('Import options', 'woocommerce-shipping-tracking');?></h4>
					<p><?php esc_html_e('CSV fields separator. Default character is ","', 'woocommerce-shipping-tracking'); ?></p>
					<input type="text" name="wcst_general_options[csv_separator]" required="required" class="wcst_option_text_field" placeholder="<?php esc_html_e('Default separator: ,', 'woocommerce-shipping-tracking'); ?>" value="<?php echo $csv_separator; ?>"></input>
				</div>
				
				<div class="wcst_option_group wcst_half">				
					<h4 ><?php esc_html_e('Automatic bulk import', 'woocommerce-shipping-tracking');?></h4>
					<p><?php wcst_html_escape_allowing_special_tags(__('<strong>NOTE:</strong> Scheduling task is performed using the WordPress function <i>wp_schedule_event()</i>. It will trigger  the scheduled import task at the specified interval <strong>ONLY</strong> if someone visits your WordPress site. More info at <a href="https://codex.wordpress.org/Function_Reference/wp_schedule_event" target="_blank">wp_schedule_event reference page</a>.', 'woocommerce-shipping-tracking')); ?></p>
					<label><?php esc_html_e('Enable automatic bulk import?', 'woocommerce-shipping-tracking'); ?></label>
					<select name="wcst_general_options[enable_bulk_import]" id="wcst_enable_bulk_import">
						<option value="no" <?php if($enable_bulk_import == "no") echo 'selected="selected"';?>><?php esc_html_e('No', 'woocommerce-shipping-tracking');?></option>
						<option value="yes" <?php if($enable_bulk_import == "yes") echo 'selected="selected"';?>><?php esc_html_e('Yes', 'woocommerce-shipping-tracking');?></option>
					</select>
				
					<div id="wcst_advanced_bulk_import_options">
						<label  class="wcst_label" ><?php esc_html_e('Set a valid csv url path', 'woocommerce-shipping-tracking'); ?></label>
						<p><?php esc_html_e('It can be a DropBox public url, http url, etc...', 'woocommerce-shipping-tracking'); ?></p>
						<input type="url" class="wcst_csv_file_input_text" placeholder="http://www.yoursite.com/wp-content/csv/file.csv" name="wcst_general_options[enable_bulk_import_csv_file_path]" value="<?php echo $enable_bulk_import_csv_file_path; ?>"></input>
						
						<label  class="wcst_label" ><?php esc_html_e('How often has the automatic import to be performed?', 'woocommerce-shipping-tracking'); ?></label>
						<select name="wcst_general_options[enable_bulk_import_time_interval]">
							<option value="hourly" <?php if($enable_bulk_import_time_interval == "hourly") echo 'selected="selected"';?>><?php esc_html_e('Hourly', 'woocommerce-shipping-tracking');?></option>
							<option value="twicedaily" <?php if($enable_bulk_import_time_interval == "twicedaily") echo 'selected="selected"';?>><?php esc_html_e('Twice a day', 'woocommerce-shipping-tracking');?></option>
							<option value="daily" <?php if($enable_bulk_import_time_interval == "daily") echo 'selected="selected"';?>><?php esc_html_e('Daily', 'woocommerce-shipping-tracking');?></option>
						</select>
						
						<label class="wcst_label"><?php esc_html_e('In case an order already has some tracking info associated, merge the existing info with imported data?', 'woocommerce-shipping-tracking');?></label>
						<select name="wcst_general_options[bulk_import_merge_data]" >
							<option value="no" <?php selected(!$bulk_import_merge_data) ?>><?php esc_html_e('No', 'woocommerce-shipping-tracking');?></option>
							<option value="yes" <?php selected($bulk_import_merge_data) ?>><?php esc_html_e('Yes', 'woocommerce-shipping-tracking');?></option>
						</select>
					</div>
				</div>	
				
				<h2 class="wcst_section_title"><?php esc_html_e('Frontend - Orders list & Order details page', 'woocommerce-shipping-tracking');?></h2>
				<div class="wcst_option_group wcst_half">
					<h4 ><?php esc_html_e('Track shipment buttons', 'woocommerce-shipping-tracking');?></h4>
					<p><?php esc_html_e('Disable "Track shipment" buttons displayed in the Orders list table (My Account -> Orders)', 'woocommerce-shipping-tracking'); ?></p>
					<?php  $selected = $disable_track_shipment_buttons ? " checked='checked' " : " "; ?>
					<label class="wcst-switch">
					  <input type="checkbox" class="wcst_toggle" name="wcst_general_options[disable_track_shipment_buttons]" value="true" <?php esc_html_e($selected); ?>>
					  <span class="wcst-slider"></span>
					</label>	
				</div>
				<div class="wcst_option_group wcst_half">
					<h4 ><?php esc_html_e('Tracking data associated with products', 'woocommerce-shipping-tracking');?></h4>
					<p><?php esc_html_e('Disable the display of the tracking data associated with a product in the Order details page -> Product table.', 'woocommerce-shipping-tracking'); ?></p>
					<label class="wcst-switch">
					  <input type="checkbox" class="wcst_toggle" name="wcst_general_options[hide_tracking_data_associated_to_product]" value="true" <?php checked($hide_tracking_data_associated_to_product, "true"); ?>>
					  <span class="wcst-slider"></span>
					</label>	
				</div>
				
				<h2 class="wcst_section_title"><?php esc_html_e('Admin order page', 'woocommerce-shipping-tracking');?></h2>
				
				<div class="wcst_option_group wcst_full">	
					<h4 ><?php esc_html_e('Automatic dispatch date field filling', 'woocommerce-shipping-tracking');?></h4>
					<p><?php esc_html_e('Automatically fill dispatch date field with today date', 'woocommerce-shipping-tracking'); ?></p>
					<select name="wcst_general_options[dispatch_date_automatic_fill_with_today_date]">
						<option value="no" <?php if($dispatch_date_automatic_fill_with_today_date == "no") echo 'selected="selected"';?>><?php esc_html_e('No', 'woocommerce-shipping-tracking');?></option>
						<option value="yes" <?php if($dispatch_date_automatic_fill_with_today_date == "yes") echo 'selected="selected"';?>><?php esc_html_e('Yes', 'woocommerce-shipping-tracking');?></option>
					</select>
				</div>
				<div class="wcst_option_group wcst_one_third">
					<h4 ><?php esc_html_e('Hide "Associated products" field', 'woocommerce-shipping-tracking');?></h4>
					<p><?php esc_html_e('The field will not be displayed anymore on the order details page', 'woocommerce-shipping-tracking');?></p>
					<?php  $selected = wcst_get_value_if_set($options, array('hide','associated_products_field')) ? " checked='checked' " : " "; ?>
					<label class="wcst-switch">
					  <input type="checkbox" class="wcst_toggle" name="wcst_general_options[hide][associated_products_field]" value="true" <?php esc_html_e($selected); ?>>
					  <span class="wcst-slider"></span>
					</label>	
				</div>	
				<div class="wcst_option_group wcst_one_third">
					<h4 ><?php esc_html_e('Hide "Dispatch date" field', 'woocommerce-shipping-tracking');?></h4>
					<p><?php esc_html_e('The field will not be displayed anymore on the order details page', 'woocommerce-shipping-tracking');?></p>
					<?php  $selected = wcst_get_value_if_set($options, array('hide','dispatch_date')) ? " checked='checked' " : " "; ?>
					<label class="wcst-switch">
					  <input type="checkbox" class="wcst_toggle" name="wcst_general_options[hide][dispatch_date]" value="true" <?php esc_html_e($selected); ?>>
					  <span class="wcst-slider"></span>
					</label>	
				</div>	
				<div class="wcst_option_group wcst_one_third">
					<h4 ><?php esc_html_e('Hide "Custom text" field', 'woocommerce-shipping-tracking');?></h4>
					<p><?php esc_html_e('The field will not be displayed anymore on the order details page', 'woocommerce-shipping-tracking');?></p>
					<?php  $selected = wcst_get_value_if_set($options, array('hide','custom_text')) ? " checked='checked' " : " "; ?>
					<label class="wcst-switch">
					  <input type="checkbox" class="wcst_toggle" name="wcst_general_options[hide][custom_text]" value="true" <?php esc_html_e($selected); ?>>
					  <span class="wcst-slider"></span>
					</label>	
				</div>	
				<div class="wcst_option_group wcst_one_third">
					<h4 ><?php esc_html_e('Hide "Send notification" field', 'woocommerce-shipping-tracking');?></h4>
					<p><?php esc_html_e('The field will not be displayed anymore on the order details page', 'woocommerce-shipping-tracking');?></p>
					<?php  $selected = wcst_get_value_if_set($options, array('hide','send_notification'))? " checked='checked' " : " "; ?>
					<label class="wcst-switch">
					  <input type="checkbox" class="wcst_toggle" name="wcst_general_options[hide][send_notification]" value="true" <?php esc_html_e($selected); ?>>
					  <span class="wcst-slider"></span>
					</label>	
				</div>	
				<div class="wcst_option_group wcst_one_third">
					<h4 ><?php esc_html_e('Hide "Disable email embedding" field', 'woocommerce-shipping-tracking');?></h4>
					<p><?php esc_html_e('The field will not be displayed anymore on the order details page', 'woocommerce-shipping-tracking');?></p>
					<?php  $selected = wcst_get_value_if_set($options, array('hide','email_embedding')) ? " checked='checked' " : " "; ?>
					<label class="wcst-switch">
					  <input type="checkbox" class="wcst_toggle" name="wcst_general_options[hide][email_embedding]" value="true" <?php esc_html_e($selected); ?>>
					  <span class="wcst-slider"></span>
					</label>	
				</div>	
				<div class="wcst_option_group wcst_one_third">
					<h4 ><?php esc_html_e('Hide "Switch order status to completed" field', 'woocommerce-shipping-tracking');?></h4>
					<p><?php esc_html_e('The field will not be displayed anymore on the order details page', 'woocommerce-shipping-tracking');?></p>
					<?php  $selected = wcst_get_value_if_set($options, array('hide','switch_order_status_to_completed')) ? " checked='checked' " : " "; ?>
					<label class="wcst-switch">
					  <input type="checkbox" class="wcst_toggle" name="wcst_general_options[hide][switch_order_status_to_completed]" value="true" <?php esc_html_e($selected); ?>>
					  <span class="wcst-slider"></span>
					</label>	
				</div>	
				<div class="wcst_option_group wcst_half">
					<h4 ><?php esc_html_e('Set "Send notification" option enabled by default', 'woocommerce-shipping-tracking');?></h4>
					<p><?php wcst_html_escape_allowing_special_tags(__('The field will be set to "enabled" by default.<br><strong>Note:</strong> even if you manually disable the field, once you reload the page it will be once again set as "enabled".', 'woocommerce-shipping-tracking'));?></p> 
					<?php  $selected = wcst_get_value_if_set($options, array('default','send_notification')) ? " checked='checked' " : " "; ?>
					<label class="wcst-switch">
					  <input type="checkbox" class="wcst_toggle" name="wcst_general_options[default][send_notification]" value="true" <?php esc_html_e($selected); ?>>
					  <span class="wcst-slider"></span>
					</label>	
				</div>	
				<div class="wcst_option_group wcst_half">
					<h4 ><?php esc_html_e('Set "Switch order status to completed" option enabled by default', 'woocommerce-shipping-tracking');?></h4>
					<p><?php esc_html_e('The field will be set to "enabled" by default', 'woocommerce-shipping-tracking');?></p>
					<?php  $selected = wcst_get_value_if_set($options, array('default','switch_order_status_to_completed')) ? " checked='checked' " : " "; ?>
					<label class="wcst-switch">
					  <input type="checkbox" class="wcst_toggle" name="wcst_general_options[default][switch_order_status_to_completed]" value="true" <?php esc_html_e($selected); ?>>
					  <span class="wcst-slider"></span>
					</label>	
				</div>	
				
				<h2 class="wcst_section_title"><?php esc_html_e('Style options', 'woocommerce-shipping-tracking');?></h2>
				<div class="wcst_option_group wcst_one_third">	
					<h4><?php esc_html_e('Date format', 'woocommerce-shipping-tracking');?></h4>
					<p><?php esc_html_e('Select the date format used for dispatch and delivery dates', 'woocommerce-shipping-tracking'); ?></p>
					<select name="wcst_general_options[date_format]">
						<option value="dd/mm/yyyy" <?php if($date_format == "dd/mm/yyyy") echo 'selected="selected"';?>><?php esc_html_e('dd/mm/yyyy', 'woocommerce-shipping-tracking');?></option>
						<option value="mm/dd/yyyy" <?php if($date_format == "mm/dd/yyyy") echo 'selected="selected"';?>><?php esc_html_e('mm/dd/yyyy', 'woocommerce-shipping-tracking');?></option>
						<option value="yyyy/mm/dd" <?php if($date_format == "yyyy/mm/dd") echo 'selected="selected"';?>><?php esc_html_e('yyyy/mm/dd', 'woocommerce-shipping-tracking');?></option>
						<option value="dd.mm.yyyy" <?php if($date_format == "dd.mm.yyyy") echo 'selected="selected"';?>><?php esc_html_e('dd.mm.yyyy', 'woocommerce-shipping-tracking');?></option>
						<option value="mm.dd.yyyy" <?php if($date_format == "mm.dd.yyyy") echo 'selected="selected"';?>><?php esc_html_e('mm.dd.yyyy', 'woocommerce-shipping-tracking');?></option>
						<option value="yyyy.mm.dd" <?php if($date_format == "yyyy.mm.dd") echo 'selected="selected"';?>><?php esc_html_e('yyyy.mm.dd', 'woocommerce-shipping-tracking');?></option>
						<option value="dd-mm-yyyy" <?php if($date_format == "dd-mm-yyyy") echo 'selected="selected"';?>><?php esc_html_e('dd-mm-yyyy', 'woocommerce-shipping-tracking');?></option>
						<option value="mm-dd-yyyy" <?php if($date_format == "mm-dd-yyyy") echo 'selected="selected"';?>><?php esc_html_e('mm-dd-yyyy', 'woocommerce-shipping-tracking');?></option>
						<option value="yyyy-mm-dd" <?php if($date_format == "yyyy-mm-dd") echo 'selected="selected"';?>><?php esc_html_e('yyyy-mm-dd', 'woocommerce-shipping-tracking');?></option>
						<option value="mmmm dd, yyyy" <?php if($date_format == "mmmm dd, yyyy") echo 'selected="selected"';?>><?php esc_html_e('F j, Y', 'woocommerce-shipping-tracking');?></option>
						<option value="mmm dd" <?php if($date_format == "mmm dd") echo 'selected="selected"';?>><?php esc_html_e('M j', 'woocommerce-shipping-tracking');?></option>
						<option value="dd mmm" <?php if($date_format == "dd mmm") echo 'selected="selected"';?>><?php esc_html_e('j M', 'woocommerce-shipping-tracking');?></option>
						<option value="dddd, dd.mm" <?php if($date_format == "dddd, dd.mm") echo 'selected="selected"';?>><?php esc_html_e('l, d.m', 'woocommerce-shipping-tracking');?></option>
					</select>
				</div>
				
				<div class="wcst_option_group wcst_one_third">	
					<h4 ><?php esc_html_e('Tracking form redirection', 'woocommerce-shipping-tracking');?></h4>
					<p><?php esc_html_e('Select redirection method. Using the "Open new tab" method could cause the browse to detect the new tab as a popup.', 'woocommerce-shipping-tracking'); ?></p>
					<select name="wcst_general_options[tracking_form_redirect_method]">
						<option value="same_page" <?php if($redirect_method == "same_page") echo 'selected="selected"';?>><?php esc_html_e('Same page', 'woocommerce-shipping-tracking');?></option>
						<option value="new_tab" <?php if($redirect_method == "new_tab") echo 'selected="selected"';?>><?php esc_html_e('Open a new tab', 'woocommerce-shipping-tracking');?></option>
					</select>
				</div>
				<div class="wcst_option_group wcst_one_third">	
					<h4 ><?php esc_html_e('Tracking info positioning', 'woocommerce-shipping-tracking');?></h4>
					<p><?php esc_html_e('Order details page: select where to display tracking info', 'woocommerce-shipping-tracking'); ?></p>
					<select name="wcst_general_options[order_details_page_positioning]">
						<option value="woocommerce_order_details_after_order_table" <?php if($order_details_page_positioning == "woocommerce_order_details_after_order_table") echo 'selected="selected"';?>><?php esc_html_e('After order table', 'woocommerce-shipping-tracking');?></option>
						<option value="woocommerce_view_order" <?php if($order_details_page_positioning == "woocommerce_view_order") echo 'selected="selected"';?>><?php esc_html_e('Before order table', 'woocommerce-shipping-tracking');?></option>
					</select>
				</div>
				<h2 class="wcst_section_title"><?php esc_html_e('Estimated shipping date display', 'woocommerce-shipping-tracking');?></h2>
				
				<div class="wcst_option_group wcst_half">
					<h4 ><?php esc_html_e('Estimated shipping for products', 'woocommerce-shipping-tracking');?></h4>
					<p><?php esc_html_e('Report estimated info on order details page and emails', 'woocommerce-shipping-tracking'); ?></p>
					<?php  $selected = $estimated_shipping_report_info_on_order_details ? " checked='checked' " : " "; ?>
					<label class="wcst-switch">
					  <input type="checkbox" class="wcst_toggle" name="wcst_general_options[estimated_shipping_report_info_on_order_details]" value="true" <?php esc_html_e($selected); ?>>
					  <span class="wcst-slider"></span>
					</label>	
				</div>	
				
				<div class="wcst_option_group wcst_half">				
					<h4 ><?php esc_html_e('Product Page - automatic estimated shipping info positioning', 'woocommerce-shipping-tracking');?></h4>
					<p><?php esc_html_e('By default estimated shipping info on product page is displayed using the [wcst_show_estimated_date] shortcode. However using the following option you can display that info automatically at specific positions:', 'woocommerce-shipping-tracking'); ?></p>
					<select name="wcst_general_options[estimated_shipping_info_product_page_positioning]">
						<option value="none" <?php if($estimated_shipping_info_product_page_positioning == "none") echo 'selected="selected"';?>><?php esc_html_e('Do not automatically show', 'woocommerce-shipping-tracking');?></option>
						<option value="woocommerce_before_add_to_cart_button" <?php if($estimated_shipping_info_product_page_positioning == "woocommerce_before_add_to_cart_button") echo 'selected="selected"';?>><?php esc_html_e('After variable options dropdown(s) and before add to cart button', 'woocommerce-shipping-tracking');?></option>
						<option value="woocommerce_before_add_to_cart_form" <?php if($estimated_shipping_info_product_page_positioning == "woocommerce_before_add_to_cart_form") echo 'selected="selected"';?>><?php esc_html_e('Before both variable options dropdown(s)	and add to cart button', 'woocommerce-shipping-tracking');?></option>
						<option value="woocommerce_after_add_to_cart_button" <?php if($estimated_shipping_info_product_page_positioning == "woocommerce_after_add_to_cart_button") echo 'selected="selected"';?>><?php esc_html_e('After add to cart button', 'woocommerce-shipping-tracking');?></option>
						<option value="woocommerce_product_thumbnails" <?php if($estimated_shipping_info_product_page_positioning == "woocommerce_product_thumbnails") echo 'selected="selected"';?>><?php esc_html_e('After product images', 'woocommerce-shipping-tracking');?></option>
						<option value="woocommerce_before_single_product_summary" <?php if($estimated_shipping_info_product_page_positioning == "woocommerce_before_single_product_summary") echo 'selected="selected"';?>><?php esc_html_e('Before product Images', 'woocommerce-shipping-tracking');?></option>
						<option value="woocommerce_single_product_summary" <?php if($estimated_shipping_info_product_page_positioning == "woocommerce_single_product_summary") echo 'selected="selected"';?>><?php esc_html_e('Before short description', 'woocommerce-shipping-tracking');?></option>
						<option value="woocommerce_after_single_product_summary" <?php if($estimated_shipping_info_product_page_positioning == "woocommerce_after_single_product_summary") echo 'selected="selected"';?>><?php esc_html_e('After product description', 'woocommerce-shipping-tracking');?></option>
					</select>
				</div>
				
				<div class="wcst_option_group wcst_half">
					<h4 ><?php esc_html_e('Cart/Checkout Pages - automatic estimated shipping display', 'woocommerce-shipping-tracking');?></h4>
					<p><?php esc_html_e('Would you like to display estimated shipping info under each product on product table displayed in Cart/Checkout page:', 'woocommerce-shipping-tracking'); ?></p>
					<select name="wcst_general_options[estimated_shipping_info_cart_checkout_pages_automaic_display]">
						<option value="no" <?php if($estimated_shipping_info_cart_checkout_pages_automaic_display == "no") echo 'selected="selected"';?>><?php esc_html_e('No', 'woocommerce-shipping-tracking');?></option>
						<option value="yes" <?php if($estimated_shipping_info_cart_checkout_pages_automaic_display == "yes") echo 'selected="selected"';?>><?php esc_html_e('Yes', 'woocommerce-shipping-tracking');?></option>
					</select>
				</div>
				
				<div class="wcst_option_group wcst_half">
					<h4 ><?php esc_html_e('Show a warning text for item no longer in stock ', 'woocommerce-shipping-tracking'); ?></h4>
					<p><?php wcst_html_escape_allowing_special_tags(__("By default, if a item is out of stock or in back order, no estimation date will be displayed. Enable this option to display a warning text. To customize it, use the <i>Text displayed when the product is out of stock</i> setting in the next section. Note: if the text is autonatically showed according to the previous option, the warning text won't be displayed for the first 4 options due to a WooCommerce limitation.", 'woocommerce-shipping-tracking')); ?></p>
					<select name="wcst_general_options[estimated_shipping_info_product_page_show_text_for_out_of_stock]">
						<option value="yes" <?php if($estimated_shipping_info_product_page_show_text_for_out_of_stock == "yes") echo 'selected="selected"';?>><?php esc_html_e('Yes', 'woocommerce-shipping-tracking');?></option>
						<option value="no" <?php if($estimated_shipping_info_product_page_show_text_for_out_of_stock == "no") echo 'selected="selected"';?>><?php esc_html_e('No', 'woocommerce-shipping-tracking');?></option>
					</select>
				</div>
				
				<h2  class="wcst_section_title"><?php esc_html_e('Texts', 'woocommerce-shipping-tracking');?></h2>
				<div class="wcst_option_group wcst_half">
					<h4 ><?php esc_html_e('Estimation date', 'woocommerce-shipping-tracking');?></h4>
					<p class="" ><?php esc_html_e('Label to display before the date:', 'woocommerce-shipping-tracking'); ?></p>
					<p>
					<?php esc_html_e('This label is used on Product, Cart and Checkout pages to display product estimated shipping date. ', 'woocommerce-shipping-tracking'); ?>
					</p>
					<?php if ($wpml_helper->is_wpml_active()): ?>
					<p>
					<?php wcst_html_escape_allowing_special_tags(__('<strong>WPML NOTE:</strong> to localize this label, simply switch language using WPML language selector and then save the options.', 'woocommerce-shipping-tracking')); ?>
					</p>
					<?php endif; ?>
					<input class="wcst_option_text_field" type="text" name="wcst_general_options[estimated_shipping_info_product_page_label][<?php echo $wpml_helper->get_current_locale();?>]" placeholder="<?php esc_html_e('Estimated shipping date:', 'woocommerce-shipping-tracking'); ?>" value="<?php echo $estimated_shipping_info_product_page_label;?>" required="required"></input>
					
					<label class="wcst_option_less_margin_label" ><?php esc_html_e('Text displayed when the product is out of stock:', 'woocommerce-shipping-tracking'); ?></label>
					<input class="wcst_option_text_field" type="text" name="wcst_general_options[estimated_shipping_info_out_of_stock][<?php echo $wpml_helper->get_current_locale();?>]" placeholder="<?php esc_html_e('Out of stock, date unavailable', 'woocommerce-shipping-tracking'); ?>" value="<?php echo $estimated_shipping_info_out_of_stock;?>" required="required"></input>
				</div>
				
				<div class="wcst_option_group wcst_half">
					<h4 ><?php esc_html_e('Track shipment button', 'woocommerce-shipping-tracking');?></h4>
					<p>
					<?php esc_html_e('This label is displayed inside the Track shipment buttons displayed inside the orders list (My Account -> Order). In case of multiple shipment, use the placeholder %s to identify the shipment progressive id.', 'woocommerce-shipping-tracking'); ?>
					</p>
					<?php if ($wpml_helper->is_wpml_active()): ?>
					<p>
					<?php wcst_html_escape_allowing_special_tags(__('<strong>WPML NOTE:</strong> to localize this label, simply switch language using WPML language selector and then save the options.', 'woocommerce-shipping-tracking')); ?>
					</p>
					<?php endif; ?>
					<input class="wcst_option_text_field" type="text" name="wcst_general_options[tracking_shipment_button][<?php echo $wpml_helper->get_current_locale();?>]" placeholder="<?php esc_html_e('Track shipment #%s', 'woocommerce-shipping-tracking'); ?>" value="<?php echo $tracking_shipment_button;?>" required="required"></input>
				</div>

				<div class="wcst_option_group wcst_half">
					<h4 ><?php esc_html_e('Email', 'woocommerce-shipping-tracking');?></h4>
					<label class="" ><?php esc_html_e('Active notification - Email subject', 'woocommerce-shipping-tracking'); ?></label>
					<p>
					<?php wcst_html_escape_allowing_special_tags(__('This is the subject used for active notification emails (the ones sent by checking the "Send a notification email" in the order details page). Permitted shortcode: <strong>[order_id]</strong>. ', 'woocommerce-shipping-tracking')); ?>
					</p>
					<input class="wcst_option_text_field" type="text" name="wcst_general_options[active_notification_email_subject][<?php echo $wpml_helper->get_current_locale();?>]" placeholder="<?php esc_html_e('Your products have been shipped', 'woocommerce-shipping-tracking'); ?>" value="<?php echo $active_notification_email_subject;?>" required="required"></input>
					
					<label class="wcst_option_less_margin_label" ><?php esc_html_e('Active notification - Email heading', 'woocommerce-shipping-tracking'); ?></label>
					<p>
					<?php wcst_html_escape_allowing_special_tags(__('This is the heading used for active notification emails (the ones sent by checking the "Send a notification email" in the order details page). Permitted shortcode: <strong>[order_id]</strong>. ', 'woocommerce-shipping-tracking')); ?>
					</p>
					<input class="wcst_option_text_field" type="text" name="wcst_general_options[active_notification_email_heading][<?php echo $wpml_helper->get_current_locale();?>]" placeholder="<?php echo get_bloginfo('name'); ?>" value="<?php echo $active_notification_email_heading;?>" required="required"></input>
					
					<label class="wcst_option_less_margin_label" ><?php esc_html_e('Active notification - "From" name', 'woocommerce-shipping-tracking'); ?></label>
					<p>
					<?php wcst_html_escape_allowing_special_tags(__('This is the From name used for the active notification emails. Leave empty to use the default value (your blog name)', 'woocommerce-shipping-tracking')); ?>
					</p>
					<input class="wcst_option_text_field" type="text" name="wcst_general_options[active_notification_from_name][<?php echo $wpml_helper->get_current_locale();?>]" placeholder="<?php echo get_bloginfo('name'); ?>" value="<?php echo $active_notification_from_name;?>" ></input>
					
					<label class="wcst_option_less_margin_label" ><?php esc_html_e('Active notification - "From" email address', 'woocommerce-shipping-tracking'); ?></label>
					<p>
					<?php wcst_html_escape_allowing_special_tags(__("This is the from email address used for the active notification emails. Leave empty to use the default value (noreply@yourdomain.com). <br><br>NOTE: some installation doesn't allow to modify the from email address for security reasons, so this option might be ineffective.", 'woocommerce-shipping-tracking')); ?>
					</p>
					<input class="wcst_option_text_field" type="text" name="wcst_general_options[active_notification_from_email_address][<?php echo $wpml_helper->get_current_locale();?>]" placeholder="" value="<?php echo $active_notification_from_email_address;?>" ></input>
				</div>	
				
				<h2 class="wcst_section_title wcst_small_margin_top"><?php esc_html_e('Track shipping status in site', 'woocommerce-shipping-tracking');?></h2>
				<div class="wcst_option_group wcst_full">
					<h4 ><?php esc_html_e('AfterShip API Key', 'woocommerce-shipping-tracking');?></h4>
					<p><strong><?php wcst_html_escape_allowing_special_tags(__('Generate a valid API Key following the <a href="https://support.aftership.com/en/article/generate-aftership-api-key-12cuqj2/" target="_blank">instruction</a>. Once done copy it in the following text area to be able to track order via AfterShip service.', 'woocommerce-shipping-tracking')); ?></strong></p>
					<!-- <p><?php wcst_html_escape_allowing_special_tags(__('The service will automatically detect the carrier from the tracking code (if supported) without the requirement of any further code.', 'woocommerce-shipping-tracking'));?></p> -->
					<input type="text" name="wcst_general_options[aftership_api_key]" class="wcst_option_text_field" placeholder="<?php esc_html_e('AfterShip API Key', 'woocommerce-shipping-tracking'); ?>" value="<?php echo $aftership_api_key; ?>"></input>
				</div>
				<!-- <div class="wcst_option_group wcst_full">				
					<h4 ><?php esc_html_e('Company detection', 'woocommerce-shipping-tracking');?></h4>
					<p><?php esc_html_e('The plugin will automatically detect the shipping company from the tracking code. This however may generate extra queries that may be exceding the AfterShip tracking codes quota plan you are actually using. To help the plugin detecting the company and save time and quota, please select the companies you are actually using. If none is seleceted, the plugin will try detecting using all of them:', 'woocommerce-shipping-tracking'); ?></p>
					<?php 
						$aftership_companies_list = $wcst_shipping_company_model->get_aftership_couriers_list_from_csv();
						foreach($aftership_companies_list as $aftership_company_data): 
							$selected = isset($aftership_api_preselected_companies[$aftership_company_data[0]]) ? " checked='checked' " : " "; ?>
							<div class="wcst_after_shipt_checkbox_container">
								<input type="checkbox" name="wcst_general_options[aftership_api_preselected_companies][<?php echo $aftership_company_data[0]; ?>]" <?php echo $selected; ?> class="wcst_option_checbox_field" value="<?php echo $aftership_company_data[0]; ?>"><?php echo $aftership_company_data[1]; ?></input>
							</div>
						<?php endforeach;
					?>				
				</div> -->
				<p class="submit">
					<input  name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes', 'woocommerce-shipping-tracking'); ?>" />
				</p>
			</fom>
		</div>
		<?php
	}
}
?>