<?php

add_action( 'rest_api_init', 'jam_cms_api_reorder_posts' ); 
function jam_cms_api_reorder_posts() {
  register_rest_route( 'jamcms/v1', '/reorderPosts', array(
    'methods' => 'POST',
    'callback' => 'jam_cms_api_reorder_posts_callback',
    'permission_callback' => function () {
      return current_user_can( 'edit_posts' );
    }
  ));
}

function jam_cms_api_reorder_posts_callback($data) {
    $parameters   = $data->get_params();

    $check = jam_cms_api_base_check($parameters, ['postIDs']);

    if(is_wp_error($check)){
        return $check;
    }

    $site_id      = $parameters['siteID'];
    $post_ids     = $parameters['postIDs'];

    global $wpdb;
    
    foreach(json_decode($post_ids) as $post_id => $menu_order) {
      $wpdb->update( $wpdb->posts, ['menu_order' => $menu_order], array('ID' => $post_id) );
    }
    
    return true;
}