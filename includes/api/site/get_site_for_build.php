<?php

add_action( 'rest_api_init', 'jam_cms_api_get_site_for_build' ); 
function jam_cms_api_get_site_for_build() {
    register_rest_route( 'jamcms/v1', '/getBuildSite', array(
        'methods' => 'GET',
        'callback' => 'jam_cms_api_get_site_for_build_callback',
        'permission_callback' => function () {
            return true;
        }
    ));
}

function jam_cms_api_get_site_for_build_callback($data) {
    $parameters = $data->get_params();
  
    $check = jam_cms_api_base_check($parameters, ['apiKey']);

    if(is_wp_error($check)){
        return $check;
    }
    
    $data = jam_cms_get_site_for_build_by_id();
    
    return $data;
}