<?php

/**
 * This Template renders the backend page of the template preview.
 * Wordpress Backend -> Settings -> Add Customer Settings -> [Tab]Template
 * 
 * @version 1.4
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('WC_Email')) {
    echo __('Error: WooCommerce Email Class not found. Make sure that you have the newest version of WooCommerce installed.', 'wac');
    return;
}

$wac = new woo_add_customer;
$blog_name = get_bloginfo('name');
$blog_name = html_entity_decode($blog_name, ENT_QUOTES, 'UTF-8');
$user = wp_get_current_user();

$message = $this->load_template_to_var('new-account', 'email/', $user->user_email, $user->display_name, $wac->get_user_reset_password_link($user->user_email), $blog_name);
//Apply the style
$woo = new WC_Email;
$message = $woo->style_inline($message);

$subject = $wac->get_mail_subject('wac_template_subject_add_account');

?>
<h2><?php echo __('Template settings', 'wac'); ?></h2>
<div id="template_preview_container">
    <div class="subject_content">
        <div><?php echo __('Email Subject:', 'wac'); ?></div>
        <div class="subject"><strong><?php echo $subject; ?></strong></div>
    </div>
    <div class="main_content">
        <div><?php echo __('Email Content:', 'wac'); ?></div>
        <div class="message"><?php echo $message; ?></div>
    </div>
</div>
<div id="load_location_container">
    <?php echo __('Template loaded from:', 'wac') . ' ' . $wac->get_template_location('new-account', 'email/');  ?>

</div>
<div id="reload_container">
    <form id='wac_options_page' action="options-general.php?page=wac-options&tab=template" method="post" enctype="multipart/form-data">
        <?php
        submit_button(__('Reload template', 'wac'));
        ?>
    </form>
</div>