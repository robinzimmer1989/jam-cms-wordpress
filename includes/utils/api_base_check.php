<?php

function jam_cms_api_base_check($parameters, $required_args = []){

  if(is_multisite()){

    if(!array_key_exists('siteID', $parameters)) {
      return new WP_Error( 'no_site_id', __('No site ID'), array( 'status' => 400 ) );
    }

    $site_id = $parameters['siteID'];

    $site = get_blog_details($site_id);

    if(!$site){
      return new WP_Error( 'invalid_site_id', __('Invalid site ID'), array( 'status' => 400 ) );
    }

    switch_to_blog($site->blog_id);
  }

  foreach($required_args as $arg){
    if(!array_key_exists($arg, $parameters)){
      return new WP_Error( 'rest_upload_no_data', __( 'No data supplied' ), array( 'status' => 400 ));
    }
  }
}

?>