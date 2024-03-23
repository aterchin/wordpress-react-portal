<?php

add_action( "init", "wrp_resource_register_post_types" );

/**
 * Register "Resource" post type
 *
 * @return void
 */
function wrp_resource_register_post_types() {

  // Customize or overwrite defaults
  $args = wrp_resource_post_default_args( "resource" );

  $args["description"] = "Individual asset comprised of images, video, and documentation.";
  $args["taxonomies"] = ["category"];
  $args["menu_icon"] = "dashicons-portfolio";

  register_post_type( "resource",  $args );

  // If making a change to something related to URL rewrites run this
  // or hit save on the admin "Permalinks page
  //flush_rewrite_rules();
}

add_filter( 'post_type_link', 'wrp_resource_post_type_link', 10, 2 );

function wrp_resource_post_type_link( $link, $post ) {

  if ( get_post_type( $post ) === 'resource' ) {

    // As portal scales to include other brands this will need to be removed.
    // Currently, this serves no purpose other than to keep a
    // record in the URI of what brands were accessed.

    //return add_query_arg( ['brand' => 'test'], $link );

    // Similar to the above, this also doesn't currently serve any purpose other than to keep a
    // record in the URI of what brands were accessed. The following will give us ?brands=35,30

    if ( $terms = get_the_terms( $post->ID, 'brands' ) ) {

     $brands = wp_list_pluck( $terms, 'term_id' );
     if ( !empty($brands) ) {
       $brands = implode( ',', $brands );
       return add_query_arg( ['brands' => $brands], $link );
     }

     return $link;
    }

  }

  return $link;
}


/*
 * wrp_resource_post_default_args
 *
 * Gets default args for post types
 *
 * @param   string $slug Slug of post type
 * @param   string $label Capitalized slug of post type (optional)
 * @return  array
 */
function wrp_resource_post_default_args( $slug, $label = '' ) {
  if ( empty( $label ) ) {
    $label = ucfirst( $slug );
  }

  return [
    "label"                 => __( "{$label}s", "wrp" ),
    "labels"                => wrp_resource_post_default_labels( $slug, $label ),
    "description"           => "Custom post type.",
    "public"                => true,
    "publicly_queryable"    => true,
    "show_ui"               => true,
    "show_in_menu"          => true,
    "has_archive"           => "{$slug}s",
    "hierarchical"          => false,
    "menu_position"         => 20,
    "supports"              => [ "title", "editor", "author", "excerpt", "thumbnail", "page_attributes", "custom-fields", "post-formats" ],
    "taxonomies"            => [ "category", "post_tag" ],
    "show_in_rest"          => true,
    "rest_base"             => "{$slug}s",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace"        => "wp/v2",
    "delete_with_user"      => false,
    "exclude_from_search"   => false,
    "capability_type"       => [ "post", "posts" ],
    "map_meta_cap"          => true,
    "can_export"            => true,
    "rewrite"               => [ "slug" => $slug, "with_front" => true ],
    "query_var"             => true,
    "show_in_graphql"       => false,
    "template_lock"         => false,
  ];
}

/*
 * wrp_resource_post_default_labels
 *
 * Gets default labels for post types
 *
 * @param   string $slug Slug of post type
 * @param   string $label Capitalized slug of post type (optional)
 * @return  array
 */
function wrp_resource_post_default_labels( $slug, $label = '') {
  if ( empty( $label ) ) {
    $label = ucfirst( $slug );
  }

  return [
    "name"                  => __( "{$label}s", "Post type general name", "wrp" ),
    "singular_name"         => __( "{$label}", "Post type singular name", "wrp" ),
    "menu_name"             => __( "{$label}s", "Admin Menu text", "wrp" ),
    "name_admin_bar"        => __( "{$label}", "Add New on Toolbar", "wrp" ),
    "add_new"               => __( "Add New", "wrp" ),
    "add_new_item"          => __( "Add New {$label}", "wrp" ),
    "new_item"              => __( "New {$label}", "wrp" ),
    "edit_item"             => __( "Edit {$label}", "wrp" ),
    "view_item"             => __( "View {$label}", "wrp" ),
    "all_items"             => __( "All {$label}s", "wrp" ),
    "search_items"          => __( "Search {$label}s", "wrp" ),
    "parent_item_colon"     => __( "Parent {$label}s:", "wrp" ),
    "not_found"             => __( "No {$label}s found.", "wrp" ),
    "not_found_in_trash"    => __( "No {$label}s found in Trash.", "wrp" ),
    "item_published"        => __( "{$label} published", "wrp" ),
    "item_published_privately"    => __( "{$label} published privately.", "wrp" ),
    "item_reverted_to_draft"      => __( "{$label} reverted to draft.", "wrp" ),
    "item_scheduled"        => __( "{$label} scheduled", "wrp" ),
    "item_updated"          => __( "{$label} updated.", "wrp" ),
    "insert_into_item"      => __( "Insert into {$label}", "wrp" ),
    "uploaded_to_this_item" => __( "Upload to this {$label}", "wrp" ),
    "filter_items_list"     => __( "Filter {$label} list", "wrp" ),
    "items_list_navigation" => __( "{$label} list navigation", "wrp" ),
    "items_list"            => __( "{$label} list", "wrp"),
    "attributes"            => __( "{$label} attributes", "wrp" ),
  ];
}

/*
 * wrp_resource_post_default_caps
 *
 * Gets default capabilities and output strings for post types
 *
 * @param   string $cap_type Slug or "post" type
 * @return  array
 */
function wrp_resource_post_default_caps( $cap_type = "post" ) {
  return [
    "capabilities" => [
      "create_posts"            => "create_{$cap_type}s",
      "delete_others_posts"     => "delete_others_{$cap_type}s",
      "delete_posts"            => "delete_{$cap_type}s",
      "delete_private_posts"    => "delete_private_{$cap_type}s",
      "delete_published_posts"  => "delete_published_{$cap_type}s",
      "edit_posts"              => "edit_{$cap_type}s",
      "edit_others_posts"       => "edit_others_{$cap_type}s",
      "edit_private_posts"      => "edit_private_{$cap_type}s",
      "edit_published_posts"    => "edit_published_{$cap_type}s",
      "publish_posts"           => "publish_{$cap_type}s",
      "read_private_posts"      => "read_private_{$cap_type}s",
      "read"                    => "read",
    ]
  ];
}
