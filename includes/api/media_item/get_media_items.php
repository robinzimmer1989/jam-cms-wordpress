<?php

add_action( 'rest_api_init', 'gcms_api_get_media_items' ); 
function gcms_api_get_media_items() {
    register_rest_route( 'gcms/v1', '/getMediaItems', array(
        'methods' => 'POST',
        'callback' => 'gcms_api_get_media_items_callback',
        'permission_callback' => function () {
            return current_user_can( 'read' );
        }
    ));
}

function gcms_api_get_media_items_callback($data) {
    $parameters = $data->get_params();
    
    $site_id    = $parameters['siteID'];
    $page       = $parameters['page'];
    $limit      = $parameters['limit'];

    gcms_api_base_check($site_id);

    $data = gcms_get_media_items($site_id, $limit, $page);

    return array(
        'items' => $data,
        'page' => count($data) == $limit ? $page + 1 : -1
    );
}

?>