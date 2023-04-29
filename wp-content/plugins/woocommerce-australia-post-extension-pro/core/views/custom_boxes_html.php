<span></span>
<?php
if (version_compare(WC()->version, '2.6.0', 'lt')) {
    $custom_boxes = (isset($custom_boxes))?$custom_boxes:'';
} else {
    $custom_boxes = (isset($this->instance_settings['custom_boxes']))?$this->instance_settings['custom_boxes']:'';
}
?>
<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="woocommerce_auspost_debug_mode"><?php _e('Add your own boxes', 'woocommerce-australia-post-pro'); ?></label>
			</th>
			<td class="forminp">
				<fieldset>
					<table id="auspost_custom_boxes_table" class="wc_gateways widefat">
						<thead>
							<tr>
								<th class="column-key"><?php _e('Box Name', 'woocommerce-australia-post-pro'); ?></th>
								<th><?php _e('Outer Length', 'woocommerce-australia-post-pro'); ?></th>
								<th><?php _e('Outer Width', 'woocommerce-australia-post-pro'); ?></th>
								<th><?php _e('Outer Height', 'woocommerce-australia-post-pro'); ?></th>
								<th><?php _e('Empty Weight', 'woocommerce-australia-post-pro'); ?></th>
								<th><?php _e('Inner Length', 'woocommerce-australia-post-pro'); ?></th>
								<th><?php _e('Inner Width', 'woocommerce-australia-post-pro'); ?></th>
								<th><?php _e('Inner Height', 'woocommerce-australia-post-pro'); ?></th>
								<th><?php _e('Maximum Weight', 'woocommerce-australia-post-pro'); ?></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
									<?php if (isset($custom_boxes) && is_array($custom_boxes)): ?>
									<?php foreach ($custom_boxes as $key => $box): ?>
										<tr>
											<td><input name="woocommerce_custom_boxes[box_name][]"  value="<?php echo $box['box_name'] ?>" type="text">&nbsp;	</td>
											<td><input name="woocommerce_custom_boxes[box_outer_length][]" class="auspost-small" value="<?php echo $box['box_outer_length'] ?>" type="text"><span class="boxes_units_lbl">mm</span></td>
											<td><input name="woocommerce_custom_boxes[box_outer_width][]" class="auspost-small" value="<?php echo $box['box_outer_width'] ?>" type="text"><span class="boxes_units_lbl">mm</span></td>
											<td><input name="woocommerce_custom_boxes[box_outer_height][]" class="auspost-small" value="<?php echo $box['box_outer_height'] ?>" type="text"><span class="boxes_units_lbl">mm</span></td>
											<td><input name="woocommerce_custom_boxes[box_empty_weight][]" class="auspost-small" value="<?php echo $box['box_empty_weight'] ?>" type="text"><span class="boxes_units_lbl">g</span></td>
											<td><input name="woocommerce_custom_boxes[box_inner_length][]" class="auspost-small" value="<?php echo $box['box_inner_length'] ?>" type="text"><span class="boxes_units_lbl">mm</span></td>
											<td><input name="woocommerce_custom_boxes[box_inner_width][]" class="auspost-small" value="<?php echo $box['box_inner_width'] ?>" type="text"><span class="boxes_units_lbl">mm</span></td>
											<td><input name="woocommerce_custom_boxes[box_inner_height][]" class="auspost-small" value="<?php echo $box['box_inner_height'] ?>" type="text"><span class="boxes_units_lbl">mm</span></td>
											<td><input name="woocommerce_custom_boxes[box_maximum_weight][]" class="auspost-small" value="<?php echo $box['box_maximum_weight'] ?>" type="text"><span class="boxes_units_lbl">g</span></td>
											<td><a class="delete_box" href="javascript:void(0);"><span class="dashicons  dashicons-trash"></span></a></td>
										</tr>
									<?php endforeach; ?>
									<?php endif; ?>
						</tbody>
						<tfoot>
							<tr>
								<th colspan="10"><a id="add_row_custom_boxes_button" href="javascript:void(0);" class="button"><?php _e('Add New Box', 'woocommerce-australia-post-pro'); ?></a></th>
							</tr>
						</tfoot>
					</table>
				</fieldset>
			</td>
		</tr>

		<script type="text/javascript">
			jQuery(function() {
				jQuery('.delete_box').click(function(e){
						if(window.confirm('Are you sure that you wanted to delete this box?')){
				    		jQuery(this).parent().parent().remove();
						}
			    });
				jQuery('#add_row_custom_boxes_button').click(function(e){
	    			e.preventDefault();
					var row = '<tr>'+
						'<td><input name="woocommerce_custom_boxes[box_name][]"  value="" type="text">&nbsp;	</td>'+
						'<td><input name="woocommerce_custom_boxes[box_outer_length][]" class="auspost-small" value="" type="text"><span class="boxes_units_lbl">mm</span></td>'+
						'<td><input name="woocommerce_custom_boxes[box_outer_width][]" class="auspost-small" value="" type="text"><span class="boxes_units_lbl">mm</span></td>'+
						'<td><input name="woocommerce_custom_boxes[box_outer_height][]" class="auspost-small" value="" type="text"><span class="boxes_units_lbl">mm</span></td>'+
						'<td><input name="woocommerce_custom_boxes[box_empty_weight][]" class="auspost-small" value="" type="text"><span class="boxes_units_lbl">g</span></td>'+
						'<td><input name="woocommerce_custom_boxes[box_inner_length][]" class="auspost-small" value="" type="text"><span class="boxes_units_lbl">mm</span></td>'+
						'<td><input name="woocommerce_custom_boxes[box_inner_width][]" class="auspost-small" value="" type="text"><span class="boxes_units_lbl">mm</span></td>'+
						'<td><input name="woocommerce_custom_boxes[box_inner_height][]" class="auspost-small" value="" type="text"><span class="boxes_units_lbl">mm</span></td>'+
						'<td><input name="woocommerce_custom_boxes[box_maximum_weight][]" class="auspost-small" value="" type="text"><span class="boxes_units_lbl">g</span></td>'+
						'<td><a class="delete_box" href="javascript:void(0);"><span class="dashicons  dashicons-trash"></span></a></td>'+
					  '</tr>';
	    			jQuery('#auspost_custom_boxes_table tbody').append(row);
			    	// activate the deletion.
			        jQuery('.delete_box').click(function(e){
						if(window.confirm('Are you sure that you wanted to delete this box?')){
			    			jQuery(this).parent().parent().remove();
			    		}
			    	});
			});
    	});
		</script>
