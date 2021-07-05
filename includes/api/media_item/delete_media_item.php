<?php

add_action( 'rest_api_init', 'jam_cms_api_delete_media_item' ); 
function jam_cms_api_delete_media_item() {
  register_rest_route( 'jamcms/v1', '/deleteMediaItem', array(
    'methods' => 'POST',
    'callback' => 'jam_cms_api_delete_media_item_callback',
    'permission_callback' => function () {
      return current_user_can( 'delete_posts' );
    }
  ));
}

function jam_cms_api_delete_media_item_callback($data) {
    $parameters = $data->get_params();

    $check = jam_cms_api_base_check($parameters, ['id']);
    
    if(is_wp_error($check)){
        return $check;
    }

    $site_id        = $parameters['siteID'];
    $attachment_id  = $parameters['id'];

    wp_delete_attachment($attachment_id, true);

    return $attachment_id;
}