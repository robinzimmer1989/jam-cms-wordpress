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

  $check = jam_cms_api_base_check($parameters, ['id']);

  if(is_wp_error($check)){
    return $check;
  }

  $post_id = $parameters['id'];

  $post = wp_delete_post($post_id);

  if(is_wp_error($post)){
    return $post;
  }

  return $post_id;
}