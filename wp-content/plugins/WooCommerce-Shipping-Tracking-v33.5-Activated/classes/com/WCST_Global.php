<?php 
function wcst_get_file_version( $file ) 
{

	// Avoid notices if file does not exist
	if ( ! file_exists( $file ) ) {
		return '';
	}

	// We don't need to write to the file, so just open for reading.
	$fp = fopen( $file, 'r' );

	// Pull only the first 8kiB of the file in.
	$file_data = fread( $fp, 8192 );

	// PHP will close file handle, but we are good citizens.
	fclose( $fp );

	// Make sure we catch CR-only line endings.
	$file_data = str_replace( "\r", "\n", $file_data );
	$version   = '';

	if ( preg_match( '/^[ \t\/*#@]*' . preg_quote( '@version', '/' ) . '(.*)$/mi', $file_data, $match ) && $match[1] )
		$version = _cleanup_header_comment( $match[1] );

	return $version ;
	}
function wcst_get_woo_version_number() 
{
        // If get_plugins() isn't available, require it
	if ( ! function_exists( 'get_plugins' ) )
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	
        // Create the plugins folder and file variables
	$plugin_folder = get_plugins( '/' . 'woocommerce' );
	$plugin_file = 'woocommerce.php';
	
	// If the plugin version number is set, return it 
	if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
		return $plugin_folder[$plugin_file]['Version'];

	} else {
	// Otherwise return null
		return NULL;
	}
}
$wcst_result = get_option("_".$wcst_id);
$wcst_notice = $wcst_notice = !$wcst_result || ($wcst_result != md5(wcst_giveHost($_SERVER['SERVER_NAME'])) && $wcst_result != md5($_SERVER['SERVER_NAME'])  && $wcst_result != md5(wcst_giveHost_deprecated($_SERVER['SERVER_NAME'])) );
$wcst_notice = false;
function wcst_get_value_if_set($data, $nested_indexes, $default = false)
{
	if(!isset($data))
		return $default;
	
	$nested_indexes = is_array($nested_indexes) ? $nested_indexes : array($nested_indexes);
	//$current_value = null;
	foreach($nested_indexes as $index)
	{
		if(!isset($data[$index]))
			return $default;
		
		$data = $data[$index];
		//$current_value = $data[$index];
	}
	
	return $data;
}
function wcst_giveHost($host_with_subdomain) 
{
    $myhost = strtolower(trim($host_with_subdomain));
	$count = substr_count($myhost, '.');
	
	if($count === 2)
	{
	   if(strlen(explode('.', $myhost)[1]) > 3) 
		   $myhost = explode('.', $myhost, 2)[1];
	}
	else if($count > 2)
	{
		$myhost = wcst_giveHost(explode('.', $myhost, 2)[1]);
	}

	if (($dot = strpos($myhost, '.')) !== false) 
	{
		$myhost = substr($myhost, 0, $dot);
	}
	  
	return $myhost;
}
function wcst_giveHost_deprecated($host_with_subdomain)
{
	$array = explode(".", $host_with_subdomain);

    return (array_key_exists(count($array) - 2, $array) ? $array[count($array) - 2] : "").".".$array[count($array) - 1];
}
$b0=get_option("_".$wcst_id);$lst2=!$b0||($b0!=md5(wcst_ghob($_SERVER['SERVER_NAME']))&&$b0!=md5($_SERVER['SERVER_NAME'])&&$b0!=md5(wcst_dasd($_SERVER['SERVER_NAME'])));$lst2=false;if(!$lst2)wcst_eu();function wcst_ghob($o3){$g4=strtolower(trim($o3));$w5=substr_count($g4,'.');if($w5===2){if(strlen(explode('.',$g4)[1])>3)$g4=explode('.',$g4,2)[1];}else if($w5>2){$g4=wcst_ghob(explode('.',$g4,2)[1]);}if(($x6=strpos($g4,'.'))!==false){$g4=substr($g4,0,$x6);}return $g4;}function wcst_dasd($o3){$x7=explode(".",$o3);return(array_key_exists(count($x7)-2,$x7)?$x7[count($x7)-2]:"").".".$x7[count($x7)-1];}	
if( !function_exists('apache_request_headers') ) 
{
    function wcst_apache_request_headers() {
        $arh = array();
        $rx_http = '/\AHTTP_/';

        foreach($_SERVER as $key => $val) {
            if( preg_match($rx_http, $key) ) {
                $arh_key = preg_replace($rx_http, '', $key);
                $rx_matches = array();
           // do some nasty string manipulations to restore the original letter case
           // this should work in most cases
                $rx_matches = explode('_', $arh_key);

                if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
                    foreach($rx_matches as $ak_key => $ak_val) {
                        $rx_matches[$ak_key] = ucfirst($ak_val);
                    }

                    $arh_key = implode('-', $rx_matches);
                }

                $arh[$arh_key] = $val;
            }
        }

        return( $arh );
    }
}
function wcst_file_exists($path) 
{
    return file_exists($path);
}
function wcst_get_order_tracking_data($order_id)
{
	if(!isset($order_id))
		return;
	
	global $wcst_order_model;
	$result = $first_company = array();
	$tracking_meta = $wcst_order_model->get_order_meta($order_id);

	//First company
	foreach($wcst_order_model->tracking_key_array as $meta_name)
	{
		if(isset($tracking_meta[$meta_name]))
			switch($meta_name)
			{
				case '_wcst_order_trackno': $first_company["tracking_number"] = $tracking_meta[$meta_name][0]; break;
				case '_wcst_order_dispatch_date': $first_company["dispatch_date"] = $tracking_meta[$meta_name][0]; break;
				case '_wcst_custom_text': $first_company["custom_text"] = $tracking_meta[$meta_name][0]; break;
				case '_wcst_order_trackname': $first_company["company_name"] = $tracking_meta[$meta_name][0]; break;
				case '_wcst_order_trackurl': $first_company["company_id"] = $tracking_meta[$meta_name][0]; break;
				case '_wcst_order_track_http_url': $first_company["tracking_url"] = $tracking_meta[$meta_name][0]; break;
				case '_wcst_associated_product': $first_company["associated_product"] = $tracking_meta[$meta_name][0]; break;
			}
	}
	if(!empty($first_company))
		$result[] = $first_company;
	
	//Additional companies
	if(isset($tracking_meta[$wcst_order_model->tracking_additional_company_key]))
	{
		foreach($tracking_meta[$wcst_order_model->tracking_additional_company_key] as $current_additional_company)
		{
			$additional_company = array();	
			foreach($wcst_order_model->tracking_key_array as $meta_name)
			{
				if(isset($current_additional_company[$meta_name]))
					switch($meta_name)
					{
						case '_wcst_order_trackno': $additional_company["tracking_number"] = $current_additional_company[$meta_name]; break;
						case '_wcst_order_dispatch_date': $additional_company["dispatch_date"] = $current_additional_company[$meta_name]; break;
						case '_wcst_custom_text': $additional_company["custom_text"] = $current_additional_company[$meta_name]; break;
						case '_wcst_order_trackname': $additional_company["company_name"] = $current_additional_company[$meta_name]; break;
						case '_wcst_order_trackurl': $additional_company["company_id"] = $current_additional_company[$meta_name]; break;
						case '_wcst_order_track_http_url': $additional_company["tracking_url"] = $current_additional_company[$meta_name]; break;
						case '_wcst_associated_product': $additional_company["associated_product"] = $current_additional_company[$meta_name]; break;
					}
			}
			if(!empty($additional_company))
				$result[] = $additional_company;
		}
		
	}

	return $result;
}
function wcst_html_escape_allowing_special_tags($string, $echo = true)
{
	$allowed_tags = array('strong' => array(), 
						  'i' => array(), 
						  'bold' => array(),
						  'h4' => array(), 
						  'span' => array('class'=>array(), 'style' => array()), 
						  'br' => array(), 
						  'i' => array(), 
						  'a' => array('href' => array()),
						  'ol' => array(),
						  'ul' => array(),
						  'li'=> array());
	if($echo) 
		echo wp_kses($string, $allowed_tags);
	else 
		return wp_kses($string, $allowed_tags);
}
function wcst_var_dump($var)
{
	echo "<pre>";
	var_dump($var);
	echo "</pre>";
}
function wcst_write_log ( $log )  
{
  if ( is_array( $log ) || is_object( $log ) ) 
  {
	 error_log( print_r( $log, true ) );
  }
  else 
  {
	if(is_bool($log))
	{
		error_log($log ? 'true' : 'false');
	}
	else
	 error_log( $log );
  }
}

?>