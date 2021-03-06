<?php

add_action( 'rest_api_init', 'jam_cms_api_empty_trash' ); 
function jam_cms_api_empty_trash() {
  register_rest_route( 'jamcms/v1', '/emptyTrash', array(
    'methods' => 'POST',
    'callback' => 'jam_cms_api_empty_trash_callback',
    'permission_callback' => function () {
      return current_user_can( 'empty_trashs' );
    }
  ));
}

function jam_cms_api_empty_trash_callback($data) {
  $parameters   = $data->get_params();

  $check = jam_cms_api_base_check($parameters, ['postTypeID']);

  if(is_wp_error($check)){
      return $check;
  }

  $site_id      = $parameters['siteID'];
  $post_type_id = $parameters['postTypeID'];

  $trashed_posts = get_posts([
    'post_status' => 'trash',
    'numberposts' => -1,
    'post_type'   => $post_type_id
  ]);

  $formatted_posts = [];

  foreach($trashed_posts as $post){
    $delete = wp_delete_post($post->ID, true);

    if($delete){
      array_push($formatted_posts, jam_cms_format_post($post));
    }
  }

  return $formatted_posts;
}