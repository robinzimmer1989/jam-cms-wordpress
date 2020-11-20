<?php

add_action( 'rest_api_init', 'gcms_api_delete_site' ); 
function gcms_api_delete_site() {
    register_rest_route( 'gcms/v1', '/deleteSite', array(
        'methods' => 'POST',
        'callback' => 'gcms_api_delete_site_callback',
        'permission_callback' => function () {
            return current_user_can( 'delete_sites' );
        }
    ));
}

function gcms_api_delete_site_callback($data) {
    $parameters = $data->get_params();

    $site_id    = $parameters['id'];

    gcms_api_base_check($site_id);

    // TODO: Needs implementation
}

?>