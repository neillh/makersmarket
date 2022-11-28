/**
 * Divider Control Customizer Controls.
 *
 * @package Skinny
 */
( function( $ ) {
	wp.customize.bind( 'ready', function() {
		var from_top = 0,
			id,
			that;
		$(document).ready(function(){

			$( '.expand-toggle' ).on( 'click', function( event ) {
				event.preventDefault();
				that = $( this );
				id = $( this ).attr( 'href' );
				if ( $( id ).is( ':hidden' ) ) {
					$( id ).slideDown( 200 );
					from_top = that.offset().top
					that.addClass( 'expand-toggled' );
					$( 'body' ).animate( { scrollTop: ( from_top ) }, 500 );
				} else {
					$( id ).slideUp( 200 );
					that.removeClass( 'expand-toggled' );
					id = null;
				}
			} );
		} );
	} );
}( jQuery ) );
