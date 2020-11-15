<?php

add_action( 'rest_api_init', 'gcms_api_get_site_for_build' ); 
function gcms_api_get_site_for_build() {
    register_rest_route( 'gcms/v1', '/getBuildSite', array(
        'methods' => 'POST',
        'callback' => 'gcms_api_get_site_for_build_callback'
    ));
}

function gcms_api_get_site_for_build_callback($data) {
    $parameters = $data->get_params();
    
    $site_id = $parameters['siteID'];
    $api_key = $parameters['apiKey'];

    // if($api_key){
    //   return false;
    // }

    if($site_id){
      $data = gcms_get_site_for_build_by_id($site_id);
      return $data;
    }

    return null;
}

?>