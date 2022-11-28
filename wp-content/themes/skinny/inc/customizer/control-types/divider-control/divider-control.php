<?php
/**
 * Customizer Custom Divider Control.
 *
 * @package Skinny\Customizer
 */
namespace Skinny\Customizer\ControlTypes;

/**
 * Class Divider_Control
 * Control for adding arbitrary HTML to a Customizer section.
 *
 */
class Divider_Control extends \WP_Customize_Control {
	/**
	 * The current setting.
	 *
	 * @var string The current setting.
	 */
	public $setting = '';

	/**
	 * The current setting description.
	 *
	 * @since 1.0.0.
	 *
	 * @var   string    The current setting description.
	 */
	public $description = '';

	/**
	 * CSS Ids to be toggled.
	 *
	 * @var string Comma separated CSS IDs.
	 */
	public $toggle_ids = '';

	/**
	 * The current setting group.
	 *
	 * @var   string    The current setting group.
	 */
	public $group = '';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 */
	public function enqueue() {
		wp_enqueue_script(  'skinny-divider-control', get_parent_theme_file_uri( 'inc/customizer/control-types/divider-control/divider.js' ), array( 'jquery', 'customize-controls' ), SKINNY_VERSION, true );
		wp_enqueue_style( 'skinny-divider-control-css', get_parent_theme_file_uri('inc/customizer/control-types/divider-control/divider.css' ), null, SKINNY_VERSION );
	}


	/**
	 * Render the description and title for the section.
	 *
	 * Prints arbitrary HTML to a customizer section. This provides useful hints for how to properly set some custom
	 * options for optimal performance for the option.
	 *
	 * @return void
	 */
	public function render_content() {
		switch ( $this->type ) {
			default:
			case 'text':
				echo '<p class="description">' . wp_kses( $this->description, wp_kses_allowed_html() ) . '</p>';
				break;

			case 'heading':
				echo '<span class="customize-control-title">' . wp_kses( $this->description, wp_kses_allowed_html() ) . '</span>';
				break;

			case 'line':
				echo '<hr />';
				break;

			case 'reset-btn':
				echo '<button class="button-link" type="button"><span class="dashicons dashicons-image-rotate"></span>' . wp_kses( $this->label, wp_kses_allowed_html() ) . '</button>';
				break;
			case 'expand-header':
				echo '<h3 class="expand-header"><a class="expand-toggle accordion-section-title" href="' . esc_attr( $this->toggle_ids ) . '">' . wp_kses( $this->label, wp_kses_allowed_html() ) . '</a></h3>';
				break;
		}
	}
}
