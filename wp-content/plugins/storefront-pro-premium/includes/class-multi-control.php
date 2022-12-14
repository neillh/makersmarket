<?php
/**
 * Multiple customize control class.
 *
 * @since  1.0.0
 * @access public
 */
class SFP_Custom_Customize_Control extends WP_Customize_Control {

	/**
	 * The type of customize control being rendered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'multi-checkbox';

	/**
	 * Before outputting the checkboxes
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $before = '<label>';

	/**
	 * In between adjacent checkboxes
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $separator = '</label><br><label>';

	/**
	 * After outputting the checkboxes
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $input = '<input type="checkbox" %value%>';

	/**
	 * After outputting the checkboxes
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $after = '</label>';

	public $multi_val;

	/**
	 * Enqueue scripts/styles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue() {
		wp_enqueue_script( 'lib-customize-controls', plugin_dir_url( __FILE__ ) . '../assets/customize-controls.js', array( 'jquery' ), SFP_VERSION, true );
		?>
		<style>
			.customize-control input[type="number"].sfp-range-number {
				width: 50px;
				margin: 0 1em;
				vertical-align: middle;
			}
			input.sfp-range-control {
				vertical-align: middle;
			}
			#customize-theme-controls .control-section#accordion-panel-woocommerce {
				display: none !important;
			}
		</style>
		<?php
	}

	protected function output_select_options( $options, $val_now ) {
		foreach ( $options as $value => $label ) {
			echo '<option value="' . esc_attr( $value ) . '"' . selected( $val_now, $value, false ) . '>' . $label . '</option>';
		}
	}

	protected function render_content_init() {
		if ( ! empty( $this->label ) ) { ?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php
		}

		if ( ! empty( $this->description ) ) {
			?>
			<span class="description customize-control-description"><?php echo $this->description; ?></span>
			<?php
		}

		$this->multi_val = $this->value();

		if ( ! is_array( $this->value() ) ) {
			$this->multi_val = explode( ',', $this->value() );
		}
	}

		/**
	 * Displays the control content.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function render_content() {

		if ( 'grid' != $this->type && empty( $this->choices ) ) {
			return;
		}

		$this->render_content_init();

		if ( 'grid' == $this->type ) {
			$this->render_grid();
		} else {
			$this->render_multi_checkbox();
		}
	}

	protected function render_multi_checkbox() {
		$multi_values = $this->multi_val;
		$i = 0;
		?>
		<div class="wp-custo-lib-multi-checkbox">
			<?php
			foreach ( $this->choices as $value => $label ) {
				echo $i == 0 ? $this->before : $this->separator;
				$i ++;
				echo str_replace( '%value%', 'value="' . esc_attr( $value ) . '" ' . checked( in_array( $value, $multi_values ), true, false ), $this->input );
				echo $label;
			}
			echo $this->after;
			?>
			<input class="hidden" type="hidden" <?php $this->link(); ?>
			       value="<?php echo esc_attr( implode( ',', $multi_values ) ); ?>"/>
		</div>
		<?php
	}

	protected function render_grid() {
		$options = array();

		for ( $i = 1; $i < 5; $i++ ) {
			$options[$i] = $i;
		}
		?>
		<select class="across" style="min-width: 16%;">
			<?php $this->output_select_options( $options, $this->multi_val[0] ); ?>
		</select> across by
		<?php for ( $i = 5; $i < 21; $i++ ) { $options[$i] = $i; } ?>
		<select class="down" style="min-width: 16%;">
			<?php $this->output_select_options( $options, $this->multi_val[1] ); ?>
		</select> down
		<input class="hidden" type="hidden" <?php $this->link(); ?>
		       value="<?php echo esc_attr( implode( ',', $this->multi_val ) ); ?>"/>
	<?php
	}
}