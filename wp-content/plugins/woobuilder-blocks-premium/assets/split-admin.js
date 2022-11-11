jQuery( function ( $ ) {
	var
		a2cConversionNotice = 'Add to cart is counted as conversion.',
		el          = wp.element.createElement,
		InnerBlocks = window.caxtonWPEditor.InnerBlocks;

	function testLabel( i, suff ) {
		suff = suff ? ' ' + suff : '';
		return 'Test ' + ( 1 + + i ) + suff;
	}

	function getTestStats( test, i ) {
		var stats = wbkSplitTestingData.stats;
		if ( ! stats[ test ] ) {
			stats[ test ] = [];
		}

		if ( ! stats[ test ][i] ) {
			stats[ test ][i] = {
				impressions: 0,
				conversions: 0,
			};
		}

		return stats[test][i];
	}

	function outputStats( testName, tests ) {
		var els = [], i;

		var forceUpdate = wp.element.useState( 0 );

		for ( i = 0; i < tests; i ++ ) {
			var stats = getTestStats( testName, i );
			els.push(
				el( 'div', {key:i, className: 'flex items-center mt1 ph2 pv1 stripe-dark'},
					el( 'div', {key:'head',className:'w-20'},
						el( 'div', {className: 'f3 fw3'}, el( 'small', {className: 'v-mid'}, 'Test' ), ' #' + (i + 1) ),
					),
					el( 'div', {key:'convpc',className:'w-40'},
						el( 'div', {key:'head',className: 'f6'}, 'Conversion rate' ),
						el( 'div', {key:'valu',className: 'f3 fw3'},
							stats.impressions ? Math.round( 1000 * stats.conversions / stats.impressions ) / 10 + '%' : '0%'
						),
					),
					el( 'div', {key:'imp',className:'w-40'},
						el( 'div', {key: 'Conv', className: 'f5'}, 'Conversions: ' + stats.conversions + '*' ),
						el( 'div', {key: 'Impr', className: 'f5'}, 'Impressions: ' + stats.impressions ),
					)
				)
			);
		}

		els.push(
			el(
				'footer', {key: 'footer'},
				el(
					'button', {
						key: 'clear', className: 'button fr', onClick: function () {
							if ( ! confirm( 'Are you sure you want to clear stats for ' + testName + ' split test?' ) ) return;
							var request = new XMLHttpRequest();
							request.open( 'POST', ajaxurl, true );
							request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8' );
							request.send( 'action=woobk_stest_clear&test_data=' + wbkSplitTestingData.post + '--' + testName );
							delete wbkSplitTestingData.stats[testName];
							forceUpdate[1]( forceUpdate[0] + 1 )
						}
					}, 'Clear stats'
				),
				el( 'div', {key: 'conv-notice', className: 'f5'}, '*' + a2cConversionNotice )
			)
		);

		return el( 'div', { className: 'flex flex-column' }, els );
	}

	CaxtonBlock( {
		id      : 'woobuilder/split-testing',
		title   : 'WooBuilder: Split testing',
		icon    : 'forms',
		category: 'woobuilder',
		fields  : {
			'name' : {
				label  : 'Test name',
				type   : 'text',
				default: 'My test',
				description: 'Give your test a descriptive name',
			},
			'tests': {
				label  : 'Tests',
				type   : 'select',
				options: [
					{value: '2', label: 'A/B (2 variations)',},
					{value: '3', label: 'A/B/C (3 tests)',},
				],
				default: '2',
			},
			'winner': {
				label: 'Pick a winner to conclude the test',
				render : function( args, $t ) {
					var winner = $t.attrs.winner;
					args.options = [
						{value: '', label: 'Test ongoing...'}
					];
					args.help = a2cConversionNotice;
					for ( var i = 0; i < $t.attrs.tests; i ++ ) {
						var st = getTestStats( $t.attrs.name, i );
						var convRate = st.impressions ? Math.round( 1000 * st.conversions / st.impressions ) / 10 + '%' : '0%';
						args.options.push( { value: i.toString(), label: testLabel( i, '(' + convRate + ')' ) } );
					}
					return $t.selectFieldEl( args );
				},
				section: 'Winner',
			},
		},
		edit    : function ( props, block ) {
			if ( ! props.attributes.name ) {
				return el( 'div', {className: 'woob-stest-wrap pa5'}, 'Please give your test a name...' );
			}
			var tabs     = [],
					secBk    = "woobuilder/split-test-section",
					name     = props.attributes.name,
					winner   = props.attributes.winner,
					tests    = + props.attributes.tests,
					tpl      = [], i,
					children = [];

			var tabStateMan = wp.element.useState( 0 );
			var activeTab = tabStateMan[0];
			var setActiveTab = tabStateMan[1];

			if ( typeof winner !== "number" ) {
				winner = winner.toString();
			}

			for ( i = 0; i < tests; i ++ ) {
				tpl.push( [secBk, {index: i.toString()}] );
			}

			var innerBlocks = el( InnerBlocks, {className: 'mv4', template: tpl, allowedBlocks: [secBk], templateLock: 'all', key: 'testsTpl'} );

			children = [innerBlocks];

			var tabProps = {
				href   : '#_', className: 'nav-tab',
				onClick: function ( e ) {
					e.preventDefault();
					setActiveTab( e.target.getAttribute( 'data-index' ) );
				}
			};
			if ( winner ) {
				tabs.push(
					el( 'a', Object.assign( tabProps, {key: winner, 'data-index': i} ), 'Winner ' + testLabel( winner ) )
				);
			} else {
				for ( i = 0; i < tests; i ++ ) {
					tabs.push(
						el( 'a', Object.assign( tabProps, {key: i, 'data-index': i} ), testLabel( i ) )
					);
				}

				tabs.push(
					el( 'a', Object.assign( tabProps, {className: 'nav-tab fr', key: 's', 'data-index': 'stats'} ), 'Stats' )
				);
			}

			children.splice( 0, 0, el( 'header', {className: "nav-tab-wrapper", key: 'nav'}, tabs ) );
			children.splice( 0, 0, el( 'span', {className: 'fl ml1 mr2 f4 mt2', key: 'head'}, 'Split testing' ) );

			children.push( el(
				'div', {key: 'stats', className: 'split-test-section-stats mv4', 'woob-stest-id': 'stats'}, outputStats( name, tests )
			) );

			return el( 'div', {
				className        : 'woob-stest-wrap', 'woob-stest': winner ? winner : activeTab,
				'woob-stest-name': wbkSplitTestingData.post + '--' + name
			}, children );
		},
		save    : function ( props, block ) {
			const attributes = {
				className: 'woob-stest-wrap',
				'woob-stest-name': wbkSplitTestingData.post + '--' + props.attributes.name
			};
			if ( props.attributes.winner ) {
				attributes['woob-stest'] = props.attributes.winner;
			}
			return el( 'div', attributes, el( InnerBlocks.Content, null ) );
		},
	} );

	CaxtonBlock( {
		id          : 'woobuilder/split-test-section',
		parent      : ['woobuilder/split-testing'],
		title       : 'WooBuilder: Split test',
		icon        : 'forms',
		category    : 'woobuilder',
		attributes  : {index: {type: 'string'},},
		wrapperProps: function ( attrs, props ) {
			attrs['woob-stest-id'] = props.index;
			return attrs;
		},
		edit        : function ( props ) {
			var i = +props.attributes.index;
			var tnum = i + 1;

			return el(
				'div',
				{className: 'split-test-section-' + i, 'woob-stest-id': i},
				el(
					InnerBlocks,
					{
						templateLock: false,
						template: [['core/paragraph']],
					}
				)
			);
		},
		save        : function ( props ) {
			var i = props.attributes.index;
			return el( 'div', {className: 'split-test-section-' + i, 'woob-stest-id': i}, el( InnerBlocks.Content, null ) );
		}
	} );
} );