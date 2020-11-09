<?php

add_action( 'rest_api_init', 'gcms_api_get_site_for_build' ); 
function gcms_api_get_site_for_build() {
    register_rest_route( 'gcms/v1', '/getBuildSite', array(
        'methods' => 'GET',
        'callback' => 'gcms_api_get_site_for_build_callback'
    ));
}

function gcms_api_get_site_for_build_callback($data) {
    $site_id = $data->get_param('siteID');
    $api_key = $data->get_param('apiKey');

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