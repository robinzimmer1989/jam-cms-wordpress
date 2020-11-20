<?php

add_action( 'rest_api_init', 'gcms_api_get_site' ); 
function gcms_api_get_site() {
    register_rest_route( 'gcms/v1', '/getSite', array(
        'methods' => 'POST',
        'callback' => 'gcms_api_get_site_callback'
    ));
}

function gcms_api_get_site_callback($data) {
    $parameters = $data->get_params();

    $site_id = $parameters['siteID'];

    if($site_id){
      $data = gcms_get_site_by_id($site_id);
      return $data;
    }

    return null;
}

?>