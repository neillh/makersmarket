<?php  $tracked_letters = (isset($this->instance_settings['tracked_letters']))?$this->instance_settings['tracked_letters']:''; ?>
<?php  $domestic_tracked_letters = \AustraliaPost\Core\Constants::domestic_tracked_letters; ?>

<span></span>
<tr valign="top">
	<th scope="row" class="titledesc">
		<label for="woocommerce_auspost_debug_mode"><?php _e('Domestic Tracked Letters', 'woocommerce-australia-post-pro'); ?></label>
	</th>
	<td class="forminp">
		<fieldset>
			<table id="auspost_tracked_letters_table" class="wc_gateways widefat" style="table-layout: fixed; width:250px;">
				<thead>
				<tr>
					<th class="column-key" style="width: 70px;"><?php _e('Enabled?', 'woocommerce-australia-post-pro');?></th>
					<th style="width:110px;"><?php _e('Item', 'woocommerce-australia-post-pro');?></th>
					<th style="width:70px;"><?php _e('Price', 'woocommerce-australia-post-pro');?></th>
					<th style="width:115px;"><?php _e('Priority? (+$0.5)', 'woocommerce-australia-post-pro');?></th>
				</tr>
				</thead>
				<tbody>
                        <?php foreach ($domestic_tracked_letters as $key => $letter): ?>
						<tr>
							<td><input name="woocommerce_tracked_letters[<?php echo $key; ?>][enabled]" <?php if(isset($tracked_letters[$key]['enabled']) && $tracked_letters[$key]['enabled'] == 'on'): ?> value="on" <?php endif; ?> <?php if(isset($tracked_letters[$key]['enabled'])): ?> checked <?php endif; ?> type="checkbox"></td>
							<td><?php echo $letter['name']; ?></td>
							<td>
                                <div class="input-box">
                                    <span class="prefix">$</span>
                                    <input class="auspost-small" type="number" min="0.1" step=".01" placeholder="<?php echo $letter['price']; ?>" name="woocommerce_tracked_letters[<?php echo $key; ?>][price]" value="<?php echo esc_attr((isset($tracked_letters[$key]))?$tracked_letters[$key]['price']:$letter['price']); ?>" />
                                </div>
                            </td>
                            <td><input name="woocommerce_tracked_letters[<?php echo $key; ?>][priority]" <?php if(isset($tracked_letters[$key]['priority']) && $tracked_letters[$key]['priority'] == 'on'): ?> value="on" <?php endif; ?> <?php if(isset($tracked_letters[$key]['priority'])): ?> checked <?php endif; ?> type="checkbox"></td>
						</tr>
                        <?php endforeach; ?>
				</tbody>
			</table>
            <p>Make sure to enable the <strong>Letters Shipping</strong> option.</p>
		</fieldset>
	</td>
</tr>

<style>
    .input-box {
        display: flex;
        align-items: center;
        max-width: 300px;
        background: #fff;
        border: 1px solid #a0a0a0;
        border-radius: 4px;
        padding-left: 0.5rem;
        overflow: hidden;
        font-family: sans-serif;
    }

    .input-box .prefix {
        font-weight: 300;
        font-size: 14px;
        color: #999;
    }

    .input-box input {
        flex-grow: 1;
        font-size: 14px;
        background: #fff;
        border: none;
        outline: none;
        padding: 0.5rem;
    }

    .input-box:focus-within {
        border-color: #777;
    }
    .input-box input:focus {
        border-color: #777 !important;
        box-shadow: none !important;
    }

    .input-box input::-webkit-outer-spin-button,
    .input-box input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    .input-box input[type=number] {
        -moz-appearance: textfield;
    }
</style>
