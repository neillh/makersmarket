"use strict";
var wcst_price_update_request = null;
jQuery(document).ready(function()
{
	jQuery(document).on('show_variation', wcst_load_estimation_date);
	jQuery(document).on('hide_variation', wcst_load_estimation_date);
	
});
function wcst_load_estimation_date(event)
{
	var variation_id = jQuery('input[name=variation_id]').val();
	var random = Math.floor((Math.random() * 1000000) + 999);
	var formData = new FormData();
	formData.append('action', 'wcst_load_estimation_date');
	formData.append('product_id', variation_id);
	//UI
	if(wcst.is_simple != 'true')
		jQuery('div.wcst_estimated_date_container').fadeOut();
	if(typeof variation_id == 'undefined' || variation_id == 0 || variation_id == "")
	{
		return;
	}
	
	if(wcst_price_update_request != null)
		wcst_price_update_request.abort();
	wcst_price_update_request = jQuery.ajax({
		url: wcst.wcst_ajax_url+"?nocache="+random,
		type: 'POST',
		data: formData,
		async: true,
		success: function (data) 
		{
			jQuery('div.wcst_estimated_date_container').html(data);
			jQuery('div.wcst_estimated_date_container').fadeIn();
						
		},
		error: function (data) 
		{
			
		},
		cache: false,
		contentType: false,
		processData: false
	});	
}