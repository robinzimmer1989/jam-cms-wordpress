<?php

add_action( 'rest_api_init', 'gcms_api_update_site' ); 
function gcms_api_update_site() {
    register_rest_route( 'gcms/v1', '/updateSite', array(
        'methods' => 'GET',
        'callback' => 'gcms_api_update_site_callback'
    ));
}

function gcms_api_update_site_callback($data) {
    $site_id = $data->get_param('siteID');

    if($site_id){
      $data = gcms_update_site_by_id($site_id);
      return $data;
    }

    return null;
}

?>