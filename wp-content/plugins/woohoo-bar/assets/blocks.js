! function () {
	var el = wp.element.createElement;
	var createBlock = wp.blocks.createBlock;
	// region Meta plugin

	function _WoohooSettingsPanel( props ) {
		return [
			el(
				wp.components.SelectControl,
				{
					label   : 'Bar Display',
					value   : props.woohoo_bar_display,
					options : [
						{label: 'Never (Inactive)', value: '',},
						{label: 'On selected categories', value: 'categories',},
						{label: 'Everywhere', value: 'everywhere',},
					],
					onChange: function ( val ) {
						props.set_display( val )
					}
				}
			),
/*
			el(
				wp.components.SelectControl,
				{
					label   : 'Bar Position',
					value   : props.woohoo_bar_position,
					options : [
						{label: 'Top', value: '',},
						{label: 'Top (fixed)', value: 'top-fixed',},
						{label: 'Bottom', value: 'bottom',},
						{label: 'Botom (fixed)', value: 'bottom-fixed',},
					],
					onChange: function ( val ) {
						props.set_position( val )
					}
				}
			),
*/
//			el(
//				wp.components.ToggleControl,
//				{
//					label   : 'Show Close icon',
//					checked : !! props.woohoo_bar_close_btn,
//					onChange: function ( val ) {
//						props.set_close_btn( val )
//					}
//				}
//			),
		];
	}

	var mapSelectToProps = function( select ) {
		var metaData = select( 'core/editor' ).getEditedPostAttribute( 'meta' );
		if ( ! metaData ) {
			return {woohoo_bar_close_btn: '', woohoo_bar_display: '', woohoo_bar_position: ''}
		}
		return metaData;
	}

	var mapDispatchToProps = function( dispatch ) {
		function metaUpdateCallback( key ) {
			return function( value ) {
				metaData = {};
				if ( typeof value === 'boolean' ) {
					value = value ? '1' : '';
				}
				metaData[key] = value;
				dispatch( 'core/editor' ).editPost( { meta: metaData } );
			}
		}
		return {
			set_close_btn: metaUpdateCallback( 'woohoo_bar_close_btn' ),
			set_display: metaUpdateCallback( 'woohoo_bar_display' ),
			set_position: metaUpdateCallback( 'woohoo_bar_position' ),
		}
	}

	var WoohooSettingsPanel = wp.data.withDispatch( mapDispatchToProps )(
		wp.data.withSelect( mapSelectToProps )( _WoohooSettingsPanel )
	);

	wp.plugins.registerPlugin( 'woohoo-bar-settings', {
		render: function() {
			return el(
				wp.editPost.PluginDocumentSettingPanel,
				{
					title    : 'Woohoo bar visibility',
					className: 'woobuilder-switch-to-default',
					key      : 'switch2default',
				},
				el( WoohooSettingsPanel, {} )
			);
		},
	} );

	function woohooBarRender( props, block, childrenBlocks ) {
		var el = wp.element.createElement;
		var blkProps = {
			className: 'relative caxton-flex-block woohoo-bar',
			key      : 'block',
			style    : {
				minHeight     : block.attrs['Min height'] + 'px',
				alignItems    : 'center',
			}
		};

		if ( block.attrs['alignment'] ) {
			blkProps.style.justifyContent = block.attrs['alignment'];
		}

		return el(
			'div',
			{className: 'relative', key: 'caxton-flex-block-wrap',},
			el( 'div', {
				key: 'bg',
				className: 'absolute absolute--fill',
				dangerouslySetInnerHTML: block.outputHTML( '{{Background}}' )
			} ),
			el( 'div', blkProps, childrenBlocks )
		);
	}

	// endregion Meta plugin

	// region Bar block

	CaxtonLayoutOptionsBlock(
		{
			id          : 'woohoo-bar/bar',
			description : woohoobarData && woohoobarData.post_type === 'woohoo_bar' ?
				'Displays a bar on your site, Switch to Document tab to set Bar Display settings in Woohoo bar visibility panel.' :
				'Displays a bar on your site.',
			title       : 'Woohoo bar',
			icon        : 'archive',
			category    : 'layout',
			fields      : function() {
				return {
					'Background': {
						type   : 'background',
						section: 'Background',
					},
					'bar_position': {
						label  : 'Bar position',
						type   : 'select',
						options : [
							{label: 'Top', value: '',},
							{label: 'Top (fixed)', value: 'top-fixed',},
							{label: 'Bottom', value: 'bottom',},
							{label: 'Botom (fixed)', value: 'bottom-fixed',},
						],
						section: 'Layout',
					},
					'show_on'    : {
						label: 'Show on',
						section: 'Layout',
						type: 'select',
						options: [
							{value: '', label: 'On all devices'},
							{value: 'dn-s dn-m', label: 'Only desktop'},
							{value: 'dn-l', label: 'Only mobile'},
						],
					},
					'alignment'    : {
						label: 'Items alignment',
						section: 'Layout',
						type: 'select',
						options: [
							{value: '', label: 'Center'},
							{value: 'space-between', label: 'Justify'},
						],
					},
					'close'    : {
						label: 'Show close icon',
						section: 'Layout',
						type: 'toggle',
						options: [
							{value: '', label: 'Center'},
							{value: 'woohoo-bar-justify-last', label: 'Justify last item'},
							{value: 'woohoo-bar-justify-first', label: 'Justify first item'},
						],
					},
					'Min height': {
						type   : 'range',
						tpl    : 'min-height:%spx;',
						section: 'Layout',
						max    : 999,
					}
				};
			},
			resizable: {height: 'Min height'},
			chooseLayoutTitle: 'Pick your bar...',
			render      : function( props, block, childrenBlocks ) {
				return woohooBarRender( props, block, childrenBlocks );
			},
			transforms: {from: [{type: 'caxton/horizontal'}]},
		},
		[
			{
				title: 'Text and button',
				img  : woohoobarData.url + 'text-and-button.png',
				props: {
					"Background color":"#2aa3ef",
					tpl: [
						["core/paragraph", {"fontSize": "medium", "placeholder": "Your Woohoo bar text...", "customTextColor": "#fff", "style":{"color":{"text":"#fff"}},} ],
						["core/buttons",[],[["core/button",{"customBackgroundColor":"#0693e4","customTextColor":"#ffffff","borderRadius":2,"className":"is-style-outline"}]]]
					],
				}
			},
			{
				title: 'Text and Countdown',
				img  : woohoobarData.url + 'text-and-countdown.png',
				props: {
					"Background color":"#2aa3ef",
					tpl: [
						["core/paragraph", {"fontSize": "medium", "placeholder": "Your Woohoo bar text...", "customTextColor": "#fff", "style":{"color":{"text":"#fff"}},} ],
						["woohoo-bar/fixed-time-countdown", {}]
					],
				}
			},
			{
				title: 'Start from scratch',
				img  : woohoobarData.url + 'scratch.png',
				props: {
					"Background color":"#2aa3ef",
					tpl: '[]',
				}
			},
		]
	);

	// endregion Bar block

	// region Countdown blocks

	function woohooCountdownBlock( _props, errorMsgFn ) {
		var
			id = _props.id,
			title = _props.title,
			fields = _props.fields;

		delete _props.fields;
		delete _props.id;

		var dataProps = Object.keys( fields );

		errorMsgFn = errorMsgFn || function( attr ) {
			for ( var i = 0; i < dataProps.length; i ++ ) {
				if ( window.shrameeDbg ) debugger;
				var datum = dataProps[i];
				if ( ! attr[datum] && isNaN( parseInt( ( attr[datum] ) ) ) ) {
					return 'Please fill in ' + (fields[datum].label || datum) + '.';
				}
			}
		}

		var props = jQuery.extend( {
			id         : 'woohoo-bar/' + id,
			icon       : 'clock',
			category   : 'layout',
			attributes : {
				tpl: {
					type: 'string'
				},
			},
			apiUrl     : function ( props ) {
				var
					attr = props.attributes,
					qry  = '';

				for ( var i = 0; i < dataProps.length; i ++ ) {
					var datum = dataProps[i];
					qry += datum + '=' + attr[datum] + '&';
				}

				return {
					apiData:
						'/woohoo_bar/v1/' + id + '?' + qry + 'x',
				};
			},
			save       : function ( props, that ) {
				classes = 'woohoo-bar-countdown woohoo-bar-' + id + ' relative';

				var allHTML =
							'<div class="' + classes + '">' +
							'<div key="bg" class="absolute absolute--fill {{Background Blur}}">{{Background}}</div>' +
							'<div class="relative pa1 tc flex flex-column items-center justify-center" style="">' +
							'<div style="{{Counter Font}}{{Letter Spacing}}{{Font size}}{{Text color}}">%content%</div>' +
							'</div></div>';

				// Populate styles
				allHTML = that.outputHTML( allHTML ).__html;


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
				var error = errorMsgFn( attr );

				if ( error ) {
					return wp.element.createElement( 'div', {
						className: 'ph2 pv1',
						style    : {color: '#d00', backgroundColor: '#fdd'},
						key      : 'notice'
					}, error );

				} else if ( props.apiData.data ) {
					classes = 'woohoo-bar-countdown relative';

					var allHTML =
								'<div class="' + classes + '">' +
								'<div key="bg" class="absolute absolute--fill {{Background Blur}}">{{Background}}</div>' +
								'<div class="relative pa1 tc flex flex-column items-center justify-center">' +
								'<div style="{{Counter Font}}{{Letter Spacing}}{{Font size}}{{Text color}}">' + props.apiData.data.html + '</div>' +
								'</div></div>';

					// Populate styles
					allHTML = that.outputHTML( allHTML ).__html;

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
						className: 'ph2 pv1',
						style    : {color: '#333', backgroundColor: '#eee'},
						key      : 'notice'
					}, 'Loading ' + title + '...' );
				}
			},
			fields     : jQuery.extend( fields, {
				'finish'    : {
					label: 'Countdown finish',
					type: 'select',
					options: [
						{value: '', label: 'Show zero'},
						{value: 'hide', label: 'Hide bar'},
					],
				},
				'Background'    : {
					type: 'background',
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
			}, fields ),
		}, _props );

		console.log( props.id, title );

		CaxtonBlock( props );
	}

	woohooCountdownBlock( {
		id: 'fixed-time-countdown',
		title: 'Fixed time countdown',
		description: 'Countdown ends at a specific date and time you specify.',
		fields: {
			'ending': {
				label: 'Ending date and time',
				type : 'datetime',
			},
		},
		transforms: {
			from: [
				{
					type  : 'block',
					blocks: ['woohoo-bar/fixed-time-countdown', 'woohoo-bar/regular-countdown', 'woohoo-bar/evergreen-countdown',],
					transform: function ( attr ) {
						return createBlock( 'woohoo-bar/fixed-time-countdown', attr );
					},
				},
			]
		},
	} );

	woohooCountdownBlock( {
		id: 'regular-countdown',
		title: 'Regular countdown',
		description: 'Counts down to specified time everyday.',
		fields: {
			'endtime': {
				help : 'Time of day countdown will count to.',
				label: 'Ending time',
				type : 'time',
			},
		},
		transforms: {
			from: [
				{
					type  : 'block',
					blocks: ['woohoo-bar/fixed-time-countdown', 'woohoo-bar/regular-countdown', 'woohoo-bar/evergreen-countdown',],
					transform: function ( attr ) {
						return createBlock( 'woohoo-bar/regular-countdown', attr );
					},
				},
			]
		},
	} );

	woohooCountdownBlock( {
		id: 'evergreen-countdown',
		title: 'Evergreen countdown',
		description: 'Starts a new counter for every visitor for specified duration.',
		fields: {
			'hours'  : {
				help : 'Hours countdown will count to.',
				label: 'Countdown hours',
				type : 'number',
			},
			'minutes': {
				help : 'Minutes countdown will count to.',
				label: 'Countdown minutes',
				type : 'number',
				default: '0',
			},
		},
		transforms: {
			from: [
				{
					type  : 'block',
					blocks: ['woohoo-bar/fixed-time-countdown', 'woohoo-bar/regular-countdown', 'woohoo-bar/evergreen-countdown',],
					transform: function ( attr ) {
						return createBlock( 'woohoo-bar/evergreen-countdown', attr );
					},
				},
			]
		},
	} );

	// endregion Countdown blocks

}();