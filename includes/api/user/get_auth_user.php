<?php

add_action( 'rest_api_init', 'jam_cms_api_get_auth_user' ); 
function jam_cms_api_get_auth_user() {
    register_rest_route( 'jamcms/v1', '/getAuthUser', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_get_auth_user_callback',
        'permission_callback' => function () {
            return current_user_can('edit_posts');
        }
    ));
}

function jam_cms_api_get_auth_user_callback($data) {

    $user_id = get_current_user_id();

    $data = jam_cms_get_user_by_id($user_id);

    return $data;
}

?>