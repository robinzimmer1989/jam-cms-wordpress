<?php

add_action( 'rest_api_init', 'jam_cms_api_get_site' ); 
function jam_cms_api_get_site() {
    register_rest_route( 'jamcms/v1', '/getSite', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_get_site_callback',
        'permission_callback' => function () {
            return current_user_can( 'read' );
        }
    ));
}

function jam_cms_api_get_site_callback($data) {
    $parameters = $data->get_params();

    $site_id    = $parameters['siteID'];

    jam_cms_api_base_check($site_id);

    $data = jam_cms_get_site_by_id($site_id);

    return $data;
}

?>