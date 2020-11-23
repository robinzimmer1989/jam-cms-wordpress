<?php

add_action( 'rest_api_init', 'gcms_api_get_auth_user' ); 
function gcms_api_get_auth_user() {
    register_rest_route( 'gcms/v1', '/getAuthUser', array(
        'methods' => 'POST',
        'callback' => 'gcms_api_get_auth_user_callback',
        'permission_callback' => function () {
            return current_user_can('read');
        }
    ));
}

function gcms_api_get_auth_user_callback($data) {

    $user_id = get_current_user_id();

    $data = gcms_get_user_by_id($user_id);

    return $data;
}

?>