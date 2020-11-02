<?php

add_action( 'rest_api_init', 'gcms_api_create_post_type' ); 
function gcms_api_create_post_type() {
    register_rest_route( 'gcms/v1', '/createCollection', array(
        'methods' => 'GET',
        'callback' => 'gcms_api_create_post_type_callback'
    ));
}

function gcms_api_create_post_type_callback($data) {
    $site_id = $data->get_param('siteID');
    $title = $data->get_param('title');
    $slug = $data->get_param('slug');

    $site = get_blog_details($site_id);

    if($site && $title && $slug){
      switch_to_blog($site->blog_id);

    }

    return null;
}

?>