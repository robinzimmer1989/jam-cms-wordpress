<?php

add_action( 'rest_api_init', 'gcms_api_update_media_item' ); 
function gcms_api_update_media_item() {
  register_rest_route( 'gcms/v1', '/updateMediaItem', array(
    'methods' => 'GET',
    'callback' => 'gcms_api_update_media_item_callback'
  ));
}

function gcms_api_update_media_item_callback($data) {
    $site_id = $data->get_param('siteID');
    $media_item_id = $data->get_param('id');
    $alt_text = $data->get_param('altText');

    $site = get_blog_details($site_id);

    if($site){
      switch_to_blog($site->blog_id);

    }

    return null;
}

?>