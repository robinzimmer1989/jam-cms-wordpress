<?php

add_action( 'rest_api_init', 'jam_cms_api_get_site_for_build' ); 
function jam_cms_api_get_site_for_build() {
    register_rest_route( 'jamcms/v1', '/getBuildSite', array(
        'methods' => 'GET',
        'callback' => 'jam_cms_api_get_site_for_build_callback'
    ));
}

function jam_cms_api_get_site_for_build_callback($data) {
    $site_id = $data->get_param('siteID');
    $api_key = $data->get_param('apiKey');

    jam_cms_api_base_check($site_id, [$api_key]);

    $api_key_db = get_option('deployment_api_key');

    if(
        !isset($api_key_db) ||
        !$api_key_db ||
        !isset($api_key) ||
        !$api_key || 
        $api_key != $api_key_db
    ){
        return new WP_Error( 'rest_incorrect_api_key', __( 'Api key incorrect.' ), array( 'status' => 403 ));
    }

    $data = jam_cms_get_site_for_build_by_id($site_id);
    
    return $data;
}

?>