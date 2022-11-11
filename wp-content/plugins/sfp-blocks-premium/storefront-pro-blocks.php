<?php

/*
Plugin Name: Storefront Pro Blocks
Plugin URI: https://pootlepress.com/
Description: Customize your WooCommerce Shop page, Home Page and Category Pages
Author: Pootlepress
Version: 3.4.0
WC tested up to: 5.1.0
Author URI: https://pootlepress.com/
@developer shramee <shramee.srivastav@gmail.com>
*/
/** Plugin admin class */
require 'inc/class-admin.php';
/** Plugin public class */
require 'inc/class-public.php';
/** Plugin public class */
require 'inc/class-grid.php';
/**
 * Storefront Pro Blocks main class
 * @static string $token Plugin token
 * @static string $file Plugin __FILE__
 * @static string $url Plugin root dir url
 * @static string $path Plugin root dir path
 * @static string $version Plugin version
 */
class Storefront_Pro_Blocks
{
    /** @var Storefront_Pro_Blocks Instance */
    private static  $_instance = null ;
    /** @var bool Is full site editing */
    private static  $_is_fse = null ;
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
    /** @var Storefront_Pro_Blocks_Admin Instance */
    public  $admin ;
    /** @var Storefront_Pro_Blocks_Public Instance */
    public  $public ;
    /**
     * Return class instance
     * @return Storefront_Pro_Blocks instance
     */
    public static function instance( $file = '' )
    {
        if ( null == self::$_instance ) {
            self::$_instance = new self( $file );
        }
        return self::$_instance;
    }
    
    /**
     * Is it full site editing screen
     * @return bool
     */
    public static function is_admin_fse()
    {
        
        if ( null === self::$_is_fse ) {
            $screen = get_current_screen();
            self::$_is_fse = $screen && in_array( $screen->base, [ 'appearance_page_gutenberg-edit-site', 'site-editor' ] );
        }
        
        return self::$_is_fse;
    }
    
    /**
     * Constructor function.
     *
     * @param string $file __FILE__ of the main plugin
     *
     * @access  private
     * @since   1.0.0
     */
    private function __construct( $file )
    {
        self::$token = 'sfp-blocks';
        self::$file = $file;
        self::$url = plugin_dir_url( $file );
        self::$path = plugin_dir_path( $file );
        self::$version = '3.4.0';
        add_action( 'plugins_loaded', [ $this, 'init' ] );
        
        if ( function_exists( 'caxton_fs' ) ) {
            // Caxton FS function exists and is executed
            $this->init_fs();
        } else {
            add_action( 'caxton_fs_loaded', [ $this, 'init_fs' ] );
        }
    
    }
    
    public static function table_columns()
    {
        $columns = [
            'img'         => 'Image',
            'name'        => 'Name',
            'description' => 'Description',
            'stock'       => 'Stock status',
            'rating'      => 'Rating',
            'price'       => 'Price',
        ];
        $taxonomies = get_object_taxonomies( 'product', 'objects' );
        if ( $taxonomies ) {
            foreach ( $taxonomies as $txn ) {
                if ( $txn->show_ui ) {
                    $columns["tax:{$txn->name}"] = $txn->label;
                }
            }
        }
        return $columns;
    }
    
    public function init()
    {
        add_action( 'admin_menu', array( Storefront_Pro_Blocks_Admin::instance(), 'admin_menu' ) );
        
        if ( !class_exists( 'Caxton' ) ) {
            // Caxton not installed
            add_action( 'admin_notices', array( $this, 'caxton_required_notice' ) );
        } else {
            
            if ( !class_exists( 'WooCommerce' ) ) {
                // Caxton not installed
                add_action( 'admin_notices', array( $this, 'woocommerce_required_notice' ) );
            } else {
                
                if ( class_exists( 'Classic_Editor' ) ) {
                    // Classic editor installed
                    add_action( 'admin_notices', array( $this, 'classic_editor_error' ) );
                } else {
                    // All clear! initiate admin and public code
                    $this->_admin();
                    //Initiate admin
                    $this->_public();
                    //Initiate public
                }
            
            }
        
        }
    
    }
    
    public function caxton_required_notice()
    {
        echo  '<div class="notice is-dismissible error">
				<p>' . sprintf( __( '%s requires that you have our free plugin %s installed and activated.', 'sfp-blocks' ), '<b>Storefront Pro Blocks</b>', '<a href="' . admin_url( 'plugin-install.php?s=caxton&tab=search&type=term' ) . '">Caxton</a>' ) . '</p>' . '<p><a style="background:#e25c4e;border-color:#d23c1e;text-shadow:none;box-shadow:0 1px 0 #883413;" href="' . admin_url( 'plugin-install.php?s=caxton&tab=search&type=term' ) . '" class="button-primary button-pootle">' . __( 'Install Caxton', 'sfp_blocks' ) . '</a></p>' . '</div>' ;
    }
    
    public function woocommerce_required_notice()
    {
        echo  '<div class="notice is-dismissible error">
				<p>' . sprintf( __( '%s requires that you have %s installed and activated.', 'sfp-blocks' ), '<b>Storefront Pro Blocks</b>', '<a href="' . admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) . '">WooCommerce</a>' ) . '</p>' . '<p><a href="' . admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) . '"
				 class="button">' . __( 'Install WooCommerce', 'sfp_blocks' ) . '</a></p>' . '</div>' ;
    }
    
    public function classic_editor_error()
    {
        echo  '<div class="notice error"><p>' . sprintf( __( 'Warning! Please de-activate the %2$s plugin before using %1$s.', 'storefront-blocks' ), '<b>Storefront Blocks</b>', '<b>Classic Editor</b>' ) . '</p></div>' ;
    }
    
    /**
     * Initiates FS SDK
     * No need to include the SDK, already done in Caxton
     * @return Freemius
     */
    function init_fs()
    {
        global  $sfp_blocks_fs ;
        
        if ( !isset( $sfp_blocks_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/inc/wp-sdk/start.php';
            $sfp_blocks_fs = fs_dynamic_init( [
                'id'               => '2380',
                'slug'             => 'sfp-blocks',
                'type'             => 'plugin',
                'public_key'       => 'pk_efd8794cafe3f672e71163b8ce2e1',
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
                'slug'    => 'storefront-blocks',
                'support' => false,
                'parent'  => array(
                'slug' => 'woocommerce',
            ),
            ),
                'is_live'          => true,
            ] );
        }
        
        return $sfp_blocks_fs;
    }
    
    /**
     * Initiates admin class and adds admin hooks
     */
    private function _admin()
    {
        //Instantiating admin class
        $this->admin = Storefront_Pro_Blocks_Admin::instance();
        //Enqueue admin end JS and CSS
        add_action( 'rest_api_init', array( $this->admin, 'rest_api_init' ) );
        add_filter( 'block_categories_all', array( $this->admin, 'block_categories' ) );
        add_action( 'enqueue_block_editor_assets', array( $this->admin, 'enqueue' ), 7 );
        add_action( 'woocommerce_email_classes', array( $this->admin, 'woocommerce_email_classes' ), 7 );
        add_action( 'woocommerce_products_general_settings', array( $this->admin, 'woocommerce_products_general_settings' ) );
        add_action( 'product_cat_add_form_fields', array( $this->admin, 'new_product_cat_url_field' ) );
        add_action( 'product_cat_edit_form_fields', array( $this->admin, 'product_cat_url_field' ), 10 );
        add_action(
            'created_term',
            array( $this->admin, 'save_product_cat_fields' ),
            10,
            3
        );
        add_action(
            'edit_term',
            array( $this->admin, 'save_product_cat_fields' ),
            10,
            3
        );
    }
    
    /**
     * Initiates public class and adds public hooks
     */
    private function _public()
    {
        //Instantiating public class
        $this->public = Storefront_Pro_Blocks_Public::instance();
        //Enqueue front end JS and CSS
        add_filter(
            'get_terms_orderby',
            array( $this->public, 'support_terms_order' ),
            10,
            2
        );
        add_action( 'template_redirect', array( $this->public, 'maybe_redirect' ) );
        add_action( 'wp', array( $this->public, 'track_recent_products' ) );
        add_action( 'init', array( $this->public, 'register_blocks' ) );
        add_action( 'init', array( $this->public, 'maybe_register_table_block' ), 11 );
        add_action( 'init', array( $this->public, 'init_scripts_register' ), 11 );
        add_action( 'wp_loaded', array( $this->public, 'maybe_process_product_action' ), 25 );
        add_action( 'wp_enqueue_scripts', array( $this->public, 'enqueue' ) );
        add_action( 'wp_footer', array( $this->public, 'wp_footer' ) );
        add_action( 'woocommerce_archive_description', array( $this->public, 'maybe_clear_shop_content' ), 99 );
    }

}
/** Intantiating main plugin class */
Storefront_Pro_Blocks::instance( __FILE__ );
// Signal that SDK was initiated.
do_action( 'sfp_blocks_loaded' );