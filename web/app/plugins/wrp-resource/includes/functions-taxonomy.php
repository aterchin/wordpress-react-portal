<?php

add_action( "init", "wrp_resource_register_taxonomy" );

/**
 * Register "Brands" taxonomy, attach to resource post type
 *
 * @return void
 */
function wrp_resource_register_taxonomy() {

  $args = wrp_resource_taxonomy_default_args( "brand" );
  register_taxonomy( "brands", [ "resource" ], $args );
}

/*
 * wrp_resource_taxonomy_default_args
 *
 * Gets default args for taxonomy category
 *
 * @param   string $slug Slug of tax category
 * @param   string $label Capitalized slug of tax category (optional)
 * @return  array
 */
function wrp_resource_taxonomy_default_args( $slug, $label = '' ) {
  if ( empty( $label ) ) {
    $label = ucfirst( $slug );
  }

  return [
    "label"                        => __( "{$label}s", "wrp" ),
    "labels"                       => wrp_resource_taxonomy_default_labels( $slug, $label ),
    "public"                       => true,
    "publicly_queryable"           => true,
    "hierarchical"                 => true,
    "show_ui"                      => true,
    "show_in_menu"                 => true,
    "show_in_nav_menus"            => true,
    "query_var"                    => true,
    "rewrite"                      => [ "slug" => "{$slug}s", "with_front" => true, ],
    "show_admin_column"            => false,
    "show_in_rest"                 => true,
    "show_tagcloud"                => false,
    "rest_base"                    => "{$slug}s",
    "rest_controller_class"        => "WP_REST_Terms_Controller",
    "rest_namespace"               => "wp/v2",
    "show_in_quick_edit"           => false,
    "sort"                         => false,
    "show_in_graphql"              => false,
  ];
}

/*
 * wrp_resource_taxonomy_default_labels
 *
 * Gets default labels for taxonomy category
 *
 * @param   string $slug Slug of tax category
 * @param   string $label Capitalized slug of tax category (optional)
 * @return  array
 */
function wrp_resource_taxonomy_default_labels( $slug, $label = '' ) {
  if ( empty( $label ) ) {
    $label = ucfirst( $slug );
  }

  return [
    "name"                          => __( "{$label}s", "wrp" ),
    "singular_name"                 => __( "{$label}", "wrp" ),
    "menu_name"                     => __( "{$label}s", "wrp" ),
    "all_items"                     => __( "All {$label}s", "wrp" ),
    "edit_item"                     => __( "Edit {$label}", "wrp" ),
    "view_item"                     => __( "View {$label}", "wrp" ),
    "update_item"                   => __( "Update {$label} name", "wrp" ),
    "add_new_item"                  => __( "Add new {$label}", "wrp" ),
    "new_item_name"                 => __( "New {$label} name", "wrp" ),
    "parent_item"                   => __( "Parent {$label}", "wrp" ),
    "parent_item_colon"             => __( "Parent {$label}:", "wrp" ),
    "search_items"                  => __( "Search {$label}s", "wrp" ),
    "popular_items"                 => __( "Popular {$label}s", "wrp" ),
    "separate_items_with_commas"    => __( "Separate {$label}s with commas", "wrp" ),
    "add_or_remove_items"           => __( "Add or remove {$label}s", "wrp" ),
    "choose_from_most_used"         => __( "Choose from the most used {$label}s", "wrp" ),
    "not_found"                     => __( "No {$label}s found", "wrp" ),
    "no_terms"                      => __( "No {$label}s", "wrp" ),
    "items_list_navigation"         => __( "{$label}s list navigation", "wrp" ),
    "items_list"                    => __( "{$label}s list", "wrp" ),
    "back_to_items"                 => __( "Back to {$label}s", "wrp" ),
  ];
}
