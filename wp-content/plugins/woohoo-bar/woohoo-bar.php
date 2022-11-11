<?php

/*
 * Plugin Name: WooHoo Bar
 * Plugin URI: https://pootlepress.com/
 * Description: Flexible and customizable bars your WooCommerce store.
 * Author: Pootlepress
 * Version: 1.0.1
 * Author URI: https://pootlepress.com/
 */
require 'inc/bars.php';
require 'inc/countdowns.php';
/**
 * Class Woohoo_Bar
 * Enqueues scripts and styles for blocks.
 * Displays a notice to admin if Caxton is not installed.
 */
class Woohoo_Bar
{
    /** @var self Instance */
    private static  $_instance ;
    /** @var WooHoo_Bar_Bars */
    private  $bars ;
    /** @var WooHoo_Bar_Countdowns */
    private  $countdowns ;
    /**
     * Returns instance of current class
     * @return self Instance
     */
    public static function instance()
    {
        if ( !self::$_instance ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * Woohoo_Bar constructor.
     */
    protected function __construct()
    {
        add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ] );
    }
    
    /**
     * Initiates hooks
     * @action init
     */
    public function plugins_loaded()
    {
        
        if ( !class_exists( 'Caxton' ) ) {
            // Caxton not installed
            add_action( 'admin_notices', array( $this, 'caxton_required_notice' ) );
        } else {
            $this->bars = new WooHoo_Bar_Bars();
            $this->countdowns = new WooHoo_Bar_Countdowns();
            $this->setup_fs();
            add_filter( 'woocommerce_taxonomy_args_product_cat', array( $this, 'enable_rest_taxonomy' ) );
            add_action( 'enqueue_block_editor_assets', array( $this, 'editor_enqueue' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
        }
    
    }
    
    /**
     * Enqueues editor styles
     * @action enqueue_block_editor_assets
     */
    public function editor_enqueue()
    {
        global  $post ;
        $url = plugin_dir_url( __FILE__ );
        wp_enqueue_style( "woohoo-bar-front", "{$url}assets/woobar.css" );
        wp_enqueue_script( "woohoo-bar-admin", "{$url}assets/blocks.min.js", array( 'caxton' ) );
        wp_localize_script( 'woohoo-bar-admin', 'woohoobarData', [
            'url'       => "{$url}assets/",
            'post_type' => get_post_type(),
        ] );
    }
    
    /**
     * Enqueues front end styles
     * @action wp_enqueue_scripts
     */
    public function enqueue()
    {
        $url = plugin_dir_url( __FILE__ );
        wp_enqueue_style( "woohoo-bar-front", "{$url}assets/woobar.css" );
        wp_register_script( "woohoo-bar-front", "{$url}assets/woobar.js" );
    }
    
    /**
     * Adds notice if Caxton is not installed.
     * @action admin_notices
     */
    public function caxton_required_notice()
    {
        echo  '<div class="notice is-dismissible error">' . '<p>' . sprintf( __( '%s requires that you have our free plugin %s installed and activated.', 'woohoo-bar' ), '<b>Woohoo bar</b>', '<a href="' . admin_url( 'plugin-install.php?s=caxton&tab=search&type=term' ) . '">Caxton</a>' ) . '</p>' . '<p><a  href="' . admin_url( 'plugin-install.php?s=caxton&tab=search&type=term' ) . '" class="button-primary">' . __( 'Install Caxton', 'woohoo-bar' ) . '</a></p>' . '</div>' ;
    }
    
    public function setup_fs()
    {
        global  $woohoo_bar_fs ;
        
        if ( !isset( $woohoo_bar_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/inc/wp-sdk/start.php';
            $woohoo_bar_fs = fs_dynamic_init( array(
                'id'              => '7009',
                'slug'            => 'woohoobar',
                'premium_slug'    => 'woohoo-bar',
                'type'            => 'plugin',
                'public_key'      => 'pk_83d45ba0860d0809a064a2dfb7da1',
                'is_premium'      => true,
                'is_premium_only' => true,
                'has_addons'      => false,
                'has_paid_plans'  => true,
                'trial'           => array(
                'days'               => 14,
                'is_require_payment' => true,
            ),
                'menu'            => array(
                'slug'    => 'edit.php?post_type=woohoo_bar',
                'support' => false,
            ),
                'is_live'         => true,
            ) );
        }
        
        return $woohoo_bar_fs;
    }
    
    public function enable_rest_taxonomy( $args )
    {
        $args['show_in_rest'] = true;
        return $args;
    }

}
Woohoo_Bar::instance();