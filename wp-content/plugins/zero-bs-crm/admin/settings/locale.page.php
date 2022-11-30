<?php 
/*!
 * Admin Page: Settings: Localisation settings
 */

// stop direct access
if ( ! defined( 'ZEROBSCRM_PATH' ) ) exit; 

global $wpdb, $zbs;  #} Req

// Retrieve all settings
$settings = $zbs->settings->getAll();

// Load currency list
global $whwpCurrencyList;
if(!isset($whwpCurrencyList)) require_once( ZEROBSCRM_INCLUDE_PATH . 'wh.currency.lib.php');
/*
#} load country list
global $whwpCountryList;
if(!isset($whwpCountryList)) require_once( ZEROBSCRM_INCLUDE_PATH . 'wh.countrycode.lib.php');
*/

// check for default font reinstalls
if ( isset( $_GET['reinstall_default_font'] ) && zeroBSCRM_isZBSAdminOrAdmin() ){

    // check nonce
    check_admin_referer( 'zbs-update-settings-reinstall-font' );

    // hard reinstall
    $fonts = $zbs->get_fonts();
    $fonts->retrieve_and_install_default_fonts();
    $default_font_reinstalled = true;

}

// check for font reinstalls
if ( isset( $_GET['reinstall_font'] ) && zeroBSCRM_isZBSAdminOrAdmin() ){

    // check nonce
    check_admin_referer( 'zbs-update-settings-reinstall-font' );

    // retrieve font to install
    $font_to_install = sanitize_text_field(  $_GET['reinstall_font'] );
    $reinstall_font = true;

}

// font install
if ( isset($_POST['editwplf']) && zeroBSCRM_isZBSAdminOrAdmin() && !empty( $_POST['jpcrm_install_a_font'] ) ){

    // check nonce
    check_admin_referer( 'zbs-update-settings-localisation' );

    // retrieve font to install
    $font_to_install = sanitize_text_field(  $_POST['jpcrm_install_a_font'] );
    $reinstall_font = false;

}

// install a font/reinstall one
if ( isset( $font_to_install ) && $font_to_install !== '' ){

    // load fonts
    $fonts = $zbs->get_fonts();

    // retrieve font name
    $font_name = $fonts->zip_to_font_name( $font_to_install );

    // font exists?
    if ( $fonts->font_is_available( $font_name ) ){

        // install if not already installed
        if ( ! $fonts->font_is_installed( $font_name ) || $reinstall_font ){

            // attempt install
            $installed = $fonts->retrieve_and_install_specific_font( $font_name, $reinstall_font );

            if ( $installed ){

                // success
                $font_installed = true;
                $font_installed_name = $font_name;

            } else {

                // install errors may be passed here
                global $zbsExtensionInstallError;

                // general install error.
                $error_str = __( 'General error installing font: ', 'zero-bs-crm' ) . $font_name;
                if ( isset( $zbsExtensionInstallError ) ){
                    $error_str .= '<br>' . $zbsExtensionInstallError;
                }

                $errors[] = $error_str;

            }


        } else {

            // font already installed
            $errors[] = __( 'Font is already installed: ', 'zero-bs-crm' ) . $font_name;

        }

    } else {

            // font doesn't exist?
            $errors[] = __( 'Font does not exist: ', 'zero-bs-crm' ) . $font_name;

    }


}

// Act on any edits!
if (isset($_POST['editwplf']) && zeroBSCRM_isZBSAdminOrAdmin()){

    // got errors?
    $errors = array();

    // check nonce
    check_admin_referer( 'zbs-update-settings-localisation' );

    // Process settings changes

    // Currency (Grim but will work for now)
    $updatedSettings['currency'] = array('chr'  => '$','strval' => 'USD');
    if (isset($_POST['wpzbscrm_currency'])) {
        foreach ($whwpCurrencyList as $currencyObj) {
            if ($currencyObj[1] == $_POST['wpzbscrm_currency']) {
                $updatedSettings['currency']['chr'] = $currencyObj[0];
                $updatedSettings['currency']['strval'] = $currencyObj[1];
                break;
            }
        }
    }

    // 2.84 Currency Formatting
    $updatedSettings['currency_position'] = 0; if (isset($_POST['wpzbscrm_currency_position']) && !empty($_POST['wpzbscrm_currency_position'])) $updatedSettings['currency_position'] = (int)sanitize_text_field($_POST['wpzbscrm_currency_position']);
    $updatedSettings['currency_format_thousand_separator'] = ','; if (isset($_POST['wpzbscrm_currency_format_thousand_separator']) && !empty($_POST['wpzbscrm_currency_format_thousand_separator'])) $updatedSettings['currency_format_thousand_separator'] = sanitize_text_field($_POST['wpzbscrm_currency_format_thousand_separator']);
    $updatedSettings['currency_format_decimal_separator'] = '.'; if (isset($_POST['wpzbscrm_currency_format_decimal_separator']) && !empty($_POST['wpzbscrm_currency_format_decimal_separator'])) $updatedSettings['currency_format_decimal_separator'] = sanitize_text_field($_POST['wpzbscrm_currency_format_decimal_separator']);
    $updatedSettings['currency_format_number_of_decimals'] = 2;
    if (isset($_POST['wpzbscrm_currency_format_number_of_decimals'])) {

        $decimalCount = (int)sanitize_text_field( $_POST['wpzbscrm_currency_format_number_of_decimals'] );
        if ($decimalCount < 0) $decimalCount = 0;
        if ($decimalCount > 10) $decimalCount = 10;
        $updatedSettings['currency_format_number_of_decimals'] = $decimalCount;

    }

    // Update setting
    foreach ($updatedSettings as $k => $v) $zbs->settings->update($k,$v);

    // $msg out
    $sbupdated = true;

    // Reload settings
    $settings = $zbs->settings->getAll();



}

?>

    <?php 
    
    // any errors?
    if ( isset( $errors ) && is_array( $errors ) ) { 
        foreach ( $errors as $error ){
            echo zeroBSCRM_UI2_messageHTML( 'warning', __( 'Error', "zero-bs-crm") , $error ) . '<br>'; 
        }
    }
    
    // default fonts reinstalled dialogs
    if ( isset( $default_font_reinstalled ) && $default_font_reinstalled ) { 
        echo zeroBSCRM_UI2_messageHTML( 'success', __( 'Default fonts reinstalled', "zero-bs-crm"),  __( 'Default fonts reinstalled successfully.', 'zero-bs-crm' ) ) . '<br>'; 
    } 
    
    // font installed dialogs
    if ( isset( $font_installed ) && $font_installed ) { 
        echo zeroBSCRM_UI2_messageHTML( 'success', __( 'Font installed', "zero-bs-crm"),  __( 'Font installed successfully: ', 'zero-bs-crm' ) . $font_installed_name ) . '<br>'; 
    } 

    // general settings save
    if ( isset( $sbupdated ) && $sbupdated ) { 
        echo zeroBSCRM_UI2_messageHTML( 'success', __( 'Settings Updated', "zero-bs-crm") ) . '<br>'; 
    } 

    // for now fonts directly displayed from setting. Could add verification of each installed font to this page.
    $font_install_setting = zeroBSCRM_getSetting('pdf_extra_fonts_installed');
    if ( !is_array( $font_install_setting ) ){
        $font_install_setting = array();
    }
    
    ?>
    <div id="sbA">

        <form method="post" action="?page=<?php echo $zbs->slugs['settings']; ?>&tab=locale">
            <input type="hidden" name="editwplf" id="editwplf" value="1" />
            <?php
            // add nonce
            wp_nonce_field( 'zbs-update-settings-localisation');
            ?>

            <table class="table table-bordered table-striped wtab">

                <thead>

                <tr>
                    <th colspan="2" class="wmid"><?php _e('General Locale Settings',"zero-bs-crm"); ?>:</th>
                </tr>

                </thead>

                <tbody>

                    <tr>
                        <td class="wfieldname"><label for="wpzbscrm_currency"><?php _e('Currency Symbol',"zero-bs-crm"); ?>:</label></td>
                        <td>
                            <select class="winput short" name="wpzbscrm_currency" id="wpzbscrm_currency">
                                <?php // prioritise common currencies first ?>
                                <option value="USD">$ (USD)</option>
                                <option value="GBP">&pound; (GBP)</option>
                                <option value="EUR">€ (EUR)</option>
                                <option disabled="disabled">----</option>
                                <?php foreach ($whwpCurrencyList as $currencyObj): ?>
                                    <option value="<?= $currencyObj[1] ?>" <?php if (isset($settings['currency']) && isset($settings['currency']['strval']) && $settings['currency']['strval'] == $currencyObj[1]) echo ' selected="selected"'; ?>>
                                        <?= $currencyObj[0].' ('.$currencyObj[1].')' ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td class="wfieldname"><label for="wpzbscrm_currency_position"><?php _e('Currency Format',"zero-bs-crm"); ?>:</label><br /><?php _e('Choose how you want your currency format to display',"zero-bs-crm"); ?></td>
                        <td style="width:540px">
                            <label for="wpzbscrm_currency_position">
                                <?php _e('Symbol position: ','zero-bs-crm'); ?>
                            </label>

                            <select class='form-control' name="wpzbscrm_currency_position" id="wpzbscrm_currency_position">
                                <option value='0' <?php if($settings['currency_position'] == 0) echo 'selected'; ?>><?php _e('Left','zero-bs-crm');?></option>
                                <option value='1' <?php if($settings['currency_position'] == 1) echo 'selected'; ?>><?php _e('Right','zero-bs-crm');?></option>
                                <option value='2' <?php if($settings['currency_position'] == 2) echo 'selected'; ?>><?php _e('Left with space','zero-bs-crm');?></option>
                                <option value='3' <?php if($settings['currency_position'] == 3) echo 'selected'; ?>><?php _e('Right with space','zero-bs-crm');?></option>
                            </select>

                            <br/>
                            <label for="wpzbscrm_currency_format_thousand_separator">
                                <?php _e('Thousand separator: ','zero-bs-crm'); ?>
                            </label>
                            <input type="text" class="winput form-control" name="wpzbscrm_currency_format_thousand_separator" id="wpzbscrm_currency_format_thousand_separator" value="<?php echo $settings['currency_format_thousand_separator']; ?>" />

                            <br/>
                            <label for="wpzbscrm_currency_format_decimal_separator">
                                <?php _e('Decimal separator: ','zero-bs-crm'); ?>
                            </label>
                            <input type="text" class="winput form-control" name="wpzbscrm_currency_format_decimal_separator" id="wpzbscrm_currency_format_decimal_separator" value="<?php echo $settings['currency_format_decimal_separator']; ?>" />

                            <br/>
                            <label for="wpzbscrm_currency_format_number_of_decimals">
                                <?php _e('Number of decimals: ','zero-bs-crm'); ?>
                            </label>
                            <input type="number" class="winput form-control" name="wpzbscrm_currency_format_number_of_decimals" id="wpzbscrm_currency_format_number_of_decimals" value="<?php echo $settings['currency_format_number_of_decimals']; ?>" />


                        </td>
                    </tr>

                </tbody>

            </table>


            <table class="table table-bordered table-striped wtab">

                <thead>

                <tr>
                    <th colspan="2" class="wmid"><?php _e('Fonts for PDF generation',"zero-bs-crm"); ?>:</th>
                </tr>

                </thead>

                <tbody>

                    <tr>
                        <td class="wfieldname">
                            <label><?php _e('Install Fonts:',"zero-bs-crm"); ?></label>
                            <p><?php _e('Choose extra fonts to include when generating PDF documents.',"zero-bs-crm"); ?></p>
                            <br>
                            <div class="ui segment">
                                <label for="jpcrm_install_a_font"><?php _e('Install a font:',"zero-bs-crm"); ?></label>
                                <select class='form-control' name="jpcrm_install_a_font" id="jpcrm_install_a_font">
                                    <option value="" disabled="disabled" selected="selected">-- <?php _e( 'Select a font to install', 'zero-bs-crm' ); ?> --</option>
                                <?php

                                    $fonts = $zbs->get_fonts();
                                    $fonts_list = $fonts->list_all_available( true );

                                    if ( is_array( $fonts_list ) ) {
                                        foreach ( $fonts_list as $font_name => $font_cleaned_name ){

                                            echo '<option value="' . $font_name . '">' . $font_cleaned_name . '</option>';

                                        }
                                    }

                                ?>
                                </select>
                                <p><?php echo sprintf( __( 'All Noto fonts are licensed under the <a href="%s" target="_blank">SIL Open Font License</a>.', 'zero-bs-crm' ) . ' ' . __( 'For more information, please go <a href="%s" target="_blank">here</a>.', 'zero-bs-crm' ), 'https://fonts.google.com/attribution', 'https://fonts.google.com/noto/specimen/Noto+Sans/about' ); ?></p>
                                <button type="submit" class="ui small green button"><?php _e( 'Install font', 'zero-bs-crm' ); ?></button>
                            </div>

                        </td>
                        <td style="width:540px">
                            <label>
                                <?php _e('Installed Fonts: ','zero-bs-crm'); ?>
                            </label>

                            <div class="ui segment">

                                <div class="ui relaxed divided list">
                                    <div class="item">
                                      <div class="content">
                                        <div class="header">
                                            Noto Sans <?php _e( '(Installed by default)', 'zero-bs-crm') ;?> 
                                            <a href="<?php
                                            $url = '?page=' . $zbs->slugs['settings'] .'&tab=locale&reinstall_default_font=1';
                                            echo add_query_arg( '_wpnonce', wp_create_nonce( 'zbs-update-settings-reinstall-font' ), $url );
                                            ?>" class="ui right floated mini icon button" title="<?php _e( 'Reinstall default fonts', 'zero-bs-crm'); ?>"><i class="sync icon"></i></a>
                                        </div>
                                        <!-- from https://fonts.google.com/noto/specimen/Noto+Sans -->
                                        <?php _e( 'Noto Sans is an unmodulated ("sans serif") design for texts in the Latin, Cyrillic and Greek scripts, which is also suitable as the complementary choice for other script-specific Noto Sans fonts.', 'zero-bs-crm' ); ?>
                                      </div>
                                    </div>

                                    <?php

                                        // display successfully installed fonts
                                        foreach ( $font_install_setting as $font_slug => $install_timestamp ){

                                            ?>
                                    <div class="item">
                                      <div class="content">
                                        <div class="header">
                                            <?php
                                            // output
                                            $font_name = $fonts->font_slug_to_name( $font_slug );
                                            $font_css_name = str_replace( ' ' ,'', $font_name ); // e.g. NotoSansJP;
                                            echo $font_name . ' <code>' . $font_css_name . '</code>'; ?>
                                            <a href="<?php
                                            $url = '?page=' . $zbs->slugs['settings'] .'&tab=locale&reinstall_font=' . $fonts->font_slug_to_name( $font_slug );
                                            echo add_query_arg( '_wpnonce', wp_create_nonce( 'zbs-update-settings-reinstall-font' ), $url );
                                            ?>" class="ui right floated mini icon button" title="<?php _e( 'Reinstall font', 'zero-bs-crm'); ?>"><i class="sync icon"></i></a>
                                        </div>
                                      </div>
                                    </div><?php

                                        }


                                    ?>

                                </div>

                            </div>

                        </td>
                    </tr>

                </tbody>

            </table>

            <table class="table table-bordered table-striped wtab">
                <tbody>

                <tr>
                    <td class="wmid"><button type="submit" class="ui primary button"><?php _e('Save Settings',"zero-bs-crm"); ?></button></td>
                </tr>

                </tbody>
            </table>

        </form>

    </div>