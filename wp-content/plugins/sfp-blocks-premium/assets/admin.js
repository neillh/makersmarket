/**
 * Plugin front end scripts
 *
 * @package Storefront_Pro_Blocks
 * @version 1.0.0
 */
(
	function ( $ ) {
		var
			el          = wp.element.createElement,
			createBlock = wp.blocks.createBlock;
		var noCatsInGrid = 'No categories matched, Please make sure you have images on your product categories.';

		function setupSlider( el ) {
			setTimeout( function() {
				CaxtonUtils.addFlexslider( function() {
					jQuery( el ).flexslider().removeClass( 'caxton-slider-pending-setup' );
				} );
			}, 700 )
		}

		function setupFonts( el ) {
			setTimeout( function() {
				CaxtonUtils.loadFonts();
			}, 200 )
		}

		// region Category loops

		CaxtonBlock( {
			id         : 'sfp-blocks/wc-category-grid',
			transforms : {
				from: [
					{
						type     : 'block',
						blocks   : [
							'sfp-blocks/wc-category-square-grid',
						],
						transform: function ( props ) {
							return createBlock( 'sfp-blocks/wc-category-grid', props );
						},
					},
				],
			},
			title      : 'Masonry Product Category Grid',
			icon       : 'layout',
			category   : 'sfp-blocks',
			apiUrl     : function ( props ) {
				var
					attr = props.attributes,
					qry  = '';
				if ( attr['Categories to show'] ) {
					qry += 'include=' + attr['Categories to show']
				}

				return {
					apiData: '/sfp_blocks/v1/category_grid?' + qry,
				};
			},
			apiCallback: function ( props, that ) {
				if ( props.apiData.data && props.apiData.data.items ) {

					var attr = props.attributes, classes;
					classes = props.apiData.data.class + ' sfp-blocks-cat-grid ' + attr['Overlay'] + ' ' + attr['Label Alignment'] + ' ' + attr['Label Position'];

					if ( attr['Full width'] ) {
						classes += ' vw-100';
					}

					if ( 1 > props.apiData.data.items.length ) {
						var stl = 'style="grid-column: 1 / -1;max-width:999px;color:#333;"';
						props.apiData.data.items.push( '<div ' + stl + ' class="caxton-notification">' + noCatsInGrid + '</div>' )
					}

					return Caxton.html2el( props.apiData.data.items.join( '' ), {
						key      : 'product-category-grid',
						className: classes,
						style    : {
							gridAutoRows : attr['Grid row height'] + 'px',
							gridGap      : attr['Grid gap'] + 'px',
							margin       : attr['Grid top/bottom margin'] + 'px ' + attr['Grid right/left margin'] + 'px',
							fontFamily   : attr['Font'],
							letterSpacing: attr['Letter Spacing'] + 'px',
							fontSize     : attr['Font size'] + 'px',
							color        : attr['Text color'],
							textShadow   : that.outputHTML( '{{Text Glow/Shadow}' ).__html,
						},
						onClick  : function ( e ) {
							e.preventDefault();
						}
					} );
				} else {
					return wp.element.createElement( 'div', {
						className: 'caxton-notification',
						key      : 'notice'
					}, 'Loading product categories...' );
				}
			},
			fields     : {
				'Categories to show'    : {
					type   : 'orderedselect',
					options: sfpBlocksProps.cats,
					section: 'Categories',
				},
				'Full width'            : {
					type   : 'toggle',
					section: 'Layout',
				},
				'Overlay'               : {
					type   : 'select',
					options: [
						{value: '', label: 'Overlay on hover',},
						{value: 'overlay-title', label: 'Behind title on hover',},
//						{value: 'overlay-always', label: 'Always overlay',},
						{value: 'overlay-title-always', label: 'Always behind title',},
					],
					section: 'Layout',
				},
				'Grid row height'       : {
					type   : 'range',
					min    : 100,
					max    : 500,
					step   : 10,
					default: '200',
					section: 'Layout',
				},
				'Grid gap'              : {
					type   : 'range',
					min    : 0,
					max    : 50,
					default: 10,
					section: 'Layout',
				},
				'Grid top/bottom margin': {
					type   : 'range',
					min    : 0,
					max    : 250,
					default: 25,
					section: 'Layout',
				},
				'Grid right/left margin': {
					type   : 'range',
					min    : 0,
					max    : 250,
					default: 0,
					section: 'Layout',
				},
				'Label Alignment'       : {
					type   : 'select',
					options: [
						{value: '', label: 'Left',},
						{value: 'tc', label: 'Center',},
						{value: 'tr', label: 'Right',},
					],
					section: 'Layout',
				},
				'Label Position'        : {
					type   : 'select',
					options: [
						{value: 'grid-label-top', label: 'Top',},
						{value: 'grid-label-mid', label: 'Mid',},
						{value: '', label: 'Bottom',},
					],
					section: 'Layout',
				},
				'on-tablet'             : {
					label  : 'Grid columns on tablet',
					type   : 'select',
					options: [
						{value: '', label: 'Same as desktop',},
						{value: 'sfbk-gcols-tab-1', label: 'Single column',},
						{value: 'sfbk-gcols-tab-2', label: '2 columns',},
						{value: 'sfbk-gcols-tab-3', label: '3 columns',},
					],
					section: 'Responsive layout',
				},
				'on-mobile'             : {
					label  : 'Grid columns on mobile',
					type   : 'select',
					options: [
						{value: '', label: 'Single column',},
						{value: 'sfbk-gcols-mob-2', label: '2 columns',},
						{value: 'sfbk-gcols-mob-3', label: '3 columns',},
						{value: '1', label: 'Same as desktop',}, // Backwards compatibility
					],
					section: 'Responsive layout',
				},
				'Font'                  : {
					type   : 'font',
					section: 'Typography',
				},
				'Font size'             : {
					type   : 'range',
					min    : 5,
					max    : 250,
					default: 16,
					section: 'Typography',
				},
				'Letter Spacing'        : {
					type   : 'range',
					max    : 25,
					min    : - 5,
					default: 0,
					section: 'Typography',
				},
				'Text color'            : {
					type   : 'color',
					default: '#fff',
					section: 'Typography',
				},

				'Text Glow/Shadow': {
					type   : 'select',
					options: [
						{value: '', label: 'No shadow/glow',},
						{value: '255,255,255', label: 'Glow',},
						{value: '0,0,0', label: 'Shadow',},
					],
					tpl    : '{{Shadow position}} {{Shadow Blur}} rgba(%s,{{Shadow Strength}})',
					section: 'Text Glow/Shadow',
				},
				'Shadow position' : {
					type   : 'select',
					options: [
						{value: 'calc( -2px + -.05em ) calc( 2px + .03em )', label: 'Far Left',},
						{value: 'calc( -1px + -.03em ) calc( 1px + .01em )', label: 'Left',},
						{value: '0 0', label: 'Center',},
						{value: 'calc( 1px + .03em ) calc( 1px + .01em )', label: 'Right',},
						{value: 'calc( 2px + .05em ) calc( 2px + .03em )', label: 'Far Right',},
					],
					default: '0 0',
					section: 'Text Glow/Shadow',
				},
				'Shadow Blur'     : {
					type   : 'range',
					tpl    : '%spx ',
					default: 3,
					max    : 25,
					section: 'Text Glow/Shadow',
				},
				'Shadow Strength' : {
					type   : 'range',
					min    : .1,
					step   : .1,
					default: .1,
					max    : 1,
					section: 'Text Glow/Shadow',
				},
			},
		} );

		CaxtonBlock( {
			id         : 'sfp-blocks/wc-category-square-grid',
			transforms : {
				from: [
					{
						type     : 'block',
						blocks   : [
							'sfp-blocks/wc-category-grid',
						],
						transform: function ( props ) {
							return createBlock( 'sfp-blocks/wc-category-square-grid', props );
						},
					},
				],
			},
			title      : 'Category Square Grid',
			icon       : 'screenoptions',
			category   : 'sfp-blocks',
			apiUrl     : function ( props ) {
				var
					attr     = props.attributes,
					qry      = '',
					numItems = attr['Grid_rows'] * attr['Grid_columns'];

				numItems = isNaN( numItems ) ? 9 : numItems;

				if ( attr['Hide titles'] ) {
					qry += 'Hide titles=' + attr['Hide titles'] + '&'
				}

				if ( attr['Show Description'] ) {
					qry += 'Show Description=' + attr['Show Description'] + '&'
				}

				if ( attr['Show Price'] ) {
					qry += 'Show Price=' + attr['Show Price'] + '&'
				}

				if ( attr['Categories to show'] ) {
					qry += 'include=' + attr['Categories to show'] + '&'
				}
				qry += 'max_items=' + numItems;
				return {
					apiData: '/sfp_blocks/v1/category_square_grid?' + qry,
				};
			},
			apiCallback: function ( props, that ) {
				if ( props.apiData.data && props.apiData.data.items ) {

					var attr = props.attributes, classes;
					classes = 'sfp-blocks-categories sfbk-squares sfbk-gcols-' + attr['Grid_columns'] + ' ' + attr['Overlay'] + ' ' + attr['Label Alignment'] + ' ' + attr['Label Position'];
					if ( attr['Full width'] ) {
						classes += ' vw-100';
					}
					if ( attr['Drop shadow'] ) {
						classes += ' sfpbk-item-shadow';
					}

					if ( 1 > props.apiData.data.items.length ) {
						var stl = 'style="grid-column: 1 / -1;max-width:999px;color:#333;"';
						props.apiData.data.items.push( '<div ' + stl + ' class="caxton-notification">' + noCatsInGrid + '</div>' )
					}

					return Caxton.html2el( props.apiData.data.items.join( '' ), {
						key      : 'products-grid',
						className: classes,
						style    : {
							gridGap      : attr['Grid gap'] + 'px',
							margin       : attr['Grid top/bottom margin'] + 'px ' + attr['Grid right/left margin'] + 'px',
							fontFamily   : attr['Font'],
							letterSpacing: attr['Letter Spacing'] + 'px',
							fontSize     : attr['Font size'] + 'px',
							color        : attr['Text color'],
							textShadow   : that.outputHTML( '{{Text Glow/Shadow}' ).__html,
						},
						onClick  : function ( e ) {
							e.preventDefault();
						}
					} );
				} else {
					return wp.element.createElement( 'div', {
						className: 'caxton-notification',
						key      : 'notice'
					}, 'Loading product categories...' );
				}
			},
			fields     : {
				'Categories to show'    : {
					type   : 'orderedselect',
					options: sfpBlocksProps.cats,
					section: 'Categories',
				},
				'Hide titles'           : {
					type   : 'toggle',
					section: 'Categories',
				},
				'Full width'            : {
					type   : 'toggle',
					section: 'Layout',
				},
				'Overlay'               : {
					type   : 'select',
					options: [
						{value: '', label: 'Overlay on hover',},
						{value: 'overlay-title', label: 'Behind title on hover',},
//						{value: 'overlay-always', label: 'Always overlay',},
						{value: 'overlay-title-always', label: 'Always behind title',},
					],
					section: 'Layout',
				},
				'Grid_rows'             : {
					label  : 'Grid rows',
					type   : 'range',
					min    : 1,
					max    : 20,
					default: 3,
					section: 'Layout',
				},
				'Grid_columns'          : {
					label  : 'Grid columns',
					type   : 'range',
					min    : 2,
					max    : 4,
					default: 3,
					section: 'Layout',
				},
				'Grid gap'              : {
					type   : 'range',
					min    : 0,
					max    : 50,
					default: 10,
					section: 'Layout',
				},
				'Grid top/bottom margin': {
					type   : 'range',
					min    : 0,
					max    : 250,
					default: 25,
					section: 'Layout',
				},
				'Grid right/left margin': {
					type   : 'range',
					min    : 0,
					max    : 250,
					default: 0,
					section: 'Layout',
				},
				'Drop shadow'           : {
					type   : 'toggle',
					section: 'Layout',
				},
				'Label Alignment'       : {
					type   : 'select',
					options: [
						{value: '', label: 'Left',},
						{value: 'tc', label: 'Center',},
						{value: 'tr', label: 'Right',},
					],
					section: 'Layout',
				},
				'Label Position'        : {
					type   : 'select',
					options: [
						{value: 'grid-label-top', label: 'Top',},
						{value: 'grid-label-mid', label: 'Mid',},
						{value: '', label: 'Bottom',},
					],
					section: 'Layout',
				},
				'on-tablet'             : {
					label  : 'Grid columns on tablet',
					type   : 'select',
					options: [
						{value: '', label: 'Same as desktop',},
						{value: 'sfbk-gcols-tab-1', label: 'Single column',},
						{value: 'sfbk-gcols-tab-2', label: '2 columns',},
						{value: 'sfbk-gcols-tab-3', label: '3 columns',},
					],
					section: 'Responsive layout',
				},
				'on-mobile'             : {
					label  : 'Grid columns on mobile',
					type   : 'select',
					options: [
						{value: '', label: 'Single column',},
						{value: 'sfbk-gcols-mob-2', label: '2 columns',},
						{value: 'sfbk-gcols-mob-3', label: '3 columns',},
						{value: '1', label: 'Same as desktop',}, // Backwards compatibility
					],
					section: 'Responsive layout',
				},
				'Font'                  : {
					type   : 'font',
					section: 'Typography',
				},
				'Font size'             : {
					type   : 'range',
					min    : 5,
					max    : 250,
					default: 16,
					section: 'Typography',
				},
				'Letter Spacing'        : {
					type   : 'range',
					max    : 25,
					min    : - 5,
					default: 0,
					section: 'Typography',
				},
				'Text color'            : {
					type   : 'color',
					default: '#fff',
					section: 'Typography',
				},

				'Text Glow/Shadow': {
					type   : 'select',
					options: [
						{value: '', label: 'No shadow/glow',},
						{value: '255,255,255', label: 'Glow',},
						{value: '0,0,0', label: 'Shadow',},
					],
					section: 'Text Glow/Shadow',
				},
				'Shadow position' : {
					type   : 'select',
					options: [
						{value: 'calc( -2px + -.05em ) calc( 2px + .03em )', label: 'Far Left',},
						{value: 'calc( -1px + -.03em ) calc( 1px + .01em )', label: 'Left',},
						{value: '0 0', label: 'Center',},
						{value: 'calc( 1px + .03em ) calc( 1px + .01em )', label: 'Right',},
						{value: 'calc( 2px + .05em ) calc( 2px + .03em )', label: 'Far Right',},
					],
					default: '0 0',
					section: 'Text Glow/Shadow',
				},
				'Shadow Blur'     : {
					type   : 'range',
					tpl    : '%spx ',
					default: 3,
					max    : 25,
					section: 'Text Glow/Shadow',
				},
				'Shadow Strength' : {
					type   : 'range',
					min    : .1,
					step   : .1,
					default: .1,
					max    : 1,
					section: 'Text Glow/Shadow',
				},
			},
		} );

		// endregion Category loops

		// region Product filters
		CaxtonBlock( {
			id         : 'sfp-blocks/wc-filter-category',
			title      : 'Filter Category',
			icon       : 'filter',
			category   : 'sfp-blocks',
			apiUrl     : function ( props ) {
				var attr = props.attributes, qry = '';
				qry += 'include=' + attr['Categories to show'];
				qry += '&Filter display=' + attr['Filter display'];
				qry += '&Multiple categories=' + attr['Multiple categories'];
				qry += '&Alignment=' + attr['Alignment'];
				qry += '&Background color=' + attr['Background color'];
				qry += '&Text color=' + attr['Text color'];
				return {
					apiData: '/sfp_blocks/v1/filter_category?' + qry,
				};
			},
			apiCallback: function ( props, that ) {
				if ( props.apiData.data ) {

					var attr = props.attributes, classes;
					classes = props.apiData.data.class + ' sfp-blocks-cat-grid ';

					if ( attr['Full width'] ) {
						classes += ' vw-100';
					}

					return Caxton.html2el( props.apiData.data, {
						key      : 'product-category-grid',
						className: classes,
						style    : {
							fontFamily   : attr['Font'],
							letterSpacing: attr['Letter Spacing'] + 'px',
							fontSize     : attr['Font size'] + 'px',
							color        : attr['Text color'],
						},
						onClick  : function ( e ) {
							e.preventDefault();
						}
					} );
				} else {
					return wp.element.createElement( 'div', {
						className: 'caxton-notification',
						key      : 'notice'
					}, 'Loading category filter...' );
				}
			},
			fields     : {
				'Filter display': {
					type   : 'select',
					options: [
						{value: '', label: 'Links'},
						{value: 'sfbk-btn br0', label: 'Boxy'},
						{value: 'sfbk-btn br2', label: 'Rounded'},
						{value: 'sfbk-btn br-pill', label: 'Pills'},
					],
				},

				'Multiple categories': {
					type: 'toggle',
				},

				'Horizontal slider': {
					type: 'toggle',
					help: 'Makes filter categories into a single bar with horizontal left right navigation.',
				},

				'Alignment'              : {
					type   : 'select',
					options: [
						{value: '', label: 'Left'},
						{value: 'center', label: 'Center'},
						{value: 'end', label: 'Right'},
						{value: 'between', label: 'Justify'},
					],
				},
				'Categories to show'     : {
					type   : 'orderedselect',
					options: sfpBlocksProps.cats,
				},
				'Font'                   : {
					type   : 'font',
					section: 'Typography',
				},
				'Font size'              : {
					type   : 'range',
					min    : 5,
					max    : 250,
					default: 16,
					section: 'Typography',
				},
				'Letter Spacing'         : {
					type   : 'range',
					max    : 25,
					min    : - 5,
					default: 0,
					section: 'Typography',
				},
				'Text color'             : {
					type   : 'color',
					default: '#444',
					section: 'Colors',
				},
				'Background color'       : {
					type   : 'color',
					default: '#ccc',
					section: 'Colors',
				},
				'Active text color'      : {
					type   : 'color',
					default: '#444',
					section: 'Colors',
				},
				'Active background color': {
					type   : 'color',
					default: '#eee',
					section: 'Colors',
				},
			},
		} );
		// endregion Product filters

		// region Products loops

		var prodCats = sfpBlocksProps.cats.slice();
		prodCats.splice( 0, 0, {value: '', label: "Any category"} );
		var prodTags = sfpBlocksProps.tags.slice();
		prodTags.splice( 0, 0, {value: '', label: "Any tag"} );

		var allProductBlocks = [
			'sfp-blocks/wc-products-masonry',
			'sfp-blocks/wc-products-grid',
			'sfp-blocks/wc-products-square-grid',
			'sfp-blocks/wc-products-normal-grid',
			'sfp-blocks/wc-products-list',
			'sfp-blocks/wc-products-slider',
			'sfp-blocks/wc-products-flip',
			'sfp-blocks/wc-products-carousel',
			'sfp-blocks/wc-products-table',
		];

		CaxtonBlock( {
			id         : 'sfp-blocks/wc-products-masonry',
			title      : 'Product Flexible Masonry',
			icon       : 'schedule',
			category   : 'sfp-blocks',
			transforms : {
				from: [
					{
						type     : 'block',
						blocks   : allProductBlocks,
						transform: function ( props ) {
							return createBlock( 'sfp-blocks/wc-products-masonry', props );
						},
					},
				],
			},
			apiUrl     : function ( props ) {
				var
					attr = props.attributes,
					qry  = '';

				if ( attr['Hide titles'] ) {
					qry += 'Hide titles=' + attr['Hide titles'] + '&'
				}

				if ( attr['Show Description'] ) {
					qry += 'Show Description=' + attr['Show Description'] + '&'
				}

				if ( attr['Show Price'] ) {
					qry += 'Show Price=' + attr['Show Price'] + '&'
				}

				if ( attr['Pagination'] ) {
					qry += 'Pagination=' + attr['Pagination'] + '&'
				}
				if ( attr['Products to show'] ) {
					qry += 'post__in=' + attr['Products to show'] + '&'
				} else if ( attr['Product Category'] ) {
					qry += 'prod_cat=' + attr['Product Category'] + '&'
					qry += 'featured_products_only=' + attr['featured_products_only'] + '&'
				} else {
					qry += 'featured_products_only=' + attr['featured_products_only'] + '&'
				}
				qry += 'max_items=' + attr.max_items + '&';
				return {
					apiData: '/sfp_blocks/v1/products_masonry?' + qry,
				};
			},
			apiCallback: function ( props, that ) {
				if ( props.apiData.data && props.apiData.data.items ) {

					var attr = props.attributes, classes;
					classes = 'sfp-blocks-products-masonry sfbk-mcols-' + attr.columns + ' ' + attr['Overlay'] + ' ' + attr['Label Alignment'] + ' ' + attr['Label Position'];
					if ( attr['Full width'] ) {
						classes += ' vw-100';
					}
					if ( attr['Drop shadow'] ) {
						classes += ' sfpbk-item-shadow';
					}

					return Caxton.el(
						'div', {},
						Caxton.html2el( props.apiData.data.items.join( '' ), {
							key      : 'products-grid',
							className: classes,
							style    : {
								gridGap      : attr['Gap'] + 'px',
								margin       : attr['Grid top/bottom margin'] + 'px ' + attr['Grid right/left margin'] + 'px',
								fontFamily   : attr['Font'],
								letterSpacing: attr['Letter Spacing'] + 'px',
								fontSize     : attr['Font size'] + 'px',
								color        : attr['Text color'],
								textShadow   : that.outputHTML( '{{Text Glow/Shadow}}' ).__html,
							},
							onClick  : function ( e ) {
								e.preventDefault();
							}
						} ),
						Caxton.html2el( props.apiData.data.pagination, {} )
					);
				} else {
					return wp.element.createElement( 'div', {
						className: 'caxton-notification',
						key      : 'notice'
					}, 'Loading products...' );
				}
			},
			fields     : {
				'inherit_global_query'  : {
					label  : 'Inherit global query',
					type   : 'toggle',
					help   : 'Category and Products to show options will be ignored when this is enabled.',
					section: 'Products',
				},
				'Products to show'      : {
					type   : 'orderedselect',
					options: sfpBlocksProps.prods,
					section: 'Products',
				},
				'Product Category'      : {
					type   : 'select',
					options: prodCats,
					section: 'Products',
				},
				'Pagination'            : {
					type   : 'select',
					options: [
						{value: '', label: 'No pagination'},
						{value: 'links', label: 'Pagination links'},
						{value: 'boxes', label: 'Pagination boxes'},
					],
					section: 'Products',
				},
				'featured_products_only': {
					label  : 'Featured Products only',
					type   : 'toggle',
					section: 'Products',
				},
				'Show Price'            : {
					type   : 'toggle',
					section: 'Products',
				},
				'Show Description'      : {
					type   : 'toggle',
					section: 'Products',
				},
				'Hide titles'           : {
					type   : 'toggle',
					section: 'Products',
				},
				'Full width'            : {
					type   : 'toggle',
					section: 'Layout',
				},
				'Overlay'               : {
					type   : 'select',
					options: [
						{value: '', label: 'Overlay on hover',},
						{value: 'overlay-title', label: 'Behind title on hover',},
//						{value: 'overlay-always', label: 'Always overlay',},
						{value: 'overlay-title-always', label: 'Always behind title',},
					],
					section: 'Layout',
				},
				'max_items'             : {
					label  : 'Maximum products to show',
					type   : 'range',
					default: '12',
					section: 'Products',
				},
				'columns'               : {
					label  : 'Columns',
					type   : 'range',
					min    : 2,
					max    : 5,
					default: 4,
					section: 'Layout',
				},
				'Gap'                   : {
					type   : 'range',
					min    : 0,
					max    : 50,
					default: 10,
					section: 'Layout',
				},
				'Grid top/bottom margin': {
					type   : 'range',
					min    : 0,
					max    : 250,
					default: 25,
					section: 'Layout',
				},
				'Grid right/left margin': {
					type   : 'range',
					min    : 0,
					max    : 250,
					default: 0,
					section: 'Layout',
				},
				'Drop shadow'           : {
					type   : 'toggle',
					section: 'Layout',
				},
				'Label Alignment'       : {
					type   : 'select',
					options: [
						{value: '', label: 'Left',},
						{value: 'tc', label: 'Center',},
						{value: 'tr', label: 'Right',},
					],
					section: 'Layout',
				},
				'Label Position'        : {
					type   : 'select',
					options: [
						{value: 'grid-label-top', label: 'Top',},
						{value: 'grid-label-mid', label: 'Mid',},
						{value: '', label: 'Bottom',},
					],
					section: 'Layout',
				},
				'on-tablet'             : {
					label  : 'Grid columns on tablet',
					type   : 'select',
					default: 'sfbk-mcols-tab-3',
					options: [
						{value: '', label: 'Same as desktop',},
						{value: 'sfbk-mcols-tab-1', label: 'Single column',},
						{value: 'sfbk-mcols-tab-2', label: '2 columns',},
						{value: 'sfbk-mcols-tab-3', label: '3 columns',},
					],
					section: 'Responsive layout',
				},
				'on-mobile'             : {
					label  : 'Grid columns on mobile',
					type   : 'select',
					default: 'sfbk-mcols-mob-2',
					options: [
						{value: '', label: 'Same as desktop',},
						{value: 'sfbk-mcols-mob-1', label: 'Single column',},
						{value: 'sfbk-mcols-mob-2', label: '2 columns',},
						{value: 'sfbk-mcols-mob-3', label: '3 columns',},
					],
					section: 'Responsive layout',
				},
				'Font'                  : {
					type   : 'font',
					section: 'Typography',
				},
				'Font size'             : {
					type   : 'range',
					min    : 5,
					max    : 250,
					default: 16,
					section: 'Typography',
				},
				'Letter Spacing'        : {
					type   : 'range',
					max    : 25,
					min    : - 5,
					default: 0,
					section: 'Typography',
				},
				'Text color'            : {
					type   : 'color',
					default: '#fff',
					section: 'Typography',
				},

				'Text Glow/Shadow': {
					type   : 'select',
					options: [
						{value: '', label: 'No shadow/glow',},
						{value: '255,255,255', label: 'Glow',},
						{value: '0,0,0', label: 'Shadow',},
					],
					tpl    : '{{Shadow position}} {{Shadow Blur}} rgba(%s,{{Shadow Strength}})',
					section: 'Text Glow/Shadow',
				},
				'Shadow position' : {
					type   : 'select',
					options: [
						{value: 'calc( -2px + -.05em ) calc( 2px + .03em )', label: 'Far Left',},
						{value: 'calc( -1px + -.03em ) calc( 1px + .01em )', label: 'Left',},
						{value: '0 0', label: 'Center',},
						{value: 'calc( 1px + .03em ) calc( 1px + .01em )', label: 'Right',},
						{value: 'calc( 2px + .05em ) calc( 2px + .03em )', label: 'Far Right',},
					],
					default: '0 0',
					section: 'Text Glow/Shadow',
				},
				'Shadow Blur'     : {
					type   : 'range',
					tpl    : '%spx ',
					default: 3,
					max    : 25,
					section: 'Text Glow/Shadow',
				},
				'Shadow Strength' : {
					type   : 'range',
					min    : .1,
					step   : .1,
					default: .1,
					max    : 1,
					section: 'Text Glow/Shadow',
				},
			},
		} );

		CaxtonBlock( {
			id         : 'sfp-blocks/wc-products-grid',
			title      : 'Masonry Product Grid',
			icon       : 'layout',
			category   : 'sfp-blocks',
			transforms : {
				from: [
					{
						type     : 'block',
						blocks   : allProductBlocks,
						transform: function ( props ) {
							return createBlock( 'sfp-blocks/wc-products-grid', props );
						},
					},
				],
			},
			apiUrl     : function ( props ) {
				var
					attr = props.attributes,
					qry  = '';

				if ( attr['Show Price'] ) {
					qry += 'Show Price=' + attr['Show Price'] + '&'
				}

				if ( attr['Show Description'] ) {
					qry += 'Show Description=' + attr['Show Description'] + '&'
				}

				if ( attr['Hide titles'] ) {
					qry += 'Hide titles=' + attr['Hide titles'] + '&'
				}


				if ( attr['Pagination'] ) {
					qry += 'Pagination=' + attr['Pagination'] + '&'
				}
				if ( attr['Products to show'] ) {
					qry += 'post__in=' + attr['Products to show'] + '&'
				} else if ( attr['Product Category'] ) {
					qry += 'prod_cat=' + attr['Product Category'] + '&'
					qry += 'featured_products_only=' + attr['featured_products_only'] + '&'
				} else {
					qry += 'featured_products_only=' + attr['featured_products_only'] + '&'
				}

				qry += 'max_items=' + attr['max_items'] + '&';

				return {
					apiData: '/sfp_blocks/v1/products_grid?' + qry,
				};
			},
			apiCallback: function ( props, that ) {
				if ( props.apiData.data && props.apiData.data.items ) {

					var attr = props.attributes, classes;
					classes = props.apiData.data.class + ' sfp-blocks-products-grid ' + attr['Label Alignment'] + ' ' + attr['Overlay'] + ' ' + attr['Label Position'];
					if ( attr['Full width'] ) {
						classes += ' vw-100';
					}

					return Caxton.el(
						'div', {},
						Caxton.html2el( props.apiData.data.items.join( '' ), {
							key      : 'products-grid',
							className: classes,
							style    : {
								gridAutoRows : attr['Grid row height'] + 'px',
								gridGap      : attr['Grid gap'] + 'px',
								margin       : attr['Grid top/bottom margin'] + 'px ' + attr['Grid right/left margin'] + 'px',
								fontFamily   : attr['Font'],
								letterSpacing: attr['Letter Spacing'] + 'px',
								fontSize     : attr['Font size'] + 'px',
								color        : attr['Text color'],
								textShadow   : that.outputHTML( '{{Text Glow/Shadow}' ).__html,
							},
							onClick  : function ( e ) {
								e.preventDefault();
							}
						} ),
						Caxton.html2el( props.apiData.data.pagination, {} )
					);
				} else {
					return wp.element.createElement( 'div', {
						className: 'caxton-notification',
						key      : 'notice'
					}, 'Loading products...' );
				}
			},
			fields     : {
				'inherit_global_query'  : {
					label  : 'Inherit global query',
					type   : 'toggle',
					help   : 'Category and Products to show options will be ignored when this is enabled.',
					section: 'Products',
				},
				'Products to show'      : {
					type   : 'orderedselect',
					options: sfpBlocksProps.prods,
					section: 'Products',
				},
				'Product Category'      : {
					type   : 'select',
					options: prodCats,
					section: 'Products',
				},
				'max_items'             : {
					label  : 'Max products (number)',
					type   : 'range',
					min    : 1,
					max    : 72,
					default: 12,
					section: 'Products',
				},
				'Pagination'            : {
					type   : 'select',
					options: [
						{value: '', label: 'No pagination'},
						{value: 'links', label: 'Pagination links'},
						{value: 'boxes', label: 'Pagination boxes'},
					],
					section: 'Products',
				},
				'featured_products_only': {
					label  : 'Featured Products only',
					type   : 'toggle',
					section: 'Products',
				},
				'Show Price'            : {
					type   : 'toggle',
					section: 'Products',
				},
				'Show Description'      : {
					type   : 'toggle',
					section: 'Products',
				},
				'Hide titles'           : {
					type   : 'toggle',
					section: 'Products',
				},
				'Full width'            : {
					type   : 'toggle',
					section: 'Layout',
				},
				'on-mobile'             : {
					label  : 'Keep layout on mobile',
					type   : 'toggle',
					section: 'Layout',
				},
				'Overlay'               : {
					type   : 'select',
					options: [
						{value: '', label: 'Overlay on hover',},
						{value: 'overlay-title', label: 'Behind title on hover',},
//						{value: 'overlay-always', label: 'Always overlay',},
						{value: 'overlay-title-always', label: 'Always behind title',},
					],
					section: 'Layout',
				},
				'Grid row height'       : {
					type   : 'range',
					min    : 100,
					max    : 500,
					step   : 10,
					default: '200',
					section: 'Layout',
				},
				'Grid gap'              : {
					type   : 'range',
					min    : 0,
					max    : 50,
					default: 10,
					section: 'Layout',
				},
				'Grid top/bottom margin': {
					type   : 'range',
					min    : 0,
					max    : 250,
					default: 25,
					section: 'Layout',
				},
				'Grid right/left margin': {
					type   : 'range',
					min    : 0,
					max    : 250,
					default: 0,
					section: 'Layout',
				},
				'Label Alignment'       : {
					type   : 'select',
					options: [
						{value: '', label: 'Left',},
						{value: 'tc', label: 'Center',},
						{value: 'tr', label: 'Right',},
					],
					section: 'Layout',
				},
				'Label Position'        : {
					type   : 'select',
					options: [
						{value: 'grid-label-top', label: 'Top',},
						{value: 'grid-label-mid', label: 'Mid',},
						{value: '', label: 'Bottom',},
					],
					section: 'Layout',
				},
				'on-tablet'             : {
					label  : 'Grid columns on tablet',
					type   : 'select',
					options: [
						{value: '', label: 'Same as desktop',},
						{value: 'sfbk-gcols-tab-1', label: 'Single column',},
						{value: 'sfbk-gcols-tab-2', label: '2 columns',},
						{value: 'sfbk-gcols-tab-3', label: '3 columns',},
					],
					section: 'Responsive layout',
				},
				'on-mobile'             : {
					label  : 'Grid columns on mobile',
					type   : 'select',
					options: [
						{value: '', label: 'Single column',},
						{value: 'sfbk-gcols-mob-2', label: '2 columns',},
						{value: 'sfbk-gcols-mob-3', label: '3 columns',},
						{value: '1', label: 'Same as desktop',}, // Backwards compatibility
					],
					section: 'Responsive layout',
				},
				'Font'                  : {
					type   : 'font',
					section: 'Typography',
				},
				'Font size'             : {
					type   : 'range',
					min    : 5,
					max    : 250,
					default: 16,
					section: 'Typography',
				},
				'Letter Spacing'        : {
					type   : 'range',
					max    : 25,
					min    : - 5,
					default: 0,
					section: 'Typography',
				},
				'Text color'            : {
					type   : 'color',
					default: '#fff',
					section: 'Typography',
				},
				'Text Glow/Shadow'      : {
					type   : 'select',
					options: [
						{value: '', label: 'No shadow/glow',},
						{value: '255,255,255', label: 'Glow',},
						{value: '0,0,0', label: 'Shadow',},
					],
					tpl    : '{{Shadow position}} {{Shadow Blur}} rgba(%s,{{Shadow Strength}})',
					section: 'Text Glow/Shadow',
				},
				'Shadow position'       : {
					type   : 'select',
					options: [
						{value: 'calc( -2px + -.05em ) calc( 2px + .03em )', label: 'Far Left',},
						{value: 'calc( -1px + -.03em ) calc( 1px + .01em )', label: 'Left',},
						{value: '0 0', label: 'Center',},
						{value: 'calc( 1px + .03em ) calc( 1px + .01em )', label: 'Right',},
						{value: 'calc( 2px + .05em ) calc( 2px + .03em )', label: 'Far Right',},
					],
					default: '0 0',
					section: 'Text Glow/Shadow',
				},
				'Shadow Blur'           : {
					type   : 'range',
					tpl    : '%spx ',
					default: 3,
					max    : 25,
					section: 'Text Glow/Shadow',
				},
				'Shadow Strength'       : {
					type   : 'range',
					min    : .1,
					step   : .1,
					default: .1,
					max    : 1,
					section: 'Text Glow/Shadow',
				},
			},
		} );

		CaxtonBlock( {
			id         : 'sfp-blocks/wc-products-square-grid',
			title      : 'Product Square Grid',
			icon       : 'screenoptions',
			category   : 'sfp-blocks',
			transforms : {
				from: [
					{
						type     : 'block',
						blocks   : allProductBlocks,
						transform: function ( props ) {
							return createBlock( 'sfp-blocks/wc-products-square-grid', props );
						},
					},
				],
			},
			apiUrl     : function ( props ) {
				var
					attr     = props.attributes,
					qry      = '',
					numItems = attr['Grid_rows'] * attr['Grid_columns'];

				numItems = isNaN( numItems ) ? 9 : numItems;

				if ( attr['Hide titles'] ) {
					qry += 'Hide titles=' + attr['Hide titles'] + '&'
				}

				if ( attr['Show Description'] ) {
					qry += 'Show Description=' + attr['Show Description'] + '&'
				}

				if ( attr['Show Price'] ) {
					qry += 'Show Price=' + attr['Show Price'] + '&'
				}

				if ( attr['Pagination'] ) {
					qry += 'Pagination=' + attr['Pagination'] + '&'
				}
				if ( attr['Products to show'] ) {
					qry += 'post__in=' + attr['Products to show'] + '&'
				} else if ( attr['Product Category'] ) {
					qry += 'prod_cat=' + attr['Product Category'] + '&'
					qry += 'featured_products_only=' + attr['featured_products_only'] + '&'
				} else {
					qry += 'featured_products_only=' + attr['featured_products_only'] + '&'
				}
				qry += 'max_items=' + numItems;
				return {
					apiData: '/sfp_blocks/v1/products_grid?' + qry,
				};
			},
			apiCallback: function ( props, that ) {
				if ( props.apiData.data && props.apiData.data.items ) {

					var attr = props.attributes, classes;
					classes = 'sfp-blocks-products sfbk-squares sfbk-gcols-' + attr['Grid_columns'] + ' ' + attr['Overlay'] + ' ' + attr['Label Alignment'] + ' ' + attr['Label Position'];
					if ( attr['Full width'] ) {
						classes += ' vw-100';
					}
					if ( attr['Drop shadow'] ) {
						classes += ' sfpbk-item-shadow';
					}

					return Caxton.el(
						'div', {},
						Caxton.html2el( props.apiData.data.items.join( '' ), {
							key      : 'products-grid',
							className: classes,
							style    : {
								gridGap      : attr['Grid gap'] + 'px',
								margin       : attr['Grid top/bottom margin'] + 'px ' + attr['Grid right/left margin'] + 'px',
								fontFamily   : attr['Font'],
								letterSpacing: attr['Letter Spacing'] + 'px',
								fontSize     : attr['Font size'] + 'px',
								color        : attr['Text color'],
								textShadow   : that.outputHTML( '{{Text Glow/Shadow}}' ).__html,
							},
							onClick  : function ( e ) {
								e.preventDefault();
							}
						} ),
						Caxton.html2el( props.apiData.data.pagination, {} )
					);
				} else {
					return wp.element.createElement( 'div', {
						className: 'caxton-notification',
						key      : 'notice'
					}, 'Loading products...' );
				}
			},
			fields     : {
				'inherit_global_query'  : {
					label  : 'Inherit global query',
					type   : 'toggle',
					help   : 'Category and Products to show options will be ignored when this is enabled.',
					section: 'Products',
				},
				'Products to show'      : {
					type   : 'orderedselect',
					options: sfpBlocksProps.prods,
					section: 'Products',
				},
				'Product Category'      : {
					type   : 'select',
					options: prodCats,
					section: 'Products',
				},
				'Pagination'            : {
					type   : 'select',
					options: [
						{value: '', label: 'No pagination'},
						{value: 'links', label: 'Pagination links'},
						{value: 'boxes', label: 'Pagination boxes'},
					],
					section: 'Products',
				},
				'featured_products_only': {
					label  : 'Featured Products only',
					type   : 'toggle',
					section: 'Products',
				}, 'Show Price'         : {
					type   : 'toggle',
					section: 'Products',
				},
				'Show Description'      : {
					type   : 'toggle',
					section: 'Products',
				},
				'Hide titles'           : {
					type   : 'toggle',
					section: 'Products',
				},
				'Full width'            : {
					type   : 'toggle',
					section: 'Layout',
				},
				'Overlay'               : {
					type   : 'select',
					options: [
						{value: '', label: 'Overlay on hover',},
						{value: 'overlay-title', label: 'Behind title on hover',},
//						{value: 'overlay-always', label: 'Always overlay',},
						{value: 'overlay-title-always', label: 'Always behind title',},
					],
					section: 'Layout',
				},
				'Grid_rows'             : {
					label  : 'Grid rows',
					type   : 'range',
					min    : 1,
					max    : 20,
					default: 3,
					section: 'Layout',
				},
				'Grid_columns'          : {
					label  : 'Grid columns',
					type   : 'range',
					min    : 2,
					max    : 4,
					default: 3,
					section: 'Layout',
				},
				'Grid gap'              : {
					type   : 'range',
					min    : 0,
					max    : 50,
					default: 10,
					section: 'Layout',
				},
				'Grid top/bottom margin': {
					type   : 'range',
					min    : 0,
					max    : 250,
					default: 25,
					section: 'Layout',
				},
				'Grid right/left margin': {
					type   : 'range',
					min    : 0,
					max    : 250,
					default: 0,
					section: 'Layout',
				},
				'Drop shadow'           : {
					type   : 'toggle',
					section: 'Layout',
				},
				'Label Alignment'       : {
					type   : 'select',
					options: [
						{value: '', label: 'Left',},
						{value: 'tc', label: 'Center',},
						{value: 'tr', label: 'Right',},
					],
					section: 'Layout',
				},
				'Label Position'        : {
					type   : 'select',
					options: [
						{value: 'grid-label-top', label: 'Top',},
						{value: 'grid-label-mid', label: 'Mid',},
						{value: '', label: 'Bottom',},
					],
					section: 'Layout',
				},
				'on-tablet'             : {
					label  : 'Grid columns on tablet',
					type   : 'select',
					options: [
						{value: '', label: 'Same as desktop',},
						{value: 'sfbk-gcols-tab-1', label: 'Single column',},
						{value: 'sfbk-gcols-tab-2', label: '2 columns',},
						{value: 'sfbk-gcols-tab-3', label: '3 columns',},
					],
					section: 'Responsive layout',
				},
				'on-mobile'             : {
					label  : 'Grid columns on mobile',
					type   : 'select',
					options: [
						{value: '', label: 'Single column',},
						{value: 'sfbk-gcols-mob-2', label: '2 columns',},
						{value: 'sfbk-gcols-mob-3', label: '3 columns',},
						{value: '1', label: 'Same as desktop',}, // Backwards compatibility
					],
					section: 'Responsive layout',
				},
				'Font'                  : {
					type   : 'font',
					section: 'Typography',
				},
				'Font size'             : {
					type   : 'range',
					min    : 5,
					max    : 250,
					default: 16,
					section: 'Typography',
				},
				'Letter Spacing'        : {
					type   : 'range',
					max    : 25,
					min    : - 5,
					default: 0,
					section: 'Typography',
				},
				'Text color'            : {
					type   : 'color',
					default: '#fff',
					section: 'Typography',
				},

				'Text Glow/Shadow': {
					type   : 'select',
					options: [
						{value: '', label: 'No shadow/glow',},
						{value: '255,255,255', label: 'Glow',},
						{value: '0,0,0', label: 'Shadow',},
					],
					tpl    : '{{Shadow position}} {{Shadow Blur}} rgba(%s,{{Shadow Strength}})',
					section: 'Text Glow/Shadow',
				},
				'Shadow position' : {
					type   : 'select',
					options: [
						{value: 'calc( -2px + -.05em ) calc( 2px + .03em )', label: 'Far Left',},
						{value: 'calc( -1px + -.03em ) calc( 1px + .01em )', label: 'Left',},
						{value: '0 0', label: 'Center',},
						{value: 'calc( 1px + .03em ) calc( 1px + .01em )', label: 'Right',},
						{value: 'calc( 2px + .05em ) calc( 2px + .03em )', label: 'Far Right',},
					],
					default: '0 0',
					section: 'Text Glow/Shadow',
				},
				'Shadow Blur'     : {
					type   : 'range',
					tpl    : '%spx ',
					default: 3,
					max    : 25,
					section: 'Text Glow/Shadow',
				},
				'Shadow Strength' : {
					type   : 'range',
					min    : .1,
					step   : .1,
					default: .1,
					max    : 1,
					section: 'Text Glow/Shadow',
				},
			},
		} );

		CaxtonBlock( {
			id         : 'sfp-blocks/wc-products-sliding-titles-grid',
			title      : 'Product Sliding Tiles',
			icon       : 'sort',
			category   : 'sfp-blocks',
			transforms : {
				from: [
					{
						type     : 'block',
						blocks   : allProductBlocks,
						transform: function ( props ) {
							return createBlock( 'sfp-blocks/wc-products-sliding-titles-grid', props );
						},
					},
				],
			},
			apiUrl     : function ( props ) {
				var
					attr     = props.attributes,
					qry      = '',
					numItems = attr['Grid_rows'] * 2;

				numItems = isNaN( numItems ) ? 10 : numItems;

				var apiProps = [
					'outline_color', 'tile_color', 'title_color', 'price_color', 'a2c_color', 'show_a2c',
					'title_font', 'title_size', 'price_font', 'price_size', 'small_images'
				];

				for ( let i = 0; i < apiProps.length; i ++ ) {
					var prop = apiProps[i];
					if ( attr[prop] ) {
						qry += prop + '=' + encodeURIComponent( attr[prop] ) + '&'
					}
				}


				if ( attr['Products to show'] ) {
					qry += 'post__in=' + attr['Products to show'] + '&'
				} else if ( attr['Product Category'] ) {
					qry += 'prod_cat=' + attr['Product Category'] + '&'
					qry += 'featured_products_only=' + attr['featured_products_only'] + '&'
				} else {
					qry += 'featured_products_only=' + attr['featured_products_only'] + '&'
				}
				qry += 'max_items=' + numItems;

				return {
					apiData: '/sfp_blocks/v1/products_sliding_tiles?' + qry,
				};
			},
			apiCallback: function ( props, that ) {
				if ( props.apiData.data && props.apiData.data.html ) {

					var attr = props.attributes, classes;
					classes = 'sfbk-sliding-tiles sfp-blocks-products border-box cf';

					return Caxton.el(
						'div', {className: 'sfbk-sliti-wrapper'},
						Caxton.html2el( props.apiData.data.html, {
							key      : 'products-grid',
							className: classes,
							ref: setupFonts,
							style    : {
								textShadow   : that.outputHTML( '{{Text Glow/Shadow}}' ).__html,
							},
							onClick  : function ( e ) {
								e.preventDefault();
							}
						} ),
					);
				} else {
					return wp.element.createElement( 'div', {
						className: 'caxton-notification',
						key      : 'notice'
					}, 'Loading products...' );
				}
			},
			fields     : {
				'Products to show'      : {
					type   : 'orderedselect',
					options: sfpBlocksProps.prods,
					section: 'Products',
				},
				'Product Category'      : {
					type   : 'select',
					options: prodCats,
					section: 'Products',
				},
				'featured_products_only': {
					label  : 'Featured Products only',
					type   : 'toggle',
					section: 'Products',
				},
				'Grid_rows'             : {
					label  : 'Grid rows',
					type   : 'range',
					min    : 1,
					max    : 20,
					default: 5,
					section: 'Products',
				},
				'tile_color'                  : {
					label  : 'Tile color',
					type: 'color',
					default: '#fff',
					section: 'Colors'
				},
				'outline_color'                  : {
					label  : 'Outline color',
					type: 'color',
					default: '#aaa',
					section: 'Colors'
				},
				'title_color'                  : {
					label  : 'Product title color',
					type: 'color',
					default: '#777',
					section: 'Colors'
				},
				'price_color'                  : {
					label  : 'Product price color',
					type: 'color',
					default: '#777',
					section: 'Colors'
				},
				'a2c_color'                  : {
					label  : 'Add to cart color',
					type: 'color',
					default: '#777',
					section: 'Colors'
				},
				'show_a2c': {
					label  : 'Show add to cart',
					type   : 'toggle',
					section: 'Layout',
				},
				'small_images': {
					label  : 'Smaller Images',
					type   : 'toggle',
					default: true,
					section: 'Layout',
					help: 'Small images load faster',
				},
				'title_font'                  : {
					label  : 'Product title font',
					type   : 'font',
					section: 'Layout',
				},
				'title_size'                  : {
					label  : 'Product title font size',
					type   : 'range',
					min    : .5,
					step   : .5,
					max    : 10,
					default: 5,
					section: 'Layout',
				},
				'price_font'                  : {
					label  : 'Product price font',
					type   : 'font',
					section: 'Layout',
				},
				'price_size'                  : {
					label  : 'Product price font size',
					type   : 'range',
					min    : .5,
					step   : .5,
					max    : 10,
					default: 3,
					section: 'Layout',
				},
			},
		} );

		CaxtonBlock( {
			id         : 'sfp-blocks/wc-product-cards',
			title      : 'Product Cards',
			icon       : 'grid-view',
			category   : 'sfp-blocks',
			transforms : {
				from: [
					{
						type     : 'block',
						blocks   : allProductBlocks,
						transform: function ( props ) {
							return createBlock( 'sfp-blocks/wc-product-cards', props );
						},
					},
				],
			},
			apiUrl     : function ( props ) {
				var
					attr     = props.attributes,
					qry      = '',
					numItems = attr['Grid_rows'] * attr['Grid_columns'];

				numItems = isNaN( numItems ) ? 9 : numItems;

				if ( attr['Hide titles'] ) {
					qry += 'Hide titles=' + attr['Hide titles'] + '&'
				}

				if ( attr['Show Description'] ) {
					qry += 'Show Description=' + attr['Show Description'] + '&'
				}

				if ( attr['Show Price'] ) {
					qry += 'Show Price=' + attr['Show Price'] + '&'
				}

				if ( attr['Pagination'] ) {
					qry += 'Pagination=' + attr['Pagination'] + '&'
				}
				if ( attr['Products to show'] ) {
					qry += 'post__in=' + attr['Products to show'] + '&'
				} else if ( attr['Product Category'] ) {
					qry += 'prod_cat=' + attr['Product Category'] + '&'
					qry += 'featured_products_only=' + attr['featured_products_only'] + '&'
				} else {
					qry += 'featured_products_only=' + attr['featured_products_only'] + '&'
				}
				qry += 'max_items=' + numItems;
				return {
					apiData: '/sfp_blocks/v1/product_cards?' + qry,
				};
			},
			apiCallback: function ( props, that ) {
				if ( props.apiData.data && props.apiData.data.items ) {

					var attr = props.attributes, classes;
					classes = 'sfp-blocks-products sfbk-cards sfbk-gcols-' + attr['Grid_columns'] + ' ' + attr['Overlay'] + ' ' + attr['Label Alignment'] + ' ' + attr['Label Position'];
					if ( attr['Full width'] ) {
						classes += ' vw-100';
					}
					if ( attr['Drop shadow'] ) {
						classes += ' sfpbk-item-shadow';
					}

					if ( attr['Cards animation'] ) {
						classes += ' ' + attr['Cards animation'];
					}

					return Caxton.el(
						'div', {},
						Caxton.html2el( props.apiData.data.items.join( '' ), {
							key      : 'products-grid',
							className: classes,
							style    : {
								gridGap      : attr['Grid gap'] + 'px',
								margin       : attr['Grid top/bottom margin'] + 'px ' + attr['Grid right/left margin'] + 'px',
								fontFamily   : attr['Font'],
								letterSpacing: attr['Letter Spacing'] + 'px',
								fontSize     : attr['Font size'] + 'px',
								color        : attr['Text color'],
								textShadow   : that.outputHTML( '{{Text Glow/Shadow}}' ).__html,
							},
							onClick  : function ( e ) {
								e.preventDefault();
							}
						} ),
						Caxton.html2el( props.apiData.data.pagination, {} )
					);
				} else {
					return wp.element.createElement( 'div', {
						className: 'caxton-notification',
						key      : 'notice'
					}, 'Loading products...' );
				}
			},
			fields     : {
				'inherit_global_query'  : {
					label  : 'Inherit global query',
					type   : 'toggle',
					help   : 'Category and Products to show options will be ignored when this is enabled.',
					section: 'Products',
				},
				'Products to show'      : {
					type   : 'orderedselect',
					options: sfpBlocksProps.prods,
					section: 'Products',
				},
				'Product Category'      : {
					type   : 'select',
					options: prodCats,
					section: 'Products',
				},
				'Pagination'            : {
					type   : 'select',
					options: [
						{value: '', label: 'No pagination'},
						{value: 'links', label: 'Pagination links'},
						{value: 'boxes', label: 'Pagination boxes'},
					],
					section: 'Products',
				},
				'featured_products_only': {
					label  : 'Featured Products only',
					type   : 'toggle',
					section: 'Products',
				},
				'Show Price'            : {
					type   : 'toggle',
					section: 'Products',
				},
				'Show Description'      : {
					type   : 'toggle',
					section: 'Products',
				},
				'Hide titles'           : {
					type   : 'toggle',
					section: 'Products',
				},
				'Cards animation'       : {
					help   : 'Appear animation for second image',
					section: 'Layout',
					type   : 'select',
					options: [
						{value: '', label: 'Fade',},
						{value: 'sfbk-anim-flip', label: 'Flip',},
					],
				},
				'Full width'            : {
					type   : 'toggle',
					section: 'Layout',
				},
				'Overlay'               : {
					type   : 'select',
					options: [
						{value: '', label: 'Overlay on hover',},
						{value: 'overlay-title', label: 'Behind title on hover',},
//						{value: 'overlay-always', label: 'Always overlay',},
						{value: 'overlay-title-always', label: 'Always behind title',},
					],
					section: 'Layout',
				},
				'Grid_rows'             : {
					label  : 'Grid rows',
					type   : 'range',
					min    : 1,
					max    : 20,
					default: 3,
					section: 'Layout',
				},
				'Grid_columns'          : {
					label  : 'Grid columns',
					type   : 'range',
					min    : 2,
					max    : 4,
					default: 3,
					section: 'Layout',
				},
				'Grid gap'              : {
					type   : 'range',
					min    : 0,
					max    : 50,
					default: 10,
					section: 'Layout',
				},
				'Grid top/bottom margin': {
					type   : 'range',
					min    : 0,
					max    : 250,
					default: 25,
					section: 'Layout',
				},
				'Grid right/left margin': {
					type   : 'range',
					min    : 0,
					max    : 250,
					default: 0,
					section: 'Layout',
				},
				'Drop shadow'           : {
					type   : 'toggle',
					section: 'Layout',
				},
				'Label Alignment'       : {
					type   : 'select',
					options: [
						{value: '', label: 'Left',},
						{value: 'tc', label: 'Center',},
						{value: 'tr', label: 'Right',},
					],
					section: 'Layout',
				},
				'Label Position'        : {
					type   : 'select',
					options: [
						{value: 'grid-label-top', label: 'Top',},
						{value: 'grid-label-mid', label: 'Mid',},
						{value: '', label: 'Bottom',},
					],
					section: 'Layout',
				},
				'on-tablet'             : {
					label  : 'Grid columns on tablet',
					type   : 'select',
					options: [
						{value: '', label: 'Same as desktop',},
						{value: 'sfbk-gcols-tab-1', label: 'Single column',},
						{value: 'sfbk-gcols-tab-2', label: '2 columns',},
						{value: 'sfbk-gcols-tab-3', label: '3 columns',},
					],
					section: 'Responsive layout',
				},
				'on-mobile'             : {
					label  : 'Grid columns on mobile',
					type   : 'select',
					options: [
						{value: '', label: 'Single column',},
						{value: 'sfbk-gcols-mob-2', label: '2 columns',},
						{value: 'sfbk-gcols-mob-3', label: '3 columns',},
						{value: '1', label: 'Same as desktop',}, // Backwards compatibility
					],
					section: 'Responsive layout',
				},
				'Font'                  : {
					type   : 'font',
					section: 'Typography',
				},
				'Font size'             : {
					type   : 'range',
					min    : 5,
					max    : 250,
					default: 16,
					section: 'Typography',
				},
				'Letter Spacing'        : {
					type   : 'range',
					max    : 25,
					min    : - 5,
					default: 0,
					section: 'Typography',
				},
				'Text color'            : {
					type   : 'color',
					default: '#fff',
					section: 'Typography',
				},

				'Text Glow/Shadow': {
					type   : 'select',
					options: [
						{value: '', label: 'No shadow/glow',},
						{value: '255,255,255', label: 'Glow',},
						{value: '0,0,0', label: 'Shadow',},
					],
					tpl    : '{{Shadow position}} {{Shadow Blur}} rgba(%s,{{Shadow Strength}})',
					section: 'Text Glow/Shadow',
				},
				'Shadow position' : {
					type   : 'select',
					options: [
						{value: 'calc( -2px + -.05em ) calc( 2px + .03em )', label: 'Far Left',},
						{value: 'calc( -1px + -.03em ) calc( 1px + .01em )', label: 'Left',},
						{value: '0 0', label: 'Center',},
						{value: 'calc( 1px + .03em ) calc( 1px + .01em )', label: 'Right',},
						{value: 'calc( 2px + .05em ) calc( 2px + .03em )', label: 'Far Right',},
					],
					default: '0 0',
					section: 'Text Glow/Shadow',
				},
				'Shadow Blur'     : {
					type   : 'range',
					tpl    : '%spx ',
					default: 3,
					max    : 25,
					section: 'Text Glow/Shadow',
				},
				'Shadow Strength' : {
					type   : 'range',
					min    : .1,
					step   : .1,
					default: .1,
					max    : 1,
					section: 'Text Glow/Shadow',
				},
			},
		} );

		CaxtonBlock( {
			id         : 'sfp-blocks/wc-products-normal-grid',
			title      : 'Product Normal Grid',
			icon       : 'screenoptions',
			category   : 'sfp-blocks',
			transforms : {
				from: [
					{
						type     : 'block',
						blocks   : allProductBlocks,
						transform: function ( props ) {
							return createBlock( 'sfp-blocks/wc-products-normal-grid', props );
						},
					},
				],
			},
			apiUrl     : function ( props ) {
				var
					attr = props.attributes,
					qry  = '',
					numItems;

				attr['Grid_columns'] = isNaN( attr['Grid_columns'] ) ? 3 : attr['Grid_columns'];
				attr['Grid_rows'] = isNaN( attr['Grid_rows'] ) ? 3 : attr['Grid_rows'];
				numItems = attr['Grid_rows'] * attr['Grid_columns']

				if ( attr['Show Price'] ) {
					qry += 'Show Price=' + attr['Show Price'] + '&';
				}
				if ( attr['show_qty'] ) {
					qry += 'show_qty=' + attr['show_qty'] + '&';
				}
				if ( attr['show_excerpt'] ) {
					qry += 'show_excerpt=' + attr['show_excerpt'] + '&';
				}

				if ( attr['Products to show'] ) {
					qry += 'post__in=' + attr['Products to show'] + '&';
				} else if ( attr['Product Category'] ) {
					qry += 'prod_cat=' + attr['Product Category'] + '&';
					qry += 'featured_products_only=' + attr['featured_products_only'] + '&';
				} else {
					qry += 'featured_products_only=' + attr['featured_products_only'] + '&';
				}
				qry += 'max_items=' + numItems + '&';
				qry += 'Grid_columns=' + attr['Grid_columns'] + '&';
				qry += 'Grid_rows=' + attr['Grid_rows'] + '&';
				return {
					apiData: '/sfp_blocks/v1/products_wc_grid?' + qry,
				};
			},
			apiCallback: function ( props, that ) {
				if ( props.apiData.data ) {
					var attr = props.attributes, classes;
					classes =
						'sfp-blocks-products ' + attr['Text Alignment'] + ' ' +
						attr['Hide price'] + ' ' + attr['Hide add to cart button'] + ' ' + attr['Hide product title'];
					if ( attr['Full width'] ) {
						classes += ' vw-100';
					}

					return Caxton.html2el( props.apiData.data, {
						key      : 'products-grid',
						className: classes,
						style    : {
							margin       : attr['Grid top/bottom margin'] + 'px ' + attr['Grid right/left margin'] + 'px',
							fontFamily   : attr['Font'],
							letterSpacing: attr['Letter Spacing'] + 'px',
							fontSize     : attr['Font size'] + 'px',
							color        : attr['Text color'],
							textShadow   : that.outputHTML( '{{Text Glow/Shadow}}' ).__html,
						},
						onClick  : function ( e ) {
							e.preventDefault();
						}
					} );
				} else {
					return wp.element.createElement( 'div', {
						className: 'caxton-notification',
						key      : 'notice'
					}, 'Loading products...' );
				}
			},
			fields     : {
				'inherit_global_query'  : {
					label  : 'Inherit global query',
					type   : 'toggle',
					help   : 'Category and Products to show options will be ignored when this is enabled.',
					section: 'Products',
				},
				'Products to show'       : {
					type   : 'orderedselect',
					options: sfpBlocksProps.prods,
					section: 'Products',
				},
				'Product Category'       : {
					type   : 'select',
					options: prodCats,
					section: 'Products',
				},
				'Pagination'             : {
					type   : 'select',
					options: [
						{value: '', label: 'No pagination'},
						{value: 'links', label: 'Pagination links'},
						{value: 'boxes', label: 'Pagination boxes'},
					],
					section: 'Products',
				},
				'featured_products_only' : {
					label  : 'Featured Products only',
					type   : 'toggle',
					section: 'Products',
				},
				'Hide price'             : {
					type   : 'toggle',
					section: 'Products',
					value  : 'sfp-blocks-hide_price',
				},
				'Hide add to cart button': {
					type   : 'toggle',
					section: 'Products',
					value  : 'sfp-blocks-hide_button',
				},
				'show_qty'               : {
					label  : 'Show Quantity',
					type   : 'toggle',
					section: 'Products',
				},
				'Hide product title'     : {
					type   : 'toggle',
					section: 'Products',
					value  : 'sfp-blocks-hide_title',
				},
				'show_excerpt'           : {
					label  : 'Show short description',
					type   : 'toggle',
					section: 'Products',
				},
				'Full width'             : {
					type   : 'toggle',
					section: 'Layout',
				},
				'Grid_rows'              : {
					label  : 'Grid rows',
					type   : 'range',
					min    : 1,
					max    : 20,
					default: 3,
					section: 'Layout',
				},
				'Grid_columns'           : {
					label  : 'Grid columns',
					type   : 'range',
					min    : 2,
					max    : 5,
					default: 3,
					section: 'Layout',
				},
				'Grid top/bottom margin' : {
					type   : 'range',
					min    : 0,
					max    : 250,
					default: 25,
					section: 'Layout',
				},
				'Grid right/left margin' : {
					type   : 'range',
					min    : 0,
					max    : 250,
					default: 0,
					section: 'Layout',
				},
				'Text Alignment'         : {
					type   : 'select',
					options: [
						{value: '', label: 'Left',},
						{value: 'tc', label: 'Center',},
						{value: 'tr', label: 'Right',},
					],
					section: 'Layout',
				},
				'Font'                   : {
					type   : 'font',
					section: 'Typography',
				},
				'Font size'              : {
					type   : 'range',
					min    : 5,
					max    : 250,
					default: 16,
					section: 'Typography',
				},
				'Letter Spacing'         : {
					type   : 'range',
					max    : 25,
					min    : - 5,
					default: 0,
					section: 'Typography',
				},
				'Text color'             : {
					type   : 'color',
					default: '#333',
					section: 'Typography',
				},

				'Text Glow/Shadow': {
					type   : 'select',
					options: [
						{value: '', label: 'No shadow/glow',},
						{value: '255,255,255', label: 'Glow',},
						{value: '0,0,0', label: 'Shadow',},
					],
					tpl    : '{{Shadow position}} {{Shadow Blur}} rgba(%s,{{Shadow Strength}})',
					section: 'Text Glow/Shadow',
				},
				'Shadow position' : {
					type   : 'select',
					options: [
						{value: 'calc( -2px + -.05em ) calc( 2px + .03em )', label: 'Far Left',},
						{value: 'calc( -1px + -.03em ) calc( 1px + .01em )', label: 'Left',},
						{value: '0 0', label: 'Center',},
						{value: 'calc( 1px + .03em ) calc( 1px + .01em )', label: 'Right',},
						{value: 'calc( 2px + .05em ) calc( 2px + .03em )', label: 'Far Right',},
					],
					default: '0 0',
					section: 'Text Glow/Shadow',
				},
				'Shadow Blur'     : {
					type   : 'range',
					tpl    : '%spx ',
					default: 3,
					max    : 25,
					section: 'Text Glow/Shadow',
				},
				'Shadow Strength' : {
					type   : 'range',
					min    : .1,
					step   : .1,
					default: .1,
					max    : 1,
					section: 'Text Glow/Shadow',
				},
			},
		} );

		CaxtonContentBlock( {
			id         : 'sfp-blocks/wc-products-viewed-heading',
			title      : 'Products description heading',
			description: 'Blocks added here are displayed only when there are a few recently viewed products.',
			icon       : 'editor-justify',
			category   : 'sfp-blocks',
			template   : [
				[
					'core/heading',
					{content: 'Recently viewed products',}
				]
			],
		} );

		CaxtonBlock( {
			id         : 'sfp-blocks/wc-products-list',
			title      : 'Product List',
			icon       : 'menu-alt',
			category   : 'sfp-blocks',
			transforms : {
				from: [
					{
						type     : 'block',
						blocks   : allProductBlocks,
						transform: function ( props ) {
							props.max_items = props['Grid_columns'] * props['Grid_rows'];
							console.log( props );
							return createBlock( 'sfp-blocks/wc-products-list', props );
						},
					},
				],
			},
			apiUrl     : function ( props ) {
				var
					attr = props.attributes,
					qry  = '';

				if ( attr['Show Price'] ) {
					qry += 'Show Price=' + attr['Show Price'] + '&';
				}

				if ( attr['Show view product button'] ) {
					qry += 'Show_view_product_button=' + attr['Show view product button'] + '&';
				}

				if ( attr['Products to show'] ) {
					qry += 'post__in=' + attr['Products to show'] + '&';
				} else if ( attr['Product Category'] ) {
					qry += 'prod_cat=' + attr['Product Category'] + '&';
					qry += 'featured_products_only=' + attr['featured_products_only'] + '&';
				} else {
					qry += 'featured_products_only=' + attr['featured_products_only'] + '&';
				}

				qry += 'max_items=' + attr['max_items'] + '&';

				return {
					apiData: '/sfp_blocks/v1/products_list?' + qry,
				};
			},
			apiCallback: function ( props, that ) {
				if ( props.apiData.data ) {
					var attr = props.attributes, classes;
					classes =
						'sfp-blocks-products ' + attr['Text Alignment'] + ' ' +
						attr['Hide price'] + ' ' + attr['Hide add to cart button'] + ' ' + attr['Hide product title'];
					if ( attr['Full width'] ) {
						classes += ' vw-100';
					}

					return Caxton.html2el( props.apiData.data, {
						key      : 'products-grid',
						className: classes,
						style    : {
							margin       : attr['Grid top/bottom margin'] + 'px ' + attr['Grid right/left margin'] + 'px',
							fontFamily   : attr['Font'],
							letterSpacing: attr['Letter Spacing'] + 'px',
							fontSize     : attr['Font size'] + 'px',
							color        : attr['Text color'],
							textShadow   : that.outputHTML( '{{Text Glow/Shadow}' ).__html,
						},
						onClick  : function ( e ) {
							e.preventDefault();
						}
					} );
				} else {
					return wp.element.createElement( 'div', {
						className: 'caxton-notification',
						key      : 'notice'
					}, 'Loading products...' );
				}
			},
			fields     : {
				'inherit_global_query'  : {
					label  : 'Inherit global query',
					type   : 'toggle',
					help   : 'Category and Products to show options will be ignored when this is enabled.',
					section: 'Products',
				},
				'Products to show'        : {
					type   : 'orderedselect',
					options: sfpBlocksProps.prods,
					section: 'Products',
				},
				'Product Category'        : {
					type   : 'select',
					options: prodCats,
					section: 'Products',
				},
				'Pagination'              : {
					type   : 'select',
					options: [
						{value: '', label: 'No pagination'},
						{value: 'links', label: 'Pagination links'},
						{value: 'boxes', label: 'Pagination boxes'},
					],
					section: 'Products',
				},
				'featured_products_only'  : {
					label  : 'Featured Products only',
					type   : 'toggle',
					section: 'Products',
				}, 'Hide price'           : {
					type   : 'toggle',
					section: 'Products',
					value  : 'sfp-blocks-hide_price',
				},
				'Hide add to cart button' : {
					type   : 'toggle',
					section: 'Products',
					value  : 'sfp-blocks-hide_button',
				},
				'Show view product button': {
					type   : 'toggle',
					section: 'Products',
					value  : 'sfp-blocks-show_vw_btn',
				},
				'Hide product title'      : {
					type   : 'toggle',
					section: 'Products',
					value  : 'sfp-blocks-hide_title',
				},
				'max_items'               : {
					label  : 'Show products (number)',
					type   : 'range',
					min    : 1,
					max    : 50,
					default: 10,
					section: 'Layout',
				},
				'Full width'              : {
					type   : 'toggle',
					section: 'Layout',
				},
				'Grid top/bottom margin'  : {
					type   : 'range',
					min    : 0,
					max    : 250,
					default: 25,
					section: 'Layout',
				},
				'Grid right/left margin'  : {
					type   : 'range',
					min    : 0,
					max    : 250,
					default: 0,
					section: 'Layout',
				},
				'Text Alignment'          : {
					type   : 'select',
					options: [
						{value: '', label: 'Left',},
						{value: 'tc', label: 'Center',},
						{value: 'tr', label: 'Right',},
					],
					section: 'Layout',
				},
				'Font'                    : {
					type   : 'font',
					section: 'Typography',
				},
				'Font size'               : {
					type   : 'range',
					min    : 5,
					max    : 250,
					default: 16,
					section: 'Typography',
				},
				'Letter Spacing'          : {
					type   : 'range',
					max    : 25,
					min    : - 5,
					default: 0,
					section: 'Typography',
				},
				'Text color'              : {
					type   : 'color',
					default: '#333',
					section: 'Typography',
				},

				'Text Glow/Shadow': {
					type   : 'select',
					options: [
						{value: '', label: 'No shadow/glow',},
						{value: '255,255,255', label: 'Glow',},
						{value: '0,0,0', label: 'Shadow',},
					],
					tpl    : '{{Shadow position}} {{Shadow Blur}} rgba(%s,{{Shadow Strength}})',
					section: 'Text Glow/Shadow',
				},
				'Shadow position' : {
					type   : 'select',
					options: [
						{value: 'calc( -2px + -.05em ) calc( 2px + .03em )', label: 'Far Left',},
						{value: 'calc( -1px + -.03em ) calc( 1px + .01em )', label: 'Left',},
						{value: '0 0', label: 'Center',},
						{value: 'calc( 1px + .03em ) calc( 1px + .01em )', label: 'Right',},
						{value: 'calc( 2px + .05em ) calc( 2px + .03em )', label: 'Far Right',},
					],
					default: '0 0',
					section: 'Text Glow/Shadow',
				},
				'Shadow Blur'     : {
					type   : 'range',
					tpl    : '%spx ',
					default: 3,
					max    : 25,
					section: 'Text Glow/Shadow',
				},
				'Shadow Strength' : {
					type   : 'range',
					min    : .1,
					step   : .1,
					default: .1,
					max    : 1,
					section: 'Text Glow/Shadow',
				},
			},
		} );

		CaxtonBlock( {
			id         : 'sfp-blocks/wc-products-slider',
			title      : 'Product Slider',
			icon       : 'slides',
			category   : 'sfp-blocks',
			transforms : {
				from: [
					{
						type     : 'block',
						blocks   : allProductBlocks,
						transform: function ( props ) {
							return createBlock( 'sfp-blocks/wc-products-slider', props );
						},
					},
				],
			},
			apiUrl     : function ( props ) {
				var
					attr = props.attributes,
					qry  = '';

				if ( attr['Pagination'] ) {
					qry += 'Pagination=' + attr['Pagination'] + '&'
				}
				if ( attr['Products to show'] ) {
					qry += 'post__in=' + attr['Products to show'] + '&'
				} else if ( attr['Product Category'] ) {
					qry += 'prod_cat=' + attr['Product Category'] + '&'
					qry += 'featured_products_only=' + attr['featured_products_only'] + '&'
				} else {
					qry += 'featured_products_only=' + attr['featured_products_only'] + '&'
				}

				if ( attr['Show Price'] ) {
					qry += 'Show Price=' + attr['Show Price'] + '&'
				}

				return {
					apiData: '/sfp_blocks/v1/products_slider?' + qry,
				};
			},
			apiCallback: function ( props, that ) {
				if ( props.apiData.data ) {

					var attr = props.attributes, classes;
					classes = 'sfp-blocks-products caxton-slider ' + attr['Text alignment'];
					if ( attr['Full width'] ) {
						classes += ' vw-100';
					}

					return Caxton.html2el( props.apiData.data, {
						key      : 'products-slider',
						className: classes,
						ref       : setupSlider,
						style    : {
							margin    : attr['Top/Bottom margin'] + 'px ' + attr['Right/Left margin'] + 'px',
							fontFamily: attr['Font'],
							minHeight : attr['Slider height'] + 'px',
							fontSize  : attr['Font size'] + 'px',
							color     : attr['Text color'],
							textShadow: that.outputHTML( '{{Text Glow/Shadow}' ).__html,
						},
						onClick  : function ( e ) {
							e.preventDefault();
						}
					} );
				} else {
					return wp.element.createElement( 'div', {
						className: 'caxton-notification',
						key      : 'notice'
					}, 'Loading products...' );
				}
			},
			fields     : {
				'inherit_global_query'  : {
					label  : 'Inherit global query',
					type   : 'toggle',
					help   : 'Category and Products to show options will be ignored when this is enabled.',
					section: 'Products',
				},
				'Products to show'      : {
					type   : 'orderedselect',
					options: sfpBlocksProps.prods,
					section: 'Products',
				},
				'Product Category'      : {
					type   : 'select',
					options: prodCats,
					section: 'Products',
				},
				'Pagination'            : {
					type   : 'select',
					options: [
						{value: '', label: 'No pagination'},
						{value: 'links', label: 'Pagination links'},
						{value: 'boxes', label: 'Pagination boxes'},
					],
					section: 'Products',
				},
				'featured_products_only': {
					label  : 'Featured Products only',
					type   : 'toggle',
					section: 'Products',
				}, 'Show Price'         : {
					type   : 'toggle',
					section: 'Products',
				},
				'Full width'            : {
					type   : 'toggle',
					section: 'Layout',
				},
				'Slider height'         : {
					type   : 'range',
					min    : 250,
					max    : 1280,
					default: 500,
					section: 'Layout',
				},
				'Top/Bottom margin'     : {
					type   : 'range',
					min    : 0,
					max    : 250,
					default: 25,
					section: 'Layout',
				},
				'Right/Left margin'     : {
					type   : 'range',
					min    : 0,
					max    : 250,
					default: 0,
					section: 'Layout',
				},
				'Text alignment'        : {
					type   : 'select',
					options: [
						{value: '', label: 'Left',},
						{value: 'tc', label: 'Center',},
						{value: 'tr', label: 'Right',},
					],
					section: 'Layout',
				},
				'Font'                  : {
					type   : 'font',
					section: 'Typography',
				},
				'Font size'             : {
					type   : 'range',
					min    : 5,
					max    : 250,
					default: 16,
					section: 'Typography',
				},
				'Text color'            : {
					type   : 'color',
					default: '#fff',
					section: 'Typography',
				},

				'Text Glow/Shadow': {
					type   : 'select',
					options: [
						{value: '', label: 'No shadow/glow',},
						{value: '255,255,255', label: 'Glow',},
						{value: '0,0,0', label: 'Shadow',},
					],
					tpl    : '{{Shadow position}} {{Shadow Blur}} rgba(%s,{{Shadow Strength}})',
					section: 'Text Glow/Shadow',
				},
				'Shadow position' : {
					type   : 'select',
					options: [
						{value: 'calc( -2px + -.05em ) calc( 2px + .03em )', label: 'Far Left',},
						{value: 'calc( -1px + -.03em ) calc( 1px + .01em )', label: 'Left',},
						{value: '0 0', label: 'Center',},
						{value: 'calc( 1px + .03em ) calc( 1px + .01em )', label: 'Right',},
						{value: 'calc( 2px + .05em ) calc( 2px + .03em )', label: 'Far Right',},
					],
					default: '0 0',
					section: 'Text Glow/Shadow',
				},
				'Shadow Blur'     : {
					type   : 'range',
					tpl    : '%spx ',
					default: 3,
					max    : 25,
					section: 'Text Glow/Shadow',
				},
				'Shadow Strength' : {
					type   : 'range',
					min    : .1,
					step   : .1,
					default: .1,
					max    : 1,
					section: 'Text Glow/Shadow',
				},
			},
		} );

		CaxtonBlock( {
			id         : 'sfp-blocks/wc-products-flip',
			title      : 'Product Flip book',
			icon       : 'slides',
			category   : 'sfp-blocks',
			transforms : {
				from: [
					{
						type     : 'block',
						blocks   : allProductBlocks,
						transform: function ( props ) {
							return createBlock( 'sfp-blocks/wc-products-flip', props );
						},
					},
				],
			},
			apiUrl     : function ( props ) {
				var
					attr = props.attributes,
					qry  = '';

				if ( attr['Pagination'] ) {
					qry += 'Pagination=' + attr['Pagination'] + '&'
				}
				if ( attr['Products to show'] ) {
					qry += 'post__in=' + attr['Products to show'] + '&'
				} else if ( attr['Product Category'] ) {
					qry += 'prod_cat=' + attr['Product Category'] + '&'
					qry += 'featured_products_only=' + attr['featured_products_only'] + '&'
				} else {
					qry += 'featured_products_only=' + attr['featured_products_only'] + '&'
				}

				if ( attr['Show Price'] ) {
					qry += 'Show Price=' + attr['Show Price'] + '&'
				}

				return {
					apiData: '/sfp_blocks/v1/products_flip?' + qry,
				};
			},
			apiCallback: function ( props, that ) {
				if ( props.apiData.data ) {

					var attr = props.attributes, classes;
					classes = 'sfp-blocks-products caxton-slider caxton-slider-pending-setup ' + attr['Text alignment'];
					if ( attr['Full width'] ) {
						classes += ' vw-100';
					}

					return wp.element.createElement(
						'div',
						{className: 'caxton-slider-wrap sfbk-flip-wrap'},
						Caxton.html2el( props.apiData.data, {
							key      : 'products-slider',
							className: classes,
							ref      : setupSlider,
							style    : {
								margin    : attr['Top/Bottom margin'] + 'px ' + attr['Right/Left margin'] + 'px',
								fontFamily: attr['Font'],
								minHeight : attr['Slider height'] + 'px',
								fontSize  : attr['Font size'] + 'px',
								color     : attr['Text color'],
								textShadow: that.outputHTML( '{{Text Glow/Shadow}' ).__html,
							},
							onClick  : function ( e ) {
								e.preventDefault();
							}
						} )
					);
				} else {
					return wp.element.createElement( 'div', {
						className: 'caxton-notification',
						key      : 'notice'
					}, 'Loading products...' );
				}
			},
			fields     : {
				'inherit_global_query'  : {
					label  : 'Inherit global query',
					type   : 'toggle',
					help   : 'Category and Products to show options will be ignored when this is enabled.',
					section: 'Products',
				},
				'Products to show'      : {
					type   : 'orderedselect',
					options: sfpBlocksProps.prods,
					section: 'Products',
				},
				'Product Category'      : {
					type   : 'select',
					options: prodCats,
					section: 'Products',
				},
				'Pagination'            : {
					type   : 'select',
					options: [
						{value: '', label: 'No pagination'},
						{value: 'links', label: 'Pagination links'},
						{value: 'boxes', label: 'Pagination boxes'},
					],
					section: 'Products',
				},
				'featured_products_only': {
					label  : 'Featured Products only',
					type   : 'toggle',
					section: 'Products',
				},
				'Show Price'            : {
					type   : 'toggle',
					section: 'Products',
					default: '1',
				},
				'Full width'            : {
					type   : 'toggle',
					section: 'Layout',
				},
				'Slider height'         : {
					type   : 'range',
					min    : 250,
					max    : 1280,
					default: 500,
					section: 'Layout',
				},
				'Top/Bottom margin'     : {
					type   : 'range',
					min    : 0,
					max    : 250,
					default: 25,
					section: 'Layout',
				},
				'Right/Left margin'     : {
					type   : 'range',
					min    : 0,
					max    : 250,
					default: 0,
					section: 'Layout',
				},
				'Text alignment'        : {
					type   : 'select',
					options: [
						{value: '', label: 'Left',},
						{value: 'tc', label: 'Center',},
						{value: 'tr', label: 'Right',},
					],
					section: 'Layout',
				},
				'Font'                  : {
					type   : 'font',
					section: 'Typography',
				},
				'Font size'             : {
					type   : 'range',
					min    : 5,
					max    : 250,
					default: 16,
					section: 'Typography',
				},
				'Text color'            : {
					type   : 'color',
					section: 'Typography',
				},

				'Text Glow/Shadow': {
					type   : 'select',
					options: [
						{value: '', label: 'No shadow/glow',},
						{value: '255,255,255', label: 'Glow',},
						{value: '0,0,0', label: 'Shadow',},
					],
					tpl    : '{{Shadow position}} {{Shadow Blur}} rgba(%s,{{Shadow Strength}})',
					section: 'Text Glow/Shadow',
				},
				'Shadow position' : {
					type   : 'select',
					options: [
						{value: 'calc( -2px + -.05em ) calc( 2px + .03em )', label: 'Far Left',},
						{value: 'calc( -1px + -.03em ) calc( 1px + .01em )', label: 'Left',},
						{value: '0 0', label: 'Center',},
						{value: 'calc( 1px + .03em ) calc( 1px + .01em )', label: 'Right',},
						{value: 'calc( 2px + .05em ) calc( 2px + .03em )', label: 'Far Right',},
					],
					default: '0 0',
					section: 'Text Glow/Shadow',
				},
				'Shadow Blur'     : {
					type   : 'range',
					tpl    : '%spx ',
					default: 3,
					max    : 25,
					section: 'Text Glow/Shadow',
				},
				'Shadow Strength' : {
					type   : 'range',
					min    : .1,
					step   : .1,
					default: .1,
					max    : 1,
					section: 'Text Glow/Shadow',
				},
			},
		} );

		CaxtonBlock( {
			id         : 'sfp-blocks/wc-products-carousel',
			title      : 'Product Carousel',
			icon       : 'slides',
			category   : 'sfp-blocks',
			transforms : {
				from: [
					{
						type     : 'block',
						blocks   : allProductBlocks,
						transform: function ( props ) {
							return createBlock( 'sfp-blocks/wc-products-carousel', props );
						},
					},
				],
			},
			apiUrl     : function ( props ) {
				var
					attr = props.attributes,
					qry  = '';

				if ( attr['Pagination'] ) {
					qry += 'Pagination=' + attr['Pagination'] + '&'
				}
				if ( attr['Products to show'] ) {
					qry += 'post__in=' + attr['Products to show'] + '&'
				} else if ( attr['Product Category'] ) {
					qry += 'prod_cat=' + attr['Product Category'] + '&'
					qry += 'featured_products_only=' + attr['featured_products_only'] + '&'
				} else {
					qry += 'featured_products_only=' + attr['featured_products_only'] + '&'
				}

				if ( attr['Show Price'] ) {
					qry += 'Show Price=' + attr['Show Price'] + '&'
				}

				return {
					apiData: '/sfp_blocks/v1/products_carousel?' + qry,
				};
			},
			apiCallback: function ( props, that ) {
				if ( props.apiData.data ) {

					var attr = props.attributes, classes;
					classes = 'sfp-blocks-products caxton-slider caxton-carousel caxton-carousel-pending-setup tc';

					setTimeout( CaxtonUtils.flexslider, 700 );

					var $slider = Caxton.html2el( props.apiData.data, {
						key      : 'products-carousel',
						className: classes,
						ref       : setupSlider,
						style    : {
							margin    : attr['Top/Bottom margin'] + 'px ' + attr['Right/Left margin'] + 'px',
							fontFamily: attr['Font'],
							fontSize  : attr['Font size'] + 'px',
							color     : attr['Text color'],
							textShadow: that.outputHTML( '{{Text Glow/Shadow}' ).__html,
						},
						onClick  : function ( e ) {
							e.preventDefault();
						}
					} );

					if ( attr['Full width'] ) {
						return wp.element.createElement( 'div', {className: 'caxton-slider-wrap vw-100', key: 'wrap'}, $slider );
					}
					return wp.element.createElement( 'div', {className: 'caxton-slider-wrap', key: 'wrap'}, $slider );
				} else {
					return wp.element.createElement( 'div', {
						className: 'caxton-notification',
						key      : 'notice'
					}, 'Loading products...' );
				}
			},
			fields     : {
				'inherit_global_query'  : {
					label  : 'Inherit global query',
					type   : 'toggle',
					help   : 'Category and Products to show options will be ignored when this is enabled.',
					section: 'Products',
				},
				'Products to show'      : {
					type   : 'orderedselect',
					options: sfpBlocksProps.prods,
					section: 'Products',
				},
				'Product Category'      : {
					type   : 'select',
					options: prodCats,
					section: 'Products',
				},
				'Pagination'            : {
					type   : 'select',
					options: [
						{value: '', label: 'No pagination'},
						{value: 'links', label: 'Pagination links'},
						{value: 'boxes', label: 'Pagination boxes'},
					],
					section: 'Products',
				},
				'featured_products_only': {
					label  : 'Featured Products only',
					type   : 'toggle',
					section: 'Products',
				}, 'Show Price'         : {
					type   : 'toggle',
					section: 'Products',
				},
				'Full width'            : {
					type   : 'toggle',
					section: 'Layout',
				},
				'Top/Bottom margin'     : {
					type   : 'range',
					min    : 0,
					max    : 250,
					default: 25,
					section: 'Layout',
				},
				'Right/Left margin'     : {
					type   : 'range',
					min    : 0,
					max    : 250,
					default: 0,
					section: 'Layout',
				},
				'Font'                  : {
					type   : 'font',
					section: 'Typography',
				},
				'Font size'             : {
					type   : 'range',
					min    : 5,
					max    : 250,
					default: 16,
					section: 'Typography',
				},
				'Text color'            : {
					type   : 'color',
					default: '#333',
					section: 'Typography',
				},

				'Text Glow/Shadow': {
					type   : 'select',
					options: [
						{value: '', label: 'No shadow/glow',},
						{value: '255,255,255', label: 'Glow',},
						{value: '0,0,0', label: 'Shadow',},
					],
					tpl    : '{{Shadow position}} {{Shadow Blur}} rgba(%s,{{Shadow Strength}})',
					section: 'Text Glow/Shadow',
				},
				'Shadow position' : {
					type   : 'select',
					options: [
						{value: 'calc( -2px + -.05em ) calc( 2px + .03em )', label: 'Far Left',},
						{value: 'calc( -1px + -.03em ) calc( 1px + .01em )', label: 'Left',},
						{value: '0 0', label: 'Center',},
						{value: 'calc( 1px + .03em ) calc( 1px + .01em )', label: 'Right',},
						{value: 'calc( 2px + .05em ) calc( 2px + .03em )', label: 'Far Right',},
					],
					default: '0 0',
					section: 'Text Glow/Shadow',
				},
				'Shadow Blur'     : {
					type   : 'range',
					tpl    : '%spx ',
					default: 3,
					max    : 25,
					section: 'Text Glow/Shadow',
				},
				'Shadow Strength' : {
					type   : 'range',
					min    : .1,
					step   : .1,
					default: .1,
					max    : 1,
					section: 'Text Glow/Shadow',
				},
			},
		} );

		function tableColumns() {
			var colsOptions = [];
			var cols = sfpBlocksProps.tableCols;
			for ( var col in cols ) {
				colsOptions.push( {value: col, label: cols[col]} );
			}
			return colsOptions;
		}

		CaxtonBlock( {
			id         : 'sfp-blocks/wc-products-table',
			title      : 'Products table',
			icon       : 'editor-table',
			category   : 'sfp-blocks',
			apiUrl     : function ( props ) {
				var
					attr = props.attributes,
					qry  = '';

				if ( attr['Show Price'] ) {
					qry += 'Show Price=' + attr['Show Price'] + '&';
				}

				if ( attr['Circle images'] ) {
					qry += 'Circle images=' + attr['Circle images'] + '&';
				}
				if ( attr['max_items'] ) {
					qry += 'max_items=' + attr['max_items'] + '&';
				}
				if ( attr['Columns'] ) {
					qry += 'Columns=' + attr['Columns'] + '&';
				} else {
					if ( attr['Show Stock Status'] ) {
						qry += 'Show Stock Status=' + attr['Show Stock Status'] + '&';
					}
					if ( attr['Show Rating'] ) {
						qry += 'Show Rating=' + attr['Show Rating'] + '&';
					}
					if ( attr['Show Filters'] ) {
						qry += 'Show Filters=' + attr['Show Filters'] + '&';
					}
					if ( attr['Show Description'] ) {
						qry += 'Show Description=' + attr['Show Description'] + '&';
					}
				}
				if ( attr['Add to cart'] ) {
					qry += 'Add to cart=' + attr['Add to cart'] + '&';
				}
				if ( attr['Request quote'] ) {
					qry += 'Request quote=' + attr['Request quote'] + '&';
				}

				if ( attr['Products to show'] ) {
					qry += 'post__in=' + attr['Products to show'] + '&'
				} else if ( attr['Product Tags'] ) {
					qry += 'prod_tag=' + attr['Product Tags'] + '&'
					qry += 'featured_products_only=' + attr['featured_products_only'] + '&'
				} else {
					qry += 'featured_products_only=' + attr['featured_products_only'] + '&'
				}

				if ( attr['Product Category'] ) {
					qry += 'prod_cat=' + attr['Product Category'] + '&'
					qry += 'featured_products_only=' + attr['featured_products_only'] + '&'
				}

				return {
					apiData: '/sfp_blocks/v1/products_table?' + qry,
				};
			},
			apiCallback: function ( props, that ) {
				if ( props.apiData.data ) {

					var attr = props.attributes, classes;
					classes = props.apiData.data.class + ' product-table-block-products-table';

					return Caxton.html2el( props.apiData.data, {
						key      : 'products-table',
						className: classes,
						style    : {
							fontFamily   : attr['Font'],
							letterSpacing: attr['Letter Spacing'] + 'px',
							fontSize     : attr['Font size'] + 'px',
							color        : attr['Text color'],
						},
						onClick  : function ( e ) {
							e.preventDefault();
						}
					} );
				} else {
					return wp.element.createElement( 'div', {
						className: 'caxton-notification',
						key      : 'notice'
					}, 'Loading products...' );
				}
			},
			fields     : {
				'inherit_global_query'  : {
					label  : 'Inherit global query',
					type   : 'toggle',
					help   : 'Category and Products to show options will be ignored when this is enabled.',
					section: 'Products',
				},
				'Products to show'      : {
					type   : 'orderedselect',
					options: sfpBlocksProps.prods,
					section: 'Products',
				},
				'max_items'             : {
					label  : 'Maximum products to show',
					type   : 'range',
					default: '12',
					section: 'Products',
				},
				'Product Category'      : {
					type   : 'select',
					options: prodCats,
					section: 'Products',
				},
				'Product Tags'          : {
					type   : 'select',
					options: prodTags,
					section: 'Products',
				},
				'featured_products_only': {
					label  : 'Featured Products only',
					type   : 'toggle',
					section: 'Products',
				},
				'Circle images'         : {
					type   : 'toggle',
					section: 'Display',
					default: '',
				},
				'Show Filters'          : {
					type   : 'toggle',
					section: 'Display',
					default: 1,
				},
				'Columns'               : {
					type   : 'orderedselect',
					section: 'Display',
					default: 'img,name,description,stock,rating,price',
					options: tableColumns(),
				},
				'Add to cart'           : {
					type   : 'toggle',
					value  : 'cart',
					section: 'Display',
					default: 'cart',
				},
				'Request quote'         : {
					type   : 'toggle',
					value  : 'quote',
					section: 'Display',
					default: '',
				},
				'Font'                  : {
					type   : 'font',
					section: 'Typography',
				},
				'Font size'             : {
					type   : 'range',
					min    : 5,
					max    : 250,
					default: 16,
					section: 'Typography',
				},
				'Letter Spacing'        : {
					type   : 'range',
					max    : 25,
					min    : - 5,
					default: 0,
					section: 'Typography',
				},
				'Text color'            : {
					type   : 'color',
					default: '#444',
					section: 'Typography',
				},
			},
		} );

		// endregion Products loops

		// region Product hero
		CaxtonBlock( {
			id         : 'sfp-blocks/wc-product-hero',
			title      : 'Single Product',
			icon       : 'archive',
			category   : 'sfp-blocks',
			attributes : {
				tpl: {
					type: 'string'
				},
			},
			apiUrl     : function ( props ) {
				var attr = props.attributes;
				return {
					apiData: '/sfp_blocks/v1/product_hero?' + (
						attr['Product'] ? 'post__in=' + attr['Product'] : ''
					),
				};
			},
			apiCallback: function ( props, that ) {

				var
					attr    = props.attributes, classes,
					baseTpl =
						'<div class="col-full flex justify-center items-center">' +
						'<div class="mh3 product-image">' +
						'{{thumb-img}}' +
						'</div>' +
						'<div class="mh3 product-info">' +
						'<h2>{{title}}</h2>' +
						'{{price}}{{excerpt}}{{a2c}}{{meta}}' +
						'</div>' +
						'</div>';

				if ( props.apiData.data ) {
					classes =
						'sfp-blocks-product-hero frontend-preview relative ' +
						'{{Text Alignment}} {{Image}} {{Content position}} {{Full width}} {{Full height}}';
					if ( attr['Full width'] ) {
						classes += '';
					}

					var allHTML =
								'<div class="' + classes + '"' +
								'style="{{Font}}{{Letter Spacing}}{{Font size}}{{Text color}}{{Shadow position}};">' +
								'<div key="bg" class="absolute absolute--fill {{Background Blur}}">{{Background}}</div>' +
								'<article key="product" class="pv5">%product_tpl%</article>' +
								'</div>';

					// Populate styles
					allHTML = that.outputHTML( allHTML ).__html;

					allHTML = allHTML.replace( '%product_tpl%', '<div class="relative">' + baseTpl + '</div>' );

					if ( !props.attributes.tpl || props.attributes.tpl !== allHTML ) {
						props.setAttributes( {tpl: allHTML} ); // Save as tpl
					}

					// Insert
					allHTML = Caxton.tplProc( allHTML, props.apiData.data );

					setTimeout( window.sfBlocksSetup, 700 );

					return wp.element.createElement(
						'div',
						{
							key    : 'hero-wrap',
							onClick: function ( e ) { e.preventDefault(); }
						},
						Caxton.html2el(
							allHTML,
							{className: 'relative', key: 'hero-html'}
						)
					);
				} else {
					return wp.element.createElement( 'div', {
						className: 'caxton-notification',
						key      : 'notice'
					}, 'Loading product data...' );
				}
			},
			fields     : {
				'Product'         : {
					type    : 'orderedselect',
					options : sfpBlocksProps.prods,
					multiple: false,
					section : 'Product',
				},
				// region BG
				'Background'      : {
					section: 'Background',
					type   : 'background',
				},
				'Background Blur' : {
					type   : 'toggle',
					section: 'Background',
					value  : 'blur-children',
				},
				// endregion BG
				'Full width'      : {
					type   : 'toggle',
					section: 'Layout',
					value  : 'vw-100',
				},
				'Full height'     : {
					type   : 'toggle',
					section: 'Layout',
					value  : 'min-vh-100',
				},
				'Image'           : {
					type   : 'select',
					options: [
						{label: 'Hide', value: 'caxton-hide-image',},
						{label: 'small', value: 'sfb-ph-small-image',},
						{label: 'Normal', value: '',},
						{label: 'Large', value: 'sfb-ph-large-image',},
					],
					section: 'Layout',
				},
				'Content position': {
					type   : 'select',
					options: [
						{value: '', label: 'Right',},
						{value: 'caxton-flex-row-reverse', label: 'Left',},
					],
					section: 'Layout',
				},
				'Text Alignment'  : {
					type   : 'select',
					options: [
						{value: '', label: 'Left',},
						{value: 'tc', label: 'Center',},
						{value: 'tr', label: 'Right',},
					],
					section: 'Layout',
				},
				'Font'            : {
					type   : 'font',
					section: 'Typography',
					tpl    : 'font-family:%s;'
				},
				'Font size'       : {
					type   : 'range',
					min    : 5,
					max    : 80,
					default: 16,
					section: 'Typography',
					tpl    : 'font-size:%spx;'
				},
				'Letter Spacing'  : {
					type   : 'range',
					max    : 25,
					min    : - 5,
					default: 0,
					section: 'Typography',
					tpl    : 'letter-spacing:%spx;'
				},
				'Text color'      : {
					type   : 'color',
					default: '#333',
					section: 'Typography',
					tpl    : 'color:%s;'
				},

				'Text Glow/Shadow': {
					type   : 'select',
					options: [
						{value: '', label: 'No shadow/glow',},
						{value: '255,255,255', label: 'Glow',},
						{value: '0,0,0', label: 'Shadow',},
					],
					section: 'Glow/Shadow',
				},
				'Shadow position' : {
					type   : 'select',
					options: [
						{value: 'calc( -2px + -.05em ) calc( 2px + .03em )', label: 'Far Left',},
						{value: 'calc( -1px + -.03em ) calc( 1px + .01em )', label: 'Left',},
						{value: '0 0', label: 'Center',},
						{value: 'calc( 1px + .03em ) calc( 1px + .01em )', label: 'Right',},
						{value: 'calc( 2px + .05em ) calc( 2px + .03em )', label: 'Far Right',},
					],
					default: '0 0',
					section: 'Glow/Shadow',
					tpl    : 'text-shadow:%s {{Shadow Blur}} rgba({{Text Glow/Shadow}},{{Shadow Strength}});'
				},
				'Shadow Blur'     : {
					type   : 'range',
					tpl    : '%spx ',
					default: 3,
					max    : 25,
					section: 'Glow/Shadow',
				},
				'Shadow Strength' : {
					type   : 'range',
					min    : .1,
					step   : .1,
					default: .1,
					max    : 1,
					section: 'Glow/Shadow',
				},
			},
		} );
		// endregion Product hero

		// region Sale ending counter
		CaxtonBlock( {
			id         : 'sfp-blocks/wc-sale-countdown',
			title      : 'Sale Countdown',
			icon       : 'archive',
			category   : 'sfp-blocks',
			attributes : {
				tpl: {
					type: 'string'
				},
			},
			apiUrl     : function ( props ) {
				var attr = props.attributes;
				return {
					apiData:
						'/sfp_blocks/v1/sale_countdown?ending=' + attr.ending,
				};
			},
			save       : function ( props, that ) {
				classes = 'sfp-blocks-sale-countdown relative';

				var allHTML =
							'<div class="' + classes + '">' +
							'<div key="bg" class="absolute absolute--fill {{Background Blur}}">{{Background}}</div>' +
							'<div class="relative pa1 tc flex flex-column items-center justify-center" style="{{Min height}}">' +
							'{{heading}}' +
							'<div style="{{Counter Font}}{{Letter Spacing}}{{Font size}}{{Text color}}">%content%</div>' +
							'{{textUnder}}' +
							'</div></div>';

				// Populate styles
				allHTML = that.outputHTML( allHTML ).__html;

				setTimeout( window.sfBlocksSetup, 700 );

				return wp.element.createElement(
					'div',
					{
						key    : 'hero-wrap',
						onClick: function ( e ) { e.preventDefault(); }
					},
					Caxton.html2el(
						allHTML,
						{className: 'relative', key: 'hero-html'}
					)
				);
			},
			apiCallback: function ( props, that ) {

				var attr = props.attributes, classes;

				if ( !attr.ending ) {
					return wp.element.createElement( 'div', {
						className: 'caxton-notification',
						key      : 'notice'
					}, 'Please set sale ending time...' );

				} else if ( props.apiData.data ) {
					classes = 'sfp-blocks-sale-countdown relative';

					var allHTML =
								'<div class="' + classes + '">' +
								'<div key="bg" class="absolute absolute--fill {{Background Blur}}">{{Background}}</div>' +
								'<div class="relative pa1 tc flex flex-column items-center justify-center" style="{{Min height}}">' +
								'{{heading}}' +
								'<div style="{{Counter Font}}{{Letter Spacing}}{{Font size}}{{Text color}}">' + props.apiData.data.html + '</div>' +
								'{{textUnder}}' +
								'</div></div>';

					// Populate styles
					allHTML = that.outputHTML( allHTML ).__html;

					setTimeout( window.sfBlocksSetup, 700 );

					return wp.element.createElement(
						'div',
						{
							key    : 'hero-wrap',
							onClick: function ( e ) { e.preventDefault(); }
						},
						Caxton.html2el(
							allHTML,
							{className: 'relative', key: 'hero-html'}
						)
					);
				} else {
					return wp.element.createElement( 'div', {
						className: 'caxton-notification',
						key      : 'notice'
					}, 'Loading sale counter...' );
				}
			},
			resizable  : {
				height: 'Min height',
			},
			fields     : {
				'ending'        : {
					label: 'Sale ending time',
					type : 'datetime',
				},
				'heading'       : {
					label: 'Heading',
					type : 'textarea',
					tpl  : '<h3 style="{{Text color}}{{Heading Font}}margin:2rem 0;">%s</h3>',
				},
				'textUnder'     : {
					label: 'Under counter text',
					type : 'textarea',
					tpl  : '<p style="{{Text color}}{{Text Font}}margin:2rem 0;">%s</p>',
				},
				'Min height'    : {
					max    : 2160,
					type   : 'range',
					default: '',
					section: 'Layout',
					tpl    : 'min-height:%spx;'
				},
				'Background'    : {
					type: 'background',
				},
				'Heading Font'  : {
					type   : 'font',
					section: 'Typography',
					tpl    : 'font-family:%s;'
				},
				'Text Font'     : {
					type   : 'font',
					section: 'Typography',
					tpl    : 'font-family:%s;'
				},
				'Counter Font'  : {
					type   : 'font',
					section: 'Typography',
					tpl    : 'font-family:%s;'
				},
				'Font size'     : {
					type   : 'range',
					min    : 5,
					max    : 80,
					default: 16,
					section: 'Typography',
					tpl    : 'font-size:%spx;'
				},
				'Letter Spacing': {
					type   : 'range',
					max    : 25,
					min    : - 5,
					default: 0,
					section: 'Typography',
					tpl    : 'letter-spacing:%spx;'
				},
				'Text color'    : {
					type   : 'color',
					default: '#fff',
					section: 'Typography',
					tpl    : 'color:%s;'
				},
			},
		} );
		// endregion Sale ending counter

		// @TODO Remove `|| true`
		if ( sfpBlocksProps.is_fse || true ) {
			fontFields = {
				'Font'      : {
					type   : 'font',
					section: 'Typography',
				},
				'Font size' : {
					type   : 'range',
					min    : 5,
					max    : 250,
					default: 16,
					section: 'Typography',
				},
				'Text color': {
					type   : 'color',
					default: '#333',
					section: 'Typography',
				},

				'Text Glow/Shadow': {
					type   : 'select',
					options: [
						{value: '', label: 'No shadow/glow',},
						{value: '255,255,255', label: 'Glow',},
						{value: '0,0,0', label: 'Shadow',},
					],
					tpl    : '{{Shadow position}} {{Shadow Blur}} rgba(%s,{{Shadow Strength}})',
					section: 'Text Glow/Shadow',
				},
				'Shadow position' : {
					type   : 'select',
					options: [
						{value: 'calc( -2px + -.05em ) calc( 2px + .03em )', label: 'Far Left',},
						{value: 'calc( -1px + -.03em ) calc( 1px + .01em )', label: 'Left',},
						{value: '0 0', label: 'Center',},
						{value: 'calc( 1px + .03em ) calc( 1px + .01em )', label: 'Right',},
						{value: 'calc( 2px + .05em ) calc( 2px + .03em )', label: 'Far Right',},
					],
					default: '0 0',
					section: 'Text Glow/Shadow',
				},
				'Shadow Blur'     : {
					type   : 'range',
					tpl    : '%spx ',
					default: 3,
					max    : 25,
					section: 'Text Glow/Shadow',
				},
				'Shadow Strength' : {
					type   : 'range',
					min    : .1,
					step   : .1,
					default: .1,
					max    : 1,
					section: 'Text Glow/Shadow',
				},

			}

			CaxtonBlock( {
				id         : 'sfp-blocks/wc-archive-title',
				title      : 'Product Category/Tag Title',
				icon       : 'editor-textcolor',
				category   : 'sfp-blocks',
				apiUrl     : function ( props ) {
					return {
						apiData: '/sfp_blocks/v1/archive_title',
					};
				},
				apiCallback: function ( props, that ) {
					if ( props.apiData.data ) {
						var attr = props.attributes, classes;

						return Caxton.html2el( props.apiData.data.html, {
							key      : 'archive-title',
							className: '',
							style    : {
								fontFamily: attr['Font'],
								fontSize  : attr['Font size'] + 'px',
								color     : attr['Text color'],
								textShadow: that.parseTpl( '{{Text Glow/Shadow}' ),
							},
							onClick  : function ( e ) {
								e.preventDefault();
							}
						} );
					} else {
						return wp.element.createElement( 'div', {
							className: 'caxton-notification',
							key      : 'notice'
						}, 'Loading...' );
					}
				},
				fields     : fontFields,
			} );

			CaxtonBlock( {
				id         : 'sfp-blocks/wc-archive-image',
				title      : 'Product Category/Tag Image',
				icon       : 'cover-image',
				category   : 'sfp-blocks',
				apiUrl     : function ( props ) {
					return {
						apiData: '/sfp_blocks/v1/archive_image',
					};
				},
				apiCallback: function ( props, that ) {
					if ( props.apiData.data ) {
						var attr = props.attributes, classes;

						return Caxton.html2el( props.apiData.data.html, {
							key      : 'archive-image',
							className: '',
							style    : {},
							onClick  : function ( e ) { e.preventDefault(); }
						} );
					} else {
						return wp.element.createElement( 'div', {
							className: 'caxton-notification',
							key      : 'notice'
						}, 'Loading...' );
					}
				},
				fields     : {
					'Align'     : {
						type   : 'select',
						options: [
							{value: '', label: 'Default',},
							{value: 'w-100', label: 'Full width',},
							{value: 'fl', label: 'Push left',},
							{value: 'fr', label: 'Push right',},
						],
					}
				},
			} );

			CaxtonBlock( {
				id         : 'sfp-blocks/wc-archive-description',
				title      : 'Product Category/Tag description',
				icon       : 'editor-justify',
				category   : 'sfp-blocks',
				apiUrl     : function ( props ) {
					return {
						apiData: '/sfp_blocks/v1/archive_description',
					};
				},
				apiCallback: function ( props, that ) {
					if ( props.apiData.data ) {
						var attr = props.attributes, classes;

						return Caxton.html2el( props.apiData.data.html, {
							key      : 'archive-description',
							className: '',
							style    : {
								fontFamily: attr['Font'],
								fontSize  : attr['Font size'] + 'px',
								color     : attr['Text color'],
								textShadow: that.parseTpl( '{{Text Glow/Shadow}' ),
							},
							onClick  : function ( e ) {
								e.preventDefault();
							}
						} );
					} else {
						return wp.element.createElement( 'div', {
							className: 'caxton-notification',
							key      : 'notice'
						}, 'Loading...' );
					}
				},
				fields     : {
					'Font'      : {
						type   : 'font',
						section: 'Typography',
					},
					'Font size' : {
						type   : 'range',
						min    : 5,
						max    : 250,
						default: 16,
						section: 'Typography',
					},
					'Text color': {
						type   : 'color',
						default: '#333',
						section: 'Typography',
					},

					'Text Glow/Shadow': {
						type   : 'select',
						options: [
							{value: '', label: 'No shadow/glow',},
							{value: '255,255,255', label: 'Glow',},
							{value: '0,0,0', label: 'Shadow',},
						],
						tpl    : '{{Shadow position}} {{Shadow Blur}} rgba(%s,{{Shadow Strength}})',
						section: 'Text Glow/Shadow',
					},
					'Shadow position' : {
						type   : 'select',
						options: [
							{value: 'calc( -2px + -.05em ) calc( 2px + .03em )', label: 'Far Left',},
							{value: 'calc( -1px + -.03em ) calc( 1px + .01em )', label: 'Left',},
							{value: '0 0', label: 'Center',},
							{value: 'calc( 1px + .03em ) calc( 1px + .01em )', label: 'Right',},
							{value: 'calc( 2px + .05em ) calc( 2px + .03em )', label: 'Far Right',},
						],
						default: '0 0',
						section: 'Text Glow/Shadow',
					},
					'Shadow Blur'     : {
						type   : 'range',
						tpl    : '%spx ',
						default: 3,
						max    : 25,
						section: 'Text Glow/Shadow',
					},
					'Shadow Strength' : {
						type   : 'range',
						min    : .1,
						step   : .1,
						default: .1,
						max    : 1,
						section: 'Text Glow/Shadow',
					},
				},
			} );

			CaxtonBlock( {
				id         : 'sfp-blocks/wc-breadcrumbs',
				title      : 'Shop breadcrumbs',
				icon       : 'products',
				category   : 'sfp-blocks',
				apiUrl     : function ( props ) {
					return {
						apiData: '/sfp_blocks/v1/breadcrumbs',
					};
				},
				apiCallback: function ( props, that ) {
					if ( props.apiData.data ) {
						var attr = props.attributes;

						return Caxton.html2el( props.apiData.data.html, {
							className: 'sfp-block sfp-block-breadcrumbs woocommerce',
							style    : {
								fontFamily: attr['Font'],
								fontSize  : attr['Font size'] + 'px',
								color     : attr['Text color'],
								textShadow: that.parseTpl( '{{Text Glow/Shadow}' ),
							},
							onClick  : function ( e ) {
								e.preventDefault();
							}
						} );
					} else {
						return wp.element.createElement( 'div', {
							className: 'caxton-notification',
							key      : 'notice'
						}, 'Loading...' );
					}
				},
				fields     : {},
			} );

			CaxtonBlock( {
				id         : 'sfp-blocks/wc-sorting',
				title      : 'Shop sorting',
				icon       : 'products',
				category   : 'sfp-blocks',
				apiUrl     : function ( props ) {
					return {
						apiData: '/sfp_blocks/v1/sorting',
					};
				},
				apiCallback: function ( props, that ) {
					if ( props.apiData.data ) {
						var attr = props.attributes;

						return Caxton.html2el( props.apiData.data.html, {
							className: 'sfp-block sfp-block-sorting woocommerce',
							style    : {
								fontFamily: attr['Font'],
								fontSize  : attr['Font size'] + 'px',
								color     : attr['Text color'],
								textShadow: that.parseTpl( '{{Text Glow/Shadow}' ),
							},
							onClick  : function ( e ) {
								e.preventDefault();
							}
						} );
					} else {
						return wp.element.createElement( 'div', {
							className: 'caxton-notification',
							key      : 'notice'
						}, 'Loading...' );
					}
				},
				fields     : {},
			} );

			CaxtonBlock( {
				id         : 'sfp-blocks/wc-results-count',
				title      : 'Shop results count',
				icon       : 'products',
				category   : 'sfp-blocks',
				apiUrl     : function ( props ) {
					return {
						apiData: '/sfp_blocks/v1/results_count',
					};
				},
				apiCallback: function ( props, that ) {
					if ( props.apiData.data ) {
						var attr = props.attributes;

						return Caxton.html2el( props.apiData.data.html, {
							className: 'sfp-block sfp-block-results-count woocommerce',
							style    : {
								fontFamily: attr['Font'],
								fontSize  : attr['Font size'] + 'px',
								color     : attr['Text color'],
								textShadow: that.parseTpl( '{{Text Glow/Shadow}' ),
							},
							onClick  : function ( e ) {
								e.preventDefault();
							}
						} );
					} else {
						return wp.element.createElement( 'div', {
							className: 'caxton-notification',
							key      : 'notice'
						}, 'Loading...' );
					}
				},
				fields     : {},
			} );

			CaxtonBlock( {
				id         : 'sfp-blocks/wc-pagination',
				title      : 'Shop pagination',
				icon       : 'products',
				category   : 'sfp-blocks',
				apiUrl     : function ( props ) {
					return {
						apiData: '/sfp_blocks/v1/pagination',
					};
				},
				apiCallback: function ( props, that ) {
					if ( props.apiData.data ) {
						var attr = props.attributes;

						return Caxton.html2el( props.apiData.data.html, {
							className: 'sfp-block sfp-block-pagination woocommerce',
							style    : {
								fontFamily: attr['Font'],
								fontSize  : attr['Font size'] + 'px',
								color     : attr['Text color'],
								textShadow: that.parseTpl( '{{Text Glow/Shadow}' ),
							},
							onClick  : function ( e ) {
								e.preventDefault();
							}
						} );
					} else {
						return wp.element.createElement( 'div', {
							className: 'caxton-notification',
							key      : 'notice'
						}, 'Loading...' );
					}
				},
				fields     : {},
			} );

			CaxtonBlock( {
				id         : 'sfp-blocks/mini-cart',
				title      : 'Mini cart',
				icon       : 'products',
				category   : 'sfp-blocks',
				apiUrl     : function ( props ) {
					var attr = props.attributes;
					return {
						apiData: '/sfp_blocks/v1/mini-cart?popup_position' + attr.popup_position,
					};
				},
				apiCallback: function ( props, that ) {
					if ( props.apiData.data ) {
						var attr = props.attributes;

						return Caxton.html2el( props.apiData.data.html, {
							className: 'sfp-block sfp-block-mini-cart woocommerce',
							style    : {},
							onClick  : function ( e ) {
								e.preventDefault();
							}
						} );
					} else {
						return wp.element.createElement( 'div', {
							className: 'caxton-notification',
							key      : 'notice'
						}, 'Loading...' );
					}
				},
				fields     : {
					popup_position: {
						label: 'Pop over cart position',
						type   : 'select',
						options: [
							{value: '', label: 'Right edge',},
							{value: 'left', label: 'Left edge',},
							{value: 'center', label: 'Center',},
						],
						section: 'Layout',
					}
				},
			} );

		}
	}
)( jQuery );