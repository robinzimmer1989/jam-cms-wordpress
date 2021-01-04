<?php

add_action( 'rest_api_init', 'jam_cms_api_delete_post' ); 
function jam_cms_api_delete_post() {
  register_rest_route( 'jamcms/v1', '/deletePost', array(
    'methods' => 'POST',
    'callback' => 'jam_cms_api_delete_post_callback',
    'permission_callback' => function () {
      return current_user_can( 'delete_posts' );
    }
  ));
}

function jam_cms_api_delete_post_callback($data) {
    $parameters   = $data->get_params();

    $site_id      = $parameters['siteID'];
    $post_id      = $parameters['id'];

    jam_cms_api_base_check($site_id, [$post_id]);

    // We need to generate the data beforehand, because otherwise it would throw an error 'Post not found'.
    $data = jam_cms_get_post_by_id($post_id);

    $post = wp_delete_post($post_id);

    if($post){
      return $data;
    }
}

?>