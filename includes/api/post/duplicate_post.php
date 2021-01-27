<?php

add_action( 'rest_api_init', 'jam_cms_api_duplicate_post' ); 
function jam_cms_api_duplicate_post() {
  register_rest_route( 'jamcms/v1', '/duplicatePost', array(
    'methods' => 'POST',
    'callback' => 'jam_cms_api_duplicate_post_callback',
    'permission_callback' => function () {
      return current_user_can( 'edit_posts' );
    }
  ));
}

function jam_cms_api_duplicate_post_callback($data) {
  $parameters   = $data->get_params();

  jam_cms_api_base_check($parameters, ['id']);

  $site_id      = $parameters['siteID'];
  $post_id      = $parameters['id'];

  // https://rudrastyh.com/wordpress/duplicate-post.html

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

    $new_post_id = wp_insert_post( $args );

    // Generate unique postname. We need the post id to do that, so we have to split the process into two steps.
    $unique_slug = wp_unique_post_slug( $post->post_name, $new_post_id, '', $post->post_type, $post->post_parent );

    wp_update_post([
      'ID'          => $new_post_id,
      'post_name'   => $unique_slug
    ]);

    $taxonomies = get_object_taxonomies($post->post_type);
    foreach ($taxonomies as $taxonomy) {
      $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
      wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
    }

    global $wpdb;
    
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
}

?>