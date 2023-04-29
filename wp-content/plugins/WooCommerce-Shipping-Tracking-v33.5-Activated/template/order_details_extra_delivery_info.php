<form method="post" action="" id="wcst_order_details_form">
<?php 
	$allow_re_edit = isset($messages_and_options['options']['order_details_page_re_edit_datetime']);
	$render_save_button = 
						(	 (!isset($delivery_info['date_start_range']) || $delivery_info['date_start_range'] == "") && 
							 (!isset($messages_and_options['options']['just_one_date_field']) || !isset($delivery_info['date_end_range']) || $delivery_info['date_end_range'] == "") &&
							 (!isset($messages_and_options['options']['time_range']) || !isset($delivery_info['time_start_range']) || $delivery_info['time_start_range'] == "") &&
							 (!isset($messages_and_options['options']['time_range']) || !isset($delivery_info['time_end_range']) || $delivery_info['time_end_range'] == "") &&
							 (!isset($messages_and_options['options']['time_secondary_range']) || !isset($delivery_info['time_secondary_start_range']) || $delivery_info['time_secondary_start_range'] == "") &&
							 (!isset($messages_and_options['options']['time_secondary_range']) || !isset($delivery_info['time_secondary_end_range']) || $delivery_info['time_secondary_end_range'] == "") 
						 ) ? true  : false;
						  
	
	$render_save_button = !$render_save_button && $allow_re_edit ? true : $render_save_button;
	
	if(isset($messages_and_options['options']['date_range']) || isset($messages_and_options['options']['time_range']))
	{
		?><h4 class="" style="margin-bottom:5px;  margin-top:15px;"><?php echo $messages_and_options['messages']['title']; ?></h4> <?php
		if(isset($messages_and_options['messages']['note']))
		{
			?>
				<p  class="form-row form-row form-row-wide">
					<?php echo $messages_and_options['messages']['note']; ?>
				</p>					
			<?php
		}
	}
	
	if(isset($messages_and_options['options']['date_range']))
	{
		?>
			<p  class="form-row form-row form-row-wide">
				<label><?php echo $messages_and_options['messages']['date_range']; ?></label> 
				<input type="text" id="wcst_start_date_range" class="wcst_input_date" name="wcst_delivery[date_start_range]" value="<?php if(isset($delivery_info['date_start_range'])) echo $delivery_info['date_start_range']; ?>" <?php if(isset($delivery_info['date_start_range']) && $delivery_info['date_start_range'] != "" && !$allow_re_edit) echo 'disabled ="disabled"';?>></input>
				<input type="text" id="wcst_end_date_range" class="wcst_input_date" name="wcst_delivery[date_end_range]" value="<?php if(isset($delivery_info['date_end_range'])) echo $delivery_info['date_end_range']; ?>" <?php if(isset($delivery_info['date_end_range']) && $delivery_info['date_end_range'] != "" && !$allow_re_edit) echo 'disabled ="disabled"';?>></input> 
			</p>					
		<?php
	}
	if(isset($messages_and_options['options']['time_range']))
	{
		?>
		<p  class="form-row form-row form-row-wide">
			<label><?php echo $messages_and_options['messages']['time_range']; ?></label>
			<input type="text" id="wcst_start_time_range" class="wcst_input_time" name="wcst_delivery[time_start_range]" value="<?php if(isset($delivery_info['time_start_range'])) echo $delivery_info['time_start_range']; ?>" <?php if(isset($delivery_info['time_start_range']) && $delivery_info['time_start_range'] != "" && !$allow_re_edit) echo 'disabled ="disabled"';?>></input>
			<input type="text" id="wcst_end_time_range" class="wcst_input_time" name="wcst_delivery[time_end_range]" value="<?php if(isset($delivery_info['time_end_range'])) echo $delivery_info['time_end_range']; ?>" <?php if( isset($delivery_info['time_end_range']) && $delivery_info['time_end_range'] != "" && !$allow_re_edit) echo 'disabled ="disabled"';?>></input>
		</p>
		<?php
	}
	if(isset($messages_and_options['options']['time_range']) && isset($messages_and_options['options']['time_secondary_range']))
	{
		?>
		<p  class="form-row form-row form-row-wide">
			<label><?php  echo $messages_and_options['messages']['time_secondary_range']; ?></label>
			<input type="text" id="wcst_start_time_secondary_range" class="wcst_input_time" name="wcst_delivery[time_secondary_start_range]" value="<?php if(isset($delivery_info['time_secondary_start_range'])) echo $delivery_info['time_secondary_start_range']; ?>" <?php if(isset($delivery_info['time_secondary_start_range']) && $delivery_info['time_secondary_start_range'] != "" && !$allow_re_edit) echo 'disabled ="disabled"';?>></input>
			<input type="text" id="wcst_end_time_secondary_range" class="wcst_input_time" name="wcst_delivery[time_secondary_end_range]" value="<?php if(isset($delivery_info['time_secondary_end_range'])) echo $delivery_info['time_secondary_end_range']; ?>" <?php if(isset($delivery_info['time_secondary_end_range']) && $delivery_info['time_secondary_end_range'] != "" && !$allow_re_edit) echo 'disabled ="disabled"';?>></input>
		</p>
		<?php
	} 
			
?>
<?php if($render_save_button): ?><input type="submit" class="button" value="<?php esc_html_e('Save', 'woocommerce-files-upload'); ?>"></input><?php endif; ?>
</form> 