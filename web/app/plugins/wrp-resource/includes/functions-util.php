<?php

function wrp_resource_mkdir($path) {
   return is_dir($path) || mkdir($path, 0775);
}

/**
 * Returns a filename of a temporary unique file.
 *
 * @see wp-admin/includes/file.php
 */
function wrp_resource_tempnam( $filename = '', $dir = '' ) {
	if ( empty( $dir ) ) {
		$dir = get_temp_dir();
	}

	if ( empty( $filename ) || in_array( $filename, array( '.', '/', '\\' ), true ) ) {
		$filename = uniqid();
	}

	// Use the basename of the given file without the extension as the name for the temporary directory.
	$temp_filename = basename( $filename );
	$temp_filename = preg_replace( '|\.[^.]*$|', '', $temp_filename );

	// If the folder is falsey, use its parent directory name instead.
	if ( ! $temp_filename ) {
		return wrp_resource_tempnam( dirname( $filename ), $dir );
	}

	// Suffix some random data to avoid filename conflicts.
	$temp_filename .= '-' . wp_generate_password( 6, false );
	$temp_filename .= '.zip';
	$temp_filename  = $dir . wp_unique_filename( $dir, $temp_filename );

	return $temp_filename;
}

// creates a zip file, what else?
function wrp_resource_create_zip($files = [], $destination = '', $overwrite = false) {

  if (file_exists($destination) && !$overwrite) {
    return false;
  }

  $valid_files = [];
  if ( is_array($files) ) {
		foreach ($files as $file) {
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
  }

  if (count($valid_files)) {
    $zip = new ZipArchive();
    if ($zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
      return false;
    }

    foreach ($valid_files as $k => $file) {
      $zip->addFile($file, basename($file));
    }
    //debug
    //error_log( 'The zip archive contains ' . $zip->numFiles . ' files with a status of ' . $zip->status );

    //close the zip -- done!
    $zip->close();

    //check to make sure the file exists
    return file_exists($destination) ? $destination : false;
  }
  else {
    return false;
  }
}
