<?php

add_action( 'rest_api_init', 'gcms_api_get_user' ); 
function gcms_api_get_user() {
    register_rest_route( 'gcms/v1', '/getUser', array(
        'methods' => 'POST',
        'callback' => 'gcms_api_get_user_callback',
        'permission_callback' => function () {
            return current_user_can( 'list_users' );
        }
    ));
}

function gcms_api_get_user_callback($data) {
    $parameters = $data->get_params();

    $site_id    = $parameters['siteID'];
    $user_id    = $parameters['id'];

    gcms_api_base_check($site_id, [$user_id]);

    $data = gcms_get_user_by_id($user_id);

    return $data;
}

?>