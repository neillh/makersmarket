<?php
/**
 * Created by PhpStorm.
 * User: shramee
 * Date: 01/11/16
 * Time: 7:13 PM
 */

if ( ! defined( 'SFPFS_STORE_URL' ) ) {
	// This should point to your WC install.
	define( 'SFPFS_STORE_URL', 'https://pootlepress.com' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file
}

// The WC download ID of your product.
define( 'SFPFS_TOKEN', 'storefront-pro' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file
define( 'SFPFS_SOFTWARE_TITLE', 'Storefront Pro' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file
define( 'SFPFS_SOFTWARE_ID', 29 ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

function sfpfs_convert_wc_to_fs_license_key( $license_key ) {
	if ( 32 < strlen( $license_key ) ) {
		$license_key = substr( $license_key, strlen( $license_key ) - 32 );
	}
	return $license_key;
}
storefront_pro_fssdk()->add_filter( 'license_key', 'sfpfs_convert_wc_to_fs_license_key' );

/**
 * The license migration script.
 *
 * IMPORTANT:
 *  You should use your own function name, and be sure to replace it throughout this file.
 *
 * @author   Vova Feldman (@svovaf)
 * @since    1.0.0
 *
 * @param int $wc_download_id The context WC download ID (from your store).
 * @param string $wc_license_key The current site's WC license key.
 * @param string $wc_store_url Your WC store URL.
 * @param bool $redirect
 *
 * @return bool
 */
function sfpfs_license_migration(
	$wc_download_id,
	$wc_license_key,
	$wc_store_url,
	$redirect = false,
	$site = null
) {
	global $sfpfs_plugin_data;

	$site = $site ? $site : $sfpfs_plugin_data->site;
	/**
	 * @var \Freemius $fs
	 */
	$fs = storefront_pro_fssdk();

	$install_details = $fs->get_opt_in_params();

	// Override is_premium flat because it's a paid license migration.
	$install_details['is_premium'] = true;
	// The plugin is active for sure and not uninstalled.
	$install_details['is_active']      = true;
	$install_details['is_uninstalled'] = false;

	// Clean unnecessary arguments.
	unset( $install_details['return_url'] );
	unset( $install_details['account_url'] );

	// Call the custom license and account migration endpoint.
	$transient_key = 'fs_license_migration_' . SFPFS_SOFTWARE_TITLE;

	$args = array_merge( $install_details, array(
		'module_title' => SFPFS_SOFTWARE_TITLE,
		'wcam_site_id' => $site,
		'license_key'  => $wc_license_key,
		'url'          => home_url(),
	) );

	$response = wp_remote_post(
		$wc_store_url . '/fs-api/wc/migrate-license.json',
		array(
			'timeout'   => 15,
			'sslverify' => false,
			'body'      => json_encode( $args ),
		)
	);

	// Cache result (5-min).
	set_transient( $transient_key, $response, 16 * MINUTE_IN_SECONDS );

	// make sure the response came back okay
	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		$error_message = $response->get_error_message();

		if ( ! empty( $error_message ) ) {
			return $error_message;
		} else {
			return __( 'An error occurred, please try again.' );
		}

	} else {
		$res_body = wp_remote_retrieve_body( $response );
		$response = json_decode( $res_body );

		if ( ! is_object( $response ) ||
		     empty( $response->success ) ||
		     empty( $response->data->user ) ||
		     empty( $response->data->install )
		) {
			if ( isset( $response->error ) ) {
				return json_encode( $response->error );
			}

			return $response;
		}

		$fs->setup_account(
			new FS_User( $response->data->user ),
			new FS_Site( $response->data->install ),
			$redirect
		);

		return true;
	}
}

/**
 * Initiate a non-blocking HTTP POST request to the same URL
 * as the current page, with the addition of "fsm_wc_{SFPFS_TOKEN}"
 * param in the query string that is set to a unique migration
 * request identifier, making sure only one request will make
 * the migration.
 *
 * @todo     Test 2 threads in parallel and make sure that `fs_add_transient()` works as expected.
 *
 * @author   Vova Feldman (@svovaf)
 * @since    1.0.0
 *
 * @param int $wc_download_id The context WC download ID (from your store).
 *
 * @return bool Is successfully spawned the migration request.
 */
function sfpfs_spawn_license_migration( $wc_download_id ) {
	#region Make sure only one request handles the migration (prevent race condition)

	// Generate unique md5.
	$migration_uid = md5( rand() . microtime() );

	$loaded_migration_uid = false;

	/**
	 * Use `fs_add_transient()` instead of `set_transient()` because
	 * we only want that one request will succeed writing this
	 * option to the storage.
	 */
	$transient_key = "fsm_wc_{$wc_download_id}";
	if ( fs_add_transient( $transient_key, $migration_uid, MINUTE_IN_SECONDS ) ) {
		$loaded_migration_uid = fs_get_transient( $transient_key );
	}

	if ( $migration_uid !== $loaded_migration_uid ) {
		return false;
	}

	#endregion

	$host        = $_SERVER['HTTP_HOST'];
	$uri         = $_SERVER['REQUEST_URI'];
	$port        = $_SERVER['SERVER_PORT'];
	$port        = ( ( ! WP_FS__IS_HTTPS && $port == '80' ) || ( WP_FS__IS_HTTPS && $port == '443' ) ) ? '' : ':' . $port;
	$current_url = ( WP_FS__IS_HTTPS ? 'https' : 'http' ) . "://{$host}{$port}{$uri}";

	$migration_url = add_query_arg(
		"fsm_wc_{$wc_download_id}",
		$migration_uid,
		$current_url
	);

	// Add cookies to trigger request with same user access permissions.
	$cookies = array();
	foreach ( $_COOKIE as $name => $value ) {
		$cookies[] = new WP_Http_Cookie( array(
			'name'  => $name,
			'value' => $value
		) );
	}

	wp_remote_post(
		$migration_url,
		array(
			'timeout'   => 0.01,
			'blocking'  => false,
			'sslverify' => false,
			'cookies'   => $cookies,
		)
	);

	return true;
}

/**
 * Run non blocking migration if all of the following (AND condition):
 *  1. Has API connectivity to api.freemius.com
 *  2. User isn't yet identified with Freemius.
 *  3. Freemius is in "activation mode".
 *  4. It's a plugin version upgrade.
 *  5. It's the first installation of the context plugin that have Freemius integrated with.
 *
 * @author   Vova Feldman (@svovaf)
 * @since    1.0.0
 *
 * @param int $wc_download_id The context WC download ID (from your store).
 * @param string $wc_license_key The current site's WC license key.
 * @param string $wc_store_url Your WC store URL.
 * @param bool $is_blocking Special argument for testing. When false, will initiate the migration in the same
 *                                HTTP request.
 *
 * @return string|bool
 */
function sfpfs_non_blocking_license_migration(
	$wc_download_id,
	$wc_license_key,
	$wc_store_url,
	$is_blocking = false
) {
	/**
	 * @var \Freemius $fs
	 */
	$fs = storefront_pro_fssdk();

	$key = "fsm_wc_{$wc_download_id}";

	if ( ! $fs->has_api_connectivity() ) {
		// No connectivity to Freemius API, it's up to you what to do.
		return 'no_connectivity';
	}

	if ( $fs->is_registered() ) {
		// User already identified by the API.
		return 'user_registered';
	}

	if ( ! $fs->is_activation_mode() ) {
		// Plugin isn't in Freemius activation mode.
		return 'not_in_activation';
	}
	if ( ! $fs->is_plugin_upgrade_mode() ) {
		// Plugin isn't in plugin upgrade mode.
		return 'not_in_upgrade';
	}

	if ( ! $fs->is_first_freemius_powered_version() ) {
		// It's not the 1st version of the plugin that runs with Freemius.
		return 'freemius_installed_before';
	}


	$migration_uid = fs_get_transient( $key );
	$in_migration  = ! empty( $_REQUEST[ $key ] );

	if ( ! $is_blocking && ! $in_migration ) {
		// Initiate license migration in a non-blocking request.
		return sfpfs_spawn_license_migration( $wc_download_id );
	} else {
		if ( $is_blocking ||
		     ( ! empty( $_REQUEST[ $key ] ) &&
		       $migration_uid === $_REQUEST[ $key ] &&
		       'POST' === $_SERVER['REQUEST_METHOD'] )
		) {
			$success = sfpfs_license_migration(
				$wc_download_id,
				$wc_license_key,
				$wc_store_url
			);

			if ( $success ) {
				$fs->set_plugin_upgrade_complete();

				return 'success';
			}

			return 'failed';
		}
	}
}

/**
 * If installation failed due to license activation  on Freemius try to
 * activate the license on WC first, and if successful, migrate the license
 * with a blocking request.
 *
 * This method will only be triggered upon failed module installation.
 *
 * @author   Vova Feldman (@svovaf)
 * @since    1.0.0
 *
 * @param object $response Freemius installation request result.
 * @param array $args Freemius installation request arguments.
 *
 * @return object|string
 */
function sfpfs_try_migrate_on_activation( $response, $args ) {
	
	if ( empty( $args['license_key'] ) || ! strpos( $args['license_key'], '_am_' ) ) {
		// Not WC API Manager (_am_) key, ignore.
		return $response;
	}

	/** @var \Freemius $fs */
	$fs = storefront_pro_fssdk();

	if ( ! $fs->has_api_connectivity() ) {
		// No connectivity to Freemius API, it's up to you what to do.
		return $response;
	}

	$license_key = $args['license_key'];

	$site_id = get_option( SFPFS_TOKEN . '_instance' );

	if ( ! $site_id ) {
		$site_id = md5( site_url() . time() ); // Create instance id for this site
		update_option( SFPFS_TOKEN . '_instance', $site_id );
	}

	if ( ( is_object( $response->error ) && 'invalid_license_key' === $response->error->code ) ||
		( is_string( $response->error ) && false !== strpos( strtolower( $response->error ), 'license' ) )
	) {
		$migrate = sfpfs_license_migration(
			SFPFS_SOFTWARE_ID,
			$license_key,
			SFPFS_STORE_URL,
			true,
			$site_id
		);

		if ( true === $migrate ) {
			/**
			 * If successfully migrated license and got to this point (no redirect),
			 * it means that it's an AJAX installation (opt-in), therefore,
			 * override the response with the after connect URL.
			 */
			return $fs->get_after_activation_url( 'after_connect_url' );
		} else {
			if ( is_string( $response->error ) ) {
				$response->error = $migrate;
			} else {
				$response->error->message = $migrate;
			}
		}
	}

	return $response;
}

#region Database Transient

if ( ! function_exists( 'fs_get_transient' ) ) {
	/**
	 * Very similar to the WP transient mechanism.
	 *
	 * @author   Vova Feldman (@svovaf)
	 * @since    1.0.0
	 *
	 * @param string $transient
	 *
	 * @return mixed
	 */
	function fs_get_transient( $transient ) {
		$transient_option  = '_fs_transient_' . $transient;
		$transient_timeout = '_fs_transient_timeout_' . $transient;

		$timeout = get_option( $transient_timeout );

		if ( false !== $timeout && $timeout < time() ) {
			delete_option( $transient_option );
			delete_option( $transient_timeout );
			$value = false;
		} else {
			$value = get_option( $transient_option );
		}

		return $value;
	}

	/**
	 * Not like `set_transient()`, this function will only ADD
	 * a transient if it's not yet exist.
	 *
	 * @author   Vova Feldman (@svovaf)
	 * @since    1.0.0
	 *
	 * @param string $transient
	 * @param mixed $value
	 * @param int $expiration
	 *
	 * @return bool TRUE if successfully added a transient.
	 */
	function fs_add_transient( $transient, $value, $expiration = 0 ) {
		$transient_option  = '_fs_transient_' . $transient;
		$transient_timeout = '_fs_transient_timeout_' . $transient;

		$current_value = fs_get_transient( $transient );

		if ( false === $current_value ) {
			$autoload = 'yes';
			if ( $expiration ) {
				$autoload = 'no';
				add_option( $transient_timeout, time() + $expiration, '', 'no' );
			}

			return add_option( $transient_option, $value, '', $autoload );
		} else {
			// If expiration is requested, but the transient has no timeout option,
			// delete, then re-create the timeout.
			if ( $expiration ) {
				if ( false === get_option( $transient_timeout ) ) {
					add_option( $transient_timeout, time() + $expiration, '', 'no' );
				}
			}
		}

		return false;
	}
}

#endregion

/**
 * If no WC license is set it might be one of the following:
 *  1. User purchased module directly from Freemius.
 *  2. User did purchase from WC, but has never activated the license on this site.
 *  3. User got access to the code without ever purchasing.
 *
 * In case it's reason #2 or if the license key is wrong, the migration will not work.
 * Since we do want to support WC licenses, hook to Freemius `after_install_failure`
 * event. That way, if a license activation fails, try activating the license on WC
 * first, and if works, migrate to Freemius right after.
 */
storefront_pro_fssdk()->add_filter( 'after_install_failure', 'sfpfs_try_migrate_on_activation', 10, 2 );

global $sfpfs_plugin_data;

$sfpfs_plugin_data = new stdClass();

// Pull WC license key from storage.
$sfpfs_plugin_data->data = get_option( SFPFS_TOKEN );
if ( ! empty( $sfpfs_plugin_data->data['api_key'] ) ) {
	$sfpfs_plugin_data->id   = get_option( SFPFS_TOKEN . '_product_id' );
	$sfpfs_plugin_data->site = get_option( SFPFS_TOKEN . '_instance' );
	add_action( 'admin_init', 'sfpfs_auto_migrate_active_license' );
}

function sfpfs_auto_migrate_active_license() {
	global $sfpfs_plugin_data;

	if ( ! defined( 'DOING_AJAX' ) ) {
		sfpfs_non_blocking_license_migration(
			SFPFS_SOFTWARE_ID,
			$sfpfs_plugin_data->data['api_key'],
			SFPFS_STORE_URL
			, true
		);
	}
}

function sfpfs_allow_more_than_32_chars_lic_key() {
	?>
	<script>
		jQuery( function ( $ ) {
			var $fsConn = $( '#fs_connect');
			if ( $fsConn.length ) {
				$fsConn.find('.fs-license-key-container' ).css( 'width', '322px' );
				$fsConn.find('#fs_license_key' ).removeAttr( 'maxlength' );
			}
		} );
	</script>
	<?php
}
add_action( 'admin_footer', 'sfpfs_allow_more_than_32_chars_lic_key' );