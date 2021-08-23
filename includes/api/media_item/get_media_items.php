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

    $check = jam_cms_api_base_check($parameters);

    if(is_wp_error($check)){
        return $check;
    }
    
    $page    = array_key_exists('page', $parameters) ? $parameters['page'] : 0;
    $limit   = array_key_exists('limit', $parameters) ? $parameters['limit'] : 10;
    $search  = array_key_exists('search', $parameters) ? $parameters['search'] : "";
    $allow   = array_key_exists('allow', $parameters) ? $parameters['allow'] : "";

    $data = jam_cms_get_media_items($limit, $page, $search, $allow);

    return array(
        'items'     => $data,
        'page'      => count($data) == $limit ? $page + 1 : -1,
        'search'    => $search
    );
}