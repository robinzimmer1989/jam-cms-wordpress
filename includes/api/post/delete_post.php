<?php

add_action( 'rest_api_init', 'gcms_api_delete_post' ); 
function gcms_api_delete_post() {
  register_rest_route( 'gcms/v1', '/deletePost', array(
    'methods' => 'POST',
    'callback' => 'gcms_api_delete_post_callback',
    'permission_callback' => function () {
      return current_user_can( 'delete_posts' );
    }
  ));
}

function gcms_api_delete_post_callback($data) {
    $parameters   = $data->get_params();

    $site_id      = $parameters['siteID'];
    $post_id      = $parameters['id'];

    gcms_api_base_check($site_id, [$post_id]);

    // We need to generate the data beforehand, because otherwise it would throw an error 'Post not found'.
    $data = gcms_get_post_by_id($site_id, $post_id);

    $post = wp_delete_post($post_id);

    if($post){
      return $data;
    }
}

?>