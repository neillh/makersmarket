"use strict";
var wcst_default_dispatch_date = "";
var wcst_index ;
var wcst_date_format;

jQuery(document).ready(function()
{
	"use strict";
	
	wcst_index = wcst_ac_options.index_additional_companies;
	wcst_date_format = wcst_ac_options.date_format;
	jQuery('#wcst-additional-shipping-button').click(wcst_additional_shipping_company);
	jQuery(document).on('click', '.wcst-remove-shipping', wcst_remove_additional_shipping_company);
});
function wcst_additional_shipping_company(event)
{
	event.preventDefault();
	event.stopImmediatePropagation();
	jQuery('#wcst-additional-shippings').append(wcst_get_template(wcst_index));
	document.dispatchEvent(new Event('wcst_additional_company_added'));
	wcst_set_date_pickers();
	wcst_index++;
	wcst_init_select2();
	return false;
}
function wcst_remove_additional_shipping_company(event)
{
	event.preventDefault();
	event.stopImmediatePropagation();
	var id = jQuery(event.currentTarget).data('id');
	jQuery("#wcst-additiona-shipping-box-"+id).remove();
	return false;
}


function wcst_get_template(index)
{
	var wcst_add_shipping_company_template = '<div id="wcst-additiona-shipping-box-'+index+'" class="wcst_shipping_info_box">';
		wcst_add_shipping_company_template += '	<ul class="totals">';
		wcst_add_shipping_company_template += '		<li>';
		wcst_add_shipping_company_template += '			<label style="display:block; clear:both;">'+wcst_ac_options.shipping_company_text+'</label>';
		wcst_add_shipping_company_template += '			<select style="margin-bottom:15px;" name="_wcst_order_additional_shipping['+index+'][trackurl]" >';
		wcst_add_shipping_company_template += '				<option value="NOTRACK">'+wcst_ac_options.no_tracking_text+'</option>';
		wcst_add_shipping_company_template += '				'+wcst_ac_options.dropdown;
		wcst_add_shipping_company_template += '			</select>';
		wcst_add_shipping_company_template += '		</li>';
		wcst_add_shipping_company_template += '		<li>';
		wcst_add_shipping_company_template += '			<label style="display:block; clear:both;">'+wcst_ac_options.tracking_number_text+'</label>';
		wcst_add_shipping_company_template += '			<input style="margin-bottom:15px;" type="text" id="wcst_tracking_code_input_'+index+'" name="_wcst_order_additional_shipping['+index+'][trackno]" placeholder="'+wcst_ac_options.tracking_number_placeholder+'" value="" class="wcst_tracking_code_input" ></input>';
		wcst_add_shipping_company_template += '		</li>';
	
		if(wcst_ac_options.is_order_details_page == 'true' && wcst_ac_options.hide_assocaited_products != 'true')
		{
			wcst_add_shipping_company_template += '		<li>';
			wcst_add_shipping_company_template += ' 		<label style="display:block; clear:both; font-weight:bold;">'+wcst_ac_options.associated_product_text+'</label>';
			wcst_add_shipping_company_template += '			<p>'+wcst_ac_options.associated_product_description_text+'</p>';
			wcst_add_shipping_company_template += '			<select name="_wcst_order_additional_shipping['+index+'][associated_product][]" class="wcst_associated_product_select" multiple>';
			Object.keys(wcst_ac_options.order_items).forEach(function(key)
			{
				wcst_add_shipping_company_template += '		<option value="'+key+'" >'+wcst_ac_options.order_items[key]+'</option>';
			});
			wcst_add_shipping_company_template += '			</select>';
			wcst_add_shipping_company_template += '		</li>';
		}
		else 
			wcst_add_shipping_company_template += '		<div class="wcst_order_items_container wcst_to_populate"></div>';
		
		if(wcst_ac_options.is_order_details_page == 'false' || wcst_ac_options.hide_dispatch_date != 'true')
		{
			wcst_add_shipping_company_template += '		<li>';
			wcst_add_shipping_company_template += '			<label style="display:block; clear:both;">'+wcst_ac_options.dispatch_date_text+'</label>';
			wcst_add_shipping_company_template += '			<input style="margin-bottom:15px;" class="wcst_dispatch_date" type="text" name="_wcst_order_additional_shipping['+index+'][order_dispatch_date]" placeholder="'+wcst_ac_options.dispatch_date_text_placeholder+'" value="'+wcst_ac_options.dispatch_date+'" ></input>';
			wcst_add_shipping_company_template += '		</li>';
		}
		if(wcst_ac_options.is_order_details_page == 'false' || wcst_ac_options.hide_custom_text != 'true')
		{
			wcst_add_shipping_company_template += '		<li>';
			wcst_add_shipping_company_template += '			<label style="display:block; clear:both;">'+wcst_ac_options.custom_text+'</label>';
			wcst_add_shipping_company_template += '			<textarea style="margin-bottom:15px;" type="text" class="wcst_custom_text" name="_wcst_order_additional_shipping['+index+'][custom_text]" placeholder="'+wcst_ac_options.info_planceholder+'" rows="4" ></textarea>';
			wcst_add_shipping_company_template += '		</li>';
		}
		if(wcst_ac_options.is_order_details_page == 'false' || wcst_ac_options.hide_send_notification != 'true')
		{
			var is_checked = wcst_ac_options.is_order_details_page == 'true' && wcst_ac_options.send_notification_on == 'true' ? " checked='checked' " : "";
			wcst_add_shipping_company_template += '		<li>';
			wcst_add_shipping_company_template += '			<input class="" id="wcst_send_shipping_notification_email_'+index+'" type="checkbox" value="true" '+is_checked+' data-id="'+index+'" name="wcst_send_shipping_notification_email['+index+']">'+wcst_ac_options.notification_placeholder+'</input>';
			wcst_add_shipping_company_template += '			<span class="wcst_description"><?php echo $active_notification_description; ?></span>';
			wcst_add_shipping_company_template += '		</li>';	
		}
		wcst_add_shipping_company_template += ' 	</ul>';
		wcst_add_shipping_company_template += ' 	<button class="button wcst-remove-shipping" data-id="'+index+'"> '+wcst_ac_options.remove_text+'</button>';
		wcst_add_shipping_company_template += '	</div>';
	return wcst_add_shipping_company_template;
}