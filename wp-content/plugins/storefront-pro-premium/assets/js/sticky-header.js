/**
 * Created by shramee on 9/10/15.
 */
jQuery( document ).ready( function ($) {
	var $t = $( '#masthead' ),
		stickyNavTop = $t.offset().top,
		$tHi = $t.outerHeight() + parseInt( $t.css( 'margin-bottom' ) ),
		$body = $('body'),
		bodyHeight = $body.innerHeight(),
		$window = $( window ),
		windowHeight = $window.innerHeight(),
		stickyNav = function () {
			var scrollTop = $window.scrollTop();

			if ( $window.width() > 768 ) {
				if ( scrollTop > stickyNavTop ) {
					$t.addClass( 'sticky' );
					$( '.secondary-navigation' ).css( 'margin-bottom', $tHi )
				} else {
					$t.removeClass( 'sticky' );
					$( '.secondary-navigation' ).css( 'margin-bottom', 0 );
				}
			}
		};

//Hide header until scroll
	if ( $window.width() < 768 || 169 > bodyHeight - windowHeight ) {
		$t.slideDown();
	} else if ( $body.hasClass( 'header-hide-until-scroll' ) ) {
		$tHi = 0;
		$t.hide().addClass( 'sticky' );
		$window.scroll( function () {
			if ( 160 < $window.scrollTop() ) {
				$t.fadeIn()
			} else {
				$t.fadeOut()
			}
		} );
	} else {

		if ( $body.hasClass('sfp-nav-styleleft-vertical') ) {
			return;
		}
		stickyNav();
		$window.scroll( function () {
			stickyNav();
		} );
	}
} );