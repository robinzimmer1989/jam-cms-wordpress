<?php

add_action( 'rest_api_init', 'gcms_api_create_post' ); 
function gcms_api_create_post() {
    register_rest_route( 'gcms/v1', '/createPost', array(
        'methods' => 'GET',
        'callback' => 'gcms_api_create_post_callback'
    ));
}

function gcms_api_create_post_callback($data) {
    $site_id = $data->get_param('siteID');
    $title = $data->get_param('title');
    $slug = $data->get_param('slug');
    $post_type = $data->get_param('postTypeID');
    $parent_id = $data->get_param('parentID');

    $site = get_blog_details($site_id);

    if($site && $title && $slug && $post_type){
      switch_to_blog($site->blog_id);

      $post_data = array(
        'post_title'  => $title,
        'post_name'   => $slug,
        'post_status' => 'draft',
        'post_type'   => $post_type,
        'post_parent' => $parent_id || 0
      );

      $post_id = wp_insert_post($post_data);

      $data = gcms_get_post_by_id($site_id, $post_id);
      return $data;
    }

    return null;
}

?>