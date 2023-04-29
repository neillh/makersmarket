"use strict";
	
var wcst_start_date_range;
var wcst_end_date_range;
jQuery(document).ready(function()
{
	"use strict";
	if(wcst.just_one_date_field == 'true')
	{
		jQuery( "#wcst_start_date_range" ).css('width', '100%');
		jQuery( "#wcst_end_date_range" ).remove();
	}
	
	wcst_start_date_range = jQuery( "#wcst_start_date_range" ).pickadate({formatSubmit: wcst.date_format, format: wcst.date_format,
																				
																				//Min date 
																				min: [wcst.delivery_min_year,wcst.delivery_min_month,wcst.delivery_min_day],

																			
																				});
	wcst_end_date_range = jQuery( "#wcst_end_date_range" ).pickadate({formatSubmit: wcst.date_format, format: wcst.date_format,
																		   
																			
																			//Min date 
																			min: [wcst.delivery_min_year,wcst.delivery_min_month,wcst.delivery_min_day],

																			
																			});
	
	
	if(wcst.delivery_excluded_dates != false)
	{
		var dates_to_exclude = new Array();
		for(var i = 0; i < wcst.delivery_excluded_dates.length; i++)
		{
			dates_to_exclude.push(new Date(parseInt(wcst.current_year),parseInt(wcst.delivery_excluded_dates[i].month)-1,parseInt(wcst.delivery_excluded_dates[i].day)));
		}
		if(typeof wcst_start_date_range.pickadate('picker') != 'undefined')
			wcst_start_date_range.pickadate('picker').set('disable', dates_to_exclude);
		
		if(typeof wcst_end_date_range.pickadate('picker') != 'undefined')
			wcst_end_date_range.pickadate('picker').set('disable',  dates_to_exclude);
	}
	
	var wcst_start_time_range = jQuery( "#wcst_start_time_range" ).pickatime({formatSubmit: 'HH:i', format: 'HH:i', min:[wcst.time_range_start_hour,wcst.time_range_start_minute], max:[wcst.time_range_end_hour,wcst.time_range_end_minute]});
	var wcst_end_time_range = jQuery( "#wcst_end_time_range" ).pickatime({formatSubmit: 'HH:i', format: 'HH:i',min:[wcst.time_range_start_hour,wcst.time_range_start_minute], max:[wcst.time_range_end_hour,wcst.time_range_end_minute]});
	var wcst_start_time_secondary_range = jQuery( "#wcst_start_time_secondary_range" ).pickatime({formatSubmit: 'HH:i', format: 'HH:i', min:[wcst.time_secondary_range_start_hour,wcst.time_secondary_range_start_minute], max:[wcst.time_secondary_range_end_hour,wcst.time_secondary_range_end_minute]});
	var wcst_end_time_secondary_range = jQuery( "#wcst_end_time_secondary_range" ).pickatime({formatSubmit: 'HH:i', format: 'HH:i',min:[wcst.time_secondary_range_start_hour,wcst.time_secondary_range_start_minute], max:[wcst.time_secondary_range_end_hour,wcst.time_secondary_range_end_minute]});
	
	wcst_add_shipping_delivery_times_to_desidered_delivery_date(null);
	jQuery(document).on('click','.shipping_method',wcst_add_shipping_delivery_times_to_desidered_delivery_date);
	
	jQuery(document).on('click', '#place_order', function(event) 
	{
		var start_date_range, end_date_range, start_time_range, end_time_range, start_time_secondary_range, end_time_secondary_range;
			
		start_date_range = wcst_start_date_range.pickadate('picker');
		if(typeof start_date_range != 'undefined')
			start_date_range = start_date_range.get('select', "yyyymmdd"); 
		
		end_date_range = wcst_end_date_range.pickadate('picker');
		if(typeof end_date_range != 'undefined')
			end_date_range = end_date_range.get('select', "yyyymmdd"); 
		
		start_time_range = wcst_start_time_range.pickatime('picker');
		if(typeof start_time_range != 'undefined')
			start_time_range = start_time_range.get('select','HH:i'); 
		
		end_time_range = wcst_end_time_range.pickatime('picker');
		if(typeof end_time_range != 'undefined')
			end_time_range = end_time_range.get('select','HH:i'); 
		
		start_time_secondary_range = wcst_start_time_secondary_range.pickatime('picker');
		if(typeof start_time_secondary_range != 'undefined')
			start_time_secondary_range = start_time_secondary_range.get('select','HH:i'); 
		
		end_time_secondary_range = wcst_end_time_secondary_range.pickatime('picker');
		if(typeof end_time_secondary_range != 'undefined')
			end_time_secondary_range = end_time_secondary_range.get('select','HH:i');  
		
		if((typeof start_date_range != 'undefined' && typeof end_date_range != 'undefined') && ( (start_date_range != null && end_date_range == null) || (start_date_range == null && end_date_range != null) || start_date_range > end_date_range) )
		{
			alert(wcst.date_error_message);
			event.preventDefault();
			event.stopImmediatePropagation();
			return false;
		}
		
		if((typeof start_time_range != 'undefined' && typeof end_time_range != 'undefined') && ( (start_time_range != null && end_time_range == null) || (start_time_range == null && end_time_range != null) || start_time_range > end_time_range) )
		{
			alert(wcst.time_error_message);
			event.preventDefault();
			event.stopImmediatePropagation();
			return false;
		}
		if((typeof start_time_secondary_range != 'undefined' && typeof end_time_secondary_range != 'undefined') && ((start_time_secondary_range != null && end_time_secondary_range == null) || (start_time_secondary_range != null && end_time_secondary_range == null) || start_time_secondary_range > end_time_secondary_range) )
		{
			alert(wcst.secondary_error_message);
			event.preventDefault();
			event.stopImmediatePropagation();
			return false;
		}
	
	});
});

function wcst_add_shipping_delivery_times_to_desidered_delivery_date(event)
{
	jQuery('.shipping_method').each(function(index, elem)
	{
		var estimated_delivery_elem = jQuery(elem).parent().find('.wcst_estimated_shipping_delivery');
		if(jQuery(elem).is(':checked'))
		{
			var max = 0;
			if(estimated_delivery_elem.length != 0)
			{
				max = estimated_delivery_elem.data('min') != "" ? estimated_delivery_elem.data('min') : 0;
				max = estimated_delivery_elem.data('max') != ""   ? estimated_delivery_elem.data('max') : max;
			}
			wcst_set_min_date(max);
		}
	});
}
function wcst_set_min_date(day_offset)
{
	var date = new Date(wcst.delivery_min_year,wcst.delivery_min_month,wcst.delivery_min_day);
	date.setDate(date.getDate() + day_offset);
	
	var start_date_range = wcst_start_date_range.pickadate('picker');
	if(typeof start_date_range != 'undefined')
	{
		start_date_range.set('min', date);
		start_date_range.clear();
	}
		
	var end_date_range = wcst_end_date_range.pickadate('picker');
	if(typeof end_date_range != 'undefined')
	{
		end_date_range.set('min', date);
		end_date_range.clear();
	}
}