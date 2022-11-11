<?php

require_once(plugin_dir_path(__FILE__) . 'class-gutenbergpro-admin-page.php');
add_action('admin_enqueue_scripts', 'gutenberg_pro_styles');

function gutenberg_pro_styles($suffix)
{
    wp_enqueue_style('gtp_admin_css', plugin_dir_url(__FILE__) . 'admin_styles.css', [], '1.0.0');
}
