<?php

function jam_cms_api_base_check($parameters, $required_args = []){

  foreach($required_args as $arg){
    if(!array_key_exists($arg, $parameters)){
      return new WP_Error( 'missing_parameter', __( 'Parameter ' . $arg . ' is missing' ), array( 'status' => 400 ));
    }
  }

  if(in_array('apiKey', $required_args)){
    
    $api_key = $parameters['apiKey'];

    $settings = get_option('jam_cms_settings');
    $api_key_db = is_array($settings) && array_key_exists("admin_api_key", $settings) ? $settings['admin_api_key'] : '';

    if(
        !isset($api_key_db) ||
        !$api_key_db ||
        !$api_key || 
        $api_key != $api_key_db
    ){
        return new WP_Error( 'rest_incorrect_api_key', __( 'Api key incorrect.' ), array( 'status' => 403 ));
    }
  }

  // Set global variable so other functions can access it
  if(function_exists('pll_default_language')){

    if(array_key_exists('language', $parameters) && $parameters['language'] != false){
      $GLOBALS['language'] = $parameters['language'];
    }else{
      $GLOBALS['language'] = pll_default_language();
    }
  }

  if(in_array('previewID', $required_args)){

    $preview_keys = get_option('jam-cms-preview-keys');

    if(!$preview_keys){
      $preview_keys = [];
    }

    $now = time();

    foreach ($preview_keys as $value) {
      if (
        $value['id'] === $parameters['previewID'] && 
        $value['expiry_date'] > $now && 
        get_post($value['post_id'])
      ) {
        return $value['post_id'];
      }
    }

    return new WP_Error( 'invalid_preview_id', __( 'Invalid Preview ID' ), array( 'status' => 403 ));
  }

  // TODO: Re-enable for true multisite setup

  // if(is_multisite()){

  //   if(!array_key_exists('siteID', $parameters)) {
  //     return new WP_Error( 'no_site_id', __('No site ID'), array( 'status' => 400 ) );
  //   }

  //   $site_id = $parameters['siteID'];

  //   if($site_id != 'default'){
  //     $site = get_blog_details($site_id);

  //     if(!$site){
  //       return new WP_Error( 'invalid_site_id', __('Invalid site ID'), array( 'status' => 400 ) );
  //     }

  //     switch_to_blog($site->blog_id);
  //   }
  // }
}