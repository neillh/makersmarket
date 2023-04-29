"use strict";

var orders_id_to_url ; 

jQuery(document).ready(function()
{
	"use strict";
	orders_id_to_url = wcst.order_to_url;
	var tracking_shipping_button_text = wcst.tracking_shipment_button;
	jQuery('table.shop_table.my_account_orders tbody tr.order').each(function(index)
	{
		var wcst_var = wcst.wc_version;
		var order_num = jQuery(this).find('td.woocommerce-orders-table__cell-order-number a').data("wcst-id");
		var main_element = jQuery(this).find('td.order-actions').length ? jQuery(this).find('td.order-actions') : jQuery(this).find('td.woocommerce-orders-table__cell.woocommerce-orders-table__cell-order-actions');
		
		if(orders_id_to_url[order_num] !== 'false')
		{
			var last_element = null;
			if(typeof orders_id_to_url[order_num] !== 'undefined')
				for(var i=0; i< orders_id_to_url[order_num].length; i++)
				{
					var value = orders_id_to_url[order_num][i];
					var button_text = tracking_shipping_button_text.replace('%s', i+1); 
					var button_element = jQuery('<a href="'+value+'" class="button wcst-myaccount-tracking-button" target="_blank">'+button_text+'</a>');
					main_element.prepend(button_element);
				}
		}
	});
});
function wcst_versionCompare(a, b) 
{
    var i, cmp, len, re = /(\.0)+[^\.]*$/;
    a = (a + '').replace(re, '').split('.');
    b = (b + '').replace(re, '').split('.');
    len = Math.min(a.length, b.length);
    for( i = 0; i < len; i++ ) {
        cmp = parseInt(a[i], 10) - parseInt(b[i], 10);
        if( cmp !== 0 ) {
            return cmp;
        }
    }
    return a.length - b.length;
}