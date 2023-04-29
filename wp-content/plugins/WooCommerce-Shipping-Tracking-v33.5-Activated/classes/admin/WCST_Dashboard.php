<?php 
class WCST_Dashboard
{
	public function __construct()
	{
		
		 add_action( 'wp_dashboard_setup', array( &$this, 'add_server_time_widget' ) );
	}
	public function add_server_time_widget()
	{
		if(current_user_can('manage_woocommerce') || current_user_can('edit_posts'))
			wp_add_dashboard_widget( 'wcst-server-time', __('WooCommerce shipping tracking - Server time', 'woocommerce-shipping-tracking'), array( &$this, 'render_server_time_widget' ));
		 
	}
	function render_server_time_widget()
	{
		$wcst_option_model = new WCST_Option();
		$hour_offset = $wcst_option_model->get_estimations_options('hour_offset', 0);
		?>
		<p class="form-field">
			<label  style="display: inline;"><?php esc_html_e( 'Current server time with offset (date format: dd/mm/yyyy):', 'woocommerce-shipping-tracking' ); ?></label>
			<span class="wrap">
				<strong style=" font-size: 20px;"><?php echo date("d/m/Y H:i",strtotime($hour_offset.' minutes')); ?></strong>
			</span>
			<br/>
			<span style="display:block; clear:both;" class="description"><?php wcst_html_escape_allowing_special_tags(sprintf(__('Rule dates are syncronized with server time. Configure a proper <strong>Timezone</strong> in the <a href="%s">Settings -> General</a> option menu', 'woocommerce-shipping-tracking'), get_admin_url()."options-general.php") ); ?></span> 			
		</p>
							
		<?php
	}
	
}
?>