<?php

function gcms_api_base_check($site_id, $required_args = []){

  if(is_multisite()){

    if(!isset($site_id) || !$site_id) {
      return new WP_Error( 'no_site_id', __('No site ID'), array( 'status' => 400 ) );
    }

    $site = get_blog_details($site_id);

    if(!$site){
      return new WP_Error( 'invalid_site_id', __('Invalid site ID'), array( 'status' => 400 ) );
    }

    switch_to_blog($site->blog_id);

  }

  foreach($required_args as $args){
    if(!isset($args) || !$args){
      return new WP_Error( 'rest_upload_no_data', __( 'No data supplied' ), array( 'status' => 400 ));
    }
  }

}

?>