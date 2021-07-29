<?php

// https://rudrastyh.com/wordpress/duplicate-post.html
function jam_cms_duplicate_post($post_id, $overrides = [], $is_revision = false){
  
  global $wpdb;

  $post = get_post($post_id);

  if (isset( $post ) && $post != null) {

    $args = array(
      'comment_status' => $post->comment_status,
      'ping_status'    => $post->ping_status,
      'post_author'    => $post->post_author,
      'post_content'   => $post->post_content,
      'post_excerpt'   => $post->post_excerpt,
      'post_name'      => $post->post_name,
      'post_parent'    => $post->post_parent,
      'post_password'  => $post->post_password,
      'post_status'    => 'draft',
      'post_title'     => $post->post_title,
      'post_type'      => $post->post_type,
      'to_ping'        => $post->to_ping,
      'menu_order'     => $post->menu_order
    );

    // Override values
    foreach($overrides as $key => $value){
      $args[$key] = $value;
    }

    $new_post_id = wp_insert_post( $args );

    // Make sure the post_name / slug is unique
    if(!$is_revision){

      // Generate unique postname. We need the post id to do that, so we have to split the process into two steps.
      $unique_slug = wp_unique_post_slug( $post->post_name, $new_post_id, '', $post->post_type, $post->post_parent );

      // We're updating the db directly with the unique slug (vs wp_update_post) to avoid an automatic post revision.
      $wpdb->update( $wpdb->posts, ['post_name' => $unique_slug], ['ID' => $new_post_id]);
      
      // We need to clear the cache here, otherwise the get_post_by_id function will receive an empty post_name field.
      clean_post_cache( $new_post_id );
    }

    $taxonomies = get_object_taxonomies($post->post_type);
    foreach ($taxonomies as $taxonomy) {
      $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
      wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
    }
    
    $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");

    if (count($post_meta_infos)!=0) {
      $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
      foreach ($post_meta_infos as $meta_info) {
        $meta_key = $meta_info->meta_key;
        if( $meta_key == '_wp_old_slug' ) continue;
        $meta_value = addslashes($meta_info->meta_value);
        $sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
      }
      $sql_query.= implode(" UNION ALL ", $sql_query_sel);
      $wpdb->query($sql_query);
    }

    $new_post = jam_cms_get_post_by_id($new_post_id);

    return $new_post;
    
  }

  return null;
  
}