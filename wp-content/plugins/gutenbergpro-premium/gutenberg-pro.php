<?php

/**
 * Plugin Name:     Gutenberg Pro
 * Description:     Put Rocket Boosters on Gutenberg Editor Blocks and take your designs out of this world!
 * Version:         1.5.2
 * Author:          Pootlepress
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     gutenberg-pro
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/__init__.php';
use  GTP\GutenbergPro\Main\gtp__GutenbergPro ;
# registering meta box to save post columns styling
add_action( 'init', function () {
    gtp__GutenbergPro::register();
} );
# rendering stylesheets
add_action( 'wp_footer', function () {
    gtp__GutenbergPro::render();
} );
# enqueue script in the gutenberg editor
add_action( 'admin_enqueue_scripts', function ( $suffix ) {
    $editor_style = plugins_url( '/', __FILE__ ) . 'build/index.css';
    wp_enqueue_style(
        'gtp-gutenberg-pro-editor-style',
        $editor_style,
        [],
        "updated"
    );
    wp_enqueue_script(
        'gtp-gutenberg-pro-plugin-script',
        plugins_url( '/', __FILE__ ) . 'build/index.js',
        [
        'wp-api',
        'wp-i18n',
        'wp-components',
        'wp-element',
        'wp-editor'
    ],
        'new',
        true
    );
    # sending php variables in javascript
    $localize_data = gtp__GutenbergPro::get_localize_data();
    wp_localize_script( 'gtp-gutenberg-pro-plugin-script', 'gtpGlobals', $localize_data );
} );
add_action( 'init', function () {
    
    if ( !is_admin() ) {
        $front_end_style = plugins_url( '/', __FILE__ ) . 'build/style-index.css';
        wp_enqueue_style(
            'gtp-gutenberg-pro-frontend-style',
            $front_end_style,
            [],
            "update"
        );
    }

} );

if ( !function_exists( 'gbpro_fs' ) ) {
    // Create a helper function for easy SDK access.
    function gbpro_fs()
    {
        global  $gbpro_fs ;
        
        if ( !isset( $gbpro_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/includes/wp-sdk/start.php';
            $gbpro_fs = fs_dynamic_init( array(
                'id'               => '7197',
                'slug'             => 'gutenbergpro',
                'type'             => 'plugin',
                'public_key'       => 'pk_ca5a930ab218f6bebb0587a1bea01',
                'is_premium'       => true,
                'is_premium_only'  => true,
                'has_addons'       => false,
                'has_paid_plans'   => true,
                'is_org_compliant' => false,
                'trial'            => array(
                'days'               => 7,
                'is_require_payment' => true,
            ),
                'menu'             => array(
                'slug'    => 'gutenberg-pro',
                'support' => false,
            ),
                'is_live'          => true,
            ) );
        }
        
        return $gbpro_fs;
    }
    
    // Init Freemius.
    gbpro_fs();
    // Signal that SDK was initiated.
    do_action( 'gbpro_fs_loaded' );
}
