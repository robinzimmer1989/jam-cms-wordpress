<?php

add_action( 'rest_api_init', 'gcms_api_get_sites' ); 
function gcms_api_get_sites() {
    register_rest_route( 'gcms/v1', '/getSites', array(
        'methods' => 'POST',
        'callback' => 'gcms_api_get_sites_callback',
        'permission_callback' => function () {
            return current_user_can( 'edit_posts' );
        }
    ));
}

function gcms_api_get_sites_callback($data) {

    if(!is_multisite()){
        return new WP_Error( 'rest_upload_no_data', __( 'No data supplied' ), array( 'status' => 400 ));
    }

    $user_id = get_current_user_id();

    $data = gcms_get_sites_by_user_id($user_id);

    return $data;
}

?>