<?php $custom_titles = (isset($this->settings['custom_titles']))?$this->settings['custom_titles']:''; ?>
<tr valign="top">
	<th class="titledesc">
		<label for="woocommerce_auspost_debug_mode"><?php _e('Custom Shipping Titles', 'woocommerce-australia-post-pro'); ?></label>
	</th>
	<td class="forminp">
		<fieldset>
			<table id="aupost_custom_titles" class="wc_gateways widefat">
                <?php do_action('wpruby_australia_post_custom_titles_before', $custom_titles); ?>
				<thead>
				<tr>
					<th><?php _e('Domestic Options', 'woocommerce-australia-post-pro'); ?></th>
					<th><?php _e('International Options', 'woocommerce-australia-post-pro'); ?></th>
				</tr>
				</thead>
				<tr>
					<td><input name="woocommerce_custom_titles[AUS_PARCEL_REGULAR]" placeholder="<?php _e('Australia Post Regular Post', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['AUS_PARCEL_REGULAR']))?$custom_titles['AUS_PARCEL_REGULAR']:''); ?>"></td>
					<td><input name="woocommerce_custom_titles[INT_PARCEL_AIR_OWN_PACKAGING]" placeholder="<?php _e('Australia Post Economy Air', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['INT_PARCEL_AIR_OWN_PACKAGING']))?$custom_titles['INT_PARCEL_AIR_OWN_PACKAGING']:''); ?>"></td>
				</tr>
				<tr>
					<td><input name="woocommerce_custom_titles[AUS_PARCEL_EXPRESS]" placeholder="<?php _e('Australia Post Express Post', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['AUS_PARCEL_EXPRESS']))?$custom_titles['AUS_PARCEL_EXPRESS']:''); ?>"></td>
					<td><input name="woocommerce_custom_titles[INT_PARCEL_SEA_OWN_PACKAGING]" placeholder="<?php _e('Australia Post Economy Sea', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['INT_PARCEL_SEA_OWN_PACKAGING']))?$custom_titles['INT_PARCEL_SEA_OWN_PACKAGING']:''); ?>"></td>
				</tr>
				<tr>
					<td><input name="woocommerce_custom_titles[AUS_PARCEL_COURIER]" placeholder="<?php _e('Australia Post Courier Post', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['AUS_PARCEL_COURIER']))?$custom_titles['AUS_PARCEL_COURIER']:''); ?>"></td>
					<td><input name="woocommerce_custom_titles[INT_PARCEL_STD_OWN_PACKAGING]" placeholder="<?php _e('Australia Post Standard International', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['INT_PARCEL_STD_OWN_PACKAGING']))?$custom_titles['INT_PARCEL_STD_OWN_PACKAGING']:''); ?>"></td>
				</tr>
				<tr>
					<td></td>
					<td><input name="woocommerce_custom_titles[INT_PARCEL_COR_OWN_PACKAGING]" placeholder="<?php _e('Australia Post Courier International', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['INT_PARCEL_COR_OWN_PACKAGING']))?$custom_titles['INT_PARCEL_COR_OWN_PACKAGING']:''); ?>"></td>
				</tr>
				<tr>
					<td></td>
					<td><input name="woocommerce_custom_titles[INT_PARCEL_EXP_OWN_PACKAGING]" placeholder="<?php _e('Australia Post Express International', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['INT_PARCEL_EXP_OWN_PACKAGING']))?$custom_titles['INT_PARCEL_EXP_OWN_PACKAGING']:''); ?>"></td>
				</tr>
				<thead>
				<tr>
					<th colspan="2" style="text-align: center;"><?php _e('Domestic Letters Options', 'woocommerce-australia-post-pro'); ?></th>
				</tr>
				</thead>
                <tr>
                    <td><input name="woocommerce_custom_titles[small_envelop]" placeholder="<?php _e('Tracked Small Letter', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['small_envelop']))?$custom_titles['small_envelop']:''); ?>"></td>
                    <td><input name="woocommerce_custom_titles[medium_envelop]" placeholder="<?php _e('Tracked Medium Letter', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['medium_envelop']))?$custom_titles['medium_envelop']:''); ?>"></td>
                </tr>
                <tr>
                    <td><input name="woocommerce_custom_titles[large_envelop]" placeholder="<?php _e('Tracked Large Letter', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['large_envelop']))?$custom_titles['large_envelop']:''); ?>"></td>
                    <td></td>
                </tr>
				<tr>
					<td><input name="woocommerce_custom_titles[AUS_LETTER_REGULAR_SMALL]" placeholder="<?php _e('Regular Small Letter', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['AUS_LETTER_REGULAR_SMALL']))?$custom_titles['AUS_LETTER_REGULAR_SMALL']:''); ?>"></td>
					<td><input name="woocommerce_custom_titles[AUS_LETTER_REGULAR_LARGE]" placeholder="<?php _e('Regular Large Letter', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['AUS_LETTER_REGULAR_LARGE']))?$custom_titles['AUS_LETTER_REGULAR_LARGE']:''); ?>"></td>
				</tr>
				<tr>
					<td><input name="woocommerce_custom_titles[AUS_LETTER_EXPRESS_SMALL]" placeholder="<?php _e('Express Post Small Envelope', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['AUS_LETTER_EXPRESS_SMALL']))?$custom_titles['AUS_LETTER_EXPRESS_SMALL']:''); ?>"></td>
					<td><input name="woocommerce_custom_titles[AUS_LETTER_EXPRESS_MEDIUM]" placeholder="<?php _e('Express Post Medium Letter', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['AUS_LETTER_EXPRESS_MEDIUM']))?$custom_titles['AUS_LETTER_EXPRESS_MEDIUM']:''); ?>"></td>
				</tr>
				<tr >
					<td><input name="woocommerce_custom_titles[AUS_LETTER_EXPRESS_LARGE]" placeholder="<?php _e('Express Post Large Envelope', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['AUS_LETTER_EXPRESS_LARGE']))?$custom_titles['AUS_LETTER_EXPRESS_LARGE']:''); ?>"></td>
                    <td></td>

				<tr>
					<td><input name="woocommerce_custom_titles[AUS_LETTER_REGULAR_LARGE_125]" placeholder="<?php _e('Regular Large Light Letter', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['AUS_LETTER_REGULAR_LARGE_125']))?$custom_titles['AUS_LETTER_REGULAR_LARGE_125']:''); ?>"></td>
					<td><input name="woocommerce_custom_titles[AUS_LETTER_REGULAR_LARGE_250]" placeholder="<?php _e('Regular Large Medium Letter', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['AUS_LETTER_REGULAR_LARGE_250']))?$custom_titles['AUS_LETTER_REGULAR_LARGE_250']:''); ?>"></td>
				</tr>
				<tr>
					<td><input name="woocommerce_custom_titles[AUS_LETTER_REGULAR_LARGE_500]" placeholder="<?php _e('Regular Large Heavy Letter', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['AUS_LETTER_REGULAR_LARGE_500']))?$custom_titles['AUS_LETTER_REGULAR_LARGE_500']:''); ?>"></td>
					<td><input name="woocommerce_custom_titles[AUS_LETTER_PRIORITY_SMALL]" placeholder="<?php _e('Priority Small Letter', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['AUS_LETTER_PRIORITY_SMALL']))?$custom_titles['AUS_LETTER_PRIORITY_SMALL']:''); ?>"></td>
				</tr>
				<tr>
					<td><input name="woocommerce_custom_titles[AUS_LETTER_PRIORITY_LARGE_125]" placeholder="<?php _e('Priority Large Light Letter', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['AUS_LETTER_PRIORITY_LARGE_125']))?$custom_titles['AUS_LETTER_PRIORITY_LARGE_125']:''); ?>"></td>
					<td><input name="woocommerce_custom_titles[AUS_LETTER_PRIORITY_LARGE_500]" placeholder="<?php _e('Priority Large Medium Letter', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['AUS_LETTER_PRIORITY_LARGE_500']))?$custom_titles['AUS_LETTER_PRIORITY_LARGE_500']:''); ?>"></td>
				</tr>
				<tr>
					<td><input name="woocommerce_custom_titles[AUS_LETTER_PRIORITY_LARGE_250]" placeholder="<?php _e('Priority Large Heavy Letter', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['AUS_LETTER_PRIORITY_LARGE_250']))?$custom_titles['AUS_LETTER_PRIORITY_LARGE_250']:''); ?>"></td>
					<td></td>
				</tr>

				<thead>
				<tr>
					<th colspan="2" style="text-align: center;"><?php _e('International Letters Options', 'woocommerce-australia-post-pro'); ?></th>
				</tr>
				</thead>

				<tr>
					<td><input name="woocommerce_custom_titles[INT_LETTER_STD_OWN_PACKAGING]" placeholder="<?php _e('Standard Letter', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['INT_LETTER_STD_OWN_PACKAGING']))?$custom_titles['INT_LETTER_STD_OWN_PACKAGING']:''); ?>"></td>
					<td><input name="woocommerce_custom_titles[INT_LETTER_EXP_OWN_PACKAGING]" placeholder="<?php _e('Express Letter', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['INT_LETTER_EXP_OWN_PACKAGING']))?$custom_titles['INT_LETTER_EXP_OWN_PACKAGING']:''); ?>"></td>
				</tr>
				<tr>
					<td><input name="woocommerce_custom_titles[INT_LETTER_COR_OWN_PACKAGING]" placeholder="<?php _e('Courier Letter', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['INT_LETTER_COR_OWN_PACKAGING']))?$custom_titles['INT_LETTER_COR_OWN_PACKAGING']:''); ?>"></td>
					<td><input name="woocommerce_custom_titles[INT_LETTER_REG_SMALL]" placeholder="<?php _e('Registered Small Letter', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['INT_LETTER_REG_SMALL']))?$custom_titles['INT_LETTER_REG_SMALL']:''); ?>"></td>
				</tr>
				<tr>
					<td><input name="woocommerce_custom_titles[INT_LETTER_REG_LARGE]" placeholder="<?php _e('Registered Large Letter', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['INT_LETTER_REG_LARGE']))?$custom_titles['INT_LETTER_REG_LARGE']:''); ?>"></td>
					<td><input name="woocommerce_custom_titles[INT_LETTER_REG_SMALL_ENVELOPE]" placeholder="<?php _e('Registered Small Envelope', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['INT_LETTER_REG_SMALL_ENVELOPE']))?$custom_titles['INT_LETTER_REG_SMALL_ENVELOPE']:''); ?>"></td>
				</tr>
				<tr>

					<td><input name="woocommerce_custom_titles[INT_LETTER_REG_LARGE_ENVELOPE]" placeholder="<?php _e('Registered Large Envelope', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['INT_LETTER_REG_LARGE_ENVELOPE']))?$custom_titles['INT_LETTER_REG_LARGE_ENVELOPE']:''); ?>"></td>
					<td><input name="woocommerce_custom_titles[INT_LETTER_AIR_OWN_PACKAGING_LIGHT]" placeholder="<?php _e('Economy Air Light Letter', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['INT_LETTER_AIR_OWN_PACKAGING_LIGHT']))?$custom_titles['INT_LETTER_AIR_OWN_PACKAGING_LIGHT']:''); ?>"></td>
				</tr>
				<tr>

					<td><input name="woocommerce_custom_titles[INT_LETTER_AIR_OWN_PACKAGING_MEDIUM]" placeholder="<?php _e('Economy Air Medium Letter', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['INT_LETTER_AIR_OWN_PACKAGING_MEDIUM']))?$custom_titles['INT_LETTER_AIR_OWN_PACKAGING_MEDIUM']:''); ?>"></td>
					<td><input name="woocommerce_custom_titles[INT_LETTER_AIR_OWN_PACKAGING_HEAVY]" placeholder="<?php _e('Economy Air Heavy Letter', 'woocommerce-australia-post-pro'); ?>" value="<?php echo esc_attr((isset($custom_titles['INT_LETTER_AIR_OWN_PACKAGING_HEAVY']))?$custom_titles['INT_LETTER_AIR_OWN_PACKAGING_HEAVY']:''); ?>"></td>
				</tr>
				<?php do_action('wpruby_australia_post_custom_titles_after', $custom_titles); ?>
			</table>
			<p class="description"><?php _e('You can customize shipping options titles in cart and checkout pages, but delivery times can not be customized because they are constantly changed by Australia Post.', 'woocommerce-australia-post-pro'); ?></p>
		</fieldset>
	</td>
</tr>
