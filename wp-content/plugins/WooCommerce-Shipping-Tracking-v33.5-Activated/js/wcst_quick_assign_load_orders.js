"use strict";
jQuery(document).ready(function()
{
	jQuery(document).on('change', '#wcst_select2_order_id', wcst_on_order_id_selection);
	jQuery(document).on('wcst_additional_company_added', wcst_additional_company_added);
	
	jQuery(".js-data-orders-ajax").select2(
	{
	  ajax: {
		url: ajaxurl,
		dataType: 'json',
		delay: 250,
		multiple: false,
		data: function (params) {
		  return {
			search_string: params.term, // search term
			page: params.page || 1,
			action: 'wcst_get_order_list'
		  };
		},
		processResults: function (data, params) 
		{
		   return {
			results: jQuery.map(data.results, function(obj) 
			{
				var additional_ids_info = "";
				var order_id = obj.order_id;
				if(obj.order_number != null || obj.order_number_formatted != null)
				{
					if(obj.order_number_formatted != null)
						order_id = obj.order_number_formatted;
					else
						order_id = obj.order_number;
				}
				return { id: obj.order_id, text: "<b>#"+order_id+"</b> on "+obj.order_date+
											  " - <b>Order status: </b> "+obj.order_status+
											  " - <b>User #"+obj.user_id+": </b> "+obj.user_login+
											  " - <b>Email: </b>"+obj.user_email+
											  " - <b>Bills to: </b> "+obj.billing_name_and_last_name+
											  " - <b>Ships to: </b> "+obj.shipping_name_and_last_name
											  }; 
			}),
			pagination: {
						  'more': typeof data.pagination === 'undefined' ? false : data.pagination.more
						}
			};
		},
		cache: true
	  },
	  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
	  minimumInputLength: 0,
	  templateResult: wcst_formatRepo, 
	  templateSelection: wcst_formatRepoSelection  
	}
	);	
	
});
function wcst_additional_company_added(event)
{
	wcst_load_order_items(jQuery("#wcst_select2_order_id").val(), '.wcst_order_items_container.wcst_to_populate');
}
function wcst_on_order_id_selection(event)
{
	wcst_load_order_items(jQuery(event.currentTarget).val(), '.wcst_order_items_container');
}
function wcst_load_order_items(order_id, selector)
{
	var formData = new FormData();
	formData.append('action', 'wcst_get_order_items_selector'); 
	formData.append('security', wcst.security);
	formData.append('order_id', order_id);
	
	//UI 
	jQuery(selector).html("<span class='wcst_loading_text'>"+wcst.loading_text+"</span>");
	
	jQuery.ajax({
		url: wcst.ajaxurl,
		type: 'POST',
		data: formData,
		async: true,
		success: function (data) 
		{
			//UI
			console.log(selector);
			jQuery(selector).html(data);
			jQuery('.wcst_associated_product_select').select2({'width': '350px'});
			jQuery( selector ).removeClass('wcst_to_populate');
		},
		error: function (data) 
		{
			
		},
		cache: false,
		contentType: false,
		processData: false
	});
}

function wcst_formatRepo (repo) 
{
	if (repo.loading) return repo.text;
	
	var markup = '<div class="clearfix">' +
			'<div class="col-sm-12">' + repo.text + '</div>';
    markup += '</div>'; 
	
    return markup;
  }

  function wcst_formatRepoSelection (repo) 
  {
	  return repo.full_name || repo.text;
  }