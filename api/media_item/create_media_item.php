<?php

add_action( 'rest_api_init', 'gcms_api_create_media_item' ); 
function gcms_api_create_media_item() {
    register_rest_route( 'gcms/v1', '/createMediaItem', array(
        'methods' => 'GET',
        'callback' => 'gcms_api_create_media_item_callback'
    ));
}

function gcms_api_create_media_item_callback($data) {
    $site_id = $data->get_param('siteID');
    $file = $data->get_param('file');

    $site = get_blog_details($site_id);

    if($site && $file){
      switch_to_blog($site->blog_id);

     
    }

    return null;
}

?>