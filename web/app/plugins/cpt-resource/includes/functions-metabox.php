<?php

add_action( 'cmb2_init', 'cpt_resource_metaboxes' );


// @see https://cmb2.io/docs/field-types

/**
 * Define the metabox and field configurations.
 */
function cpt_resource_metaboxes() {

  if (function_exists('new_cmb2_box')) {
    /**
     * Initiate the metabox
     */
    $cmb = new_cmb2_box(
      [
        'id'            => 'resource_assets_metabox',
        'title'         => __( 'Assets', 'cpt-resource' ),
        'object_types'  => ['resource'], // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
        'show_in_rest' => WP_REST_Server::ALLMETHODS,
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
      ]
    );

    // Regular text field
    $cmb->add_field(
      [
        'name'  => 'Resource Title',
        'desc'  => 'This title will show on the front end',
        'id'    => 'resource_title',
        'type'  => 'text',
      ]
    );

    $cmb->add_field(
      [
        'name' => 'File List',
        'desc' => '',
        'id'   => 'resource_file_list',
        'type' => 'file_list',
        // 'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
        // 'query_args' => array( 'type' => 'image' ), // Only images attachment

        // Optional, override default text strings
        // 'text' => [
        //   'add_upload_files_text' => 'Replacement', // default: "Add or Upload Files"
        //   'remove_image_text' => 'Replacement', // default: "Remove Image"
        //   'file_text' => 'Replacement', // default: "File:"
        //   'file_download_text' => 'Replacement', // default: "Download"
        //   'remove_text' => 'Replacement', // default: "Remove"
        // ],
      ]
    );
    $cmb->add_field(
      [
    	  'name'    => 'Download count',
    	  'desc'    => 'Automatically updates when someone downloads resource package',
    	  'default' => '0',
    	  'id'      => 'download_count',
    	  'type'    => 'hidden',
    	  //'type'    => 'text',
        'rest_value_cb' => function( $value ) {
          return (string) $value;
        },
      ]
    );
  }
}
