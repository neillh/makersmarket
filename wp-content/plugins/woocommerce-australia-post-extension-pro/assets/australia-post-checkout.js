jQuery(document).ready( function (e) {


	// wc_checkout_params is required to continue, ensure the object exists
	if ( typeof wc_checkout_params === 'undefined' )
		return false;

	var updateTimer,
	dirtyInput = false,
	xhr;

	jQuery(document).on('updated_checkout',function(){
		jQuery('.fee').on('click', function() {
				if ( xhr ) xhr.abort();

				jQuery( '#order_methods, #order_review' ).block({ message: null, overlayCSS: { background: '#fff', backgroundSize: '16px 16px', opacity: 0.6 } });

				var data = {
					action: 'woocommerce_update_order_review',
					security: wc_checkout_params.update_order_review_nonce,
					post_data: jQuery( 'form.checkout' ).serialize()
				};

				xhr = jQuery.ajax({
					type: 'POST',
					url: wc_checkout_params.wc_ajax_url.replace('%%endpoint%%','update_order_review'),
					//url: wc_checkout_params.ajax_url,
					data: data,
					success: function( response ) {
							if ( response ) {
								if ( response.fragments ) {
									jQuery.each( response.fragments, function ( key, value ) {
										jQuery( key ).replaceWith( value );
										jQuery( key ).unblock();
									} );
								}
								jQuery('#order_review').unblock();
								jQuery(  '#order_methods, #order_review' ).find( 'input[name=payment_method]:checked' ).trigger('click');
								jQuery( 'body' ).trigger('updated_checkout' );
							}
						}
					});
			});
	});
	

});


jQuery(document).ready( function (e) {
	// wc_cart_params is required to continue, ensure the object exists
	if ( typeof wc_cart_params === 'undefined' )
		return false;
	var updateTimer,
	dirtyInput = false,
	xhr;
	jQuery(document).on('change','.fee', function() {
			if ( xhr ) xhr.abort();

			jQuery( '.cart_totals' ).block({ message: null, overlayCSS: { background: '#fff', backgroundSize: '16px 16px', opacity: 0.6 } });
			var shipping_methods = [];

			jQuery( 'select.shipping_method, input[name^=shipping_method][type=radio]:checked, input[name^=shipping_method][type=hidden]' ).each( function( index, input ) {
				shipping_methods[ jQuery( this ).data( 'index' ) ] = jQuery( this ).val();
			} );
			if(jQuery('.fee').serialize() == ''){
				var fees = 'fees=false';
			}else{
				var fees = jQuery('.fee').serialize(); 
			}
			var data = {
				action: 'woocommerce_update_shipping_method',
				security: wc_cart_params.update_shipping_method_nonce,
				fees: fees,
				shipping_method: shipping_methods,
			};

			xhr = jQuery.ajax({
				type: 'POST',
				url: wc_cart_params.wc_ajax_url.replace('%%endpoint%%','update_shipping_method'),
				data: data,
				success: function( response ) {
						if ( response ) {
							jQuery( 'div.cart_totals' ).replaceWith( response );
							jQuery('.cart_totals').unblock();
							jQuery( 'body' ).trigger('updated_shipping_method' );
						}
					}
				});
	});
});