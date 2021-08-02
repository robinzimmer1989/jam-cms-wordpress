<?php

add_action( 'rest_api_init', 'jam_cms_api_set_language_in_mass' ); 
function jam_cms_api_set_language_in_mass() {
  register_rest_route( 'jamcms/v1', '/setLanguageInMass', array(
      'methods' => 'POST',
      'callback' => 'jam_cms_api_set_language_in_mass_callback',
      'permission_callback' => function () {
          return current_user_can( 'edit_posts' );
      }
  ));
}

function jam_cms_api_set_language_in_mass_callback($data) {
  $parameters = $data->get_params();

  $check = jam_cms_api_base_check($parameters, ['type', 'ids', 'language'] );

  if(is_wp_error($check)){
    return $check;
  }

  if(!class_exists('PLL_Admin_Model')){
    return new WP_Error( 'polylang_required', __( 'Couln\'t find language plugin' ), array( 'status' => 400 ) );
  }

  // The 'set_language_in_mass' function isn't returning anything so we have to do some checks in advance to make sure the request succeeds

  // Check of type value is correct
  if($parameters['type'] !== 'post' && $parameters['type'] !== 'term'){
    return new WP_Error( 'invalid_parameters', __( 'Parameter type must be term or post' ), array( 'status' => 400 ) );
  }

  // Check if ids is an array
  $ids = json_decode($parameters['ids']);

  if(!is_array($ids)){
    return new WP_Error( 'invalid_parameters', __( 'Parameter ids must be an array of ids' ), array( 'status' => 400 ) );
  }

  $options = get_option('polylang');

  $model = new PLL_Admin_Model($options);
  
  $model->set_language_in_mass($parameters['type'], $ids, $parameters['language']);

  return true;
}