/**
 * Created by shramee on 9/10/15.
 */
(function( exports, $ ){
	var api = wp.customize;

	api.lib_multi_checkbox = api.Control.extend( {
		ready : function () {
			var $p = this.container,
				$inputs = $p.find( 'input:not(.hidden)' );
			$inputs.change( function () {

				checkbox_values = $p.find( 'input:checked' ).not('.hidden').map(
					function () {
						return this.value;
					}
				).get().join( ',' );

				$p.find( 'input.hidden' ).val( checkbox_values ).trigger( 'change' );

			} );
		}
	} );
	api.controlConstructor['multi-checkbox'] = api.lib_multi_checkbox;

	api.lib_grid = api.Control.extend( {
		ready : function () {
			var $p = this.container,
				$inputs = $p.find( 'select' );

			$inputs.change( function () {
				checkbox_values = $p.find( 'select' ).map(
					function () {
						return this.value;
					}
				).get().join( ',' );

				$p.find( 'input.hidden' ).val( checkbox_values ).trigger( 'change' );

			} );
		}
	} );
	api.controlConstructor['grid'] = api.lib_grid;

	api.sfp_range = api.Control.extend( {
		ready : function () {
			var
				control = this,
				container = control.container,
			$range = container.find( 'input[type="range"]' );
			$range
				.addClass( 'sfp-range-control' )
				.after( $( ' <span class="dashicons dashicons-update"></span>' ) )
				.after( $( ' <input class="sfp-range-number" type="number">' ) )
				.on( 'change input', function() {
					$( this ).siblings( '.sfp-range-number' ).val( this.value );
				} );

			$range.siblings( '.sfp-range-number' ).val( control.setting.get() );

			container.find( '.sfp-range-number' ).change( function() {
				control.setting.set( this.value );
			} );
			container
				.find( '.dashicons' )
				.css('vertical-align', 'middle')
				.click( function () {
					var $t = $( this ),
						$input = $t.siblings( 'input' );
					$input.val( '' );
					control.setting.set( false );
				} );
		}
	} );
	api.controlConstructor['range'] = api.sfp_range;

	$( document ).ready( function () {
		var $blog_layout = $( '#input_storefront-pro-blog-layout' ),
			$grid = $( '#customize-control-storefront-pro-blog-grid' ),
			$post_content = $( 'select[data-customize-setting-link="storefront-pro-blog-content"]' ),
			$ham_lbl = $( '#customize-control-storefront-pro-pri-nav-label' ),
			$nav_style = $( 'select[data-customize-setting-link="storefront-pro-nav-style"]' ),
			$post_tiles = $( '#storefront-pro-blog-layouttiles' ),
			$excert_fields = $(
				'#customize-control-storefront-pro-blog-excerpt-count,' +
				'#customize-control-storefront-pro-blog-excerpt-end,' +
				'#customize-control-storefront-pro-blog-rm-butt-text'
			),
			$sticky_header = $( 'input[data-customize-setting-link="storefront-pro-header-sticky"]' ),
			$sticky_header_fields = $( '#customize-control-storefront-pro-header-hide-until-scroll, #customize-control-storefront-pro-header-sticky-compress' ),
			$showSearch = $( '#customize-control-storefront-pro-show-search-box' ),
			$showSrchFld = $showSearch.find('input'),
			$oldSearchBoxOptions = $( '.customize-control[id^="customize-control-storefront-pro-search-box-"]' );

		toggleNavOptions = function () {
			if ( ! $nav_style.val() ) {
				$showSearch.slideDown();
			} else {
				$showSearch.slideUp();
				$showSrchFld.prop( 'checked', false );
			}
			toggleSearchOptions();
			if ( $nav_style.val() && $nav_style.val().indexOf( 'left-vertical hamburger' ) > - 1 ) {
				$ham_lbl.slideDown();
			} else {
				$ham_lbl.slideUp();
			}
		};
		$nav_style.change( toggleNavOptions );
		setTimeout( toggleNavOptions, 250 );

		toggleSearchOptions = function () {
			if ( $showSrchFld.prop( 'checked' ) ) {
				$oldSearchBoxOptions.slideDown();
			} else {
				$oldSearchBoxOptions.slideUp();
			}
		};
		$showSrchFld.change( toggleSearchOptions );
		setTimeout( toggleSearchOptions, 250 );

		show_hide_grid = function () {
			if ( $blog_layout.find( 'input:checked' ).val() ) {
				$grid.slideDown();
			} else {
				$grid.slideUp();
			}
		};

		show_hide_excerpt_options = function () {
			var $t = $post_tiles,
				$controls = $( '#customize-control-storefront-pro-blog-layout').nextAll('.customize-control:not(#customize-control-storefront-pro-blog-grid)' );
			if ( $t.prop('checked') ) {
				$controls.slideUp();
			} else {
				$controls.slideDown();
			}
		};

		show_hide_post_tiles_options = function () {
			if ( $post_content.val() ) {
				$excert_fields.slideUp();
			} else {
				$excert_fields.slideDown();
			}
		};

		sticky_header_hide_until_scroll = function() {
			if ( $sticky_header.prop('checked') ) {
				$sticky_header_fields.slideDown();
			} else {
				$sticky_header_fields.slideUp();
			}
		};



		$blog_layout.find( 'input' ).change( show_hide_grid );
		show_hide_grid();

		$post_content.change( show_hide_excerpt_options );
		show_hide_excerpt_options();

		$post_tiles.change( show_hide_post_tiles_options );
		show_hide_post_tiles_options();

		$sticky_header.change( sticky_header_hide_until_scroll );
		sticky_header_hide_until_scroll();
	} );
})( wp, jQuery );