<?php

add_action( 'rest_api_init', 'jam_cms_api_delete_site' ); 
function jam_cms_api_delete_site() {
    register_rest_route( 'jamcms/v1', '/deleteSite', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_delete_site_callback',
        'permission_callback' => function () {
            return current_user_can( 'delete_sites' );
        }
    ));
}

function jam_cms_api_delete_site_callback($data) {
    $parameters = $data->get_params();

    $check = jam_cms_api_base_check($parameters, ['id']);

    if(is_wp_error($check)){
        return $check;
    }

    $site_id    = $parameters['id'];

    // TODO: Needs implementation
}