<?php 
	if(isset($messages_and_options['options']['date_range']) || isset($messages_and_options['options']['time_range']))
	{
		?><h4 class="wcst_extra_delivery_section_title" style=""><?php echo $messages_and_options['messages']['title']; ?></h4> <?php
		if(isset($messages_and_options['messages']['note']) && $messages_and_options['messages']['note'] != "")
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
				<input type="text" id="wcst_start_date_range" class="wcst_input_date" name="wcst_delivery[date_start_range]" value="<?php if(isset($delivery_info['date_start_range'])) echo $delivery_info['date_start_range']; ?>"></input>
				<input type="text" id="wcst_end_date_range" class="wcst_input_date" name="wcst_delivery[date_end_range]" value="<?php if(isset($delivery_info['date_end_range'])) echo $delivery_info['date_end_range']; ?>"></input>
			</p>					
		<?php
	}
	if(isset($messages_and_options['options']['time_range']))
	{
		?>
		<p  class="form-row form-row form-row-wide">
			<label><?php echo $messages_and_options['messages']['time_range']; ?></label>
			<input type="text" id="wcst_start_time_range" class="wcst_input_time" name="wcst_delivery[time_start_range]" value="<?php if(isset($delivery_info['time_start_range'])) echo $delivery_info['time_start_range']; ?>"></input>
			<input type="text" id="wcst_end_time_range" class="wcst_input_time" name="wcst_delivery[time_end_range]" value="<?php if(isset($delivery_info['time_end_range'])) echo $delivery_info['time_end_range']; ?>"></input>
		</p>
		<?php
	}
	if(isset($messages_and_options['options']['time_range']) && isset($messages_and_options['options']['time_secondary_range']))
	{
		?>
		<p  class="form-row form-row form-row-wide">
			<label><?php  echo $messages_and_options['messages']['time_secondary_range']; ?></label>
			<input type="text" id="wcst_start_time_secondary_range" class="wcst_input_time" name="wcst_delivery[time_secondary_start_range]" value="<?php if(isset($delivery_info['time_secondary_start_range'])) echo $delivery_info['time_secondary_start_range']; ?>"></input>
			<input type="text" id="wcst_end_time_secondary_range" class="wcst_input_time" name="wcst_delivery[time_secondary_end_range]" value="<?php if(isset($delivery_info['time_secondary_end_range'])) echo $delivery_info['time_secondary_end_range']; ?>"></input>
		</p>
		<?php
	}
			
?>