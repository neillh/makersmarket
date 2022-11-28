<?php
/**
 * Switcher Customizer Control.
 *
 * @package Skinny\Customizer\ControlTypes
 */

namespace Skinny\Customizer\ControlTypes;

use function Skinny\Customizer\enqueue_controls_assets;

/**
 * Switcher Control Class.
 */
class Switcher_Control extends \WP_Customize_Control {

	/**
	 * Holds the switcher type
	 *
	 * @var string
	 */
	protected $switcher_type = 'default';

	/**
	 * The slug of this control in the customizer.
	 *
	 * @var string
	 */
	public $type = 'skinny_switcher_control';

	/**
	 * Constructor.
	 *
	 * Supplied `$args` override class property defaults.
	 *
	 * @param \WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string                $id      Control ID.
	 * @param array                 $args    @see \WP_Customize_Control::__construct().
	 */
	public function __construct( \WP_Customize_Manager $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );

		if ( isset( $args['switcher_type'] ) ) {
			$this->switcher_type = $args['switcher_type'];
		}
	}

	/**
	 * Enqueues required JS and CSS
	 *
	 * @return void
	 */
	public function enqueue() {
		wp_enqueue_style(
			'skinny-alpha-color-picker',
			get_theme_file_uri( 'inc/customizer/control-types/switcher-control/switcher-control.css' ),
			array(),
			SKINNY_VERSION
		);
	}

	/**
	 * The control is rendered in JS not PHP.
	 *
	 * @return void
	 */
	public function render_content() {}

	/**
	 * Convert the control settings to JSON.
	 *
	 * @return void
	 */
	public function to_json() {
		parent::to_json();

		$this->json['id']            = $this->id;
		$this->json['value']         = $this->value();
		$this->json['link']          = $this->get_link();
		$this->json['choices']       = $this->choices;
		$this->json['switcher_type'] = isset( $this->switcher_type ) ? $this->switcher_type : 'default';
	}

	/**
	 * Renders the JS template for the control
	 *
	 * @return void
	 */
	protected function content_template() {
		?>
		<#
		if ( ! data.choices ) {
		return;
		}
		#>

		<# if ( data.label ) { #>
		<span class="customize-control-title">{{ data.label }}</span>
		<# } #>

		<# if ( data.description ) { #>
		<span class="customize-control-description">{{ data.description }}</span>
		<# } #>

		<div role="group" class="switcher__wrapper <# if ( data.switcher_type === 'color-scheme' ) { #>color_scheme<# } #>">
			<#
			for ( choice in data.choices ) {
			var c = data.choices[choice];
			#>
			<label for="{{ data.id }}-{{ choice }}" class="switcher__choice">
				<span class="screen-reader-text">{{ c.label }} design style</span>
				<input 	type="radio" value="{{ choice }}"
						  name="_customize-{{ data.id }}" id="{{ data.id }}-{{ choice }}"
						  class="layout"
						  {{{ data.link }}}
				<# if ( data.value === choice ) { #>
				checked="checked"
				<# } #>
				/>
				<# if ( data.switcher_type === 'color-scheme' && c.preview_image ) { #>
					<img src="{{ c.preview_image }}" class="color-scheme"/>
					<span class="color-scheme__check"></span>
					<span class="label">{{ c.label }}</span>
				<# } else if ( c.preview_image ) { #>
					<img src="{{ c.preview_image }}" />
				<# } #>
			</label>
			<# } #>
		</div>
		<?php
	}
}
