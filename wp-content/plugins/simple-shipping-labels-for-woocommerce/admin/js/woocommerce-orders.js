/**
 * Add functionality to each "print-simple-shipping-label" button
 * in WooCommerce orders page "Shipping label" column,
 * after document is ready (otherwise querySelectorAll() returns null).
 */
document.addEventListener('DOMContentLoaded', (event) => {
	// Add functionality to "Print" buttons.
	document.querySelectorAll('.button-sslabels-print').forEach(button => {
		button.addEventListener('click', generateLabels);
	})
})


/**
 * Generate an HTML page in a new window for the user to print the labels.
 * The page holds the shipping labels with CSS page breaks.
 */
function generateLabels() {
	var clickedButton = this;
	var checkedOrders = document.querySelectorAll('input[name="post[]"]:checked');
	var orders = [];

	// Append a single button that was pressed to the list is no orders were checked/selected.
	if (checkedOrders.length == 0) {
		orders.push(this.value);

	// If user checked any orders - append their "Print" buttons to the list.
	} else {
		for (let i = 0; i < checkedOrders.length; i++) {
			orders.push(checkedOrders[i].value);
		}
	}

	// This function is triggered on "Print" button click, near one of the WooCommerce orders.
	// Here we fetch a generated labels page (using WordPress built-in admin AJAX module) 
	// with a single or bulk order ids to print.
	// We use JavaScript at the end to open a new tab and write the html page into it.
	clickedButton.innerHTML = sslabels.button_active_name;
	clickedButton.disabled = true;

	var data = {
		'action': 'sslabels_api',
		'orders': orders,
		'api_action': 'generate_labels',
		'_ajax_nonce': `${sslabels._ajax_nonce}`
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ajaxurl, data, function(response) {
		
		// Open new browser tab and fill it with labels to print.
		var popup_window = window.open('','Shipping Labels');
		popup_window.document.write(response);	// Write the content of the document.
		popup_window.document.close();	// Finishes writing to a document.
		
		try {
			popup_window.focus();	// Focus on the new open window
			clickedButton.innerHTML = sslabels.button_name;
			clickedButton.disabled = false;
		} catch (e) {
			clickedButton.innerHTML = sslabels.button_name;
			clickedButton.disabled = false;
			alert("Browser or extension pop-up blocker is enabled. Please add this site to your exceptions list for the generated labels page pop-up window.");
		}

	}).fail(function() {
		alert( "Could not generate labels." );
		clickedButton.innerHTML = sslabels.button_name;
		clickedButton.disabled = false;
	});
	
}
