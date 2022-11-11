<?php

/*
 * Plugin Name: WooBuilder blocks
 * Plugin URI: http://pootlepress.com/
 * Description: Bring the power of WordPress' blocks builder to products for fully customizable product layouts.
 * Author: PootlePress
 * Version: 4.4.2
 * Author URI: http://pootlepress.com/
 * @developer shramee <shramee.srivastav@gmail.com>
 */
/** Plugin admin class */
require 'inc/class-admin.php';
/** Plugin admin class */
require 'inc/class-fse.php';
/** Plugin public class */
require 'inc/class-public.php';
/** Plugin public class */
require 'inc/class-split-testing.php';
if ( file_exists( __DIR__ . '/inc/class-pro.php' ) ) {
    /** Plugin Pro class */
    require 'inc/class-pro.php';
}
/**
 * WooBuilder blocks main class
 * @static string $token Plugin token
 * @static string $file Plugin __FILE__
 * @static string $url Plugin root dir url
 * @static string $path Plugin root dir path
 * @static string $version Plugin version
 */
class WooBuilder_Blocks
{
    /** @var WooBuilder_Blocks Instance */
    private static  $_instance = null ;
    /** @var string Token */
    public static  $token ;
    /** @var string Version */
    public static  $version ;
    /** @var string Plugin main __FILE__ */
    public static  $file ;
    /** @var string Plugin directory url */
    public static  $url ;
    /** @var string Plugin directory path */
    public static  $path ;
    /** @var WooBuilder_Blocks_Admin Instance */
    public  $admin ;
    /** @var WooBuilder_Blocks_Public Instance */
    public  $public ;
    private  $templates = array() ;
    /** @var WooBuilder_Blocks_FSE Instance */
    private  $fse ;
    public static function templates( $reload = false )
    {
        if ( $reload || !self::instance()->templates ) {
            self::instance()->templates = apply_filters( 'woobuilder_templates', [] );
        }
        return self::instance()->templates;
    }
    
    public static function template( $id )
    {
        $tpls = self::templates();
        if ( isset( $tpls[$id] ) ) {
            return $tpls[$id];
        }
        return [];
    }
    
    /**
     * @param bool $post_type
     * @return bool|string[] If passed in post type is WooBk type or all WooBk Types
     */
    public static function is_type( $post_type = null )
    {
        $post_types = [
            'product',
            'woobuilder_template',
            'wp_template',
            'wp_template_part'
        ];
        if ( !is_null( $post_type ) ) {
            return in_array( $post_type, $post_types );
        }
        return $post_types;
    }
    
    public static function blocks()
    {
        return [
            'wc_hook',
            'sale_counter',
            'stock_countdown',
            'related_products',
            'upsell_products',
            'add_to_cart',
            'add_to_cart_sticky',
            'request_quote',
            'product_price',
            'tabs',
            'excerpt',
            'long_description',
            'meta',
            'title',
            'rating',
            'reviews',
            'images',
            'cover',
            'images_carousel'
        ];
    }
    
    /**
     * Returns meta keys
     * @return array Meta key string
     */
    public static function meta_keys()
    {
        return [
            'woobk_bg_color',
            'woobk_bg_image',
            'woobk_bg_parallax',
            'woobk_bg_gradient',
            'woobk_hide_header',
            'woobk_hide_footer',
            'woobk_hide_sidebar',
            'woobk_add_to_cart_text',
            'woobk_out_of_stock_text',
            'woobk_on_back_order_text',
            'woobk_thankyou_page'
        ];
    }
    
    /**
     * Checks if WooBuilder is enabled on product.
     * @param int $product_id
     * @return string|int Template id or enabled
     */
    public static function template_id( $product_id = 0 )
    {
        if ( !$product_id ) {
            $product_id = get_the_ID();
        }
        return get_post_meta( $product_id, 'woobuilder', 'single' );
    }
    
    /**
     * Checks if WooBuilder is enabled on product.
     * @param int $product_id
     * @return bool Enabled
     */
    public static function enabled( $product_id = 0 )
    {
        return !!self::template_id( $product_id );
    }
    
    /**
     * Return class instance
     * @return WooBuilder_Blocks instance
     */
    public static function instance( $file = '' )
    {
        if ( null == self::$_instance ) {
            self::$_instance = new self( $file );
        }
        return self::$_instance;
    }
    
    /**
     * Constructor function.
     * @param string $file __FILE__ of the main plugin
     * @access  private
     * @since   1.0.0
     */
    private function __construct( $file )
    {
        self::$token = 'woobuilder-blocks';
        self::$file = $file;
        self::$url = plugin_dir_url( $file );
        self::$path = plugin_dir_path( $file );
        self::$version = '4.4.1';
        add_action( 'plugins_loaded', [ $this, 'init' ] );
        
        if ( function_exists( 'caxton_fs' ) ) {
            // Caxton FS function exists and is executed
            $this->init_fs();
        } else {
            add_action( 'caxton_fs_loaded', [ $this, 'init_fs' ] );
        }
    
    }
    
    public function init()
    {
        $this->admin = WooBuilder_Blocks_Admin::instance();
        add_action( 'admin_menu', array( $this->admin, 'admin_menu' ) );
        
        if ( !class_exists( 'Caxton' ) ) {
            // Caxton not installed
            add_action( 'admin_notices', array( $this, 'caxton_required_notice' ) );
        } else {
            
            if ( !class_exists( 'WooCommerce' ) ) {
                // WooCommerce not installed
                add_action( 'admin_notices', array( $this, 'wc_required_notice' ) );
            } else {
                
                if ( class_exists( 'Classic_Editor' ) ) {
                    // Classic editor installed
                    add_action( 'admin_notices', array( $this, 'classic_editor_error' ) );
                } else {
                    
                    if ( $this->init_fs()->can_use_premium_code() || $this->init_fs()->has_secret_key() ) {
                        // All clear! initiate admin and public code
                        $this->_admin();
                        //Initiate admin
                        $this->_fse();
                        //Initiate fse
                        $this->_public();
                        //Initiate public
                    }
                
                }
            
            }
        
        }
    
    }
    
    /**
     * Initiates FS SDK
     * No need to include the SDK, already done in Caxton
     * @return Freemius
     */
    public function init_fs()
    {
        global  $wb_fs ;
        
        if ( !isset( $wb_fs ) ) {
            require_once dirname( __FILE__ ) . '/inc/wp-sdk/start.php';
            try {
                $wb_fs = fs_dynamic_init( array(
                    'id'               => '3514',
                    'slug'             => 'woobuilder-blocks',
                    'type'             => 'plugin',
                    'public_key'       => 'pk_c52effbb9158dc8c4098e44429e4a',
                    'is_premium'       => true,
                    'is_premium_only'  => true,
                    'has_addons'       => false,
                    'has_paid_plans'   => true,
                    'is_org_compliant' => false,
                    'trial'            => array(
                    'days'               => 14,
                    'is_require_payment' => true,
                ),
                    'menu'             => array(
                    'slug'    => 'woobuilder-blocks',
                    'support' => false,
                    'parent'  => array(
                    'slug' => 'woocommerce',
                ),
                ),
                    'is_live'          => true,
                ) );
            } catch ( Freemius_Exception $e ) {
                error_log( 'Error ' . $e->getCode() . ': ' . $e->getMessage() );
            }
        }
        
        return $wb_fs;
    }
    
    public function caxton_required_notice()
    {
        echo  '<div class="notice is-dismissible error">
				<p>' . sprintf( __( '%s requires that you have our free plugin %s installed and activated.', 'woobuilder-blocks' ), '<b>WooBuilder Blocks</b>', '<a href="' . admin_url( 'plugin-install.php?s=caxton&tab=search&type=term' ) . '">Caxton</a>' ) . '</p>' . '<p><a style="background:#e25c4e;border-color:#d23c1e;text-shadow:none;box-shadow:0 1px 0 #883413;" href="' . admin_url( 'plugin-install.php?s=caxton&tab=search&type=term' ) . '" class="button-primary button-pootle">' . __( 'Install Caxton', 'sfp_blocks' ) . '</a></p>' . '</div>' ;
    }
    
    public function wc_required_notice()
    {
        echo  '<div class="notice is-dismissible error">
				<p>' . sprintf( __( '%s requires that you have our free plugin %s installed and activated.', 'woobuilder-blocks' ), '<b>WooBuilder Blocks</b>', '<a href="' . admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) . '">WooCommerce</a>' ) . '</p>' . '<p><a style="background:#e25c4e;border-color:#d23c1e;text-shadow:none;box-shadow:0 1px 0 #883413;" href="' . admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) . '" class="button-primary button-pootle">' . __( 'Install WooCommerce', 'sfp_blocks' ) . '</a></p>' . '</div>' ;
    }
    
    public function classic_editor_error()
    {
        echo  '<div class="notice error"><p>' . sprintf( __( 'Warning! Please de-activate the %2$s plugin before using %1$s.', 'woobuilder-blocks' ), '<b>WooBuilder Blocks</b>', '<b>Classic Editor</b>' ) . '</p></div>' ;
    }
    
    /**
     * Initiates admin class and adds admin hooks
     */
    private function _admin()
    {
        //Instantiating admin class
        add_filter(
            'gutenberg_can_edit_post_type',
            [ $this->admin, 'enable_gutenberg_products' ],
            11,
            2
        );
        add_filter(
            'use_block_editor_for_post_type',
            [ $this->admin, 'enable_gutenberg_products' ],
            11,
            2
        );
        add_filter( 'save_post', array( $this->admin, 'save_post' ) );
        add_filter( 'dbx_post_sidebar', array( $this->admin, 'admin_footer' ) );
        add_filter( 'post_submitbox_misc_actions', array( $this->admin, 'product_meta_fields' ) );
        add_filter(
            'rest_request_after_callbacks',
            array( $this->admin, 'rest_request_after_callbacks' ),
            10,
            3
        );
        add_action( 'rest_api_init', array( $this->admin, 'rest_api_init' ) );
        add_action( 'woocommerce_email_classes', array( $this->admin, 'woocommerce_email_classes' ), 25 );
        add_filter( 'block_categories_all', array( $this->admin, 'block_categories' ) );
        add_action( 'enqueue_block_editor_assets', array( $this->admin, 'enqueue' ), 7 );
        add_action( 'rest_api_init', array( $this->admin, 'save_global_post' ), 0 );
        add_action( 'wp_enqueue_media', array( $this->admin, 'restore_global_post' ), 0 );
    }
    
    /**
     * Initiates public class and adds public hooks
     */
    private function _fse()
    {
        //Instantiating public class
        $this->fse = WooBuilder_Blocks_FSE::instance();
        add_filter(
            'get_block_templates',
            array( $this->fse, 'get_block_templates' ),
            99,
            3
        );
        add_filter(
            'get_block_file_template',
            array( $this->fse, 'get_block_file_template' ),
            11,
            3
        );
        add_filter(
            'init',
            array( $this->fse, 'register_fse_blocks' ),
            11,
            3
        );
        //		add_filter( 'pre_get_block_file_template', array( $this, 'maybe_return_blocks_template' ), 10, 3 );
    }
    
    /**
     * Initiates public class and adds public hooks
     */
    private function _public()
    {
        //Instantiating public class
        $this->public = WooBuilder_Blocks_Public::instance();
        // Register blocks
        add_action( 'init', array( $this->public, 'init' ) );
        add_action( 'wp_loaded', array( $this->public, 'product_actions' ), 25 );
        add_action( 'wp_head', array( $this->public, 'maybe_setup_woobuilder_product' ) );
        add_filter( 'woocommerce_taxonomy_args_product_visibility', array( $this->public, 'enable_rest_taxonomy' ) );
        add_filter( 'woocommerce_taxonomy_args_product_cat', array( $this->public, 'enable_rest_taxonomy' ) );
        add_filter( 'woocommerce_taxonomy_args_product_tag', array( $this->public, 'enable_rest_taxonomy' ) );
        add_filter( 'woocommerce_taxonomy_args_product_shipping_class', array( $this->public, 'enable_rest_taxonomy' ) );
        add_filter( 'woocommerce_product_single_add_to_cart_text', array( $this->public, 'replace_add_to_cart_text' ), 1 );
        add_filter(
            'woocommerce_variation_option_name',
            array( $this->public, 'append_stock_status' ),
            4,
            4
        );
        add_filter( 'woocommerce_out_of_stock_message', array( $this->public, 'out_of_stock_message' ) );
        add_filter(
            'woocommerce_get_availability',
            array( $this->public, 'get_availability' ),
            10,
            2
        );
        add_filter( 'woocommerce_dropdown_variation_attribute_options_args', array( $this->public, 'prepare_variations_map' ) );
        add_filter( 'woocommerce_thankyou', array( $this->public, 'redirect_thankyou_page' ) );
        //Enqueue front end JS and CSS
        add_action( 'wp_enqueue_scripts', array( $this->public, 'enqueue' ) );
        add_action( 'wp_footer', array( $this->public, 'maybe_woobk_scripts' ) );
    }

}
/** Intantiating main plugin class */
WooBuilder_Blocks::instance( __FILE__ );