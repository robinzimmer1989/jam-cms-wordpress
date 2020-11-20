<?php

add_action( 'rest_api_init', 'gcms_api_get_site' ); 
function gcms_api_get_site() {
    register_rest_route( 'gcms/v1', '/getSite', array(
        'methods' => 'POST',
        'callback' => 'gcms_api_get_site_callback',
        'permission_callback' => function () {
            return current_user_can( 'edit_posts' );
        }
    ));
}

function gcms_api_get_site_callback($data) {
    $parameters = $data->get_params();

    $site_id    = $parameters['siteID'];

    gcms_api_base_check($site_id);

    $data = gcms_get_site_by_id($site_id);

    return $data;
}

?>