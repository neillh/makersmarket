<?php

/**
 * Plugin Name:       Storefront Pro
 * Plugin URI:        http://www.pootlepress.com/storefront-pro/
 * Description:       Customize the design of every element of your Storefront website
 * Version:           5.14.1
 * Author:            pootlepress
 * Author URI:        http://pootlepress.com/
 * Requires at least: 5.0.0
 * Tested up to:      5.4.0
 * WC tested up to:   4.0
 *
 * Text Domain: storefront-pro
 * Domain Path: /languages/
 *
 * @package Storefront_Pro
 * @category Core
 * @author Shramee Srivastav <shramee.srivastav@gmail.com>
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
define( 'SFP_TKN', 'storefront-pro' );
define( 'SFP_URL', plugin_dir_url( __FILE__ ) );
define( 'SFP_PATH', plugin_dir_path( __FILE__ ) );
define( 'SFP_VERSION', '5.14.1' );
/** Including abstract class */
require_once 'includes/class-abstract.php';
/** Including variables and function */
require_once 'includes/vars-n-funcs.php';
/** Including customizer class */
require_once 'includes/class-customizer-fields.php';
/** Including public class */
require_once 'includes/class-public-templates.php';
require_once 'includes/class-public.php';
/** Including public class */
require_once 'includes/class-nav-icons.php';
/** Including admin class */
require_once 'includes/class-admin.php';
///** Skin preview class */
//require_once 'includes/class-skin.php';
/** Including header and nav styling class */
require_once 'includes/class-header-nav-styles.php';
/** Including WooCommerce styling class */
require_once 'includes/class-woocommerce.php';
/** Including footer styling class */
require_once 'includes/class-footer-styles.php';
/** Including Content styling class */
require_once 'includes/class-content-styles.php';
/** Including Pootle_Update_Manager class */
require_once 'includes/wp-sdk/start.php';
/** Including Pootle_Update_Manager class */
require_once 'includes/wcam-fs.php';
/**
 * Helper function for easy SDK access.
 * @since 4.0.0
 * @return Freemius
 */
function storefront_pro_fssdk()
{
    global  $storefront_pro_fssdk ;
    if ( !isset( $storefront_pro_fssdk ) ) {
        /** @var Freemius $storefront_pro_fssdk */
        $storefront_pro_fssdk = fs_dynamic_init( array(
            'id'              => '553',
            'slug'            => 'storefront-pro',
            'type'            => 'plugin',
            'public_key'      => 'pk_4626a94d653f306db2491e3b43d1c',
            'is_premium'      => true,
            'is_premium_only' => true,
            'has_addons'      => false,
            'has_paid_plans'  => true,
            'menu'            => array(
            'slug'    => 'storefront-pro',
            'support' => false,
            'parent'  => array(
            'slug' => 'themes.php',
        ),
        ),
            'is_live'         => true,
        ) );
    }
    return $storefront_pro_fssdk;
}

// Init Freemius.
storefront_pro_fssdk();
/**
 * Returns the main instance of Storefront_Pro to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return Storefront_Pro Instance
 */
function SfPro()
{
    return Storefront_Pro::instance();
}

// End SfPro()
SfPro();
/**
 * Main Storefront_Pro Class
 *
 * @class Storefront_Pro
 * @version	1.0.0
 * @since 1.0.0
 * @package	Storefront_Pro
 * @author Shramee Srivastav <shramee.srivastav@gmail.com>
 */
final class Storefront_Pro
{
    /**
     * Storefront_Pro The single instance of Storefront_Pro.
     * @var 	object
     * @access  private
     * @since 	1.0.0
     */
    private static  $_instance = null ;
    public static  $url ;
    /**
     * The token.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public  $token ;
    /**
     * The version number.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public  $version ;
    /**
     * The admin object.
     * @var     Storefront_Pro_Admin
     * @access  public
     * @since   1.0.0
     */
    public  $admin ;
    /**
     * The public object.
     * @var     Storefront_Pro_Public
     * @access  public
     * @since   1.0.0
     */
    public  $public ;
    /**
     * Constructor function.
     * @access  public
     * @since   1.0.0
     */
    public function __construct()
    {
        $this->token = SFP_TKN;
        $this->plugin_url = plugin_dir_url( __FILE__ );
        $this->plugin_path = plugin_dir_path( __FILE__ );
        $this->version = SFP_VERSION;
        self::$url = $this->plugin_url;
        register_activation_hook( __FILE__, array( $this, 'install' ) );
        add_action( 'plugins_loaded', array( $this, 'include_ext_plugins' ), 99 );
        add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
        add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_links' ) );
        add_action( 'admin_footer', array( $this, 'admin_footer' ), 999 );
        add_action( 'init', array( $this, 'setup' ) );
        add_action( 'customizer', array( $this, 'setup' ) );
    }
    
    public function admin_footer()
    {
        global  $pagenow ;
        if ( 'plugins.php' != $pagenow ) {
            return;
        }
        $ajax_url = admin_url( 'admin-ajax.php' );
        $referrer = admin_url( 'plugins.php' );
        ?>
		<style>
			#storefront-pro-import, #storefront-pro-reset {
				position:fixed;
				width: 340px;
				padding: 10px 16px 16px;
				right: 50%;
				top: 34%;
				margin-right: -170px;
				background: #fff;
				box-shadow: 1px 1px 3px 2px rgba(0,0,0,0.16);
				display: none;
			}
			#storefront-pro-import:target, #storefront-pro-reset:target {
				display: block;
			}
		</style>
		<div id='storefront-pro-import'>
			<div id="sfp-import-msg"></div>
			<p>
				<?php 
        _e( 'Are you sure you wanna import settings? All your current Storefront Pro settings will be lost.', SFP_TKN );
        ?>
			</p>
			<p><input type="file" id="sfp-import-file"></p>
			<a class='button button-primary' type='button' id='sfp-import-start'>
				<?php 
        _e( 'Yeah, Load file', SFP_TKN );
        ?>
			</a>
			<a class='button right' href='#storefront-pro'><?php 
        _e( 'No, thanks', SFP_TKN );
        ?></a>
		</div>
		<div class='notice notice-warning' id='storefront-pro-reset'>
			<p><?php 
        _e( 'Are you sure you wanna reset to default Storefront options?', SFP_TKN );
        ?></p>
			<a class='button' href='<?php 
        echo  "{$ajax_url}?action=storefront_pro_reset&redirect={$referrer}" ;
        ?>'>
				<?php 
        _e( 'Yeah', SFP_TKN );
        ?></a>
			<a class='button button-primary right' href='#storefront-pro'><?php 
        _e( 'Cancel', SFP_TKN );
        ?></a>
		</div>
		<script>
			( function ( $ ) {
				var $msg = $( '#sfp-import-msg' ),
					msg = function ( response ) {
						if ( ! response ) return;
						if ( typeof response == 'string' ) {
							try {
								response = JSON.parse( response )
							} catch( e ) {
								console.log( e );
								alert( 'Invalid response' );
								return;
							}
						}
						if ( ! response.msg ) alert( '<?php 
        _e( 'Invalid response', SFP_TKN );
        ?>' );
						if ( ! response.type ) response.type = 'info';
						$msg.html(
							'<div class="notice notice-' + response.type + '"><p>' + response.msg + '</p></div>'
						)
					};
				$( '#sfp-import-start' ).click( function () {
					if ( ! window.FileReader ) {
						alert( '<?php 
        _e( 'The FileReader API is not supported in this browser.', SFP_TKN );
        ?>' );
						return;
					}

					var $i = $( '#sfp-import-file' ), // Put file input ID here
						input = $i[0];

					if ( input.files && input.files[0] ) {
						var file = input.files[0];
						console.log( file );
						var fr = new FileReader();
						fr.onload = function () {
							var json = fr.result;
							console.log( json );
							msg( {
								msg: '<?php 
        _e( 'Requesting import from file %s.', SFP_TKN );
        ?>'.replace( '%s', file.name )
							} );
							$.ajax( {
								type: "POST",
								url: '<?php 
        echo  "{$ajax_url}?action=storefront_pro_import" ;
        ?>',
								data: {
									json: json,
									nonce: '<?php 
        echo  wp_create_nonce( 'sfp_import_settings' ) ;
        ?>'
								},
								success: function ( res ) {
									msg( res );
								}
							} );
						};
						fr.readAsText( file );
					} else {
						// Handle errors here
						alert( "<?php 
        _e( 'File not selected or browser incompatible.', SFP_TKN );
        ?>" )
					}
				} );
			} )( jQuery );
		</script>

		<?php 
    }
    
    public function custom_logo_remove()
    {
        remove_theme_support( 'custom-logo' );
    }
    
    /**
     * Plugin page links
     *
     * @since  1.0.0
     */
    public function plugin_links( $links )
    {
        $ajax_url = admin_url( 'admin-ajax.php' );
        $links[] = "<a href='{$ajax_url}?action=storefront_pro_export' download='storefront-pro-export.json'>Export</a>";
        $links[] = "<a href='#storefront-pro-import'>Import</a>";
        if ( WP_DEBUG ) {
            $links[] = "<a href='#storefront-pro-reset'>Reset</a>";
        }
        return $links;
    }
    
    /**
     * Setup all the things.
     * Only executes if Storefront or a child theme using Storefront as a parent is active and the extension specific filter returns true.
     * Child themes can disable this extension using the storefront_pro_enabled filter
     * @return void
     */
    public function setup()
    {
        $theme = wp_get_theme();
        
        if ( 'Storefront' == $theme->name || 'storefront' == $theme->template && apply_filters( 'storefront_pro_supported', true ) ) {
            $this->custom_logo_remove();
            //Setting admin object
            $this->admin = new Storefront_Pro_Admin( $this->token, $this->plugin_path, $this->plugin_url );
            //Setting public object
            $this->public = new Storefront_Pro_Public( $this->token, $this->plugin_path, $this->plugin_url );
            // Hide the 'More' section in the customizer
            add_filter( 'storefront_customizer_more', '__return_false' );
        } else {
            add_action( 'admin_notices', array( $this, 'install_storefront_notice' ) );
        }
    
    }
    
    /**
     * Load the localisation file.
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function include_ext_plugins()
    {
        global  $pagenow ;
        if ( 'plugins.php' == $pagenow ) {
            return;
        }
        if ( !class_exists( 'Storefront_Pro_Live_Search' ) ) {
            require 'includes/ext/live-search/storefront-pro-live-search.php';
        }
        if ( !class_exists( 'Pootle_Page_Customizer' ) ) {
            require 'includes/page-customizer/page-customizer.php';
        }
        if ( !class_exists( 'Storefront_Footer_Bar' ) ) {
            require 'includes/ext/storefront-footer-bar/storefront-footer-bar.php';
        }
        if ( !class_exists( 'Storefront_Header_Bar' ) ) {
            require 'includes/ext/storefront-header-bar/storefront-header-bar.php';
        }
        if ( !class_exists( 'Storefront_Product_Sharing' ) ) {
            require 'includes/ext/storefront-product-sharing/storefront-product-sharing.php';
        }
        if ( !class_exists( 'WC_pif' ) ) {
            require 'includes/ext/woocommerce-product-image-flipper/image-flipper.php';
        }
    }
    
    /**
     * Main Storefront_Pro Instance
     *
     * Ensures only one instance of Storefront_Pro is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @see Storefront_Pro()
     * @return Storefront_Pro instance
     */
    public static function instance()
    {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    // End instance()
    /**
     * Load the localisation file.
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function load_plugin_textdomain()
    {
        load_plugin_textdomain( 'storefront-pro', false, basename( dirname( __FILE__ ) ) . '/languages' );
    }
    
    /**
     * Cloning is forbidden.
     *
     * @since 1.0.0
     */
    public function __clone()
    {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
    }
    
    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.0.0
     */
    public function __wakeup()
    {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
    }
    
    /**
     * Installation.
     * Runs on activation. Logs the version number and assigns a notice message to a WordPress option.
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function install()
    {
        $this->_log_version_number();
        // get theme customizer url
        $url = admin_url() . 'customize.php?';
        $url .= 'url=' . urlencode( site_url() . '?storefront-customizer=true' );
        $url .= '&return=' . urlencode( admin_url() . 'plugins.php' );
        $url .= '&storefront-customizer=true';
        $notices = get_option( 'activation_notice', array() );
        $notices[] = sprintf(
            __( '%sThanks for installing the Storefront Pro extension. To get started, visit the %sCustomizer%s.%s %sOpen the Customizer%s', 'storefront-pro' ),
            '<p>',
            '<a href="' . esc_url( $url ) . '">',
            '</a>',
            '</p>',
            '<p><a href="' . esc_url( $url ) . '" class="button button-primary">',
            '</a></p>'
        );
        update_option( 'activation_notice', $notices );
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        
        if ( $custom_logo_id ) {
            $image = wp_get_attachment_image_src( $custom_logo_id, 'full' );
            set_theme_mod( 'storefront-pro-logo', $image[0] );
        }
    
    }
    
    /**
     * Log the plugin version number.
     * @access  private
     * @since   1.0.0
     * @return  void
     */
    private function _log_version_number()
    {
        // Log the version number.
        update_option( $this->token . '-version', $this->version );
    }
    
    /**
     * Storefront install
     * If the user activates the plugin while having a different parent theme active, prompt them to install Storefront.
     * @since   1.0.0
     * @return  void
     */
    public function install_storefront_notice()
    {
        echo  '<div class="notice is-dismissible updated">
				<p>' . __( 'Storefront Pro requires that you use Storefront as your parent theme.', 'storefront-pro' ) . ' <a href="' . esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-theme&theme=storefront' ), 'install-theme_boutique' ) ) . '">' . __( 'Install Storefront now', 'storefront-pro' ) . '</a></p>
			</div>' ;
    }

}
// End Class