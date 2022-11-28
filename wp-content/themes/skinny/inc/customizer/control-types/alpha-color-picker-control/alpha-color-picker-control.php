<?php
/**
 * Customizer Custom Alpha Color Picker Control
 *
 * @package Skinny
 */
namespace Skinny\Customizer\ControlTypes;

/**
 * Class Alpha_Color_Picker_Control
 *
 * Control for adding arbitrary HTML to a Customizer section.
 */
class Alpha_Color_Picker_Control extends \WP_Customize_Control {
	/**
	 * The type of control being rendered
	 */
	public $type = 'alpha-color';

	/**
	 * Add support for palettes to be passed in.
	 *
	 * Supported palette values are true, false, or an array of RGBa and Hex colors.
	 */
	public $palette;

	/**
	 * Add support for showing the opacity value on the slider handle.
	 */
	public $show_opacity;

	/**
	 * Enqueue control related scripts/styles.
	 *
	 */
	public function enqueue() {
		wp_enqueue_style( 'skinny-alpha-color-picker-css', get_theme_file_uri( 'inc/customizer/control-types/alpha-color-picker-control/alpha-color-picker.css' ), array( 'wp-color-picker' ), SKINNY_VERSION );

		wp_enqueue_script( 'skinny-alpha-color-picker', get_theme_file_uri( 'inc/customizer/control-types/alpha-color-picker-control/alpha-color-picker.js' ), array( 'jquery', 'customize-controls', 'wp-color-picker' ), SKINNY_VERSION, true );

		wp_localize_script( 'skinny-alpha-color-picker', 'ACP', array(
			'colorSelectString' => __( 'Select', 'skinny' ),
		) );
	}

	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		// Process the palette.
		if ( is_array( $this->palette ) ) {
			$palette = implode( '|', $this->palette );
		} else {
			// Default to true.
			$palette = ( false === $this->palette || 'false' === $this->palette ) ? 'false' : 'true';
		}
		// Support passing show_opacity as string or boolean. Default to true.
		$show_opacity = ( false === $this->show_opacity || 'false' === $this->show_opacity ) ? 'false' : 'true';
		?>
		<label>
			<?php
			// Output the label and description if they were passed in.
			if ( isset( $this->label ) && '' !== $this->label ) {
				echo '<span class="customize-control-title">' . esc_html( $this->label ) . '</span>';
			}
			if ( isset( $this->description ) && '' !== $this->description ) {
				echo '<span class="description customize-control-description">' . esc_html( $this->description ) . '</span>';
			}
			?>
		</label>
		<input class="alpha-color-control" type="text" data-show-opacity="<?php echo esc_attr( $show_opacity ); ?>" data-palette="<?php echo esc_attr( $palette ); ?>" data-default-color="<?php echo esc_attr( $this->settings['default']->default ); ?>" <?php $this->link(); ?>  />
		<?php
	}
}
