<?php
/*!
 * Jetpack CRM
 * https://jetpackcrm.com
 *
 * WooSync: Admin: Settings page
 *
 */
namespace Automattic\JetpackCRM;

// block direct access
defined( 'ZEROBSCRM_PATH' ) || exit;

/**
 * Page: WooSync Settings
 */
function jpcrm_settings_page_html_woosync_main() {

	global $zbs;

	$settings = $zbs->modules->woosync->settings->getAll();

	$contact_statuses_csv = zeroBSCRM_getCustomerStatuses();
	$contact_statuses = explode( ',', $contact_statuses_csv );

	$woo_order_statuses = array(
		'wcpending'    => __( 'Pending', 'zero-bs-crm' ),
		'wcprocessing' => __( 'Processing', 'zero-bs-crm' ),
		'wconhold'     => __( 'On hold', 'zero-bs-crm' ),
		'wccompleted'  => __( 'Completed', 'zero-bs-crm' ),
		'wccancelled'  => __( 'Cancelled', 'zero-bs-crm' ),
		'wcrefunded'   => __( 'Refunded', 'zero-bs-crm' ),
		'wcfailed'     => __( 'Failed', 'zero-bs-crm' ),
	);
	$woo_order_statuses = apply_filters( 'zbs-woo-additional-status', $woo_order_statuses );

	$auto_deletion_options = array(
		'do_nothing'          => __( 'Do nothing', 'zero-bs-crm' ),
		'change_status'       => sprintf( __( 'Change transaction/invoice status to `%s`', 'zero-bs-crm' ), __( 'Deleted', 'zero-bs-crm' ) ),
		'hard_delete_and_log' => __( 'Delete transaction/invoice, and add log to contact', 'zero-bs-crm' ),
	);

	// Act on any edits!
	if ( isset( $_POST['editwplf'] ) ) {

		// Retrieve
		$updatedSettings = array();

		// enable order mapping
		$updatedSettings['enable_woo_status_mapping'] = empty( $_POST['jpcrm_enable_woo_status_mapping'] ) ? 0 : 1;

		//order mapping - if not set, these all go to default..
		$updatedSettings['wcpending']    = !empty( $_POST['wcpending'] ) ? sanitize_text_field( $_POST['wcpending'] ) : '';
		$updatedSettings['wcprocessing'] = !empty( $_POST['wcprocessing'] ) ? sanitize_text_field( $_POST['wcprocessing'] ) : '';
		$updatedSettings['wconhold']     = !empty( $_POST['wconhold'] ) ? sanitize_text_field( $_POST['wconhold'] ) : '';
		$updatedSettings['wccompleted']  = !empty( $_POST['wccompleted'] ) ? sanitize_text_field( $_POST['wccompleted'] ) : '';
		$updatedSettings['wccancelled']  = !empty( $_POST['wccancelled'] ) ? sanitize_text_field( $_POST['wccancelled'] ) : '';
		$updatedSettings['wcrefunded']   = !empty( $_POST['wcrefunded'] ) ? sanitize_text_field( $_POST['wcrefunded'] ) : '';
		$updatedSettings['wcfailed']     = !empty( $_POST['wcfailed'] ) ? sanitize_text_field( $_POST['wcfailed'] ) : '';

		//copy shipping address into second address
		$updatedSettings['wccopyship'] = !empty( $_POST['wpzbscrm_wccopyship'] );

		// tag objects with item name|coupon
		$updatedSettings['wctagcust']          = !empty( $_POST['wpzbscrm_wctagcust'] );
		$updatedSettings['wctagtransaction']   = !empty( $_POST['wpzbscrm_wctagtransaction'] );
		$updatedSettings['wctaginvoice']       = !empty( $_POST['wpzbscrm_wctaginvoice'] );
		$updatedSettings['wctagcoupon']        = !empty( $_POST['wpzbscrm_wctagcoupon'] );
		$updatedSettings['wctagcouponprefix']  = !empty( $_POST['wctagcouponprefix'] ) ? zeroBSCRM_textProcess( $_POST['wctagcouponprefix'] ) : '';
		$updatedSettings['wctagproductprefix'] = !empty( $_POST['wctagproductprefix'] ) ? zeroBSCRM_textProcess( $_POST['wctagproductprefix'] ) : '';

		// switches
		$updatedSettings['wcinv']  = !empty( $_POST['wpzbscrm_wcinv'] );
		$updatedSettings['wcprod'] = !empty( $_POST['wpzbscrm_wcprod'] );
		$updatedSettings['wcport'] = !empty( $_POST['wpzbscrm_wcport'] ) ? preg_replace( '/\s*,\s*/', ',', sanitize_text_field( $_POST['wpzbscrm_wcport'] ) ) : '';
		$updatedSettings['wcacc']  = !empty( $_POST['wpzbscrm_wcacc'] );

		// trash/delete action
		$updatedSettings['auto_trash'] = 'change_status';
		if ( isset( $_POST['jpcrm_woosync_auto_trash'] ) && in_array( $_POST['jpcrm_woosync_auto_trash'], array_keys( $auto_deletion_options ), true ) ) {
			$updatedSettings['auto_trash'] = sanitize_text_field( $_POST['jpcrm_woosync_auto_trash'] );
		}
		$updatedSettings['auto_delete'] = 'change_status';
		if ( isset( $_POST['jpcrm_woosync_auto_delete'] ) && in_array( $_POST['jpcrm_woosync_auto_delete'], array_keys( $auto_deletion_options ), true ) ) {
			$updatedSettings['auto_delete'] = sanitize_text_field( $_POST['jpcrm_woosync_auto_delete'] );
		}

		#} Brutal update
		foreach ( $updatedSettings as $k => $v ) {
			$zbs->modules->woosync->settings->update( $k, $v );
		}

		// $msg out!
		$sbupdated = true;

		// Reload
		$settings = $zbs->modules->woosync->settings->getAll();

	}

	// Show Title
	jpcrm_render_setting_title( 'WooSync Settings', '' );

	?>
	<p style="padding-top: 18px; text-align:center;margin:1em">
		<?php
		echo sprintf(
			'<a href="%s&tab=%s&subtab=%s" class="ui button green"><i class="plug icon"></i> %s</a>',
			zbsLink($zbs->slugs['settings']),
			$zbs->modules->woosync->slugs['settings'],
			$zbs->modules->woosync->slugs['settings_connections'],
			__( 'Manage WooSync Connections', 'zero-bs-crm' )
		) . sprintf(
			'<a href="%s" class="ui basic positive button" style="margin-top:1em"><i class="shopping cart icon"></i> %s</a>',
			zbsLink( $zbs->slugs['woosync'] ),
			__( 'WooSync Hub', 'zero-bs-crm' )
		); ?>
	</p>
	<p id="sbDesc"><?php _e( 'Here you can configure the global settings for WooSync.', 'zero-bs-crm' ); ?></p>

	<?php
	if ( !empty( $sbupdated ) ) {
		echo '<div class="ui message success">' . __( 'Settings Updated', 'zero-bs-crm' ) . '</div>';
	}
	?>

	<div id="sbA">
		<form method="post">
			<input type="hidden" name="editwplf" id="editwplf" value="1" />
			<table class="table table-bordered table-striped wtab">

				<thead>
					<tr>
						<th colspan="2" class="wmid"><?php _e( 'WooSync Settings', 'zero-bs-crm' ); ?>:</th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<td class="wfieldname">
							<label for="wpzbscrm_wccopyship"><?php _e( 'Add Shipping Address', 'zero-bs-crm' ); ?>:</label><br />
							<?php _e( 'Tick to store shipping address as contacts second address', 'zero-bs-crm' ); ?>
						</td>
						<td style="width:540px">
							<input type="checkbox" class="winput form-control" name="wpzbscrm_wccopyship" id="wpzbscrm_wccopyship" value="1"<?php echo ( !empty( $settings['wccopyship'] ) ? ' checked="checked"' : '' ); ?> />
						</td>
					</tr>

					<tr>
						<td class="wfieldname">
							<label for="wpzbscrm_wctagcust"><?php _e( 'Tag Contact', 'zero-bs-crm' ); ?>:</label><br />
							<?php _e( 'Tick to tag your contact with their item name', 'zero-bs-crm' ); ?>
						</td>
						<td style="width:540px">
							<input type="checkbox" class="winput form-control" name="wpzbscrm_wctagcust" id="wpzbscrm_wctagcust" value="1"<?php echo ( !empty( $settings['wctagcust'] ) ? ' checked="checked"' : '' ); ?> />
						</td>
					</tr>


					<tr>
						<td class="wfieldname">
							<label for="wpzbscrm_wctagtransaction"><?php _e( 'Tag Transaction', 'zero-bs-crm' ); ?>:</label><br />
							<?php _e( 'Tick to tag your transaction with the item name', 'zero-bs-crm' ); ?>
						</td>
						<td style="width:540px">
							<input type="checkbox" class="winput form-control" name="wpzbscrm_wctagtransaction" id="wpzbscrm_wctagtransaction" value="1"<?php echo ( !empty( $settings['wctagtransaction'] ) ? ' checked="checked"' : '' ); ?> />
						</td>
					</tr>


					<tr>
							<td class="wfieldname">
								<label for="wpzbscrm_wctaginvoice"><?php _e( 'Tag Invoice', 'zero-bs-crm' ); ?>:</label><br />
								<?php _e( 'Tick to tag your invoice with the item name', 'zero-bs-crm' ); ?>
							</td>
							<td style="width:540px">
								<input type="checkbox" class="winput form-control" name="wpzbscrm_wctaginvoice" id="wpzbscrm_wctaginvoice" value="1"<?php echo ( !empty( $settings['wctaginvoice'] ) ? ' checked="checked"' : '' ); ?> />
							</td>
					</tr>


					<tr>
						<td class="wfieldname">
							<label for="jpcrm_woosync_auto_trash"><?php _e( 'Order Trash action', 'zero-bs-crm' ); ?>:</label><br />
							<?php _e( 'Choose what should happen when an order is trashed in WooCommerce', 'zero-bs-crm' ); ?>
						</td>
						<td style="width:540px">
							<select id="jpcrm_woosync_auto_trash" name="jpcrm_woosync_auto_trash" class="winput form-control">
								<?php

								$current_auto_trash_setting = !empty( $settings['auto_trash'] ) ? $settings['auto_trash'] : 'change_status';

								foreach ( $auto_deletion_options as $option_key => $option_label ) {
									?>
									<option value="<?php echo $option_key; ?>"<?php echo ( $option_key === $current_auto_trash_setting ? ' selected="selected"' : '' ); ?>>
										<?php echo $option_label; ?>
									</option>
									<?php
								}

								?>
							</select>
						</td>
					</tr>

					<tr>
						<td class="wfieldname">
							<label for="jpcrm_woosync_auto_delete"><?php _e( 'Order Delete action', 'zero-bs-crm' ); ?>:</label><br />
							<?php _e( 'Choose what should happen when an order is deleted in WooCommerce', 'zero-bs-crm' ); ?>
						</td>
						<td style="width:540px">
							<select id="jpcrm_woosync_auto_delete" name="jpcrm_woosync_auto_delete" class="winput form-control">
								<?php

								$current_auto_delete_setting = !empty( $settings['auto_delete'] ) ? $settings['auto_delete'] : 'change_status';

								foreach ( $auto_deletion_options as $option_key => $option_label ) {
									?>
									<option value="<?php echo $option_key; ?>"<?php echo ( $option_key === $current_auto_delete_setting ? ' selected="selected"' : '' ); ?>>
										<?php echo $option_label; ?>
									</option>
									<?php
								}

								?>
							</select>
						</td>
					</tr>

					<tr>
						<td class="wfieldname">
							<label for="wpzbscrm_wctagcoupon"><?php _e( 'Include Coupon as tag', 'zero-bs-crm' ); ?>:</label><br />
							<?php _e( 'Tick to include any used WooCommerce coupon codes as tags (depends on above settings)', 'zero-bs-crm' ); ?>
						</td>
						<td style="width:540px">
							<input type="checkbox" class="winput form-control" name="wpzbscrm_wctagcoupon" id="wpzbscrm_wctagcoupon" value="1"<?php echo ( !empty( $settings['wctagcoupon'] ) ? ' checked="checked"' : '' ); ?> />
						</td>
					</tr>

					<tr>
						<td class="wfieldname">
							<label for="wpzbscrm_wcinv"><?php _e( 'Create Invoices from WooCommerce Orders', 'zero-bs-crm' ); ?>:</label><br />
							<?php _e( 'Tick to create invoices from your WooCommerce orders', 'zero-bs-crm' ); ?>
						</td>
						<td style="width:540px">
							<input type="checkbox" class="winput form-control" name="wpzbscrm_wcinv" id="wpzbscrm_wcinv" value="1"<?php echo ( !empty( $settings['wcinv'] ) ? ' checked="checked"' : '' ); ?> />
						</td>
					</tr>

					<tr>
						<td class="wfieldname">
							<label for="wpzbscrm_wcacc"><?php _e( 'Show Invoices on My Account', 'zero-bs-crm' ); ?>:</label><br />
							<?php _e( 'Tick to show a Jetpack CRM Invoices menu item under WooCommerce My Account', 'zero-bs-crm' ); ?>
						</td>
						<td style="width:540px">
							<input type="checkbox" class="winput form-control" name="wpzbscrm_wcacc" id="wpzbscrm_wcacc" value="1"<?php echo ( !empty( $settings['wcacc'] ) ? ' checked="checked"' : '' ); ?> />
							<?php
							$invoices_enabled = zeroBSCRM_getSetting( 'feat_invs' ) > 0;
							if ( !$invoices_enabled ) {
								?>
								<br />
								<small><?php _e( 'Warning: Invoicing module is currently disabled.', 'zero-bs-crm' ); ?></small>
								<?php
							}
							?>
						</td>
					</tr>

					<tr>
						<td class="wfieldname">
							<label for="wctagproductprefix"><?php _e( 'Product tag prefix', 'zero-bs-crm' ); ?>:</label><br />
							<?php _e( 'Enter a tag prefix for product tags (e.g. Product: )', 'zero-bs-crm' ); ?>
						</td>
						<td style='width:540px'>
							<input type="text" class="winput form-control" name="wctagproductprefix" id="wctagproductprefix" value="<?php echo ( !empty( $settings['wctagproductprefix'] ) ? $settings['wctagproductprefix'] : '' ); ?>" placeholder="<?php _e( "e.g. 'Product: '", 'zero-bs-crm' ); ?>" />
						</td>
					</tr>

					<tr>
						<td class="wfieldname">
							<label for="wctagcouponprefix"><?php _e( 'Coupon tag prefix', 'zero-bs-crm' ); ?>:</label><br />
							<?php _e( 'Enter a tag prefix for coupon tags (e.g. Coupon: )', 'zero-bs-crm' ); ?>
						</td>
						<td style='width:540px'>
							<input type="text" class="winput form-control" name="wctagcouponprefix" id="wctagcouponprefix" value="<?php echo ( !empty( $settings['wctagcouponprefix'] ) ? $settings['wctagcouponprefix'] : '' ); ?>" placeholder="<?php _e( "e.g. 'Coupon: '", 'zero-bs-crm' ); ?>" />
						</td>
					</tr>

					<!-- #follow-on-refinements commented out for now as we need to review how product index works now we have accessible line items in v3.0
						<tr>
							<td class="wfieldname">
								<label for="wpzbscrm_wcprod"><?php // _e( 'Use Product Index', 'zero-bs-crm' ); ?>:</label><br />
								<?php // _e( 'Tick to allow Product Index on Invoices. Makes creating invoices easier', 'zero-bs-crm' ); ?></td>
							<td style="width:540px">
								<input type="checkbox" class="winput form-control" name="wpzbscrm_wcprod" id="wpzbscrm_wcprod" value="1"<?php // echo ( !empty( $settings['wcprod'] ) ? ' checked="checked"' : '' ); ?> />
							</td>
						</tr>
					-->

					<tr>
						<td class="wfieldname">
							<label for="wpzbscrm_port"><?php _e( 'WooCommerce My Account', 'zero-bs-crm' ); ?>:</label><br />
							<?php _e( 'Enter a comma-separated list of Jetpack CRM custom fields to let customers edit these via WooCommerce My Account (e.g. custom-field-1,other-custom-field)', 'zero-bs-crm' ); ?>
						</td>
						<td style="width:540px">
							<input type="text" class="winput form-control" name="wpzbscrm_wcport" id="wpzbscrm_port" value="<?php echo ( !empty( $settings['wcport'] ) ? $settings['wcport'] : '' ); ?>" />
						</td>
					</tr>
					<tr>
						<td class="wfieldname">
							<label for="jpcrm_enable_woo_status_mapping"><?php _e( 'Enable order status mapping', 'zero-bs-crm' ); ?>:</label><br />
							<?php _e( 'Tick here if you want WooCommerce order status changes to automatically change contact statuses', 'zero-bs-crm' ); ?>
						</td>
						<td style="width:540px"><input type="checkbox" class="winput form-control" name="jpcrm_enable_woo_status_mapping" id="jpcrm_enable_woo_status_mapping" value="1"<?php echo isset( $settings['enable_woo_status_mapping'] ) && (int)$settings['enable_woo_status_mapping'] === 0 ? '' : ' checked="checked"'; ?> />
						</td>
					</tr>

					<tr>
						<td class="wfieldname">
							<label><?php _e( 'Order status map', 'zero-bs-crm' ); ?>:</label><br />
							<?php _e( 'Here you can choose how you want to map WooCommerce order statuses to CRM contact statuses (if the above setting is enabled)', 'zero-bs-crm' ); ?>
						</td>
						<td style="width:540px">
							<table style="width:100%">
								<tr>
									<th><?php _e( 'Order status', 'zero-bs-crm' ); ?></th>
									<th><?php _e( 'Contact status', 'zero-bs-crm' ); ?></th>
								</tr>
								<?php

								foreach ( $woo_order_statuses as $k => $v ) {

									$selected = '';
									if ( is_array( $settings ) && isset( $settings[$k] ) ) {
										$selected = $settings[$k];
									}

									?>
									<tr class="jpcrm_woosync_order_status_map">
										<td><?php echo $v; ?></td>
										<td>
											<select class="winput" name="<?php echo $k; ?>" id="<?php echo $k; ?>">
												<option value="-1"><?php _e( 'Default', 'zero-bs-crm' ); ?></option>
												<?php
												// Jetpack CRM statuses chosen by user...
												foreach ( $contact_statuses as $status ) {
													echo '<option value="' . $status . '"' . ( $selected === $status ? ' selected' : '' ) . '>' . $status . '</option>';
												}
												?>
											</select>
										</td>
									</tr>
									<?php
								}

								?>

							</table>
						</td>
					</tr>

				</tbody>
			</table>

			<table class="table table-bordered table-striped wtab">
				<tbody>

					<tr>
						<td colspan="2" class="wmid"><button type="submit" class="button button-primary button-large"><?php _e( 'Save Settings', 'zero-bs-crm' ); ?></button></td>
					</tr>

				</tbody>
			</table>

		</form>

	</div>
	<?php
	wp_enqueue_style( 'jpcrm-woo-sync-settings-main', plugins_url( '/css/jpcrm-woo-sync-settings-main' . wp_scripts_get_suffix() . '.css', JPCRM_WOO_SYNC_ROOT_FILE ) );
}
