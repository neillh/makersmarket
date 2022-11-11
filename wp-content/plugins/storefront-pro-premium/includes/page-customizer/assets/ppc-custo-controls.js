/**
 * Created by shramee on 6/10/15.
 */
jQuery( function ( $, undef ) {
	var api = wp.customize;

	api.lib_alpha_color_control = api.Control.extend( {
		ready: function () {
			var control = this,
				picker = this.container.find( '.color-picker-hex' );

			picker.val( control.setting() ).libColorPicker( {
				change: function () {
					try {
						control.setting.set( picker.libColorPicker( 'color' ) );
					}
					catch ( e ) {
						console.log( e ); // pass exception object to error handler
					}
				},
				clear: function () {
					control.setting.set( '' );
				}
			} );

			control.setting.bind( function ( value ) {
				picker.val( value );
				picker.libColorPicker( 'color', value );
			} );
		}
	} );

	api.controlConstructor['lib_color'] = api.lib_alpha_color_control;
	setTimeout(
		function () {
			var
				$bgT = $( 'select[data-customize-setting-link="pootle-page-customizer[background-type]"]' ),
				$ctrl = {
					color: $( '#customize-control-pootle-page-customizer-background-color' ),
					image: $( '#customize-control-pootle-page-customizer-background-image, #customize-control-pootle-page-customizer-background-attachment' ),
					video: $( '#customize-control-pootle-page-customizer-background-video, #customize-control-pootle-page-customizer-background-responsive-image' ),
				},
				backgroundType = function () {
					var
						hide = ['color', 'image', 'video'];
					for ( var i = 0; i < hide.length; i ++ ) {
						if ( hide[i] === $bgT.val() ) {
							$ctrl[ hide[i] ].show();
						} else {
							$ctrl[ hide[i] ].hide();
						}
					}
				};

			$bgT.change( backgroundType );
			backgroundType();
		}, 700
	);
} );