<?php
/**
 * Customizer Custom Slider Control
 *
 * @package Skinny
 */

namespace Skinny\Customizer\ControlTypes;

/**
 * Class Range_Slider
 *
 * Control for adding arbitrary HTML to a Customizer section.
 */
class Range_Slider_Control extends \WP_Customize_Control {
	/**
	 * The type of control being rendered
	 */
	public $type = 'slider_control';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 */
	public function enqueue() {
		wp_enqueue_script(  'skinny-range-slider', get_parent_theme_file_uri( 'inc/customizer/control-types/range-slider-control/slider.js' ), array( 'jquery', 'customize-controls' ), SKINNY_VERSION, true );
		wp_enqueue_style( 'skinny-range-slider-css', get_parent_theme_file_uri('inc/customizer/control-types/range-slider-control/slider.css' ), null, SKINNY_VERSION );
	}

	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		?>
		<div class="slider-custom-control">
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span><span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span><input type="number" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" class="customize-control-slider-value" <?php $this->link(); ?> />
			<div class="slider" slider-min-value="<?php echo isset( $this->input_attrs['min'] ) ? esc_attr( $this->input_attrs['min'] ) : ''; ?>" slider-max-value="<?php echo isset( $this->input_attrs['max'] ) ? esc_attr( $this->input_attrs['max'] ) : ''; ?>" slider-step-value="<?php echo isset( $this->input_attrs['step'] ) ? esc_attr( $this->input_attrs['step'] ) : ''; ?>"></div><span class="slider-reset dashicons dashicons-image-rotate" slider-reset-value="<?php echo esc_attr( $this->value() ); ?>"></span>
		</div>
		<?php
	}
}
