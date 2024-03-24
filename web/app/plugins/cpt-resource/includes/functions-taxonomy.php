<?php

add_action( "init", "cpt_resource_register_taxonomy" );

/**
 * Register "Brands" taxonomy, attach to resource post type
 *
 * @return void
 */
function cpt_resource_register_taxonomy() {

  $args = cpt_resource_taxonomy_default_args( "brand" );
  register_taxonomy( "brands", [ "resource" ], $args );
}

/*
 * cpt_resource_taxonomy_default_args
 *
 * Gets default args for taxonomy category
 *
 * @param   string $slug Slug of tax category
 * @param   string $label Capitalized slug of tax category (optional)
 * @return  array
 */
function cpt_resource_taxonomy_default_args( $slug, $label = '' ) {
  if ( empty( $label ) ) {
    $label = ucfirst( $slug );
  }

  return [
    "label"                        => __( "{$label}s", "cpt" ),
    "labels"                       => cpt_resource_taxonomy_default_labels( $slug, $label ),
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
 * cpt_resource_taxonomy_default_labels
 *
 * Gets default labels for taxonomy category
 *
 * @param   string $slug Slug of tax category
 * @param   string $label Capitalized slug of tax category (optional)
 * @return  array
 */
function cpt_resource_taxonomy_default_labels( $slug, $label = '' ) {
  if ( empty( $label ) ) {
    $label = ucfirst( $slug );
  }

  return [
    "name"                          => __( "{$label}s", "cpt" ),
    "singular_name"                 => __( "{$label}", "cpt" ),
    "menu_name"                     => __( "{$label}s", "cpt" ),
    "all_items"                     => __( "All {$label}s", "cpt" ),
    "edit_item"                     => __( "Edit {$label}", "cpt" ),
    "view_item"                     => __( "View {$label}", "cpt" ),
    "update_item"                   => __( "Update {$label} name", "cpt" ),
    "add_new_item"                  => __( "Add new {$label}", "cpt" ),
    "new_item_name"                 => __( "New {$label} name", "cpt" ),
    "parent_item"                   => __( "Parent {$label}", "cpt" ),
    "parent_item_colon"             => __( "Parent {$label}:", "cpt" ),
    "search_items"                  => __( "Search {$label}s", "cpt" ),
    "popular_items"                 => __( "Popular {$label}s", "cpt" ),
    "separate_items_with_commas"    => __( "Separate {$label}s with commas", "cpt" ),
    "add_or_remove_items"           => __( "Add or remove {$label}s", "cpt" ),
    "choose_from_most_used"         => __( "Choose from the most used {$label}s", "cpt" ),
    "not_found"                     => __( "No {$label}s found", "cpt" ),
    "no_terms"                      => __( "No {$label}s", "cpt" ),
    "items_list_navigation"         => __( "{$label}s list navigation", "cpt" ),
    "items_list"                    => __( "{$label}s list", "cpt" ),
    "back_to_items"                 => __( "Back to {$label}s", "cpt" ),
  ];
}
