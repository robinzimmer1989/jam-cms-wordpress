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

  $check = jam_cms_api_base_check($parameters, ['id']);

  if(is_wp_error($check)){
    return $check;
  }

  $post_id      = $parameters['id'];

  $post = jam_cms_duplicate_post($post_id);

  return $post;
}