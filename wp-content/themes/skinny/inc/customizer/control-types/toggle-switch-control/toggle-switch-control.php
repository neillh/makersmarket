<?php
/**
 * Customizer Custom Toggle Switch Control
 *
 * @package Skinny
 */
namespace Skinny\Customizer\ControlTypes;

/**
 * Class Toggle_Switch_Control
 *
 * Control for adding arbitrary HTML to a Customizer section.
 */
class Toggle_Switch_Control extends \WP_Customize_Control {
	/**
	 * The type of control being rendered.
	 */
	public $type = 'toggle_switch';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 */
	public function enqueue() {
		wp_enqueue_style( 'skinny-toggle-switch-css', get_theme_file_uri( 'inc/customizer/control-types/toggle-switch-control/toggle-switch.css' ), null, SKINNY_VERSION );
	}

	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		?>
		<div class="toggle-switch-control">
			<div class="toggle-switch">
				<input type="checkbox" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" class="toggle-switch-checkbox" value="<?php echo esc_attr( $this->value() ); ?>"
					<?php
					$this->link();
					checked( $this->value() );
					?>
				>
				<label class="toggle-switch-label" for="<?php echo esc_attr( $this->id ); ?>">
					<span class="toggle-switch-inner"></span>
					<span class="toggle-switch-switch"></span>
				</label>
			</div>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php if ( ! empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php } ?>
		</div>
		<?php
	}
}
