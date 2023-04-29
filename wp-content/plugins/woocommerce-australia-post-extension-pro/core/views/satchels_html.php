<?php
if (version_compare(WC()->version, '2.6.0', 'lt')) {
	$satchels = (isset($this->satchels))?$this->satchels:'';
} else {
	$satchels = (isset($this->instance_settings['satchels']))?$this->instance_settings['satchels']:'';
}
?>
<tr valign="top">
	<th class="titledesc">
		<label for="woocommerce_auspost_debug_mode"><?php _e('Using Australia Post Satchels', 'woocommerce-australia-post-pro'); ?></label>
	</th>
	<td class="forminp">
		<fieldset>
			<table id="satchels_table" class="wc_gateways ">
				<thead>
				<tr>
					<th><?php _e('Regular Parcel Post', 'woocommerce-australia-post-pro'); ?></th>
					<th><?php _e('Express Post', 'woocommerce-australia-post-pro'); ?></th>
					<th><?php _e('Courier Post', 'woocommerce-australia-post-pro'); ?></th>

				</tr>
				</thead>
				<tr>
					<td><img src="<?php echo plugins_url('../../assets/images/regular.jpg', __FILE__); ?>"></td>
					<td><img src="<?php echo plugins_url('../../assets/images/express.jpg', __FILE__); ?>"></td>
					<td><img src="<?php echo plugins_url('../../assets/images/courier.jpg', __FILE__); ?>"></td>
				</tr>
				<tr>
					<td>
						<p>
							<input id="r_small" type="checkbox" name="woocommerce_satchels[regular][small]" <?php if (isset($satchels['regular']['small'])) {
								checked($satchels['regular']['small'], true);
							} ?>  />
							<label for="r_small"><?php _e('Small satchel', 'woocommerce-australia-post-pro'); ?></label>
							<small><?php _e('(Holds up to 5kg 355 x 220mm)', 'woocommerce-australia-post-pro'); ?></small>
						</p>
                        <p>
                            <input id="r_1kg" type="checkbox" name="woocommerce_satchels[regular][1kg]" <?php if (isset($satchels['regular']['1kg'])) {
								checked($satchels['regular']['1kg'], true);
							} ?> />
                            <label for="r_1kg"><?php _e('Medium satchel', 'woocommerce-australia-post-pro'); ?></label>
                            <small><?php _e('(Holds up to 5kg 385 x 265mm)', 'woocommerce-australia-post-pro'); ?></small>
                        </p>
						<p>
							<input id="r_medium" type="checkbox" name="woocommerce_satchels[regular][medium]" <?php if (isset($satchels['regular']['medium'])) {
								checked($satchels['regular']['medium'], true);
							} ?> />
							<label for="r_medium"><?php _e('Large satchel', 'woocommerce-australia-post-pro'); ?></label>
							<small><?php _e('(Holds up to 5kg 405 x 310mm)', 'woocommerce-australia-post-pro'); ?></small>
						</p>
						<p>
							<input id="r_large" type="checkbox" name="woocommerce_satchels[regular][large]" <?php if (isset($satchels['regular']['large'])) {
								checked($satchels['regular']['large'], true);
							} ?> />
							<label for="r_large"><?php _e('Extra Large satchel', 'woocommerce-australia-post-pro'); ?></label>
							<small><?php _e('(Holds up to 5kg 510 x 435mm)', 'woocommerce-australia-post-pro'); ?></small>
						</p>
					</td>
					<td>
						<p>
							<input id="x_small" type="checkbox" name="woocommerce_satchels[express][small]" <?php if (isset($satchels['express']['small'])) {
								checked($satchels['express']['small'], true);
							} ?> />
							<label for="x_small"><?php _e('Small satchel', 'woocommerce-australia-post-pro'); ?> </label>
							<small><?php _e('(Holds up to 5kg 355 x 220mm)', 'woocommerce-australia-post-pro'); ?></small>
						</p>
                        <p>
                            <input id="x_1kg" type="checkbox" name="woocommerce_satchels[express][1kg]" <?php if (isset($satchels['express']['1kg'])) {
								checked($satchels['express']['1kg'], true);
							} ?> />
                            <label for="x_1kg"><?php _e('Medium satchel', 'woocommerce-australia-post-pro'); ?></label>
                            <small><?php _e('(Holds up to 5kg 385 x 265mm)', 'woocommerce-australia-post-pro'); ?></small>
                        </p>
						<p>
							<input id="x_medium" type="checkbox" name="woocommerce_satchels[express][medium]" <?php if (isset($satchels['express']['medium'])) {
								checked($satchels['express']['medium'], true);
							} ?> />
							<label for="x_medium"><?php _e('Large satchel', 'woocommerce-australia-post-pro'); ?></label>
							<small><?php _e('(Holds up to 5kg 405 x 310mm)', 'woocommerce-australia-post-pro'); ?></small>
						</p>
						<p>
							<input id="x_large" type="checkbox" name="woocommerce_satchels[express][large]" <?php if (isset($satchels['express']['large'])) {
								checked($satchels['express']['large'], true);
							} ?> />
							<label for="x_large"><?php _e('Extra Large satchel', 'woocommerce-australia-post-pro'); ?></label>
							<small><?php _e('(Holds up to 5kg 510 x 435mm)', 'woocommerce-australia-post-pro'); ?></small>
						</p>
					</td>
					<td>
						<input id="c_medium"  type="checkbox" name="woocommerce_satchels[courier][medium]" <?php if (isset($satchels['courier']['medium'])) {
							checked($satchels['courier']['medium'], true);
						} ?> />
						<label for="c_medium"><?php _e('Medium satchel', 'woocommerce-australia-post-pro'); ?></label>
						<small><?php _e('(Holds up to 5kg 405 x 310mm)', 'woocommerce-australia-post-pro'); ?></small>
					</td>
				</tr>

			</table>
			<p class="description"><?php _e('Domestic satchels make sending easy, giving you a fixed price upfront so you\'ll always know your postage costs.', 'woocommerce-australia-post-pro'); ?></p>
		</fieldset>
	</td>
</tr>
<hr>