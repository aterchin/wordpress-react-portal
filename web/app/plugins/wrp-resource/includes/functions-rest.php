<?php

/**
 * Register routes for REST API
 *
 * @return void
 */
add_action( 'rest_api_init', 'wrp_resource_register_rest_routes' );

function wrp_resource_register_rest_routes() {

  // We're not using a custom route for something which can easily be queried via standard
  // built-in wordpress routes.  For example, the below GET request will return the same thing
  // as the Get Category route below and additionally will attach the rest of the files
  //
  // /wp-json/wp/v2/resources/?categories=58

  // Example: get by query param 'category_name'.

  // -> GET custom posts of type 'resource' with category 'Billboards'
  // /wp-json/wrp-resource/v1/resources?category_name=Billboards

  register_rest_route( 'wrp-resource/v1', 'resources', [
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'wrp_resource_example_get_category',
    'permission_callback' => '__return_true'
  ] );


  register_rest_route( 'wrp-resource/v1', 'download/(?P<id>\d+)', [
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'wrp_resource_download',
    'permission_callback' => '__return_true',
    'args' => [
      'id' => [
        'validate_callback' => function( $param ) {
          return is_numeric( $param );
        }
      ]
    ],
  ] );
}

/**
 * Format a given field's value for output in the REST API.
 *
 * @param WP_REST_Request     $request
 * @return array || string    Query response
 */
function wrp_resource_example_get_category(WP_REST_Request $request) {
  $args = [
    'post_type' => ['resource'],
    'numberposts' => '100',
    'category_name' => $request->get_param( 'category_name' ),
    'order' => 'DESC',
    'orderby' => 'date',
  ];

  $the_query = new WP_Query( $args );

  if ( $the_query->have_posts() ) {
    $data = [];

    while ( $the_query->have_posts() ) {
      $the_query->the_post();
      $id = get_the_ID();

      $post = get_post($id);
      $link = get_permalink($id);
      $featured_image = get_the_post_thumbnail_url($id);

      $post_object = (object) [
        'id' => $post->ID,
        'title' => (object) ['rendered' => $post->post_title],
        'date' => $post->post_date,
        'slug' => $post->post_name,
        'link' => $link,
        'featured_img_url' => $featured_image,
        'image' => get_the_post_thumbnail_url($post->ID),
        'excerpt' => (object) ['rendered' => get_the_excerpt()]
      ];

      $data[] = $post_object;
    }

    return $data;
  }
  else {
    return 'No posts.';
  }
}

function wrp_resource_download( WP_REST_Request $request ) {
	$id = $request->get_param('id');

  // gets array of file URLs uploaded to post
  $filemeta = get_post_meta( $id, 'resource_file_list', true );
  if (empty($filemeta)) {
    return new WP_Error( 'empty_id', 'Invalid post id', array('status' => 404) );
  }
  // get attachment IDs, cause the URLs returned are hardcoded with the domain it was uploaded
  // and attachment will get the path only, which we need for the zip file
  $files = [];
  foreach (array_keys($filemeta) as $attachment_id) {
    $file_path = get_attached_file( $attachment_id );
    if ($file_path) {
      $files[$attachment_id] = $file_path;
    }
  }

  $zip_filename = wrp_resource_tempnam();

  $zip_created = wrp_resource_create_zip( $files, $zip_filename );

  $response = wrp_resource_serve_zip( $zip_filename );

  if ($response->status === 200) {
    // add to download count
    $count = get_post_meta($id, 'download_count', true);
    $count = empty($count) ? 1 : $count + 1;
    update_post_meta($id, 'download_count', strval($count));

    // no need for file now
    unlink( $zip_filename );
  }

  return $response;
}

/**
 * Serves an zip via the REST endpoint.
 *
 * By default, every REST response is passed through json_encode(), as the
 * typical REST response contains JSON data.
 *
 * This method hooks into the REST server to return a binary file.
 *
 * @param string $path Absolute path to the zip to serve.
 *
 * @return WP_REST_Response The REST response object to serve an file.
 */
function wrp_resource_serve_zip( $path ) {
  $response = new WP_REST_Response;

  if ( file_exists( $path ) ) {
    // Zip file exists, prepare a binary-data response.
    $response->set_data( file_get_contents( $path ) );
    // header("Pragma: public");
    // header("Expires: 0");
    // header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    // header("Cache-Control: public");
    // header("Content-Description: File Transfer");
    // //We can likely use the 'application/zip' type, but the octet-stream 'catch all' works just fine.
    // header("Content-type: application/octet-stream");
    // header("Content-Disposition: attachment; filename='$zip_filename'");
    // header("Content-Transfer-Encoding: binary");
    // header("Content-Length: ".filesize($zip_filename));
    // while (ob_get_level()) {
    //   ob_end_clean();
    // }
    // @readfile($zip_filename);
    // exit;
    $response->set_headers( [
        'Pragma' => 'public',
        'Expires' => 0,
        'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        'Cache-Control' => 'public',
        'Content-Type'   => "application/octet-stream",
        'Content-Length' => filesize( $path ),
        'Content-Transfer-Encoding' => "binary",
        'Content-Disposition' => 'attachment; filename="'.basename($path).'"',
        'Content-Description' => 'File Transfer'
    ] );

    // This filter will return our binary file!
    add_filter( 'rest_pre_serve_request', 'wrp_resource_do_serve_zip', 0, 2 );
  }
  else {
    // Return a simple "not-found" JSON response.
    $response->set_data( 'not-found' );
    $response->set_status( 404 );
  }

  return rest_ensure_response($response);
}

/**
 * Action handler that is used by `serve_zip()` to serve a binary image
 * instead of a JSON string.
 *
 * @return bool Returns true, if the zip was served; this will skip the
 *              default REST response logic.
 */
function wrp_resource_do_serve_zip( $served, $result ) {
  $is_zip   = false;
  $zip_data = null;

  // Check the "Content-Type" header to confirm that we really want to return
  // binary file data.
  foreach ( $result->get_headers() as $header => $value ) {
    if ( 'content-type' === strtolower( $header ) ) {
      $is_zip = (0 === strpos($value, 'application/octet-stream'));
      $zip_data = $result->get_data();
      break;
    }
  }
  // Output the binary data and tell the REST server to not send any other
  // details (via "return true").
  if ( $is_zip && is_string( $zip_data ) ) {
    while (ob_get_level()) {
      ob_end_clean();
    }
    echo $zip_data;

    return true;
  }

  return $served;
}

/**
 * Register routes for REST API
 *
 * @return void
 */
add_action( 'rest_api_init', 'wrp_resource_handle_orderby' );

function wrp_resource_handle_orderby() {

  // Add meta field to the allowed values of the REST API orderby parameter

  // See filter convention at end of method: 'rest_POST_TYPE_collection_params()'

  //developer.wordpress.org/reference/classes/wp_rest_posts_controller/get_collection_params/
  //developer.wordpress.org/reference/classes/wp_rest_terms_controller/get_collection_params/
  add_filter( 'rest_resource_collection_params', function( $params ) {
      $params['orderby']['enum'][] = 'download_count';
      return $params;
    }, 10, 1
  );

  // Manipulate query

  // See filter convention at end of method: 'rest_POST_TYPE_query()'

  //developer.wordpress.org/reference/classes/wp_rest_posts_controller/get_items/
  //developer.wordpress.org/reference/hooks/rest_this-post_type_query/
  add_filter('rest_resource_query', function ( $args, $request ) {
      $order_by = $request->get_param( 'orderby' );
      if ( isset( $order_by ) && 'download_count' === $order_by ) {
        $args['meta_key'] = $order_by;
        $args['orderby']  = 'meta_value_num'; // 'meta_value' for string fields
      }
      return $args;
    }, 10, 2
  );

}
