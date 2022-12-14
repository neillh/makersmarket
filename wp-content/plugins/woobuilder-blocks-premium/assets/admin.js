/**
 * Plugin front end scripts
 *
 * @package WooBuilder_Blocks
 * @version 1.0.0
 */
jQuery( function ( $ ) {
	var el             = wp.element.createElement,
			InnerBlocks    = window.caxtonWPEditor.InnerBlocks,
			productOptions = [],
			postType       = woobuilderData.post_type,
			isMyType       = woobuilderData.is_my_type,
			isFSE          = woobuilderData.is_full_site_editing,
			// Needs conditional in code to work
			removeBlocks   = isMyType ? {} : {
				'cover': 'remove',
			};

	for ( const prodID in woobuilderData.prods ) {
		if ( woobuilderData.prods.hasOwnProperty( prodID ) ) {
			productOptions.push( {
				value: prodID,
				label: woobuilderData.prods[prodID][0],
				image: woobuilderData.prods[prodID][1],
			} );
		}
	}

	function getAPIURL( endpoint, queryString, attr, exclude ) {
		if ( !queryString ) {
			queryString = '';
		} else {
			queryString += '&';
		}
		if ( attr ) {
			queryString = queryString + $.param( attr ).replace( /\+/g, ' ' );
		}
		var productID = woobuilderData.post;

		if ( attr.product_id ) {
			productID = attr.product_id;
		}

		return '/woobuilder_blocks/v1/' + endpoint + '?post=' + productID + '&' + queryString
	}

	function productsPicker() {
		return {
			label   : 'Choose Product',
			type    : 'orderedSelect',
			multiple: false,
			options : productOptions,
		};
	}

	function woobFields( fields ) {
		if ( !fields ) {
			fields = {};
		}

		if ( !isMyType ) {
			fields['product_id'] = productsPicker();
		}

		if ( typeof fields['text_align'] === 'undefined' ) {
			fields['text_align'] = {
				label  : 'Alignment',
				type   : 'select',
				section: 'Appearance',
				options: [
					{value: '', label: 'Theme default',},
					{value: 'left', label: 'Left',},
					{value: 'right', label: 'Right',},
					{value: 'center', label: 'Center',},
				],
				default: '',
			};
		} else if ( !fields['text_align'] ) {
			delete fields['text_align'];
		}

		fields['font'] = {
			label  : 'Font',
			type   : 'font',
			section: 'Appearance',
		};
		fields['font_size'] = {
			label  : 'Font Size',
			default: 16,
			type   : 'range',
			section: 'Appearance',
		};
		fields['text_color'] = {
			label  : 'Text color',
			type   : 'color',
			section: 'Appearance',
			default: '',
		};
		return fields;
	}

	function woobApiCallbackGenerator( title, setupJSBlocks ) {
		return function ( props, that ) {
			var label = null;

			if ( !isMyType ) {
				if ( !props.attributes.product_id ) {
					return wp.element.createElement( 'div', {
						className: 'notice notice-error ma0',
						key      : 'notice',
						style    : {padding: "25px"},
					}, 'Please choose a product to display ' + title + ' block.' );
				} else {
					label = wp.element.createElement( 'div', {
						className: 'woobk-label truncate child',
						key      : 'label'
					}, '#' + props.attributes.product_id + ' ' + woobuilderData.prods[props.attributes.product_id][0] + '...' );

				}
			}

			if ( props.apiData && props.apiData.data ) {
				setupJSBlocks && setTimeout( window.WoobuilderBlocksSetup, 500 );
				return wp.element.createElement(
					'div',
					{className: 'hide-child'},
					Caxton.html2el( props.apiData.data, {
						className: 'woocommerce',
						key      : 'block-html',
						style    : {},
						onClick  : function ( e ) {
							e.preventDefault();
						}
					} ),
					label
				);
			} else {
				return wp.element.createElement( 'div', {
					className: 'caxton-notification',
					key      : 'notice'
				}, 'Loading ' + title + '...' );
			}
		};
	}

	// region WooBuilder: Template

	CaxtonLayoutOptionsBlock(
		{
			id      : 'woobuilder/tpl',
			title   : 'Woobuilder Template',
			category: 'woobuilder',
		},
		[
			{
				title: 'Classic',
				img  : woobuilderData.img_url + 'classic.png',
				props: {
					tpl: [
						[
							'caxton/grid',
							{
								"tpl": '[' +
											 '["caxton/section", {"Grid area": "span 1/span 6", "tpl": "[[\\"woobuilder/images\\", {}]]" }],' +
											 '["caxton/section", {"Grid area": "span 1/span 6", "tpl": "[[\\"woobuilder/title\\", {}], [\\"woobuilder/rating\\", {}], [\\"woobuilder/product-price\\", {}], [\\"woobuilder/excerpt\\", {}], [\\"woobuilder/add-to-cart\\", {}], [\\"woobuilder/meta\\", {}]]" }]' +
											 ']',
							}
						]
					],
				}
			},
			{
				title: 'Classic image right',
				img  : woobuilderData.img_url + 'classic-right.png',
				props: {
					tpl: [
						[
							'caxton/grid',
							{
								"tpl": '[' +
											 '["caxton/section", {"Grid area": "span 1/span 6", "tpl": "[[\\"woobuilder/title\\", {}], [\\"woobuilder/rating\\", {}], [\\"woobuilder/product-price\\", {}], [\\"woobuilder/excerpt\\", {}], [\\"woobuilder/add-to-cart\\", {}], [\\"woobuilder/meta\\", {}]]" }],' +
											 '["caxton/section", {"Grid area": "span 1/span 6", "tpl": "[[\\"woobuilder/images\\", {}]]" }]' +
											 ']',
							}
						]
					],
				}
			},
			{
				title: 'Elegant',
				img  : woobuilderData.img_url + 'elegant.png',
				props: {
					tpl: [
						[
							"caxton/grid",
							{
								"tpl": "[" +
											 '["caxton/section",{"Grid area":"span 1/span 3"}],' +
											 '["caxton/section",{"tpl":"[' +
											 '[\\"woobuilder/images\\", {\\"woobuilder_style\\":\\"left-gallery\\"}],' +
											 '[\\"woobuilder/title\\", {\\"font_size\\":27}],' +
											 '[\\"woobuilder/product-price\\", {}],' +
											 '[\\"woobuilder/excerpt\\", {}],' +
											 '[\\"woobuilder/add-to-cart\\", {}],' +
											 '[\\"core/spacer\\", {\\"height\\":50}],' +
											 '[\\"woobuilder/tabs\\", {}]' +
											 ' ]","Grid area":"span 1/span 6"}],' +
											 '["caxton/section",{"Grid area":"span 1/span 3"}]' +
											 "]"
							}
						]
					],
				}
			},
			{
				title: 'Carousel',
				img  : woobuilderData.img_url + 'carousel.png',
				props: {
					tpl: [
						["woobuilder/title", {"font": "", "font_size": 59, "text_color": ""}],
						["woobuilder/images-carousel", {"font": "", "font_size": 16, "text_color": ""}],
						["core/spacer", {"height": 30}],
						[
							"caxton/grid", {
							"tpl": '[["caxton/section",{"Grid area":"span 1/span 6", "tpl":"[' +
										 '[\\"woobuilder/product-price\\", {}],' +
										 '[\\"woobuilder/excerpt\\", {}],' +
										 '[\\"woobuilder/add-to-cart\\", {}]' +
										 ']"}],["caxton/section",{"Grid area":"span 1/span 6"}]]',
						}
						],
						["woobuilder/tabs", {"desc": "", "font": "", "font_size": 16, "text_color": ""}]
					],
				}
			},
			{
				title: 'Full screen',
				img  : woobuilderData.img_url + 'full-screen.png',
				props: {
					tpl: [
						["woobuilder/title", {"font": "", "font_size": 50, "text_color": ""}],
						[
							"caxton/grid",
							{
								"tpl"   : '[["caxton/section",{"tpl": "[[\\"woobuilder/images\\", {\\"woobuilder_style\\":\\"hide-gallery\\"}]]","Grid area":"span 1/span 12"}]]',
								"Layout": "vw-100",
							}
						],
						["woobuilder/product-price", {"font": "", "font_size": 16, "text_color": ""}],
						["woobuilder/excerpt", {"font": "", "font_size": 16, "text_color": ""}],
						["woobuilder/add-to-cart", {"woobuilder_style": "", "font": "", "font_size": 16, "text_color": ""}],
						["core/spacer", {"height": 100}],
						["woobuilder/tabs", {"desc": "", "font": "", "font_size": 16, "text_color": ""}]
					],
				}
			}
		]
	);

	// endregion WooBuilder: Template

	// region WooBuilder: Product rating

	CaxtonBlock( {
		id         : 'woobuilder/rating',
		title      : 'WooBuilder: Product rating',
		icon       : 'star-filled',
		category   : 'woobuilder',
		apiUrl     : function ( props ) {
			var
				attr = props.attributes,
				qry  = '';
			return {
				apiData: getAPIURL( 'rating', qry, attr ),
			};
		},
		apiCallback: woobApiCallbackGenerator( 'Product rating' ),
		fields     : woobFields( {
//			'Text color': {
//				type: 'color',
//				default: '#fff',
//				section: 'Typography',
//			},
		} ),
	} );

	// endregion WooBuilder: Product rating

	// region WooBuilder: Product title

	CaxtonBlock( {
		id         : 'woobuilder/title',
		title      : 'WooBuilder: Product title',
		icon       : 'editor-textcolor',
		category   : 'woobuilder',
		apiUrl     : function ( props ) {
			var
				attr = props.attributes,
				qry  = '';
			return {
				apiData: getAPIURL( 'title', qry, attr ),
			};
		},
		apiCallback: woobApiCallbackGenerator( 'Product title' ),
		fields     : woobFields( {
//			'Text color': {
//				type: 'color',
//				default: '#fff',
//				section: 'Typography',
//			},
		} ),
	} );

	// endregion WooBuilder: Product title

	// region WooBuilder: Add to cart

	CaxtonBlock( {
		id         : 'woobuilder/add-to-cart',
		title      : 'WooBuilder: Add to cart',
		icon       : 'cart',
		category   : 'woobuilder',
		apiUrl     : function ( props ) {
			var
				attr = props.attributes,
				qry  = '';
			return {
				apiData: getAPIURL( 'add_to_cart', qry, attr ),
			};
		},
		apiCallback: woobApiCallbackGenerator( 'Add to cart', 'runSetup' ),
		fields     : woobFields( {
			'woobuilder_style' : {
				label  : 'Outlined button and input',
				type   : 'checkbox',
				value  : '1',
				section: 'Layout',
			},
			'attributes_layout': {
				label  : 'Attributes layout',
				type   : 'select',
				help   : 'Display field in either a vertical or horizontal style.',
				options: [
					{value: '', label: 'Default',},
					{value: 'horizontal', label: 'Horizontal',},
				],
				section: 'Layout',
			},
		} ),
	} );

	// endregion WooBuilder: Add to cart

	// region WooBuilder: Add to cart

	CaxtonBlock( {
		id         : 'woobuilder/add-to-cart-sticky',
		title      : 'WooBuilder: Sticky add to cart',
		icon       : 'cart',
		category   : 'woobuilder',
		apiUrl     : function ( props ) {
			var
				attr = props.attributes,
				qry  = '';
			return {
				apiData: getAPIURL( 'add_to_cart_sticky', qry, attr ),
			};
		},
		apiCallback: woobApiCallbackGenerator( 'Sticky add to cart', 'runSetup' ),
		fields     : woobFields( {
			woobuilder_style: {
				label      : 'Stick to top or bottom',
				description: 'We strongly recommend using show on scroll for stick to top position.',
				type       : 'select',
				options    : [
					{value: '', label: 'Bottom',},
					{value: 'top', label: 'Top',},
				],
			},
			show_on_scroll  : {
				label: 'Show on scroll',
				type : 'toggle',
			},
			bg_colour       : {
				label: 'Background colour',
				type : 'color',
			},
			button_colour   : {
				label: 'Button colour',
				type : 'color',
			},
			button_text     : {
				label: 'Button text colour',
				type : 'color',
			},
		} ),
	} );

	// endregion WooBuilder: Add to cart

	// region WooBuilder: Sale Countdown

	CaxtonBlock( {
		id         : 'woobuilder/sale-counter',
		title      : 'WooBuilder: Sale Countdown',
		icon       : 'clock',
		category   : 'woobuilder',
		apiUrl     : function ( props ) {
			var
				attr = props.attributes,
				qry  = '';
			return {
				apiData: getAPIURL( 'sale_counter', qry, attr ),
			};
		},
		apiCallback: woobApiCallbackGenerator( 'Sale Countdown', 'runSetup' ),
		fields     : woobFields( {
			'woobuilder_style': {
				label  : 'Style',
				type   : 'select',
				options: [
					{value: '', label: 'Dial around text',},
					{value: 'above', label: 'Dial above text',},
					{value: 'below', label: 'Dial below text',},
					{value: 'no-dial', label: 'Just text',},
					{value: 'colon', label: 'Colon separation',},
				],
				section: 'Layout',
			},
			'active_color'    : {
				label  : 'Arc color',
				type   : 'color',
				default: '#555',
				section: 'Layout',
			},
			'track_width'     : {
				label  : 'Circle width',
				type   : 'range',
				default: '2',
				section: 'Layout',
			},
			'track_color'     : {
				label  : 'Circle color',
				type   : 'color',
				default: '#ddd',
				section: 'Layout',
			},
		} ),
	} );

	// endregion WooBuilder: Sale Countdown

	// region WooBuilder: WooCommerce Hook
	CaxtonBlock( {
		id         : 'woobuilder/wc-hook',
		title      : 'WooBuilder: WooCommerce hooks',
		description: 'This improves compatibility of your custom product template with other WooCommerce plugins.',
		icon       : 'products',
		category   : 'woobuilder',
		apiUrl     : function ( props ) {return {};},
		apiCallback: function ( props, that ) {
			var hook = props.attributes.hook;

			if ( !hook ) {
				return wp.element.createElement( 'div', {
					className: 'caxton-notification',
				}, 'Please select the hook to fire.' );
			}

			return wp.element.createElement(
				'div', {
					className: 'caxton-notification',
				},
				wp.element.createElement( 'b', {}, hook ),
				' hook will run here on frontend.'
			);
		},
		fields     : {
			'hook': {
				label  : 'Hook',
				type   : 'select',
				help   : 'Selected hook will run at this place in your template.',
				options: [
					{value: '', label: 'Choose a hook...',},
					{value: 'woocommerce_before_single_product_summary', label: 'woocommerce_before_single_product_summary',},
					{value: 'woocommerce_single_product_summary', label: 'woocommerce_single_product_summary',},
					{value: 'woocommerce_after_single_product_summary', label: 'woocommerce_after_single_product_summary',},
				],
			},
		},
	} );
	// endregion WooBuilder: WooCommerce Hook

	// region WooBuilder: Stock countdown

	CaxtonBlock( {
		id         : 'woobuilder/stock-countdown',
		title      : 'WooBuilder: Stock countdown',
		icon       : 'products',
		category   : 'woobuilder',
		apiUrl     : function ( props ) {
			var
				attr = props.attributes,
				qry  = '';
			return {
				apiData: getAPIURL( 'stock_countdown', qry, attr ),
			};
		},
		apiCallback: woobApiCallbackGenerator( 'Stock countdown', 'runSetup' ),
		fields     : woobFields( {
			'message'     : {
				label  : 'Message',
				type   : 'text',
				help   : '%s will be replaced with stock quantity.',
				default: 'Only %s items left in stock!',
				section: 'Layout',
			},
			'message1'    : {
				label  : 'Message last item',
				type   : 'text',
				help   : 'Message displayed when stock quantity is 1',
				default: 'Last item left in stock!',
				section: 'Layout',
			},
			'max'         : {
				label  : 'Max stock count',
				max    : '1000',
				type   : 'range',
				default: '25',
				section: 'Layout',
			},
			'track_color' : {
				label  : 'Bar color',
				type   : 'color',
				default: '#ccc',
				section: 'Layout',
			},
			'active_color': {
				label  : 'Bar stock color',
				type   : 'color',
				default: '#09c',
				section: 'Layout',
			},
		} ),
	} );

	// endregion WooBuilder: Stock countdown

	var WOOBUILDER_COVER_FIELDS = {
		'Overlay'   : {
			type: 'overlay',
		},
		'Parallax'  : {
			type   : 'toggle',
			section: 'Layout',
		},
		'Full width': {
			type   : 'toggle',
			section: 'Layout',
		},
		'Min height': {
			max    : 2160,
			type   : 'range',
			default: '500',
			section: 'Layout',
		},
	};

	if ( !isMyType ) {
		WOOBUILDER_COVER_FIELDS = _.assign( {product_id: productsPicker()}, WOOBUILDER_COVER_FIELDS );
	}

	// region WooBuilder: Cover
	removeBlocks['cover'] || CaxtonBlock( {
		id       : 'woobuilder/cover',
		title    : 'WooBuilder: Cover',
		icon     : 'archive',
		category : 'woobuilder',
		resizable: {
			height: 'Min height',
		},
		toolbars : {
			BlockAlignment: 'BlockAlignToolbar',
		},
		fields   : WOOBUILDER_COVER_FIELDS,
		edit     : function ( props, block ) {
			var img;

			var TEMPLATE = [
				['woobuilder/title', {"text_align": "center", "font_size": 25}],
				['woobuilder/product-price', {"text_align": "center",}],
				['woobuilder/excerpt', {"text_align": "center",}],
				['woobuilder/add-to-cart', {"text_align": "center",}],
			];

			if ( !isMyType ) {
				if ( !props.attributes.product_id ) {
					return wp.element.createElement( 'div', {
						className: 'notice notice-error ma0',
						key      : 'notice',
						style    : {padding: "25px"},
					}, 'Please choose a product to display Woobuilder cover block.' );
				}
				for ( var i = 0; i < TEMPLATE.length; i ++ ) {
					var blk = TEMPLATE[i];
					blk[1].product_id = props.attributes.product_id;
				}
				img = woobuilderData.prods[props.attributes.product_id][2];
			} else {
				var imgId = wp.data.select( 'core/editor' ).getEditedPostAttribute( 'featured_media' );
				if ( imgId ) {
					img = wp.data.select( 'core' ).getMedia( imgId );
					img = img && (
						img.media_details.sizes.large || img.media_details.sizes.full
					);
					img = img ? img.source_url : woobuilderData.thumbnail;
				}
			}

			return el(
				'div', {
					className: 'woobuilder-cover-wrap bg-center ph4 cover flex flex-column justify-center relative' + (
						props.attributes['Full width'] ? ' vw-100' : ''
					),
					style    : {
						backgroundImage     : img && 'url(' + img + ')',
						backgroundAttachment: props.attributes.Parallax ? 'fixed' : '',
						minHeight           : props.attributes['Min height'] + 'px',
					},
				},
				el( 'div', {className: 'woobuilder-cover'},
					Caxton.html2el( block.outputHTML( '{{Overlay}}' ) ),
					el( InnerBlocks, {template: TEMPLATE} )
				)
			);
		},
		save     : function ( props, block ) {
			return el( 'div', {}, Caxton.html2el( block.outputHTML( '{{Overlay}}' ) ), el( 'div', {className: 'woobuilder-cover'}, el( InnerBlocks.Content, null ) ) );
		}
	} );

	// endregion WooBuilder: Cover

	// region WooBuilder: Product price

	CaxtonBlock( {
		id         : 'woobuilder/product-price',
		title      : 'WooBuilder: Product price',
		icon       : 'tag',
		category   : 'woobuilder',
		apiUrl     : function ( props ) {
			var
				attr = props.attributes,
				qry  = '';
			return {
				apiData: getAPIURL( 'product_price', qry, attr ),
			};
		},
		apiCallback: woobApiCallbackGenerator( 'Product price' ),
		fields     : woobFields( {
//			'Text color': {
//				type: 'color',
//				default: '#fff',
//				section: 'Typography',
//			},
		} ),
	} );

	// endregion WooBuilder: Product price

	// region WooBuilder: Product tabs

	CaxtonBlock( {
		id         : 'woobuilder/tabs',
		title      : 'WooBuilder: Product tabs',
		icon       : 'tag',
		category   : 'woobuilder',
		apiUrl     : function ( props ) {
			var
				attr = props.attributes,
				qry  = '';
			return {
				apiData: getAPIURL( 'tabs', qry, attr ),
			};
		},
		apiCallback: woobApiCallbackGenerator( 'Product tabs' ),
		fields     : woobFields( {
			text_align: false,
			layout    : {
				label  : 'Layout',
				type   : 'select',
				section: 'Appearance',
				options: [
					{value: '', label: 'Default',},
					{value: 'hrzntl-tabs', label: 'Horizontal tabs',},
					{value: 'accordion', label: 'Accordion',},
				],
			},
			desc      : {
				label: 'Product Description',
				type : 'textarea',
			},
		} ),
	} );

	isMyType && CaxtonContentBlock( {
		id         : 'woobuilder/long-description',
		title      : 'WooBuilder: Long description',
		description: 'Just for organising your content. Shows product long description when used in a template.',
		icon       : 'editor-justify',
		category   : 'woobuilder',
		template   : [
			[
				'core/paragraph',
				{placeholder: 'Add in long description for your product. Pro tip: You can add other blocks ;)',}
			]
		],
	} );

	// endregion WooBuilder: Product tabs

	// region WooBuilder: Related products

	isMyType && CaxtonBlock( {
		id         : 'woobuilder/related-products',
		title      : 'WooBuilder: Related products',
		icon       : 'products',
		category   : 'woobuilder',
		apiUrl     : function ( props ) {
			var
				attr = props.attributes,
				qry  = '';
			return {
				apiData: getAPIURL( 'related_products', qry, attr ),
			};
		},
		apiCallback: woobApiCallbackGenerator( 'Related products' ),
		fields     : woobFields( {
			text_align: false,
//			'Text color': {
//				type: 'color',
//				default: '#fff',
//				section: 'Typography',
//			},
		} ),
	} );

	// endregion WooBuilder: Related products

	// region WooBuilder: Upsell products

	isMyType && CaxtonBlock( {
		id         : 'woobuilder/upsell-products',
		title      : 'WooBuilder: Upsell products',
		icon       : 'products',
		category   : 'woobuilder',
		apiUrl     : function ( props ) {
			var
				attr = props.attributes,
				qry  = '';
			return {
				apiData: getAPIURL( 'upsell_products', qry, attr ),
			};
		},
		apiCallback: woobApiCallbackGenerator( 'Upsell products' ),
		fields     : woobFields( {
			text_align: false,
//			'Text color': {
//				type: 'color',
//				default: '#fff',
//				section: 'Typography',
//			},
		} ),
	} );

	// endregion WooBuilder: Upsell products

	// region WooBuilder: Product Short Description

	CaxtonBlock( {
		id         : 'woobuilder/excerpt',
		title      : 'WooBuilder: Product Short Description',
		icon       : 'editor-justify',
		category   : 'woobuilder',
		apiUrl     : function ( props ) {
			var
				attr = props.attributes,
				qry  = '';
			return {
				apiData: getAPIURL( 'excerpt', qry, attr ),
			};
		},
		apiCallback: woobApiCallbackGenerator( 'Product Short Description' ),
		fields     : woobFields( {
//			'Text color': {
//				type: 'color',
//				default: '#fff',
//				section: 'Typography',
//			},
		} ),
	} );

	// endregion WooBuilder: Product Short Description

	// region WooBuilder: Product Meta

	CaxtonBlock( {
		id         : 'woobuilder/meta',
		title      : 'WooBuilder: Product Meta',
		icon       : 'format-aside',
		category   : 'woobuilder',
		apiUrl     : function ( props ) {
			var
				attr = props.attributes,
				qry  = '';
			return {
				apiData: getAPIURL( 'meta', qry, attr ),
			};
		},
		apiCallback: woobApiCallbackGenerator( 'Product Meta' ),
		fields     : woobFields( {
//			'Text color': {
//				type: 'color',
//				default: '#fff',
//				section: 'Typography',
//			},
		} ),
	} );

	// endregion WooBuilder: Product Meta

	// region WooBuilder: Product Reviews

	CaxtonBlock( {
		id         : 'woobuilder/reviews',
		title      : 'WooBuilder: Product Reviews',
		icon       : 'archive',
		category   : 'woobuilder',
		apiUrl     : function ( props ) {
			var
				attr = props.attributes,
				qry  = '';
			return {
				apiData: getAPIURL( 'reviews', qry, attr ),
			};
		},
		apiCallback: woobApiCallbackGenerator( 'Product Reviews' ),
		fields     : woobFields( {
			text_align: false,
//			'Text color': {
//				type: 'color',
//				default: '#fff',
//				section: 'Typography',
//			},
		} ),
	} );

	// endregion WooBuilder: Product Reviews

	// region WooBuilder: Product Images

	CaxtonBlock( {
		id          : 'woobuilder/images',
		title       : 'WooBuilder: Product Images',
		description : 'Image Gallery may not show properly in the preview but works on site.',
		icon        : 'images-alt2',
		category    : 'woobuilder',
		resizable   : {
			width: 'width',
		},
		apiUrl      : function ( props ) {
			var
				attr = $.extend( {}, props.attributes ),
				qry  = '';

			delete attr.width;
			delete attr.alignment;
			return {
				apiData: getAPIURL( 'images', qry, attr ),
			};
		},
		wrapperProps: function ( attrs, props, block ) {
			if ( props.alignment ) {
				attrs['data-align'] = props.alignment;
			}
			return attrs;
		},
		apiCallback : woobApiCallbackGenerator( 'Product Images' ),
		fields      : woobFields( {
			text_align        : false,
			width             : {
				label  : 'Gallery width',
				type   : 'number',
				help   : 'Clear to reset to full width.',
				section: 'Layout',
			},
			'woobuilder_style': {
				label  : 'Gallery images',
				type   : 'select',
				options: [
					{value: '', label: 'Default',},
					{value: 'hide-gallery', label: 'Hide',},
					{value: 'left-gallery', label: 'Left',},
					{value: 'right-gallery', label: 'Right',},
				],
				section: 'Layout',
			},
			'alignment'       : {
				label  : 'Alignment',
				type   : 'select',
				options: [
					{value: '', label: 'Center',},
					{value: 'left', label: 'Left',},
					{value: 'right', label: 'Right',},
				],
				section: 'Layout',
			},
			'img_size'        : {
				label  : 'Image size',
				help   : 'Set to large if images appear blurry',
				type   : 'select',
				options: [
					{value: '', label: 'Default',},
					{value: 'medium', label: 'Medium',},
					{value: 'medium_large', label: 'Medium Large',},
					{value: 'large', label: 'Large',},
				],
				section: 'Layout',
			},
			first_video_url   : {
				label  : 'First Youtube/Vimeo video URL',
				help   : 'Shows as the first gallery item.',
				type   : 'text',
				section: 'First video',
			},
			first_video_file  : {
				label       : 'First video upload',
				help        : 'Shows as the first gallery item.',
				type        : 'file',
				item        : 'video',
				allowedTypes: ['video'],
				section     : 'First video',
			},
			first_video_thumb : {
				label  : 'First video thumbnail',
				help   : 'Thumbnail for first video gallery item.',
				type   : 'image',
				size   : 'thumbnail',
				section: 'First video',
			},
			last_video_url    : {
				label  : 'Last Youtube/Vimeo video URL',
				help   : 'Shows as the last gallery item.',
				type   : 'text',
				section: 'Last video',
			},
			last_video_file   : {
				label       : 'Last video upload',
				help        : 'Shows as the last gallery item.',
				type        : 'file',
				item        : 'video',
				allowedTypes: ['video'],
				section     : 'Last video',
			},
			last_video_thumb  : {
				label  : 'Last video thumbnail',
				help   : 'Thumbnail for last video gallery item.',
				type   : 'image',
				size   : 'thumbnail',
				section: 'Last video',
			},
			img_radius        : {
				label  : 'Image radius',
				type   : 'range',
				section: 'Layout',
			},
		} ),
	} );

	// endregion WooBuilder: Product Images

	// region WooBuilder: Product Images Carousel

	CaxtonBlock( {
		id         : 'woobuilder/images-carousel',
		title      : 'WooBuilder: Product Images Carousel',
		icon       : 'slides',
		category   : 'woobuilder',
		description: 'Image Gallery may not show properly in the preview but works on site.',
		apiUrl     : function ( props ) {
			var
				attr = props.attributes,
				qry  = '';
			return {
				apiData: getAPIURL( 'images_carousel', qry, attr ),
			};
		},
		apiCallback: woobApiCallbackGenerator( 'Product Images Carousel', 1 ),
		fields     : woobFields( {
			text_align      : false,
			first_video_url : {
				label  : 'First Youtube/Vimeo video URL',
				help   : 'Shows as the first gallery item.',
				type   : 'text',
				section: 'Video',
			},
			first_video_file: {
				label       : 'Upload first video',
				help        : 'Shows as the first gallery item.',
				type        : 'file',
				allowedTypes: ['video'],
				section     : 'Video',
			},
			last_video_url  : {
				label  : 'Last Youtube/Vimeo video URL',
				help   : 'Shows as the last gallery item.',
				type   : 'text',
				section: 'Video',
			},
			last_video_file : {
				label       : 'Upload last video',
				help        : 'Shows as the last gallery item.',
				type        : 'file',
				allowedTypes: ['video'],
				section     : 'Video',
			},
//			'woobuilder_style': {
//				label  : 'Hide gallery image',
//				type   : 'checkbox',
//				value  : 'hide-gallery',
//				section: 'Layout',
//			}
		} ),
	} );

	// endregion WooBuilder: Product Images Carousel

	// region WooBuilder: Product Request Quote

	CaxtonBlock( {
		id         : 'woobuilder/request-quote',
		title      : 'WooBuilder: Product Request Quote',
		icon       : 'slides',
		category   : 'woobuilder',
		apiUrl     : function ( props ) {
			var
				attr = props.attributes,
				qry  = '';
			return {
				apiData: getAPIURL( 'request_quote', qry, attr ),
			};
		},
		apiCallback: woobApiCallbackGenerator( 'Product Request Quote', 1 ),
		fields     : woobFields( {
			text_align: false,
//			'woobuilder_style': {
//				label  : 'Hide gallery image',
//				type   : 'checkbox',
//				value  : 'hide-gallery',
//				section: 'Layout',
//			}
		} ),
	} );

	// endregion WooBuilder: Product Request Quote

	if ( isFSE ) {
		CaxtonBlock( {
			id         : 'woobuilder/product-blocks-content',
			title      : 'WooBuilder content',
			description: 'Displays block contents for WooBuilder products.',
			icon       : 'welcome-widgets-menus',
			category   : 'woobuilder',
			edit: function() {
				return Caxton.html2el(
					'<div class="ma2 pa1 bg-black-10"><div class="pa5 tc bg-white-80">' +
					'<div class="f2 ma0 o-50">WooBuilder content block</div>' +
					'<div class="f2 flip-v o-05 nt4">WooBuilder content block</div>' +
					'<span class="dashicons dashicons-welcome-widgets-menus o-30 pt3 w5 h5 nb4" style="font-size:11rem;"></span>' +
					'<div class="f4 ma0 o-50">Shows WooBuilder Blocks content for your product.</div>' +
					'</div></div>' );
			},
			save: function () { return null;}
		} );
		CaxtonBlock( {
			id         : 'woobuilder/product-notices',
			title      : 'WooBuilder: Product notices',
			description: 'Shows product notices.',
			icon       : 'bell',
			category   : 'woobuilder',
			edit: function() {
				return Caxton.html2el(
					'<div class="pa2 tc bg-black-10 o-70 mv1">Shows product notices on frontend.</div>'
				);
			},
			save: function () { return null; }
		} );
	}

	function WooBuilderButtons() {
		var el = wp.element.createElement;
		return [
			el(
				wp.editPost.PluginPostStatusInfo,
				{
					className: 'woobuilder-switch-to-default',
					key      : 'switch2default',
				},
				el(
					'a',
					{
						id       : 'woobuilder-switch-to-default',
						className: 'is-destructive components-button editor-post-trash is-button is-default is-large',
						onClick  : function () {
							var sure = confirm( 'Are you sure You want to revert to default editor?' );
							if ( sure ) {
								window.location = woobuilderData.switchToDefaultEditorUrl
							}
						},
					},
					'Switch to default editor'
				)
			),
		];
	}

	if ( 'product' === postType ) {
		wp.plugins.registerPlugin( 'woobuilder', {
			render: WooBuilderButtons,
		} );
	}

} );