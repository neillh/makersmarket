"use strict";
jQuery(document).ready(function()
{
	jQuery(document).on('click', '#wcst_date_range', wcst_show_just_one_delivery_date_option);
	wcst_show_just_one_delivery_date_option(null);
});
function wcst_show_just_one_delivery_date_option(event)
{
	if(document.getElementById('wcst_date_range').checked)
		jQuery('#wcst_just_one_date_field').show();
	else 
		jQuery('#wcst_just_one_date_field').hide();
}
function wcst_addRow(tableID) 
{

	var row_index = jQuery('#'+tableID+" tr").length - 1;
	jQuery('#'+tableID).append('<tr>\
						<td><input type="checkbox" /></td>\
						<td><input type="number" step="1" min="1" max="31" value="1" name="wcst_checkout_options[options][delivery_date_to_exclude]['+row_index+'][day]"/></td>\
						<td><input type="number" step="1" min="1" max="12" value="1" name="wcst_checkout_options[options][delivery_date_to_exclude]['+row_index+'][month]"/></td>\
					</tr>');
	/* var table = document.getElementById(tableID);
	
	var rowCount = table.rows.length;
	var row = table.insertRow(rowCount);

	var colCount = table.rows[0].cells.length;

	for(var i=0; i<colCount; i++) 
	{

		var newcell	= row.insertCell(i);

		newcell.innerHTML = table.rows[0].cells[i].innerHTML;
		//alert(newcell.childNodes);
		switch(newcell.childNodes[0].type) {
			case "text":
					newcell.childNodes[0].value = "";
					break;
			case "checkbox":
					newcell.childNodes[0].checked = false;
					break;
			case "select-one":
					newcell.childNodes[0].selectedIndex = 0;
					break;
		}
	} */
}

function wcst_deleteRow(tableID) 
{
	try {
	var table = document.getElementById(tableID);
	var rowCount = table.rows.length;

	for(var i=0; i<rowCount; i++) {
		var row = table.rows[i];
		var chkbox = row.cells[0].childNodes[0];
		if(null != chkbox && true == chkbox.checked) {
			if(rowCount <= 1) 
			{
				break;
			}
			table.deleteRow(i);
			rowCount--;
			i--;
		}


	}
	}catch(e) {
		alert(e);
	}
}