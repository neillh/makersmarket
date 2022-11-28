<?php
/**
 * Customizer Control: responsive
 * Taken from Astra Theme. https://wpastra.com/
 *
 * @package     Skinny\Customizer
 */
namespace Skinny\Customizer\ControlTypes;

/**
 * A text control with validation for CSS units.
 */
class Responsive_Control extends \WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @var string
	 */
	public $type = 'skinny-responsive';

	/**
	 * The responsive type.
	 *
	 * @var string
	 */
	public $responsive = true;

	/**
	 * The control type.
	 *
	 * @var array
	 */
	public $units = array();

	/**
	 * Enqueue control related scripts/styles.
	 *
	 */
	public function enqueue() {

		wp_enqueue_script(
			'skinny-responsive',
			get_parent_theme_file_uri( 'inc/customizer/control-types/responsive-control/responsive.js' ),
			array( 'jquery', 'customize-base' ),
			SKINNY_VERSION,
			true
		);
		wp_enqueue_style(
			'skinny-responsive-css',
			get_parent_theme_file_uri( 'inc/customizer/control-types/responsive-control/responsive.css' ),
			null,
			SKINNY_VERSION
		);
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @see WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$this->json['default'] = $this->setting->default;
		if ( isset( $this->default ) ) {
			$this->json['default'] = $this->default;
		}

		$val = maybe_unserialize( $this->value() );

		if ( ! is_array( $val ) || is_numeric( $val ) ) {

			$val = array(
				'desktop'      => $val,
				'tablet'       => '',
				'mobile'       => '',
				'desktop-unit' => '',
				'tablet-unit'  => '',
				'mobile-unit'  => '',
			);
		}

		$this->json['value']      = $val;
		$this->json['choices']    = $this->choices;
		$this->json['link']       = $this->get_link();
		$this->json['id']         = $this->id;
		$this->json['label']      = esc_html( $this->label );
		$this->json['units']      = $this->units;
		$this->json['responsive'] = $this->responsive;

		$this->json['inputAttrs'] = '';
		foreach ( $this->input_attrs as $attr => $value ) {
			$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
		}

	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 */
	protected function content_template() {
		?>


		<label class="customizer-text" for="" >
			<# if ( data.label ) { #>
			<span class="customize-control-title">{{{ data.label }}}</span>

			<# if ( data.responsive ) { #>
			<ul class="skinny-responsive-btns">
				<li class="desktop active">
					<button type="button" class="preview-desktop active" data-device="desktop">
						<i class="dashicons dashicons-desktop"></i>
					</button>
				</li>
				<li class="tablet">
					<button type="button" class="preview-tablet" data-device="tablet">
						<i class="dashicons dashicons-tablet"></i>
					</button>
				</li>
				<li class="mobile">
					<button type="button" class="preview-mobile" data-device="mobile">
						<i class="dashicons dashicons-smartphone"></i>
					</button>
				</li>
			</ul>
			<# } #>
			<# } #>
			<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
			<# }

			value_desktop = '';
			value_tablet  = '';
			value_mobile  = '';

			if ( data.value['desktop'] ) {
			value_desktop = data.value['desktop'];
			}

			if ( data.value['tablet'] ) {
			value_tablet = data.value['tablet'];
			}

			if ( data.value['mobile'] ) {
			value_mobile = data.value['mobile'];
			} #>

			<div class="input-wrapper skinny-responsive-wrapper">

				<# if ( data.responsive ) { #>
				<input {{{ data.inputAttrs }}} data-id='desktop' class="skinny-responsive-input desktop active" type="number" value="{{ value_desktop }}"/>
				<select class="skinny-responsive-select desktop" data-id='desktop-unit' <# if ( _.size( data.units ) === 1 ) { #> disabled="disabled" <# } #>>
				<# _.each( data.units, function( value, key ) { #>
				<option value="{{{ key }}}" <# if ( data.value['desktop-unit'] === key ) { #> selected="selected" <# } #>>{{{ data.units[ key ] }}}</option>
				<# }); #>
				</select>

				<input {{{ data.inputAttrs }}} data-id='tablet' class="skinny-responsive-input tablet" type="number" value="{{ value_tablet }}"/>
				<select class="skinny-responsive-select tablet" data-id='tablet-unit' <# if ( _.size( data.units ) === 1 ) { #> disabled="disabled" <# } #>>
				<# _.each( data.units, function( value, key ) { #>
				<option value="{{{ key }}}" <# if ( data.value['tablet-unit'] === key ) { #> selected="selected" <# } #>>{{{ data.units[ key ] }}}</option>
				<# }); #>
				</select>

				<input {{{ data.inputAttrs }}} data-id='mobile' class="skinny-responsive-input mobile" type="number" value="{{ value_mobile }}"/>
				<select class="skinny-responsive-select mobile" data-id='mobile-unit' <# if ( _.size( data.units ) === 1 ) { #> disabled="disabled" <# } #>>
				<# _.each( data.units, function( value, key ) { #>
				<option value="{{{ key }}}" <# if ( data.value['mobile-unit'] === key ) { #> selected="selected" <# } #>>{{{ data.units[ key ] }}}</option>
				<# }); #>
				</select>

				<# } else { #>
				<input {{{ data.inputAttrs }}} data-id='desktop' class="skinny-responsive-input skinny-non-reponsive desktop active" type="number" value="{{ value_desktop }}"/>
				<select class="skinny-responsive-select skinny-non-reponsive desktop" data-id='desktop-unit' <# if ( _.size( data.units ) === 1 ) { #> disabled="disabled" <# } #>>
				<# _.each( data.units, function( value, key ) { #>
				<option value="{{{ key }}}" <# if ( data.value['desktop-unit'] === key ) { #> selected="selected" <# } #>>{{{ data.units[ key ] }}}</option>
				<# }); #>
				</select>
				<# } #>
			</div>
		</label>
		<?php
	}

	/**
	 * Render the control's content.
	 *
	 * @see WP_Customize_Control::render_content()
	 */
	protected function render_content() {}
}
