<?php

add_action( 'rest_api_init', 'gcms_api_get_sites' ); 
function gcms_api_get_sites() {
    register_rest_route( 'gcms/v1', '/getSites', array(
        'methods' => 'POST',
        'callback' => 'gcms_api_get_sites_callback'
    ));
}

function gcms_api_get_sites_callback($data) {
    $user_id = get_current_user_id();

    if($user_id){
      $data = gcms_get_sites_by_user_id($user_id);
      return $data;
    }

    return null;
}

?>