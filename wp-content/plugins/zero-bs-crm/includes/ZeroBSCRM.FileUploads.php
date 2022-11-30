<?php 
/*!
 * Jetpack CRM
 * https://jetpackcrm.com
 * V1.20
 *
 * Copyright 2020 Automattic
 *
 * Date: 01/11/16
 */

/* ======================================================
  Breaking Checks ( stops direct access )
   ====================================================== */
    if ( ! defined( 'ZEROBSCRM_PATH' ) ) exit;
/* ======================================================
  / Breaking Checks
   ====================================================== */




/* ======================================================
  Acceptible Mime Types
   ====================================================== */

	#} A list of applicable Mimetypes for file uploads
	function zeroBSCRM_returnMimeTypes(){ 
		return array(
										'pdf' => array('application/pdf'),
										'doc' => array('application/msword'),
										'docx' => array('application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
										'ppt' => array('application/vnd.ms-powerpointtd>'),
										'pptx' => array('application/vnd.openxmlformats-officedocument.presentationml.presentation'),
										'xls' => array('application/vnd.ms-excel'),
										'xlsx' => array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
										'csv' => array('text/csv'),
										'png' => array('image/png'),
										'jpg' => array('image/jpeg'),
										'jpeg' => array('image/jpeg'),
										'gif' => array('image/gif'),
										'mp3' => array('audio/mpeg'),
										'txt' => array('text/plain'),
										'zip' => array('application/zip', 'application/x-compressed-zip'),
										'mp4' => array('video/mp4')
												# plus 'any'
			);
	}
	
	/* 
	 * Returns the extension for the provided mimetype, false otherwise.
	 */
	function jpcrm_return_ext_for_mimetype( $mimetype ) {
		global $zbs;
		$all_types = $zbs->acceptable_mime_types;
		
		foreach( $all_types as $extension => $ext_mimetypes ) {
			foreach( $ext_mimetypes as $this_mimetype ) {
				if ( $this_mimetype === $mimetype ) {
					return $extension;
				}
			}
		}

		return false;
	}

/* ======================================================
  / Acceptible Mime Types
   ====================================================== */





/* ======================================================
  File Upload Related Funcs
   ====================================================== */

	// str e.g. .pdf, .xls
	function zeroBS_acceptableFileTypeListStr(){

		$ret = '';
	  
		global $zbs;

		#} Retrieve settings
		$settings = $zbs->settings->getAll();
		
		if (isset($settings['filetypesupload'])) {

			if (isset($settings['filetypesupload']['all']) && $settings['filetypesupload']['all'] == 1){

				$ret = __( 'All File Types', 'zero-bs-crm' );

			} else {

				foreach ($settings['filetypesupload'] as $filetype => $enabled){

					if (isset($settings['filetypesupload'][$filetype]) && $enabled == 1) {

						if (!empty($ret)) $ret .= ', ';

						$ret .= '.'.$filetype;

					}

				} 

			}

		}

		if (empty($ret)) $ret = 'No Uploads Allowed';

		return $ret;
	}

	function zeroBS_acceptableFileTypeListArr(){

		$ret = array();
	  
		global $zbs;

		#} Retrieve settings
		$settings = $zbs->settings->getAll();
		
		if (isset($settings['filetypesupload'])) 
			foreach ($settings['filetypesupload'] as $filetype => $enabled){

				if (isset($settings['filetypesupload'][$filetype]) && $enabled == 1) $ret[] = '.'.$filetype;

			} 

		return $ret;
	}

	function zeroBS_acceptableFileTypeMIMEArr(){

		$ret = array();
	  
		global $zbs;

		#} Retrieve settings
		$settings = $zbs->settings->getAll();
		
		// if all, pass that
		if ( isset( $settings['filetypesupload'] ) && isset($settings['filetypesupload']['all']) && $settings['filetypesupload']['all'] == 1){

			return array('all'=>1);

		}
		if (isset($settings['filetypesupload'])) {
			if (isset($settings['filetypesupload']['all']) && $settings['filetypesupload']['all'] == 1) {
				// add all
				foreach ($settings['filetypesupload'] as $filetype => $enabled){
					$ret = array_merge( $ret, $zbs->acceptable_mime_types[$filetype] );
				}
			} else {
				// individual
				foreach ($settings['filetypesupload'] as $filetype => $enabled) {
					if ( isset( $settings['filetypesupload'][$filetype] ) && $enabled == 1 ) {
						$ret = array_merge( $ret, $zbs->acceptable_mime_types[$filetype] );
					}
				}
			}
		}

		return $ret;
	}

	/**
	 * Returns an array with all the mime types accepted for uploads from 
	 * contacts.
	 */
	function jpcrm_acceptable_filetype_mime_array_from_contacts() {
		global $zbs;

		$ret = array();
		$settings = $zbs->settings->getAll();
		if ( isset( $settings['filetypesupload'] ) ) {
			foreach ( $settings['filetypesupload'] as $filetype => $enabled ) {
				if ( 
					$enabled == 1 
					&& isset( $settings['filetypesupload'][$filetype] ) 
					&& isset( $zbs->acceptable_mime_types[$filetype] ) 
				) {
					$ret = array_merge( $ret, $zbs->acceptable_mime_types[$filetype] );
				}
			}
		}

		return $ret;
	}


	function jpcrm_acceptable_file_type_list_str_for_contact() {
		$ret = '';
	  
		global $zbs;

		$settings = $zbs->settings->getAll();
		
		if ( isset( $settings['filetypesupload'] ) && is_array( $settings['filetypesupload'] ) ) {
			foreach ($settings['filetypesupload'] as $filetype => $enabled){
				if (isset($settings['filetypesupload'][$filetype]) && $enabled == 1 && $filetype !== 'all') {
					if (!empty($ret)) $ret .= ', ';
					$ret .= '.'.$filetype;
				}
			} 
		}
		if (empty($ret)) $ret = 'No Uploads Allowed';

		return $ret;
	}


	#} removes a link to file (quote, invoice, other)
	// not always customer id... sometimes inv/co etc.
	function zeroBS_removeFile($objectID=-1,$fileType='',$fileURL=''){

	  	if ( current_user_can( 'admin_zerobs_customers' ) ) {   //only admin can do this too (extra security layer)

	  		global $zbs;

			if ($objectID !== -1 && !empty($fileURL)){
				
				/* centralised into zeroBSCRM_files_getFiles
				switch ($fileType){

					case 'customer':

						$filesArrayKey = 'zbs_customer_files';

						break;
					case 'quotes':

						$filesArrayKey = 'zbs_customer_quotes';

						break;
					case 'invoices':

						$filesArrayKey = 'zbs_customer_invoices';

						break;
				} */

				#} good?
				// zeroBSCRM_files_getFiles if (isset($filesArrayKey)){
				if (in_array($fileType, array('customer','quotes','invoices','company'))){

					#} First remove list reference:

						#} any change?
						$changeFlag = false; $fileObjToDelete = false;

						#} Load files arr

						/* centralised into zeroBSCRM_files_getFiles
						// for DAL1 contacts + quotes/invs:
						if (!$zbs->isDAL2() || $filesArrayKey == 'zbs_customer_quotes' || $filesArrayKey == 'zbs_customer_invoices') // DAL1
							$filesList = get_post_meta($objectID, $filesArrayKey, true);
						else // DAL2
							$filesList = $zbs->DAL->contacts->getContactMeta($objectID,'files');
						*/
						$filesList = zeroBSCRM_files_getFiles($fileType,$objectID);


						if (is_array($filesList) && count($filesList) > 0){

							#} defs
							$ret = array();
							
							#} Cycle through and remove any with this url - lame, but works for now
							foreach ($filesList as $fileObj){

								if ($fileObj['url'] != $fileURL) 
									$ret[] = $fileObj;
								else {
									$fileObjToDelete = $fileObj;
									$changeFlag = true;

									// also, if the removed file(s) are logged in any slots, clear the slot :)
    								$slot = zeroBSCRM_fileslots_fileSlot($fileObj['file'],$objectID,ZBS_TYPE_CONTACT);
    								if ($slot !== false && !empty($slot)){
										zeroBSCRM_fileslots_clearFileSlot($slot,$objectID,ZBS_TYPE_CONTACT);
									}
								}

							}

							if ($changeFlag) {

								/* zeroBSCRM_files_updateFiles 
								// for DAL1 contacts + quotes/invs:
								if (!$zbs->isDAL2() || $filesArrayKey == 'zbs_customer_quotes' || $filesArrayKey == 'zbs_customer_invoices') // DAL1
									update_post_meta($objectID,$filesArrayKey,$ret);
								else // DAL2
									$zbs->DAL->updateMeta(ZBS_TYPE_CONTACT,$objectID,'files',$ret);
								*/
								zeroBSCRM_files_updateFiles($fileType,$objectID,$ret);

							}

						} #} else w/e

					#} Then delete actual file ... 
					if ($changeFlag && isset($fileObjToDelete) && isset($fileObjToDelete['file'])){

						#} Brutal 
						#} #recyclingbin
						if (file_exists($fileObjToDelete['file'])) {

							#} Delete
							unlink($fileObjToDelete['file']);

							#} Check if deleted:
							if (file_exists($fileObjToDelete['file'])){

								// try and be more forceful:
								chmod($fileObjToDelete['file'], 0777);
								unlink(realpath($fileObjToDelete['file']));

								if (file_exists($fileObjToDelete['file'])){
									
									// tone down perms, at least
									chmod($fileObjToDelete['file'], 0644);

									// add message
									return __('Could not delete file from server:','zero-bs-crm').' '.$fileObjToDelete['file'];

								}

							}

						}

					}

					return true;

				}


			}

		} #} / can manage options


		return false;
	}

  

/* ======================================================
  	File Upload related funcs
   ====================================================== */

   function zeroBSCRM_privatiseUploadedFile($fromPath='',$filename=''){

   		#} Check dir created
   		$currentUploadDirObj = zeroBSCRM_privatisedDirCheck();
		if (is_array($currentUploadDirObj) && isset($currentUploadDirObj['path'])){ 
			$currentUploadDir = $currentUploadDirObj['path'];
			$currentUploadURL = $currentUploadDirObj['url'];
		} else {
			$currentUploadDir = false;
			$currentUploadURL = false;
		}

   		if (!empty($currentUploadDir)){

   			// generate a safe name + check no file existing
   			// this is TEMP code to be rewritten on formally secure file sys WH
   			$filePreHash = md5($filename.time());
   			// actually limit to first 16 chars is plenty
   			$filePreHash = substr($filePreHash,0,16);
   			$finalFileName = $filePreHash.'-'.$filename;
   			$finalFilePath = $currentUploadDir.'/'.$finalFileName;

   			// check exists, deal with (unlikely) dupe names
   			$c = 1;
   		 	while (file_exists($finalFilePath) && $c < 50){

   		 		// remake
   				$finalFileName = $filePreHash.'-'.$c.'-'.$filename;
   		 		$finalFilePath = $currentUploadDir.'/'.$finalFileName;

   		 		// let it roll + retest
   		 		$c++;
   		 	}

   			if (rename($fromPath.'/'.$filename,$finalFilePath)){

   				// moved :)

   				// check perms?
   				/* https://developer.wordpress.org/reference/functions/wp_upload_bits/
			    // Set correct file permissions
			    $stat = @ stat( dirname( $new_file ) );
			    $perms = $stat['mode'] & 0007777;
			    $perms = $perms & 0000666;
			    @ chmod( $new_file, $perms );
			    */

			    $endPath = $finalFilePath;
			    // this url is temp, it should be fed via php later.
			    $endURL = $currentUploadURL.'/'.$finalFileName;


   				// the caller-func needs to remove/change data/meta :)
   				return array('file'=>$endPath,'url'=>$endURL);

   			} else {

   				// failed to move
   				return false;
   			}


   		}

   		return false; // couldn't - no dir to move to :)


   }

function zeroBSCRM_privatisedDirCheck( $echo = false ) {
	$storage_dir_info = jpcrm_storage_dir_info();

	if ( $storage_dir_info === false ) {
		return false;
	}

	$is_dir_created = jpcrm_create_and_secure_dir_from_external_access( $storage_dir_info['path'], false );

	if ( $is_dir_created === false ) {
		return false;
	}

	return $storage_dir_info;
}

function jpcrm_get_hash_for_contact( $crm_contact ) {
	global $zbs;
	$zbs->load_encryption();
	return $zbs->encryption->hash( $crm_contact['id'] . $zbs->encryption->get_encryption_key( 'contact_hash' ) );
}

/*
 * Returns the 'dir info' for the storage folder.
 * dir info = 
 * [ 
 *      'path' => 'path for the physical file',
 *      'url'  => 'public facing url'
 * ]
 *
 */
function jpcrm_storage_dir_info() {
	$uploads_dir = WP_CONTENT_DIR;
	$uploads_url = content_url();
	$private_dir_name = 'jpcrm-storage';

	if ( ! empty( $uploads_dir ) && ! empty( $uploads_url ) ) {
		$full_dir_path = $uploads_dir . '/' . $private_dir_name;
		$full_url      = $uploads_url . '/' . $private_dir_name;
		return array( 'path' => $full_dir_path, 'url' => $full_url );
	}

	return false;
}

/*
 * Returns the 'dir info' for the fonts folder.
 */
function jpcrm_storage_fonts_dir_path() {
	$root_storage_info = jpcrm_storage_dir_info();

	if ( !$root_storage_info ) {
		return false;
	}

	$fonts_dir = $root_storage_info['path'] . '/fonts/';

	// Create and secure fonts dir as needed
	if ( !jpcrm_create_and_secure_dir_from_external_access( $fonts_dir ) || !is_dir( $fonts_dir ) ) {
		return false;
	}

	return $fonts_dir;
}

/*
 * Returns the 'dir info' for the provided contact.
 * contact dir info = 
 * [ 
 * 'avatar' => 
 *   [ 
 *      'path' => 'path for the physical file',
 *      'url'  => 'public facing url'
 *    ]
 * ]
 *
 */
function jpcrm_storage_dir_info_for_contact( $contact_id ) {
	global $zbs;

	$root_storage_info = jpcrm_storage_dir_info();

	if ( $root_storage_info === false ) {
		return false;
	}

	$contact_folder_name   = 'contacts';
	$contacts_storage_info = array( 
		'path' => $root_storage_info['path'] . '/' . $contact_folder_name, 
		'url'  => $root_storage_info['url'] . '/' . $contact_folder_name 
	);


	$crm_contact  = $zbs->DAL->contacts->getContact( $contact_id );
	$contact_hash = jpcrm_get_hash_for_contact( $crm_contact );
	$contact_path = sprintf( '/%s-%s/', $crm_contact['id'], $contact_hash );

	$avatar_path     = $contacts_storage_info['path'] . $contact_path . 'avatar';
	$avatar_dir_info = array(
		'path' => $avatar_path,
		'url'  => $contacts_storage_info['url']  . $contact_path . 'avatar',
	);

	$files_path     = $contacts_storage_info['path'] . $contact_path . 'files';
	$files_dir_info = array(
		'path' => $files_path,
		'url'  => $contacts_storage_info['url']  . $contact_path . 'files',
	);

	// add more dirs as needed
	return array ( 'avatar' => $avatar_dir_info, 'files' => $files_dir_info );
}

// 2.95.5+ we also add a subdir for 'work' (this is used by CPP when making thumbs, for example)
function zeroBSCRM_privatisedDirCheckWorks( $echo = false ) {
	$uploads_dir = WP_CONTENT_DIR;
	$uploads_url = content_url();
	$private_dir_name = 'jpcrm-storage/tmp';

	if ( ! empty( $uploads_dir ) && ! empty( $uploads_url ) ) {
		$full_dir_path = $uploads_dir . '/' . $private_dir_name;
		$full_url      = $uploads_url . '/' . $private_dir_name;

		// check existence
		if ( !file_exists( $full_dir_path ) ) {

			// doesn't exist, attempt to create
			mkdir( $full_dir_path, 0755, true );
			// force perms?
			chmod( $full_dir_path, 0755 );

		}

		if ( is_dir( $full_dir_path ) ) {
			jpcrm_create_and_secure_dir_from_external_access( $full_dir_path );
			return array( 'path' => $full_dir_path, 'url' => $full_url );
		}
	}

	return false;
}



/* ======================================================
  / File Upload related funcs
   ====================================================== */
   
/* ======================================================
  File Slots helpers
   ====================================================== */

   function zeroBSCRM_fileSlots_getFileSlots($objType=1){

   		global $zbs;

   		$fileSlots = array();

        $settings = zeroBSCRM_getSetting('customfields'); $cfbInd = 1;

        switch ($objType){

        	case 1:

		        if (isset($settings['customersfiles']) && is_array($settings['customersfiles']) && count($settings['customersfiles']) > 0){

			         foreach ($settings['customersfiles'] as $cfb){

			            $cfbName = ''; if (isset($cfb[0])) $cfbName = $cfb[0];
			         	$key = $zbs->DAL->makeSlug($cfbName); // $cfbInd
			            if (!empty($key)){
			            	$fileSlots[] = array('key'=>$key,'name'=>$cfbName);
			            	$cfbInd++;
			            }

			        }

		    	}

		    break;

		}

    	return $fileSlots;
   }

   // returns the slot (if assigned) of a given file
   function zeroBSCRM_fileslots_fileSlot($file='',$objID=-1,$objType=1){

   		// get all slotted files for contact/obj
   	
   		if ($objID > 0 && !empty($file)){

   			global $zbs;
   			$fileSlots = zeroBSCRM_fileslots_allSlots($objID,$objType);
   			// cycle through
   			if (count($fileSlots) > 0){

   				foreach ($fileSlots as $fsKey => $fsFile){

   					if ($fsFile == $file) return $fsKey;

   				}

   			}


   		}
   		return false;
   }


   // returns all slots (if assigned) of a given obj(contact)
   function zeroBSCRM_fileslots_allSlots($objID=-1,$objType=1){

   		if ($objID > 0){

   			global $zbs;
   			$fileSlots = zeroBSCRM_fileSlots_getFileSlots(ZBS_TYPE_CONTACT);
   			$ret = array();
   			if (count($fileSlots) > 0){

   				foreach ($fileSlots as $fs){

   					$ret[$fs['key']] = zeroBSCRM_fileslots_fileInSlot($fs['key'],$objID,$objType);

   				}

   			}
   			return $ret;
   	
   		}
   		return false;
   }

   // returns a file for a slot
   function zeroBSCRM_fileslots_fileInSlot($fileSlot='',$objID=-1,$objType=1){

   		if ($objID > 0){

   			global $zbs;

   			return $zbs->DAL->meta($objType,$objID,'cfile_'.$fileSlot);

   		}
   		return false;
 
   }

   // adds a file to a slot
   function zeroBSCRM_fileslots_addToSlot($fileSlot='',$file='',$objID=-1,$objType=1,$overrite=false){

   		if ($objID > 0){

   			//echo '<br>zeroBSCRM_fileslots_addToSlot '.$fileSlot.' '.$file.' '.$objID.' ext:'.zeroBSCRM_fileslots_fileInSlot($fileSlot,$objID).'!';

   			global $zbs;

	   		// check existing?
	   		if (!$overrite){
	   			$existingFile = zeroBSCRM_fileslots_fileInSlot($fileSlot,$objID);
	   			if (!empty($existingFile)) return false;
	   		} else {

	   			// overrite... so remove any if present before..
	   			zeroBSCRM_fileslots_clearFileSlot($fileSlot,$objID,$objType);
	   		}

	        // DAL2 add via meta (for now)
	        $zbs->DAL->updateMeta($objType,$objID,'cfile_'.$fileSlot,$file);
	        return true;

	    }

	    return false;

   	
   }

   function zeroBSCRM_fileslots_clearFileSlot($fileSlot='',$objID=-1,$objType=1){

   		if ($objID > 0){

   			global $zbs;
			return $zbs->DAL->deleteMeta(array(
						'objtype' 			=> $objType,
						'objid' 			=> $objID,
						'key'	 			=> 'cfile_'.$fileSlot
			   		));

		}

		return false;
   }


   function zeroBSCRM_files_baseName($filePath='',$privateRepo=false){

   		$file = '';
   		if (!empty($filePath)){


		    $file = basename($filePath);
		    if ($privateRepo) $file = substr($file,strpos($file, '-')+1);

   		}

   		return $file;

   }

/* ======================================================
  / File Slots helpers
   ====================================================== */

// gives an hashed filename+salt that is generally suitable for filesystems
function jpcrm_get_hashed_filename( $filename, $suffix='' ) {
	global $zbs;
	$zbs->load_encryption();
	$salt = $zbs->encryption->get_encryption_key( 'filename' );
	$hashed_filename = $zbs->encryption->hash( $filename . $salt ) . $suffix;
	return $hashed_filename;
}