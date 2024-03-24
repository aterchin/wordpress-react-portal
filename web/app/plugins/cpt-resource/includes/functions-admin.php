<?php

add_action( 'restrict_manage_posts', 'cpt_resource_taxonomy_add_admin_filters', 10, 1 );

/*
 * cpt_resource_taxonomy_add_admin_filters
 *
 * Hook adds filter for brand taxonomy posts
 */
function cpt_resource_taxonomy_add_admin_filters( $post_type ){

  if( 'resource' !== $post_type ) {
    return;
  }
  $taxonomies_slugs = ['brands'];
  // loop through the taxonomy filters array
  foreach( $taxonomies_slugs as $slug ){
    $taxonomy = get_taxonomy( $slug );
    $selected = '';
    // if the current page is already filtered, get the selected term slug
    $selected = isset( $_REQUEST[ $slug ] ) ? $_REQUEST[ $slug ] : '';
    // render a dropdown for this taxonomy's terms
    wp_dropdown_categories(
      [
        'show_option_all' =>  $taxonomy->labels->all_items,
        'taxonomy'        =>  $slug,
        'name'            =>  $slug,
        'orderby'         =>  'name',
        'value_field'     =>  'slug',
        'selected'        =>  $selected,
        'hierarchical'    =>  true,
      ]
    );
  }
}

add_action( 'manage_resource_posts_columns', 'cpt_resource_manage_posts_columns', 10, 2 );

/*
 * cpt_resource_custom_columns
 *
 * Filters the columns displayed in the Posts list table.
 * @see manage_post_type_posts_columns
 * @see
 */
function cpt_resource_manage_posts_columns( $columns ) {

  unset($columns['author']);

  // reordering existing: removing author, adding 'brand'
  $new_columns = [
    'cb' => '<input type="checkbox" />',
    'title' => $columns['title'],
    'categories' => $columns['categories'],
    'brand' => __(' Brand'),
    'date' => $columns['date'],
  ];

  return $new_columns;
}

add_action( 'manage_resource_posts_custom_column' , 'cpt_resource_manage_custom_column', 10, 2 );

/*
 * cpt_resource_custom_column
 *
 * Hook fires for each custom column of a specific post type in the Posts list table.
 * @see manage_post_type_posts_custom_column
 */
function cpt_resource_manage_custom_column( $column, $post_id ) {
  switch ( $column ) {
    case 'brand':
      $terms = wp_get_post_terms( $post_id, 'brands' );
      $termslist = [];
      echo '';
      foreach ( $terms as $term ) {
        // The $term is an object, so we don't need to specify the $taxonomy.
        $term_link = get_term_link( $term );
        if ( is_wp_error( $term_link ) ) {
          continue;
        }
        $termslist[] = '<a href="' . esc_url( $term_link ) . '">' . $term->name . '</a>';
      }
      //echo 'arr' . $termslist;
      echo implode(', ', $termslist);
      //echo 'this is the term: ' . $term->name;
    break;
    default: break;
  }
}

/*
 * cpt_resource_update_posts
 *
 * Update all posts for whatever reason.  Maybe ordering isn't working cause you added a field
 * and some of those fields are null and resaving will fix it so the field is added.  Who knows?
 * I mean, who really knows?  Not me.
 *
 * Usage: /?update-resource-posts (assuming you're an admin)
 */
add_action( 'wp_loaded', 'cpt_resource_update_posts' );

function cpt_resource_update_posts() {
  // Check to see if we're an admin
  if ( current_user_can( 'manage_options' ) ) {
    if ( isset( $_REQUEST['update-resource-posts'] ) ) {
      $args = [
        'post_type' => 'resource',
        'numberposts' => -1
      ];
      $posts = get_posts( $args );
      foreach ( $posts as $post ) {
        //$post->post_title = $post->post_title . '';
        //wp_update_post( $post );
        $count = get_post_meta($post->ID, 'download_count', true);
        if (empty($count)) {
          update_post_meta($post->ID, 'download_count', '0');
        }
      }
    }
  }
}
