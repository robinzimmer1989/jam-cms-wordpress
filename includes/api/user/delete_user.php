<?php

add_action( 'rest_api_init', 'gcms_api_delete_user' ); 
function gcms_api_delete_user() {
    register_rest_route( 'gcms/v1', '/deleteUser', array(
        'methods' => 'POST',
        'callback' => 'gcms_api_delete_user_callback',
        'permission_callback' => function () {
            return current_user_can( 'remove_users' );
        }
    ));
}

function gcms_api_delete_user_callback($data) {
    $parameters = $data->get_params();

    $site_id    = $parameters['siteID'];
    $user_id    = $parameters['id'];

    gcms_api_base_check($site_id, [$user_id]);

    // We need to generate the user object before deletion
    $data = gcms_get_user_by_id($user_id);

    if(is_multisite()){
        $site = get_blog_details($site_id);
        remove_user_from_blog($user_id, $site->blog_id);

    }else {
        wp_delete_user($user_id);
    }

    return $data;
}

?>