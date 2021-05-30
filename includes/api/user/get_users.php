<?php

add_action( 'rest_api_init', 'jam_cms_api_get_users' ); 
function jam_cms_api_get_users() {
    register_rest_route( 'jamcms/v1', '/getUsers', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_get_users_callback',
        'permission_callback' => function () {
            return current_user_can( 'list_users' );
        }
    ));
}

function jam_cms_api_get_users_callback($data) {
    $parameters = $data->get_params();

    $check = jam_cms_api_base_check($parameters);

    if(is_wp_error($check)){
        return $check;
    }

    $site_id    = $parameters['siteID'];
    $page       = $parameters['page'];
    $limit      = $parameters['limit'];

    $data = jam_cms_get_users($limit, $page);

    return array(
        'items' => $data,
        'page'  => count($data) == $limit ? $page + 1 : -1
    );
}

?>