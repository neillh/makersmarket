/**
 *  Multiple select select2 Customizer Controls.
 *
 * @package Skinny
 */
( function( $ ) {
	wp.customize.bind( 'ready', function() {

		/**
		 * Dropdown Select2 Custom Control
		 */

		$( '.customize-control-dropdown_select2' ).each( function() {
			$( '.customize-control-select2' ).select2({
				allowClear: true,
				width: '100%'
			});
		});

		$( '.customize-control-select2' ).on( 'change', function() {
			var select2Val = $( this ).val();
			$( this )
				.parent()
				.find( '.customize-control-dropdown-select2' )
				.val( select2Val )
				.trigger( 'change' );
		});
	});
}( jQuery ) );
