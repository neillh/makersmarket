<?php 
class WCST_HtmlHelper
{
	public function __construct()
	{
		add_action('wp_ajax_wcst_get_order_items_selector', array( &$this,'ajax_wcst_get_order_items_selector'), 10);
	}
	function ajax_wcst_get_order_items_selector()
	{
		$order_id = filter_input( INPUT_POST, 'order_id', FILTER_VALIDATE_INT );
		$wc_order = wc_get_order($order_id);
		if($wc_order && wp_verify_nonce( wcst_get_value_if_set($_POST, 'security', ""), 'wcst_get_order_selector' ))
		{
			$this->render_order_items_selector($wc_order);
		}
		wp_die();
	}
	function generic_shipping_comanies_dropdown_options($selected = "")
	{
		$option_model = new WCST_Option();
		$options = $option_model->get_option();
		$shipping_companies = WCST_AdminMenu::get_shipping_companies_list();
		$custom_companies = get_option( 'wcst_user_defined_companies');
		foreach( $shipping_companies as $k => $v )
		{
			if (isset($options[$k]) == '1') 
			{
				echo '<option value="'.$k.'" ';
				if ( $selected === $k) {
					echo 'selected="selected"';
				}
				echo '>'.$v.'</option>';  
			}
			
		}
		//Custom companies
		if(isset($custom_companies) && is_array($custom_companies))
			foreach( $custom_companies as $index => $custom_company )
			{
				if (isset($options[$index]) == '1') 
				{
					echo '<option value="'.$index.'" ';
					if ( $selected === $index.'')
					{
						echo 'selected="selected"';
					}
					echo '>'.$custom_company['name'].'</option>';  
				}
			}
	}
	function shipping_dropdown_options($data, $options, $already_shifted = false, $part = '')
		{ 
		 
			if ($part == '0' || $part == '' ) {
				$part = '';
			}
			
			$no_company_selected = 0;
			foreach($data as $key => $value)
				if(strpos('_wcst_order_trackurl', $key) !== false)
					$no_company_selected++;
			$no_company_selected = $no_company_selected > 0 ? false:true;
			
			if(!$already_shifted)
			{
				if(isset($data['_wcst_order_trackurl'.$part][0]))
					$data['_wcst_order_trackurl'.$part] = $data['_wcst_order_trackurl'.$part][0];
				else
					$data['_wcst_order_trackurl'.$part] = null;
			}
			$favorite = isset($options['favorite']) ? $options['favorite'] : "-1";
			$shipping_companies = WCST_AdminMenu::get_shipping_companies_list();
			$custom_companies = get_option( 'wcst_user_defined_companies');
			
			foreach( $shipping_companies as $k => $v )
			{
				if (isset($options[$k]) == '1') 
				{
					echo '<option value="'.$k.'" ';
					if ( ($no_company_selected && $favorite === $k) || (isset($data['_wcst_order_trackurl'.$part]) && $data['_wcst_order_trackurl'.$part] == $k)) {
						echo 'selected="selected"';
					}
					echo '>'.$v.'</option>';  
				}
				
			}
			//Custom companies
			if(isset($custom_companies) && is_array($custom_companies))
			{
				//Sorts the array by name
				uasort($custom_companies, function($a, $b)
				{
						return $a['name'] > $b['name'];
				});
				if(isset($shipping_companies) && is_array($shipping_companies))
					echo '<optgroup label="'.esc_html__('Custom companies', 'woocommerce-shipping-tracking').'">';
				foreach( $custom_companies as $index => $custom_company )
				{
					if (isset($options[$index]) == '1') 
					{
						echo '<option value="'.$index.'" ';
						if ( ($no_company_selected && $favorite===(string)$index) || (isset($data['_wcst_order_trackurl'.$part]) && $data['_wcst_order_trackurl'.$part] == $index.''))
						{
							echo 'selected="selected"';
						}
						echo '>'.$custom_company['name'].'</option>';  
					}
				}
				if(isset($shipping_companies) && is_array($shipping_companies))
					echo '</optgroup>';
			}
			
		}
		function render_order_items_selector($wc_order, $data = array())
		{
			global $wcst_product_model;
			?>
			<li>
				<label style="display:block; clear:both; font-weight:bold;"><?php esc_html_e('Associated products', 'woocommerce-shipping-tracking'); ?></label>
				<p><?php esc_html_e('Use this option only if you wish to give feedback to the user about which products the shipment refers', 'woocommerce-shipping-tracking'); ?></p>
				<select name="_wcst_associated_product[]" class="wcst_associated_product_select" multiple>
				<?php
					$associated_products = wcst_get_value_if_set($data, array('_wcst_associated_product'), array());
					foreach($wc_order->get_items() as $order_item):
						$specific_product_id = $order_item->get_variation_id() ? $order_item->get_variation_id() : $order_item->get_product_id();
						$attributes = $wcst_product_model->get_order_variation_attribute_value_and_name($order_item->get_product());
						?>
						<option value="<?php echo $order_item->get_id()?>" <?php selected(in_array($order_item->get_id(), $associated_products)); ?>><?php echo $order_item->get_name()." ".$attributes;?></option>
						<?php 
					endforeach;
				?>
				</select>	
			</li>
			<?php
		}
		function render_shipping_companies_tracking_info_configurator_widget($post = null) 
		{
			global $wcst_order_model, $wcst_time_model, $wcst_product_model;
			$wpml_helper = new WCST_Wpml();
			$lang_code = str_replace("_formal", "", $wpml_helper->get_current_locale());
			$lang_code = $lang_code."_".strtoupper($lang_code);
			$option_model = new WCST_Option();
			$general_options = $option_model->get_general_options();
			$date_format = isset($general_options['date_format']) ? $general_options['date_format'] : "dd/mm/yyyy";
			$admin_order_details_autofocus = isset($general_options['admin_order_details_autofocus']) ? $general_options['admin_order_details_autofocus'] : "no";
			$is_order_details_page = isset($post);
			$wc_order = $is_order_details_page ? wc_get_order($post->ID) : false;
			$data = $is_order_details_page ? $wcst_order_model->get_order_meta($post->ID) : array();
			
			//dispatch date managment
			$dispatch_date_automatic_fill_with_today_date = $option_model->get_general_options('dispatch_date_automatic_fill_with_today_date', 'no');
			if($dispatch_date_automatic_fill_with_today_date == 'yes' && !isset($data['_wcst_order_dispatch_date'][0]))
				$dispatch_date = current_time($option_model->get_sql_date_format_according_to_date_option());
			else 
			{
				$dispatch_date = isset($data['_wcst_order_dispatch_date'][0]) ? $data['_wcst_order_dispatch_date'][0] : "";
				$dispatch_date = $wcst_time_model->format_data($dispatch_date);
			}
			
			
			$is_email_embedding_disabled = isset($post) ? $wcst_order_model->is_email_tracking_info_embedding_disabled($post->ID) : false;
			$options = $option_model->get_option();
			$style1 = 'style="display: none"';
			$btn1 = '';
			$active_notification_description = esc_html__('Clicking on the "Update" button, the plugin will send a notification email containing the tracking codes for which this option has been checked.', 'woocommerce-shipping-tracking');;
			$track_without_code_description = esc_html__('Tracking info will be showed even if no tracking code has been entered. Use the Custom text textarea to give more details about the shipping.', 'woocommerce-shipping-tracking');;
			$urltrack = isset($data['_wcst_order_track_http_url']) ? $data['_wcst_order_track_http_url'][0] : '#' ;
			if( isset( $data['_wcst_order_trackno1'][0]) && $data['_wcst_order_trackno1'][0] != '' ){
				$style1 = '';
				$btn1 = 'style="display: none"';
			}
			$index_additional_companies = 0;
			if(isset($data['_wcst_additional_companies']))
			{
										//old wc versions
				$additiona_companies = is_string($data['_wcst_additional_companies'][0]) ? unserialize(array_shift($data['_wcst_additional_companies'])) : $data['_wcst_additional_companies'];
				
			}
			
			wp_enqueue_style('van-datepicker-default', WCST_PLUGIN_PATH.'/css/datepicker/default.css');   
			wp_enqueue_style('van-datepicker-date-default', WCST_PLUGIN_PATH.'/css/datepicker/default.date.css');   
			wp_enqueue_style('van-datepicker-time-default', WCST_PLUGIN_PATH.'/css/datepicker/default.time.css');  
			wp_enqueue_style('wcst-shipping-companies-info-widget',  WCST_PLUGIN_PATH.'/css/wcst_shipping_companies_tracking_info_configurator_widget.css');
			
			wp_enqueue_script('van-picker', WCST_PLUGIN_PATH.'/js/datepicker/picker.js', array( 'jquery' ));
			wp_enqueue_script('van-datepicker', WCST_PLUGIN_PATH.'/js/datepicker/picker.date.js', array( 'jquery' ));
			wp_enqueue_script('van-timepicker', WCST_PLUGIN_PATH.'/js/datepicker/picker.time.js', array( 'jquery' ));
			if(wcst_file_exists(WCST_PLUGIN_ABS_PATH.'js/datepicker/translations/'.$lang_code.'.js'))
				wp_enqueue_script('van-datepicker-localization', WCST_PLUGIN_PATH.'/js/datepicker/translations/'.$lang_code.'.js');	
			
			
			wp_register_script('wcst-order-details', WCST_PLUGIN_PATH.'/js/wcst-order-details.js',	array( 'jquery' ));
			$js_options = array(
					'autofocus' => $admin_order_details_autofocus,
					'date_format' => $date_format
				);
			wp_localize_script( 'wcst-order-details', 'wcst_options', $js_options );
			wp_enqueue_script( 'wcst-order-details' );
			
			?>
			<p>
			<?php esc_html_e('Add, edit or remove the tracking info. Once done click on the "Save Order" button to update order tracking info.', 'woocommerce-shipping-tracking'); ?>
			</p>
			<div class="wcst_shipping_info_box">
				<ul class="totals">
					<li>
						<label  style="display:block; clear:both; font-weight:bold;"><?php esc_html_e('Shipping Company:', 'woocommerce-shipping-tracking'); ?></label>
						<select style="margin-bottom:15px;" id="_wcst_order_trackurl" name="_wcst_order_trackurl" >
							<option value="NOTRACK" <?php if ( isset($data['_wcst_order_trackurl'][0]) && $data['_wcst_order_trackurl'][0] == 'NOTRACK') {
								echo 'selected="selected"';
							} ?>><?php esc_html_e('No Tracking', 'woocommerce-shipping-tracking'); ?></option>
							<?php $this->shipping_dropdown_options( $data, $options ); ?>
						</select>
					</li>
					<li>
						<label style="display:block; clear:both; font-weight:bold;"><?php esc_html_e('Tracking Number:', 'woocommerce-shipping-tracking'); ?></label>
						<p>	<?php esc_html_e('In case the tracking URL requires multiple codes, insert them by separating using the "," character. Example: "code1,code2,code2"', 'woocommerce-shipping-tracking');?></p>
						<input style="margin-bottom:15px;" type="text" id="_wcst_order_trackno" name="_wcst_order_trackno" placeholder="<?php esc_html_e('Enter Tracking No', 'woocommerce-shipping-tracking'); ?>" value="<?php if (isset($data['_wcst_order_trackno'][0])) echo $data['_wcst_order_trackno'][0]; ?>" class="wcst_tracking_code_input" />
					</li>
					<?php if($wc_order && !wcst_get_value_if_set($general_options, array('hide','associated_products_field'), false)): 
					
						$this->render_order_items_selector($wc_order, $data); 
					
					else: ?>
					<div class="wcst_order_items_container"></div>
					<?php endif; ?>
					<?php if(!$wc_order || !wcst_get_value_if_set($general_options, array('hide','dispatch_date'), false)): ?>
					<li>
						<label  style="display:block; clear:both; font-weight:bold;"><?php esc_html_e('Dispatch date', 'woocommerce-shipping-tracking'); ?></label>
						<input style="margin-bottom:15px;" type="text" class="wcst_dispatch_date" id="_wcst_order_dispatch_date" name="_wcst_order_dispatch_date" placeholder="<?php echo(sprintf( esc_html__('%s or %s', 'woocommerce-shipping-tracking'), date('d/m/y'), date('jS F Y'))); ?>" value="<?php echo $dispatch_date; ?>"  />
					</li>
					<?php endif; 
					if(!$wc_order || !wcst_get_value_if_set($general_options, array('hide','custom_text'), false)): ?>
					<li>
						<label style="display:block; clear:both; font-weight:bold;"><?php esc_html_e('Custom text', 'woocommerce-shipping-tracking'); ?></label>
						<textarea style="margin-bottom:15px;" type="text"  name="_wcst_custom_text" placeholder="<?php esc_attr_e('Info about the shipped item(s) or whatever you want', 'woocommerce-shipping-tracking'); ?>" rows="4"><?php if (isset($data['_wcst_custom_text'][0])) echo $data['_wcst_custom_text'][0]; ?></textarea>
					</li>
					<?php endif; 
					if(!$wc_order || !wcst_get_value_if_set($general_options, array('hide','send_notification'), false)): ?>
					<li>
						<input class="wcst_send_shipping_notification_email_checkbox" <?php checked(wcst_get_value_if_set($general_options, array('default','send_notification'), false) && $is_order_details_page) ?> id="wcst_send_shipping_notification_email_default" type="checkbox" value="true" data-id="default" name="wcst_send_shipping_notification_email[default]"><?php esc_attr_e('Send a notification email', 'woocommerce-shipping-tracking'); ?></input>
						<span class="wcst_description"><?php echo $active_notification_description; ?></span>
					</li>	
					<?php endif;  ?>
					<li>
						<a target="_blank" class="button" href="<?php echo $urltrack;?>"><?php esc_html_e('Tracking link', 'woocommerce-shipping-tracking'); ?></a>
					</li>					
					
				</ul>
			</div>
			<h4 id="wcst_additional_tracking_boxes_title"><?php esc_html_e('Additional tracking codes', 'woocommerce-shipping-tracking'); ?></h4>
			<div id="wcst-additional-shippings">
				<?php if(isset($additiona_companies))
						foreach($additiona_companies as $company):
					
					$dispatch_date = isset($company['_wcst_order_dispatch_date']) ? $company['_wcst_order_dispatch_date'] : "";
					$dispatch_date = $wcst_time_model->format_data($dispatch_date);
					$urltrack = isset($company['_wcst_order_track_http_url']) ? $company['_wcst_order_track_http_url'] : "#";
					?>
					<div id="wcst-additiona-shipping-box-<?php echo $index_additional_companies?>" class="wcst_shipping_info_box">
						<ul class="totals">
							<li>
								<label style="display:block; clear:both;"><?php esc_html_e('Shipping Company:', 'woocommerce-shipping-tracking'); ?></label>								
								<select style="margin-bottom:15px;" name="_wcst_order_additional_shipping[<?php echo $index_additional_companies?>][trackurl]"  >
									<option value="NOTRACK" <?php if ( isset($company['_wcst_order_trackurl']) && $company['_wcst_order_trackurl'] == 'NOTRACK') {
										echo 'selected="selected"';
									} ?>><?php esc_html_e('No Tracking', 'woocommerce-shipping-tracking'); ?></option>
									<?php $this->shipping_dropdown_options( $company, $options, true ); ?>
								</select>
							</li>
							<li>
								<label style="display:block; clear:both;"><?php esc_html_e('Tracking Number:', 'woocommerce-shipping-tracking'); ?></label>
								<input style="margin-bottom:15px;"type="text" id="wcst_tracking_code_input_<?php echo $index_additional_companies?>" name="_wcst_order_additional_shipping[<?php echo $index_additional_companies?>][trackno]" placeholder="<?php esc_html_e('Enter Tracking No', 'woocommerce-shipping-tracking'); ?>" value="<?php if (isset($company['_wcst_order_trackno'])) echo $company['_wcst_order_trackno']; ?>" class="wcst_tracking_code_input" />
							</li>
							<?php if($wc_order && !wcst_get_value_if_set($general_options, array('hide','associated_products_field'), false)): ?> 
							<li>
								<label style="display:block; clear:both; font-weight:bold;"><?php esc_html_e('Associated products', 'woocommerce-shipping-tracking'); ?></label>
								<p><?php esc_html_e('Use this option only if you wish to give feedback to the user about which products the shipment refers', 'woocommerce-shipping-tracking'); ?></p>
								<select name="_wcst_order_additional_shipping[<?php echo $index_additional_companies?>][associated_product][]" class="wcst_associated_product_select" multiple>
								<?php 
									$associated_products = wcst_get_value_if_set($company, array('_wcst_associated_product'), array());
									foreach($wc_order->get_items() as $order_item):
										$specific_product_id = $order_item->get_variation_id() ? $order_item->get_variation_id() : $order_item->get_product_id();
										$attributes = $wcst_product_model->get_order_variation_attribute_value_and_name($order_item->get_product());
										?>
										<option value="<?php echo $order_item->get_id()?>" <?php selected(in_array($order_item->get_id(), $associated_products)); ?>><?php echo $order_item->get_name()." ".$attributes;?></option>
										<?php 
									endforeach;
								?>
								</select>	
							</li>
							<?php endif; 
							if(!$wc_order || !wcst_get_value_if_set($general_options, array('hide','dispatch_date'), false)): ?>
							<li>
								<label style="display:block; clear:both;"><?php esc_html_e('Dispatch date', 'woocommerce-shipping-tracking'); ?></label>
								<input style="margin-bottom:15px;" type="text" class="wcst_dispatch_date" name="_wcst_order_additional_shipping[<?php echo $index_additional_companies?>][order_dispatch_date]" placeholder="<?php esc_attr_e('19/02/16 or 15th Dec 2016', 'woocommerce-shipping-tracking'); ?>" value="<?php echo $dispatch_date; ?>"  />
							</li>
							<?php endif; 
							if(!$wc_order || !wcst_get_value_if_set($general_options, array('hide','custom_text'), false)): ?>
							<li>
								<label style="display:block; clear:both;"><?php esc_html_e('Custom text', 'woocommerce-shipping-tracking'); ?></label>
								<textarea style="margin-bottom:15px;" type="text" class="wcst_custom_text" name="_wcst_order_additional_shipping[<?php echo $index_additional_companies?>][custom_text]" placeholder="<?php esc_attr_e('Info about the shipped item(s) or whatever you want', 'woocommerce-shipping-tracking'); ?>" rows="4"><?php if (isset($company['_wcst_custom_text'])) echo $company['_wcst_custom_text']; ?></textarea>
							</li>
							<?php endif; 
							if(!$wc_order || !wcst_get_value_if_set($general_options, array('hide','send_notification'), false)): ?>
							<li>
								<input class="" id="wcst_send_shipping_notification_email_<?php echo $index_additional_companies?>" type="checkbox" value="true" data-id="<?php echo $index_additional_companies?>" <?php checked(wcst_get_value_if_set($general_options, array('default','send_notification'), false) && $is_order_details_page) ?> name="wcst_send_shipping_notification_email[<?php echo $index_additional_companies?>]"><?php esc_html_e('Send a notification email', 'woocommerce-shipping-tracking'); ?></input>
								<span class="wcst_description"><?php echo $active_notification_description; ?></span>
							</li>	
							<?php endif; ?>
							<li>
								<a target="_blank" class="button" href="<?php echo $urltrack;?>"><?php esc_html_e('Tracking link', 'woocommerce-shipping-tracking'); ?></a>
							</li>
						</ul>
						<button class="button wcst-remove-shipping" data-id="<?php echo $index_additional_companies?>"> <?php esc_html_e('Remove', 'woocommerce-shipping-tracking'); ?></button>
					</div>
				<?php $index_additional_companies++; endforeach; ?>
			</div>
			<div class="clear"></div>
			<button class="button" id="wcst-additional-shipping-button"><?php esc_html_e('Add another tracking code', 'woocommerce-shipping-tracking'); ?></button>
			
			<?php if(!$is_order_details_page || !wcst_get_value_if_set($general_options, array('hide','email_embedding'), false)): ?>
			<div class="<?php if($is_order_details_page) echo 'wcst_option_container'; ?>">
				<h4 class="wcst_option_title"><?php esc_html_e('Disable email embedding', 'woocommerce-shipping-tracking'); ?> 
				</h4>
				<span class="wcst_description"><?php wcst_html_escape_allowing_special_tags(__('This overrides the <strong>General option -> Email options</strong> settings allowing you to not embed any tracking info into WooCommerce emails', 'woocommerce-shipping-tracking')); ?></span>
				<select name="_wcst_order_disable_email">
					<option value="no"><?php esc_html_e('No', 'woocommerce-shipping-tracking'); ?></option>
					<option value="disable_email_embedding" <?php if($is_email_embedding_disabled) echo 'selected="selected"';?>><?php esc_html_e('Yes', 'woocommerce-shipping-tracking'); ?></option>
				</select>
			</div>
			<?php endif; ?>
			
			<?php if($is_order_details_page && $wc_order && $wc_order->get_status() != 'completed' && !wcst_get_value_if_set($general_options, array('hide','switch_order_status_to_completed'), false)): ?>
			<div class="wcst_option_container">
				<h4 class="wcst_option_title"><?php esc_html_e('Switch order status to completed', 'woocommerce-shipping-tracking'); ?></h4>
				<span class="wcst_description"><?php esc_html_e('This saves you some time. Check the following option to set the order status as "Completed" :)', 'woocommerce-shipping-tracking'); ?></span>
				<input type="checkbox" name="_wcst_switch_order_to_completed" value="yes" <?php checked(wcst_get_value_if_set($general_options, array('default','switch_order_status_to_completed'), false), "true"); ?>><?php esc_html_e('Yes', 'woocommerce-shipping-tracking');  ?></input>
			</div>
			<?php endif;
			
			//dispatch date managment
			ob_start();
			$this->shipping_dropdown_options( $data, $options);
			$dropdown_data =  ob_get_clean();
			$dispatch_date = $dispatch_date_automatic_fill_with_today_date == 'yes' ? current_time($option_model->get_sql_date_format_according_to_date_option()) : "";
			$order_items_array = array();
			if($wc_order)
				foreach($wc_order->get_items() as $item)
				{
					$attributes = $wcst_product_model->get_order_variation_attribute_value_and_name($item->get_product());
					$order_items_array[$item->get_id()] = $item->get_name()." ".$attributes;
				}
			$additional_company_js_options = array( 'index_additional_companies' =>$index_additional_companies,
													 'date_format' => $date_format,
													 'dropdown' => $dropdown_data,
													 'dispatch_date' => $dispatch_date,
													 'order_items' => $order_items_array,
													 'hide_assocaited_products' => wcst_get_value_if_set($general_options, array('hide','associated_products_field'), false) ? 'true' : 'false',
													 'hide_dispatch_date' => wcst_get_value_if_set($general_options, array('hide','dispatch_date'), false) ? 'true' : 'false',
													 'hide_custom_text' => wcst_get_value_if_set($general_options, array('hide','custom_text'), false) ? 'true' : 'false',
													 'hide_send_notification' => wcst_get_value_if_set($general_options, array('hide','send_notification'), false) ? 'true' : 'false',
													 'send_notification_on' => wcst_get_value_if_set($general_options, array('default','send_notification'), false) ? 'true' : 'false',
													 'is_order_details_page' =>$is_order_details_page ? 'true' : false,
													 'shipping_company_text' => str_replace("'","\'",esc_html__('Shipping Company:', 'woocommerce-shipping-tracking')),
													 'no_tracking_text' => str_replace("'","\'",esc_html__('No Tracking', 'woocommerce-shipping-tracking')),
													 'tracking_number_text' => str_replace("'","\'", esc_html__('Tracking Number:', 'woocommerce-shipping-tracking')),
													 'tracking_number_placeholder' => str_replace("'","\'",esc_attr__('Enter Tracking No', 'woocommerce-shipping-tracking')),
													 'dispatch_date_text' => str_replace("'","\'",esc_html__('Dispatch date', 'woocommerce-shipping-tracking')),
													 'dispatch_date_text_placeholder' => str_replace("'","\'",esc_attr__('19/02/23 or 15th December 2023', 'woocommerce-shipping-tracking')),
													 'custom_text' => str_replace("'","\'",esc_html__('Custom text', 'woocommerce-shipping-tracking')),
													 'info_planceholder' => str_replace("'","\'", esc_attr__('Info about the shipped item(s) or whatever you want', 'woocommerce-shipping-tracking')),
													 'notification_placeholder' => esc_attr__('Send a notification email', 'woocommerce-shipping-tracking'),
													 'remove_text' => str_replace("'","\'",esc_html__('Remove', 'woocommerce-shipping-tracking')),
													 'associated_product_text' => str_replace("'","\'",esc_html__('Associated products', 'woocommerce-shipping-tracking')),
													 'associated_product_description_text' => str_replace("'","\'",esc_html__('Use this option only if you wish to give feedback to the user about which products the shipment refers', 'woocommerce-shipping-tracking'))
													);
			wp_register_script('wcst-add-additional-company', WCST_PLUGIN_PATH. '/js/wcst-additional-companies.js', array( 'jquery' ));
			wp_localize_script('wcst-add-additional-company', 'wcst_ac_options', $additional_company_js_options);
			wp_enqueue_script('wcst-add-additional-company');
		}	
}
?>