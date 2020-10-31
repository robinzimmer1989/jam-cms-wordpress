<?php

add_action( 'rest_api_init', 'gcms_actions_getSite' ); 
function gcms_actions_getSite() {
    register_rest_route( 'wp/v2', '/getSite', array(
        'methods' => 'GET',
        'callback' => 'gcms_actions_getSite_callback'
    ));
}

function gcms_actions_getSite_callback($data) {
    $siteID = $data->get_param('siteID');

    if($siteID){
      $data = gcms_resolver_getSiteByID($siteID);
      return $data;
    }

    return null;
}

?>