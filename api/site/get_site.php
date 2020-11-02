<?php

add_action( 'rest_api_init', 'gcms_api_get_site' ); 
function gcms_api_get_site() {
    register_rest_route( 'gcms/v1', '/getSite', array(
        'methods' => 'GET',
        'callback' => 'gcms_api_get_site_callback'
    ));
}

function gcms_api_get_site_callback($data) {
    $site_id = $data->get_param('siteID');

    if($site_id){
      $data = gcms_get_site_by_id($site_id);
      return $data;
    }

    return null;
}

?>