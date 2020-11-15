<?php

add_action( 'rest_api_init', 'gcms_api_delete_site' ); 
function gcms_api_delete_site() {
    register_rest_route( 'gcms/v1', '/deleteSite', array(
        'methods' => 'POST',
        'callback' => 'gcms_api_delete_site_callback'
    ));
}

function gcms_api_delete_site_callback($data) {
    $parameters = $data->get_params();

    $site_id = $parameters['id'];

    if($site_id){
      
    }

    return null;
}

?>