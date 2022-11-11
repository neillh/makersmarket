/**
 * Created by shramee on 23/10/15.
 * @package makesite
 */
jQuery( document ).ready( function ( $ ) {
	$( '.menu-item-depth-0 .field-css-classes' ).each( function () {
		var $t = $( this ),
			$input = $t.find('input'),
			$cb = $( '<input/>' ).attr( 'type', 'checkbox' ),
			$p = $( '<p/>' ).html( '<label> Make a mega menu</label>' ).addClass( 'field-mega-menu description description-wide' );
		$cb.addClass( 'enable-mega-menu' );
		if ( -1 < $input.val().search('mega-menu') ) {
			$cb.prop( 'checked', true );
		}
		$p.find( 'label' ).prepend( $cb );
		$t.before( $p );
	} );
	$( '.enable-mega-menu' ).change( function () {
		var $t = $( this ),
			$class = $t.closest( '.menu-item-settings' ).find('.field-css-classes input' ),
			valNow = $class.val();
		if ( this.checked ) {
			valNow = $class.val() + ' mega-menu';
		} else {
			valNow = $class.val().replace( ' mega-menu', '' ).replace( 'mega-menu', '' );
		}
		$class.val( valNow );
	} );

	$( '.menu-item .field-css-classes' ).each( function () {
		var $t = $( this ),
			$input = $t.find('input'),
			$cb = $( '<input/>' ).attr( 'type', 'checkbox' ),
			$p = $( '<p/>' ).html( '<label> Hide label</label>' ).addClass( 'field-hide-label description description-wide' );

		// Hide Label
		$cb.addClass( 'enable-hide-label' );
		if ( -1 < $input.val().search('hide-label') ) {
			$cb.prop( 'checked', true );
		}
		$p.find( 'label' ).prepend( $cb );
		$t.before( $p );

		// Icon picker
		var $fa = $( '<span class="fa-preview"></span>' ),
			$lbl = $( '<label class="pick-fa-icon button button-primary"><input type="text" class="fa-icon-input">Choose icon</label>' ),
			$p = $( '<p><span class="fa-icon-remove button">Remove Icon</span></p>' ).addClass( 'field-fa-icon description description-wide' );
		$lbl.addClass( 'enable-fa-picker' );
		if ( -1 < $input.val().indexOf('fa-') ) {
			$.each( $input.val().split( ' ' ), function ( i, v ) {
				if ( -1 < v.indexOf( 'fa-' ) || v === 'fas' || v === 'fab' ) {
					$fa.addClass( v );
				}
			} );
		}
		$p.prepend( $lbl.prepend( $fa ) );
		$t.before( $p );
	} );
	$( '.enable-hide-label' ).change( function () {
		var $t = $( this ),
			$class = $t.closest( '.menu-item-settings' ).find('.field-css-classes input' ),
			valNow = $class.val();
		if ( this.checked ) {
			valNow = $class.val() + ' hide-label';
		} else {
			valNow = $class.val().replace( ' hide-label', '' ).replace( 'hide-label', '' );
		}
		$class.val( valNow );
	} );

	$( '.fa-icon-input' ).iconpicker().on( 'iconpickerUpdated', function () {
		var $t = $( this ),
			$fa = $t.siblings( '.fa-preview' ),
			$remove = $t.closest( '.menu-item-settings' ).find('.fa-icon-remove' ),
			$class = $t.closest( '.menu-item-settings' ).find('.field-css-classes input' ),
			clss = [];
		$remove.click();

		clss = $class.val().split( ' ' )
		if ( $t.val() ) {
			clss.push( $t.val() );
			$fa.addClass( $t.val() );
		}
		$class.val( clss.join( ' ' ) );
	} );
	$( '.fa-icon-remove' ).click( function () {
		var $t = $( this ),
			$fa = $t.parent().find( '.fa-preview' ),
			$class = $t.closest( '.menu-item-settings' ).find('.field-css-classes input' ),
			clsA = $class.val().split( ' ' ),
			cls = [];

		$.each( clsA, function ( i, v ) {
			if ( v && -1 == v.indexOf( 'fa-' ) ) {
				cls.push( v );
			}
		} );
		$fa.attr( 'class', 'fa fa-preview' );
		$class.val( cls.join( ' ' ) );
	} );
} );