<?php

add_action( 'rest_api_init', 'gcms_api_delete_media_item' ); 
function gcms_api_delete_media_item() {
  register_rest_route( 'gcms/v1', '/deleteMediaItem', array(
    'methods' => 'GET',
    'callback' => 'gcms_api_delete_media_item_callback'
  ));
}

function gcms_api_delete_media_item_callback($data) {
    $site_id = $data->get_param('siteID');
    $attachment_id = $data->get_param('id');

    $site = get_blog_details($site_id);

    if($site){
      switch_to_blog($site->blog_id);

      wp_delete_attachment($attachment_id, true);

      return $attachment_id;
    }

    return null;
}

?>