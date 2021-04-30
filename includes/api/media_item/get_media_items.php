<?php

add_action( 'rest_api_init', 'jam_cms_api_get_media_items' ); 
function jam_cms_api_get_media_items() {
    register_rest_route( 'jamcms/v1', '/getMediaItems', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_get_media_items_callback',
        'permission_callback' => function () {
            return current_user_can( 'edit_posts' );
        }
    ));
}

function jam_cms_api_get_media_items_callback($data) {
    $parameters = $data->get_params();

    jam_cms_api_base_check($parameters);
    
    $site_id    = $parameters['siteID'];
    $page       = $parameters['page'];
    $limit      = $parameters['limit'];

    $data = jam_cms_get_media_items($site_id, $limit, $page);

    return array(
        'items' => $data,
        'page' => count($data) == $limit ? $page + 1 : -1
    );
}

?>