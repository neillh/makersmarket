/**
 * Created by shramee on 9/10/15.
 */

/*!
 * imagesLoaded PACKAGED v3.2.0
 * JavaScript is all like "You images are done yet or what?"
 * MIT License
 */

(function(){"use strict";function e(){}function t(e,t){for(var n=e.length;n--;)if(e[n].listener===t)return n;return-1}function n(e){return function(){return this[e].apply(this,arguments)}}var i=e.prototype,r=this,s=r.EventEmitter;i.getListeners=function(e){var t,n,i=this._getEvents();if("object"==typeof e){t={};for(n in i)i.hasOwnProperty(n)&&e.test(n)&&(t[n]=i[n])}else t=i[e]||(i[e]=[]);return t},i.flattenListeners=function(e){var t,n=[];for(t=0;t<e.length;t+=1)n.push(e[t].listener);return n},i.getListenersAsObject=function(e){var t,n=this.getListeners(e);return n instanceof Array&&(t={},t[e]=n),t||n},i.addListener=function(e,n){var i,r=this.getListenersAsObject(e),s="object"==typeof n;for(i in r)r.hasOwnProperty(i)&&-1===t(r[i],n)&&r[i].push(s?n:{listener:n,once:!1});return this},i.on=n("addListener"),i.addOnceListener=function(e,t){return this.addListener(e,{listener:t,once:!0})},i.once=n("addOnceListener"),i.defineEvent=function(e){return this.getListeners(e),this},i.defineEvents=function(e){for(var t=0;t<e.length;t+=1)this.defineEvent(e[t]);return this},i.removeListener=function(e,n){var i,r,s=this.getListenersAsObject(e);for(r in s)s.hasOwnProperty(r)&&(i=t(s[r],n),-1!==i&&s[r].splice(i,1));return this},i.off=n("removeListener"),i.addListeners=function(e,t){return this.manipulateListeners(!1,e,t)},i.removeListeners=function(e,t){return this.manipulateListeners(!0,e,t)},i.manipulateListeners=function(e,t,n){var i,r,s=e?this.removeListener:this.addListener,o=e?this.removeListeners:this.addListeners;if("object"!=typeof t||t instanceof RegExp)for(i=n.length;i--;)s.call(this,t,n[i]);else for(i in t)t.hasOwnProperty(i)&&(r=t[i])&&("function"==typeof r?s.call(this,i,r):o.call(this,i,r));return this},i.removeEvent=function(e){var t,n=typeof e,i=this._getEvents();if("string"===n)delete i[e];else if("object"===n)for(t in i)i.hasOwnProperty(t)&&e.test(t)&&delete i[t];else delete this._events;return this},i.removeAllListeners=n("removeEvent"),i.emitEvent=function(e,t){var n,i,r,s,o=this.getListenersAsObject(e);for(r in o)if(o.hasOwnProperty(r))for(i=o[r].length;i--;)n=o[r][i],n.once===!0&&this.removeListener(e,n.listener),s=n.listener.apply(this,t||[]),s===this._getOnceReturnValue()&&this.removeListener(e,n.listener);return this},i.trigger=n("emitEvent"),i.emit=function(e){var t=Array.prototype.slice.call(arguments,1);return this.emitEvent(e,t)},i.setOnceReturnValue=function(e){return this._onceReturnValue=e,this},i._getOnceReturnValue=function(){return this.hasOwnProperty("_onceReturnValue")?this._onceReturnValue:!0},i._getEvents=function(){return this._events||(this._events={})},e.noConflict=function(){return r.EventEmitter=s,e},"function"==typeof define&&define.amd?define("eventEmitter/EventEmitter",[],function(){return e}):"object"==typeof module&&module.exports?module.exports=e:this.EventEmitter=e}).call(this),function(e){function t(t){var n=e.event;return n.target=n.target||n.srcElement||t,n}var n=document.documentElement,i=function(){};n.addEventListener?i=function(e,t,n){e.addEventListener(t,n,!1)}:n.attachEvent&&(i=function(e,n,i){e[n+i]=i.handleEvent?function(){var n=t(e);i.handleEvent.call(i,n)}:function(){var n=t(e);i.call(e,n)},e.attachEvent("on"+n,e[n+i])});var r=function(){};n.removeEventListener?r=function(e,t,n){e.removeEventListener(t,n,!1)}:n.detachEvent&&(r=function(e,t,n){e.detachEvent("on"+t,e[t+n]);try{delete e[t+n]}catch(i){e[t+n]=void 0}});var s={bind:i,unbind:r};"function"==typeof define&&define.amd?define("eventie/eventie",s):e.eventie=s}(this),function(e,t){"use strict";"function"==typeof define&&define.amd?define(["eventEmitter/EventEmitter","eventie/eventie"],function(n,i){return t(e,n,i)}):"object"==typeof module&&module.exports?module.exports=t(e,require("wolfy87-eventemitter"),require("eventie")):e.imagesLoaded=t(e,e.EventEmitter,e.eventie)}(window,function(e,t,n){function i(e,t){for(var n in t)e[n]=t[n];return e}function r(e){return"[object Array]"==f.call(e)}function s(e){var t=[];if(r(e))t=e;else if("number"==typeof e.length)for(var n=0;n<e.length;n++)t.push(e[n]);else t.push(e);return t}function o(e,t,n){if(!(this instanceof o))return new o(e,t,n);"string"==typeof e&&(e=document.querySelectorAll(e)),this.elements=s(e),this.options=i({},this.options),"function"==typeof t?n=t:i(this.options,t),n&&this.on("always",n),this.getImages(),u&&(this.jqDeferred=new u.Deferred);var r=this;setTimeout(function(){r.check()})}function h(e){this.img=e}function a(e,t){this.url=e,this.element=t,this.img=new Image}var u=e.jQuery,c=e.console,f=Object.prototype.toString;o.prototype=new t,o.prototype.options={},o.prototype.getImages=function(){this.images=[];for(var e=0;e<this.elements.length;e++){var t=this.elements[e];this.addElementImages(t)}},o.prototype.addElementImages=function(e){"IMG"==e.nodeName&&this.addImage(e),this.options.background===!0&&this.addElementBackgroundImages(e);var t=e.nodeType;if(t&&d[t]){for(var n=e.querySelectorAll("img"),i=0;i<n.length;i++){var r=n[i];this.addImage(r)}if("string"==typeof this.options.background){var s=e.querySelectorAll(this.options.background);for(i=0;i<s.length;i++){var o=s[i];this.addElementBackgroundImages(o)}}}};var d={1:!0,9:!0,11:!0};o.prototype.addElementBackgroundImages=function(e){for(var t=m(e),n=/url\(['"]*([^'"\)]+)['"]*\)/gi,i=n.exec(t.backgroundImage);null!==i;){var r=i&&i[1];r&&this.addBackground(r,e),i=n.exec(t.backgroundImage)}};var m=e.getComputedStyle||function(e){return e.currentStyle};return o.prototype.addImage=function(e){var t=new h(e);this.images.push(t)},o.prototype.addBackground=function(e,t){var n=new a(e,t);this.images.push(n)},o.prototype.check=function(){function e(e,n,i){setTimeout(function(){t.progress(e,n,i)})}var t=this;if(this.progressedCount=0,this.hasAnyBroken=!1,!this.images.length)return void this.complete();for(var n=0;n<this.images.length;n++){var i=this.images[n];i.once("progress",e),i.check()}},o.prototype.progress=function(e,t,n){this.progressedCount++,this.hasAnyBroken=this.hasAnyBroken||!e.isLoaded,this.emit("progress",this,e,t),this.jqDeferred&&this.jqDeferred.notify&&this.jqDeferred.notify(this,e),this.progressedCount==this.images.length&&this.complete(),this.options.debug&&c&&c.log("progress: "+n,e,t)},o.prototype.complete=function(){var e=this.hasAnyBroken?"fail":"done";if(this.isComplete=!0,this.emit(e,this),this.emit("always",this),this.jqDeferred){var t=this.hasAnyBroken?"reject":"resolve";this.jqDeferred[t](this)}},h.prototype=new t,h.prototype.check=function(){var e=this.getIsImageComplete();return e?void this.confirm(0!==this.img.naturalWidth,"naturalWidth"):(this.proxyImage=new Image,n.bind(this.proxyImage,"load",this),n.bind(this.proxyImage,"error",this),n.bind(this.img,"load",this),n.bind(this.img,"error",this),void(this.proxyImage.src=this.img.src))},h.prototype.getIsImageComplete=function(){return this.img.complete&&void 0!==this.img.naturalWidth},h.prototype.confirm=function(e,t){this.isLoaded=e,this.emit("progress",this,this.img,t)},h.prototype.handleEvent=function(e){var t="on"+e.type;this[t]&&this[t](e)},h.prototype.onload=function(){this.confirm(!0,"onload"),this.unbindEvents()},h.prototype.onerror=function(){this.confirm(!1,"onerror"),this.unbindEvents()},h.prototype.unbindEvents=function(){n.unbind(this.proxyImage,"load",this),n.unbind(this.proxyImage,"error",this),n.unbind(this.img,"load",this),n.unbind(this.img,"error",this)},a.prototype=new h,a.prototype.check=function(){n.bind(this.img,"load",this),n.bind(this.img,"error",this),this.img.src=this.url;var e=this.getIsImageComplete();e&&(this.confirm(0!==this.img.naturalWidth,"naturalWidth"),this.unbindEvents())},a.prototype.unbindEvents=function(){n.unbind(this.img,"load",this),n.unbind(this.img,"error",this)},a.prototype.confirm=function(e,t){this.isLoaded=e,this.emit("progress",this,this.element,t)},o.makeJQueryPlugin=function(t){t=t||e.jQuery,t&&(u=t,u.fn.imagesLoaded=function(e,t){var n=new o(this,e,t);return n.jqDeferred.promise(u(this))})},o.makeJQueryPlugin(),o});

/**
 * Public end styling
 * @author shramee <shramee.srivastav@gmail.com>
 * @package storefront pro
 */
jQuery( document ).ready( function ( $ ) {// Add focus class to li
//iPad Menu
	// Navigation link focus handler
	$( '.main-navigation, .secondary-navigation' ).find( 'a' ).on( 'focus.storefront blur.storefront', function () {
		$( this ).parents().toggleClass( 'focus' );
	} );

	// Cart focus handler
	$( '.site-header-cart' ).find( 'a' ).on( 'focus.storefront blur.storefront', function () {
		$( this ).parents().toggleClass( 'focus' );
	} );

	if ( (
	     'ontouchstart' in window || navigator.maxTouchPoints
	     ) && $( window ).width() > 763 ) {
		// Add an identifying class to dropdowns when on a touch device
		// This is required to switch the dropdown hiding method from a negative `left` value to `display: none`.
		$( '.main-navigation ul ul, .secondary-navigation ul ul, .site-header-cart .widget_shopping_cart' ).addClass( 'sub-menu--is-touch-device' );

		// Ensure the dropdowns close when user taps outside the site header
		$( '.site-content, .header-widget-region, .site-footer, .site-header:not(a)' ).on( 'click', function () {
			return;
		} );
	}

// Skrollr
	if ( ! ( /Android|iPhone|iPad|iPod|BlackBerry|Windows Phone/i ).test( navigator.userAgent || navigator.vendor || window.opera ) ) {
		skrollr.init( {forceHeight: false} );
	}

// Make sure sfpSettings object exists
	if ( 'object' != typeof sfpSettings || ! sfpSettings ) sfpSettings = {};

	var $window = $( window ),
		windowWidth = $window.width(),
		$body = $( 'body' ),
		$header = $body.find( '#masthead' ),
		$ham_ol = $( '.overlay.hamburger-overlay' ),
		$layoutButts = $( '.layout-buttons' ),
		$products = $( '.products' ),
		gutter = parseInt( $products.children( '.product:first' ).css( 'margin-right' ) ),
		$navbar = $( '#site-navigation' );
// Fixing mega menu in multi line nav
	$( '.main-navigation' ).find( 'li.mega-menu' ).each( function() {
		var $t = $( this );
		if ( ! $t.is( ':visible' ) ) return;
		var $offset = $t.offset().top - $t.closest( '.main-navigation' ).offset().top + $t.height();
		$t.children( '.sub-menu' ).css( 'top', $offset );
	} );

// Search Animation
	if ( ! $body.hasClass('sfp-nav-styleleft-vertical') && windowWidth > 767 ) {
		if ( $navbar.children( '.primary-navigation' ).length ) {
			$navbar.find( '.sf-pro-search ul' ).hide();

			toggleSearch = function () {
				$navbar.toggleClass( 'show-search' );
				$navbar.children( '.primary-navigation, .site-header-cart' )
				       .slideToggle( {easing: 'linear'} );
				$navbar.children( '.sfp-nav-search' )
				       .slideToggle( {easing: 'linear'} )
				       .find( '.search-field' ).val( '' );
			};

			$navbar.find( '.sf-pro-search a' ).click( function() {
				$navbar.css( 'height', $navbar.innerHeight() );
				toggleSearch();
				$navbar.find('.search-field').focus();
			} );
			$navbar.find( '.sfp-nav-search-close' ).click( function() {
				$navbar.css( 'height', '' );
				toggleSearch();
			} );
		}
	}

// Mobile menu
	$( '.menu-toggle' ).click( function ( e ) {
		e.stopPropagation();
		e.preventDefault();
		$('.main-navigation').toggleClass( 'toggled' );
	} );

// Shop/Mobile-store masonry
	if ( sfpSettings.shopLayout && -1 < sfpSettings.shopLayout.indexOf( 'masonry' ) && $products.length ) {
		$products.children( '.product' ).css( 'margin-right', '0' );
		$products.masonry( {
			itemSelector: '.product',
			gutter: gutter
		} );

		$products.imagesLoaded( function () {
			$products.masonry();
		} );
		$window.resize( function () {
			$products.masonry();
		} );
	}


	console.log( sfpSettings.wcQuickView, $products.length );
// Shop Quick view
	if ( sfpSettings.wcQuickView && $products.length ) {
		$body.append( '<div id="sfp-quick-view-product-overlay" style="display: none;" onclick="jQuery( \'#sfp-quick-view-product-overlay\' ).fadeOut()"><div id="sfp-quick-view-product" style=""></div></div>' );
		var $qwp = $( '#sfp-quick-view-product' );
		$qwp.click( function( e ) {
			e.stopPropagation();
		} );
		$qwp.on( 'change', '[data-attribute_name]', function ( e ) {
			var
				$t = $( this ),
				$form = $t.closest( '.variations_form' ),
				vars = $form.data( 'product_variations' ),
				qry = $form.serialize().split( '&' );

			// Filter product attributes
			qry = qry.filter( function ( el ) {
				return el.indexOf( 'attribute_pa_' ) === 0;
			} );

			for ( var i = 0; i < vars.length; i ++ ) {
				var
					variable = vars[i],
					matches = true;
				for ( var j = 0; j < qry.length; j ++ ) {
					var attr = qry[j].split( '=' );
					if ( variable.attributes[ attr[0] ] !== attr[1] ) {
						matches = false;
						break;
					}
				}
				if ( matches ) {
					// Add image
					console.log( variable, variable.image );
					if ( variable.image ) {
						$qwp.find( '.sfp-pqv-image img' ).attr( 'src', variable.image.src );
						$qwp.find( '.sfp-pqv-image img' ).attr( 'srcset', variable.image.srcset );
					}
					break;
				}
			}

		} );
		$body.on( 'click', '.sfp-quick-view', function ( e ) {
			e.preventDefault();
			var $t = $( this ),
				a = '<a href="'		+ $t.attr( 'href' )		+ '">';
			$qwp.html(
				'<div class="sfp-pqv-close" onclick="jQuery( \'#sfp-quick-view-product-overlay\' ).fadeOut()">&times;</div>' +
				'<div class="sfp-pqv-image">'	+ $t.data( 'pqv-image' )	+ '</div>' +
				'<div class="sfp-pqv-info">' +
					'<h2 class="sfp-pqv-title">' + a+ $t.data( 'pqv-title' )	+ '</a></h2>' +
					'<div class="sfp-pqv-price">'	+ $t.data( 'pqv-price' )	+ '</div>' +
					'<div class="sfp-pqv-excerpt">'	+ $t.data( 'pqv-excerpt' )	+ ' ' +  a + sfpPublicL10n.more + '...</a></div>' +
					'<div class="sfp-pqv-a2c">'		+ $t.data( 'pqv-a2c' )		+ '</div>' +
					'<div class="sfp-pqv-more">' + a + sfpPublicL10n.more + '...</a></div>' +
				'</div>'
			);
			jQuery( '#sfp-quick-view-product-overlay' ).fadeIn();
		} );
	}

// Shop/Mobile-store masonry
	if ( sfpSettings.mobStore && windowWidth < 768 ) {
		$products.children( '.product' ).css( 'margin-right', '0' );
		if ( $products.length ) {
			$products.masonry( {
				itemSelector: '.product',
				gutter: gutter
			} );

			$products.imagesLoaded( function () {
				$products.masonry( {
					itemSelector: '.product',
					gutter: gutter
				} );
			} );
			$window.resize( function () {
				gutter = parseInt( $products.width() / 100 );
				$products.masonry( {
					itemSelector: '.product',
					gutter: gutter
				} );
			} );
		}
	}

// Infinite scroll
	if ( sfpSettings.infiniteScroll ) {
		$( 'div[class^="columns"] + .storefront-sorting' ).hide();

		$( '.site-main' ).jscroll( {
			loadingHtml: '<div class="sfp-loading"></div><h4 class="sfp-loading-text">' + sfpPublicL10n.loading + '...</h4>',
			nextSelector: 'a.next',
			contentSelector: '.scroll-wrap',
			callback: function () {
				// Shop/Mobile-store masonry
				if ( sfpSettings.mobStore && windowWidth < 768 ) {
					var newProds = $( '.jscroll-added .products' );
					$products.append( newProds );
					$products.masonry( 'reloadItems' );
					$products.masonry();
					$products.imagesLoaded( function () { $products.masonry() } );
				}
				window.sfproProductImageFlip && window.sfproProductImageFlip( $( '.jscroll-added' ).last() );
			}
		} );
	}

// Mobile store
	$layoutButts.find( '.layout-list' ).click( function () {
		$body.addClass( 'layout-list' );
		$window.resize();
	} );
	$layoutButts.find( '.layout-masonry' ).click( function () {
		$body.removeClass( 'layout-list' );
		$window.resize();
	} );

// Hamburger Menu Function
	toggle_hamburger_header = function () {
		var $mh = $( '#masthead' );
		$mh.toggleClass( 'toggled' );
		$( '.full-width-hamburger-wrap' ).fadeToggle();
		if ( 0 == parseInt( $mh.css( 'left' ) ) ) {
			$mh.animate( {
				left: -250
			} );
			$ham_ol.fadeOut(200)
		} else {
			$mh.animate( {
				left: 0
			} );
			$ham_ol.fadeIn(200)
		}
	};
	$( '.header-toggle' ).click( toggle_hamburger_header );
	$ham_ol.click( toggle_hamburger_header );

} );

/**
 * navigation.js
 * storefront navigation js to add nav-menu class to first ul in site-navigation
 */
(
	function () {
		var container, button, menu;
		container = document.getElementById( 'site-navigation' );
		if ( ! container ) {
			return;
		}
		menu = container.getElementsByTagName( 'ul' )[0];
		if ( 'undefined' === typeof menu ) {
			return;
		}
		menu.setAttribute( 'aria-expanded', 'false' );
		if ( - 1 === menu.className.indexOf( 'nav-menu' ) ) {
			menu.className += ' nav-menu';
		}

		jQuery( function ( $ ) {
			// Add dropdown toggle that displays child menu items.
			var handheld = $( '.handheld-navigation' );

			if ( handheld.length ) {
				handheld.find( '.menu-item-has-children > a, .page_item_has_children > a' ).each( function () {

					// Add dropdown toggle that displays child menu items
					var
						$a = $( this ),
						btn = $( '<button></button>' );
					btn.attr( 'aria-expanded', 'false' );
					btn.addClass( 'dropdown-toggle' );

					var btnSpan = $( '<span></span>' ).addClass( 'screen-reader-text' ).text( sfpSettings.i18n.expand );

					btn.append( btnSpan );

					$( $a ).after( btn );

					// Set the active submenu dropdown toggle button initial state
					if ( $a.parent( '.current-menu-ancestor' ).length ) {
						btn.attr( 'aria-expanded', 'true' );
						btn.addClass( 'toggled-on' );
						btn.siblings( 'ul' ).addClass( 'toggled-on' );
					}

					// Add event listener
					btn.click( function () {
						var btn = $( this );

						btn.toggleClass( 'toggled-on' );

						if ( btn.hasClass( 'toggled-on' ) ) {
							btn.attr( 'aria-expanded', 'true' );
							btn.siblings( 'ul' ).addClass( 'toggled-on' );
							btn.find( 'span' ).html( sfpSettings.i18n.collapse );
						} else {
							btn.attr( 'aria-expanded', 'false' );
							btn.siblings( 'ul' ).removeClass( 'toggled-on' );
							btn.find( 'span' ).html( sfpSettings.i18n.expand );
						}
					} );
				} );
			}
		} );
	}
)();
