<?php

add_action( 'rest_api_init', 'gcms_api_get_users' ); 
function gcms_api_get_users() {
    register_rest_route( 'gcms/v1', '/getUsers', array(
        'methods' => 'POST',
        'callback' => 'gcms_api_get_users_callback',
        'permission_callback' => function () {
            return current_user_can( 'list_users' );
        }
    ));
}

function gcms_api_get_users_callback($data) {
    $parameters = $data->get_params();

    $site_id    = $parameters['siteID'];
    $page       = $parameters['page'];
    $limit      = $parameters['limit'];

    gcms_api_base_check($site_id);

    $data = gcms_get_users($limit, $page);

    return array(
        'items' => $data,
        'page' => count($data) == $limit ? $page + 1 : -1
    );
}

?>