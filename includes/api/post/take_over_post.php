<?php

add_action( 'rest_api_init', 'jam_cms_api_take_over_post' ); 
function jam_cms_api_take_over_post() {
  register_rest_route( 'jamcms/v1', '/takeOverPost', array(
    'methods' => 'POST',
    'callback' => 'jam_cms_api_take_over_post_callback',
    'permission_callback' => function () {
      return current_user_can( 'edit_posts' );
    }
  ));
}

function jam_cms_api_take_over_post_callback($data) {
  $parameters   = $data->get_params();

  $check = jam_cms_api_base_check($parameters, ['id']);

  if(is_wp_error($check)){
    return $check;
  }

  $site_id      = $parameters['siteID'];
  $post_id      = $parameters['id'];

  jam_cms_set_post_lock($post_id);

  $post = jam_cms_get_post_by_id($post_id);

  return $post;
}