/**
 * Plugin front end scripts
 *
 * @package Storefront_Pro_Blocks
 * @version 1.0.0
 */
jQuery( function ( $ ) {

	var $productTables = $( '.sfpbk-product-table-wrap' );
	$productTables.on( 'change', 'select.sfpbk-pt-filter', function ( e ) {
		var
			$p                = $( this ).closest( '.sfpbk-product-table-wrap' ),
			macthingSelectors = 'tr';
		$p.find( "tr[data-terms]" ).hide();
		$p.find( 'select.sfpbk-pt-filter' ).each( function () {
			var $t = $( this );
			macthingSelectors += this.value ? "[data-terms*='|" + $t.data( 'tax' ) + ':' + this.value + "|']" : '';
		} );
		var $matches = $p.find( macthingSelectors );

		if ( $matches.length ) {
			$p.removeClass( 'sfpbk-no-products' );
			$matches.show();
		} else {
			$p.addClass( 'sfpbk-no-products' );
		}
	} );

	$productTables.find( '.sfpbk-pt-action button[value="cart"]' ).text( 'Select products to add to cart' );
	;
	$productTables.find( '.sfpbk-pt-action button[value="quote"]' ).text( 'Select products to request quote' );
	;

	$productTables.on( 'change', 'input[type=number]', function ( e ) {
		var
			$wrap         = $( this ).closest( '.sfpbk-product-table-wrap' ),
			totalProducts = 0;

		$wrap.find( 'input[name*="sfpbk-pt-prod"]' ).each( function () {
			totalProducts += + this.value;
		} );
		console.log( $wrap.find( 'input[name*="sfpbk-pt-prod"]' ).length, totalProducts );

		var $btn = $wrap.find( '.sfpbk-pt-action' ).find( 'button' );
		if ( totalProducts > 1 ) {
			$btn.filter( "[value='cart']" ).text( 'Add ' + totalProducts + ' products to cart' );
			$btn.filter( "[value='quote']" ).text( 'Request quote for  ' + totalProducts + ' products' );
		} else if ( totalProducts ) {
			$btn.filter( "[value='cart']" ).text( 'Add ' + totalProducts + ' product to cart' );
			$btn.filter( "[value='quote']" ).text( 'Request quote for  ' + totalProducts + ' product' );
		} else {
			$btn.filter( "[value='cart']" ).text( 'Select products to add to cart' );
			$btn.filter( "[value='quote']" ).text( 'Select products to request quote' );
		}
	} );

	// region Product table variations
	$productTables.on( 'change', 'select[name*="sfpbk-pt-variation"]', function ( e ) {
		var
			$ro     = $( this ).closest( 'tr[data-product-id]' ),
			sels    = $ro.find( 'select[name*="sfpbk-pt-variation"]' ),
			pid     = $ro.data( 'product-id' ),
			data    = sfpbkProdutVariations[pid],
			attrsID = [],
			price   = data.price,
			prodMsg = 'Not available';

		for ( var i = 0; i < data.attrs.length; i ++ ) {
			var attrVal = sels.filter( '[name="sfpbk-pt-variation[' + pid + '][' + data.attrs[i] + ']"]' ).val();
			attrsID.push( attrVal );
			if ( ! attrVal ) {
				prodMsg = ''; // Don't do Not available message if all attributes are not selected yet
			}
		}

		attrsID = attrsID.join( ',' );

		var match = data.vars[attrsID];

		if ( ! match ) {
			for ( var variationRegex in data.vars ) {
				if ( data.vars.hasOwnProperty( variationRegex ) ) {
					if ( attrsID.match( new RegExp( '^' + variationRegex + '$' ) ) ) {
						match = data.vars[variationRegex];
						break;
					}
				}
			}
		}

		$ro.find( '[name="sfpbk-pt-prods[375]"]' ).prop( 'disabled', true );

		if ( match ) {
			price = match.price;
			prodMsg = match.availability_html;
			$ro.find( '[name="sfpbk-pt-prods[375]"]' ).prop( 'disabled', false );
		}

		$price = $ro.find( '.prod-table-price' );

		$price.html( price );

		if ( prodMsg ) {
			sels.addClass( 'sfpbk-pt-error' );
			$price.html( price + '<div class="sfpbk-pt-error-msg">' + prodMsg + '</div>' );
		} else {
			sels.removeClass( 'sfpbk-pt-error' );
		}

	} );
	// endregion Product table variations

	$productTables.on( 'change', 'input[type=checkbox]', function ( e ) {
		$( this ).closest( 'td' ).find( 'input[name]' ).val( + this.checked ).change();
	} );

	$( 'body' ).on( 'change', '.sfp-blocks-product-hero [data-attribute_name]', function ( e ) {
		var
			$t     = $( this ),
			$block = $t.closest( '.sfp-blocks-product-hero' );

		var
			$form = $t.closest( '.variations_form' ),
			qry   = $form.serialize().split( '&' ),
			vars  = $form.data( 'product_variations' );

		// Filter product attributes
		qry = qry.filter( function ( el ) {
			return el.indexOf( 'attribute_' ) === 0 || el.indexOf( 'attribute_pa_' ) === 0;
		} );

		for ( var i = 0; i < vars.length; i ++ ) {
			var variable = vars[i], matches = true;

			for ( var j = 0; j < qry.length; j ++ ) {
				var attr = qry[j].split( '=' );
				if ( variable.attributes[attr[0]] !== attr[1] ) {
					matches = false;
					break;
				}
			}

			if ( matches ) {
				// Add image
				if ( variable.image ) {
					$block.find( '.product-image img' ).attr( 'src', variable.image.src );
					$block.find( '.product-image img' ).attr( 'srcset', variable.image.srcset );
				}
				break;
			}
		}
	} );

	// region Sales Countdown
	var salesCounter = $( '.sfpbk-sale_countdown' );

	if ( salesCounter.length ) {
		var
			date      = salesCounter.data( 'date-end' ),
			timeParts = ['days', 'hours', 'minutes', 'seconds'],
			timeEls   = {};

		for ( var i = 0; i < timeParts.length; i ++ ) {
			timeEls[timeParts[i]] = {
				circ: salesCounter.find( '.sfpbk-timr-arc-' + timeParts[i] ),
				num : salesCounter.find( '.sfpbk-timr-number-' + timeParts[i] ),
			};
		}

		timeEls['days'].max = 31;
		timeEls['hours'].max = 24;
		timeEls['minutes'].max = 60;
		timeEls['seconds'].max = 60;

		setInterval( function () {
			var
				dt      = new Date(),
				timeNow = Math.floor( dt.getTime() / 1000 ),
				diff    = date - timeNow;
			timeEls['days'].val = Math.floor( diff / (
				60 * 60 * 24
			) );
			timeEls['hours'].val = Math.floor( diff % (
				60 * 60 * 24
			) / (
																					 60 * 60
																				 ) );
			timeEls['minutes'].val = Math.floor( diff % (
				60 * 60
			) / 60 );
			timeEls['seconds'].val = Math.floor( diff % 60 );

			for ( var j = 0; j < timeParts.length; j ++ ) {
				var els = timeEls[timeParts[j]];
				els.circ.attr( 'stroke-dasharray', els.val * 100 / els.max + ',100' );
				els.num.html( els.val );
			}

		}, 1000 );
	}
	// endregion Sales Countdown

	window.sfBlocksSetup = function () {
		$( 'select' ).each( function () {
			var $t = $( this );
			if ( ! $t.closest( '.sfblocks-select-wrap' ).length ) {
				$t.wrap( '<span class="sfblocks-select-wrap"></span>' );
			}
		} );
	};

	window.sfBlocksSetup();

	if ( $( '.sfbk-category-filter' ).length ) {
		CaxtonUtils.addFlexslider( function () {

			function processCategoryFilters() {
				$( '.sfbk-category-filter' ).each( function () {
					var $t = $( this );
					total_width = 0;
					$t.find( 'li' ).each( function () {
						total_width += $( this ).width();
					} );
					if ( total_width < $t.width() ) {
						$t.addClass( 'no-direction-nav' );
					} else {
						$t.removeClass( 'no-direction-nav' );
					}
					avg_width = total_width / $t.find( 'li' ).length;
					$t.data( 'averageItemWidth', avg_width );
				} );

			}

			processCategoryFilters();
			window.addEventListener( 'resize', processCategoryFilters );

			$( '.sfbk-category-filter-slider' ).each( function () {
				var $t = $( this );
				avg_width = $t.data( 'averageItemWidth' );
				console.log( avg_width );

				$t.flexslider( {
					move         : window.innerWidth > avg_width * 2 ? 2 : 1,
					animation    : "slide",
					animationLoop: false,
					slideshow    : false,
					itemWidth    : avg_width + 7,
					itemMargin   : 7,
					controlNav   : false,
					start        : function () {
						$( '.sfbk-category-filter.o-0' ).removeClass( 'o-0' );
					}
				} );
			} );
		} );
	}

	var $slidingTiles = $( '.sfbk-sliti-col-reverse' );

	if ( $slidingTiles.length ) {
		var itemSel = '.sfbk-sliti-item';

		function calculateDimensions( $slidingTile, $slidingItem1 ) {
			var
				$items      = $slidingTile.find( itemSel ),
				$p = $slidingTile.parent(),
				parentheight = $p.height(),
				items       = $items.length / 2, // Items are duplicated
				slideHeight = $slidingTile.height(),
				itemHeight  = $slidingTile.find( itemSel ).height(),
				position    = slideHeight - itemHeight - itemHeight;
			$slidingItem1.css( {marginBottom: position} );
			$slidingTile.sfbkData = {
				position: position,
				positionDelta: position - ( - parentheight ),
				scrollDelta: parentheight - window.innerHeight,
				height: parentheight,
				top: $p.offset().top,
				range: [$slidingTile.offset().top, $slidingTile.offset().top + $slidingTile.height() ],
			};

			console.log( $slidingTile.sfbkData );
		}

		$slidingTiles.each( function () {
			var $slidingTile = $( this );
			var $slidingItem1 = $slidingTile.find( itemSel ).first();
			$slidingTile.parents().css( 'overflow', 'visible' );

			window.$slidingTile = $slidingTile;
			calculateDimensions( $slidingTile, $slidingItem1 );
			setTimeout( function () {
				calculateDimensions( $slidingTile, $slidingItem1 );
			}, 500 );
			setTimeout( function () {
				calculateDimensions( $slidingTile, $slidingItem1 );
			}, 1100 );
			window.addEventListener( 'resize', function ( event ) {
				calculateDimensions( $slidingTile, $slidingItem1 );
			} );
			$( window ).on( 'scroll', function () {
				var scroll = window.scrollY - $slidingTile.sfbkData.top;
				var offset = scroll/$slidingTile.sfbkData.scrollDelta * $slidingTile.sfbkData.positionDelta

				console.log( scroll, offset );

				if( scroll < 0 ) {
					$slidingItem1.css( {marginBottom: $slidingTile.sfbkData.position} );
				} else if ( scroll < $slidingTile.sfbkData.scrollDelta ) {
					$slidingItem1.css( {marginBottom: $slidingTile.sfbkData.position - offset} );
				} else {
					$slidingItem1.css( {marginBottom: $slidingTile.sfbkData.position - $slidingTile.sfbkData.positionDelta} );
				}
			} );

		} );
	}
} );