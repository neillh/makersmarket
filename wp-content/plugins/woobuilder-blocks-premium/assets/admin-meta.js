(
	function () {

		var components = wp.components;
		var el = wp.element.createElement;

		function _WooBuilderProductSettings( props ) {
			metaData = props.metaData;
			var controls = [
				el(
					components.TextControl,
					{
						label: 'Custom Add To Cart Text',
						value: metaData.woobk_add_to_cart_text || '',
						onChange: function ( val ) {
							props.setMeta( 'woobk_add_to_cart_text', val )
						}
					}
				),
				el(
					components.TextControl,
					{
						label: 'Custom Out Of Stock Text',
						value: metaData.woobk_out_of_stock_text || '',
						onChange: function ( val ) {
							props.setMeta( 'woobk_out_of_stock_text', val )
						}
					}
				),
				el(
					components.TextControl,
					{
						label: 'Custom On Back Order Text',
						value: metaData.woobk_on_back_order_text || '',
						onChange: function ( val ) {
							props.setMeta( 'woobk_on_back_order_text', val )
						}
					}
				),
				el(
					components.SelectControl,
					{
						label: 'Custom Thank You Page',
						value: metaData.woobk_thankyou_page || '',
						options: [{ value:'', label: 'Please choose...' }, ...woobuilderData.pages],
						onChange: function ( val ) {
							props.setMeta( 'woobk_thankyou_page', val )
						}
					}
				),
				el(
					components.ToggleControl,
					{
						label   : 'Hide Header',
						checked : 'hide' === metaData.woobk_hide_header,
						value: 'hide',
						onChange: function ( val ) {
							props.setMeta( 'woobk_hide_header', val ? 'hide' : '' )
						}
					}
				),
				el(
					components.ToggleControl,
					{
						label   : 'Hide Sidebar',
						checked : 'hide' === metaData.woobk_hide_sidebar,
						value: 'hide',
						onChange: function ( val ) {
							props.setMeta( 'woobk_hide_sidebar', val ? 'hide' : '' )
						}
					}
				),
				el(
					components.ToggleControl,
					{
						label   : 'Hide Footer',
						checked   : 'hide' === metaData.woobk_hide_footer,
						value: 'hide',
						onChange: function ( val ) {
							props.setMeta( 'woobk_hide_footer', val ? 'hide' : '' )
						}
					}
				),

				el(
					wp.blockEditor.PanelColorSettings,
					{
						title        : 'Background',
						colorSettings: [
							{
								label   : 'Background color',
								value   : metaData.woobk_bg_color,
								onChange: function ( val ) {
									props.setMeta( 'woobk_bg_color', val )
								},
							}
						],
					},
					el(
						components.BaseControl,
						{
							label: 'Background image',
						},
						el(
							wp.editor.MediaUpload,
							{
								key         : 'imagePicker',
								onSelect( media ) {
									props.setMeta( 'woobk_bg_image', media.url );
								},
								allowedTypes: ['image'],
								value       : metaData.woobk_bg_image,
								label       : 'Background image',
								render( {open} ) {
									if ( metaData.woobk_bg_image ) {
										return [
											el( 'img', {src: metaData.woobk_bg_image, className: 'mb2 db', key: 'image'} ),
											el( 'button', {
													className: 'components-button is-secondary is-small',
													onClick  : open,
													key      : 'btn'
												},
												'Change image'
											),
											el( 'button', {
													className: 'components-button is-tertiary is-small fr',
													onClick() { props.setMeta( 'woobk_bg_image', '' ) },
													key      : 'btn'
												},
												'Remove image'
											),
										];
									}
									return el( 'span', {className: 'ml3 v-mid dib'},
										el( components.Button, {
												className: 'is-primary is-small',
												onClick  : open,
												key      : 'btn'
											},
											'Select image'
										)
									);
								},
							}
						),
					),
					el(
						components.ToggleControl,
						{
							label   : 'Background fixed parallax',
							checked : 'fix' === metaData.woobk_bg_parallax,
							value: 'fix',
							onChange: function ( val ) {
								props.setMeta( 'woobk_bg_parallax', val ? 'fix' : '' )
							}
						}
					),
					el(
	//					components.GradientPickerControl,
						components.__experimentalGradientPicker || components.GradientPicker,
						{
							label   : 'Background gradient',
							value   : metaData.woobk_bg_gradient,
							options : [
								{label: 'Show', value: '',},
								{label: 'Hide', value: 'hide',},
							],
							onChange: function ( val ) {
								props.setMeta( 'woobk_bg_gradient', val )
							}
						}
					),
				),
			];

			return el(
				wp.editPost.PluginDocumentSettingPanel,
				{className: "my-document-setting-plugin", title: "WooBuilder Settings", icon: null},
				controls
			);
		}

		function blocksWrapStyle( prop, val ) {
			if ( !blocksWrapStyle.wrapper ) blocksWrapStyle.wrapper = document.querySelector( '.edit-post-visual-editor' );
			if ( blocksWrapStyle.wrapper ) blocksWrapStyle.wrapper.style[prop] = val;
		}

		var metaEffects = {
			woobk_bg_color: function ( val ) {
				val && blocksWrapStyle( 'backgroundColor', val );
			},
			woobk_bg_image: function ( val ) {
				blocksWrapStyle( 'background', val ? 'center/cover url("' + val + '")' : '' );
			},
			woobk_bg_gradient: function ( val ) {
				val && blocksWrapStyle( 'backgroundImage', val );
			},
		};

		function applyMetaEffects( metaData ) {
			if ( ! metaData ) return;
			for ( var name in metaEffects ) {
				if ( metaEffects.hasOwnProperty( name ) ) {
					metaEffects[name]( metaData[name] || '', name );
				}
			}
		}

		var mapDispatchToProps = function ( dispatch ) {
			return {
				setMeta: function ( name, value ) {
					var meta = {};
					meta[name] = value || '';
					if ( metaEffects[name] ) {
						metaEffects[name]( meta[name], name );
					}
					dispatch( 'core/editor' ).editPost( {meta: meta} );
				},
			}
		}

		var mapSelectToProps = function ( select ) {
			var metaData = select( 'core/editor' ).getEditedPostAttribute( 'meta' );
			applyMetaEffects( metaData );
			return {metaData: metaData || {}};
		}

		var WooBuilderProductSettings = wp.data.withDispatch( mapDispatchToProps )(
			wp.data.withSelect( mapSelectToProps )( _WooBuilderProductSettings )
		);

		wp.plugins.registerPlugin( 'woobuilder-product-settings', {render: WooBuilderProductSettings} );
	}
)();