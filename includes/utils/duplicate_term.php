<?php

function jam_cms_duplicate_term($term_id, $overrides = [], $is_revision = false){
  
  global $wpdb;

  $term = get_term($term_id);

  if (is_wp_error($term)){
    return $term;
  }

  $unique_slug = wp_unique_term_slug($term->slug, $term);

  $new_term = wp_insert_term($unique_slug, $term->taxonomy, [
    'description' => $term->description,
    'parent'      => $term->parent,
  ]);

  if(is_wp_error($new_term)){
    return $new_term;
  }

  $sql = $wpdb->prepare( sprintf( "INSERT INTO %s (`term_id`, `meta_key`, `meta_value`) SELECT %%d, `meta_key`, `meta_value`  FROM %s WHERE `term_id` = %%d", $wpdb->termmeta, $wpdb->termmeta ), $new_term['term_id'], $term_id );
  $wpdb->query( $sql );

  clean_term_cache($new_term['term_id']);

  return $new_term;
}