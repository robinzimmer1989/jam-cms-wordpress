<?php

add_action( 'rest_api_init', 'gcms_api_delete_media_item' ); 
function gcms_api_delete_media_item() {
  register_rest_route( 'gcms/v1', '/deleteMediaItem', array(
    'methods' => 'POST',
    'callback' => 'gcms_api_delete_media_item_callback',
    'permission_callback' => function () {
      return current_user_can( 'delete_posts' );
    }
  ));
}

function gcms_api_delete_media_item_callback($data) {
    $parameters     = $data->get_params();

    $site_id        = $parameters['siteID'];
    $attachment_id  = $parameters['id'];

    gcms_api_base_check($site_id, [$attachment_id]);

    wp_delete_attachment($attachment_id, true);

    return $attachment_id;
}

?>