<?php
/**
 * Customizer Custom Multiple Select Control
 *
 * @package Skinny\Customizer
 */
namespace Skinny\Customizer\ControlTypes;

/**
 * Class Customize_Multiple_Select_Control
 *
 * Control for adding arbitrary HTML to a Customizer section.
 */
class Multiple_Select_Control extends \WP_Customize_Control {

	/**
	 * The type of control being rendered
	 */
	public $type = 'dropdown_select2';

	/**
	 * The type of Select2 Dropwdown to display. Can be either a single select dropdown or a multi-select dropdown. Either false for true. Default = false
	 */
	private $multiselect = false;

	/**
	 * The Placeholder value to display. Select2 requires a Placeholder value to be set when using the clearall option. Default = 'Please select...'
	 */
	private $placeholder = 'Type or Select..';

	/**
	 * Constructor
	 */
	public function __construct( $manager, $id, $args = array(), $options = array() ) {
		parent::__construct( $manager, $id, $args );
		// Check if this is a multi-select field
		if ( isset( $this->input_attrs['multiselect'] ) && $this->input_attrs['multiselect'] ) {
			$this->multiselect = true;
		}
		// Check if a placeholder string has been specified
		if ( isset( $this->input_attrs['placeholder'] ) && $this->input_attrs['placeholder'] ) {
			$this->placeholder = $this->input_attrs['placeholder'];
		}
	}

	/**
	 * Enqueue control related scripts/styles.
	 *
	 */
	public function enqueue() {

		wp_enqueue_script( 'skinny-multiple-select', get_parent_theme_file_uri( 'inc/customizer/control-types/multiple-select-control/multiple-select.js' ), array( 'jquery', 'customize-controls', 'select2' ), SKINNY_VERSION, true );
		wp_enqueue_style( 'skinny-multiple-select-css', get_parent_theme_file_uri( 'inc/customizer/control-types/multiple-select-control/multiple-select.css' ), null, SKINNY_VERSION );
	}

	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		$defaultValue = $this->value();
		if ( $this->multiselect ) {
			$defaultValue = explode( ',', $this->value() );
		}
		?>
		<div class="dropdown_select2_control">
			<?php if ( ! empty( $this->label ) ) { ?>
				<label for="<?php echo esc_attr( $this->id ); ?>" class="customize-control-title">
					<?php echo esc_html( $this->label ); ?>
				</label>
			<?php } ?>
			<?php if ( ! empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php } ?>
			<input type="hidden" id="<?php echo esc_attr( $this->id ); ?>" class="customize-control-dropdown-select2" value="<?php echo esc_attr( $this->value() ); ?>" name="<?php echo esc_attr( $this->id ); ?>" <?php $this->link(); ?> />
			<select name="select2-list-<?php echo ( $this->multiselect ? 'multi[]' : 'single' ); ?>" class="customize-control-select2" data-placeholder="<?php echo esc_attr( $this->placeholder ); ?>" <?php echo ( $this->multiselect ? 'multiple="multiple" ' : '' ); ?>>
				<?php
				if ( ! $this->multiselect ) {
					// When using Select2 for single selection, the Placeholder needs an empty <option> at the top of the list for it to work (multi-selects dont need this)
					echo '<option></option>';
				}
				if ( is_array( $this->choices ) ) {
					foreach ( $this->choices as $key => $value ) {
						echo '<option value="' . esc_attr( $key ) . '" ' . ( in_array( esc_attr( $key ), $defaultValue ) ? 'selected="selected"' : '' ) . '>' . esc_attr( $value ) . '</option>';
					}
				} else {
					foreach ( $this->choices as $key => $value ) {
						echo '<option value="' . esc_attr( $key ) . '" ' . selected( esc_attr( $key ), $defaultValue, false ) . '>' . esc_attr( $value ) . '</option>';
					}
				}

				?>
			</select>
		</div>
		<?php
	}
}
