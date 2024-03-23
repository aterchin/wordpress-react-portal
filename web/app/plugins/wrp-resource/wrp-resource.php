<?php
/**
 * Plugin Name:     WRP Resource
 * Plugin URI:      https://github.com/aterchin/wordpress-react-portal
 * Description:     Resource post type functions and hooks
 * Author:          Adam Terchin
 * Author URI:      https://github.com/aterchin
 * Text Domain:     wrp-resource
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         wrp_resource
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

define( 'WRP_RESOURCE_PATH',  plugin_dir_path( __FILE__ ) );

// load utility first
require_once WRP_RESOURCE_PATH . 'includes/functions-util.php';

// things you only see in the admin UI
require_once WRP_RESOURCE_PATH . 'includes/functions-admin.php';

// custom post type creation
require_once WRP_RESOURCE_PATH . 'includes/functions-post.php';

// custom taxonomy category creation
require_once WRP_RESOURCE_PATH . 'includes/functions-taxonomy.php';

// additional CBM2 metaboxes
require_once WRP_RESOURCE_PATH . 'includes/functions-metabox.php';

// WP_REST_Server-related routes and callbacks
require_once WRP_RESOURCE_PATH . 'includes/functions-rest.php';
