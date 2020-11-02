<?php

add_action( 'rest_api_init', 'gcms_api_delete_site' ); 
function gcms_api_delete_site() {
    register_rest_route( 'gcms/v1', '/deleteSite', array(
        'methods' => 'GET',
        'callback' => 'gcms_api_delete_site_callback'
    ));
}

function gcms_api_delete_site_callback($data) {
    $site_id = $data->get_param('id');

    if($site_id){
      
    }

    return null;
}

?>