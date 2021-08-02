<?php

add_action( 'rest_api_init', 'jam_cms_api_update_term' ); 
function jam_cms_api_update_term() {
  register_rest_route( 'jamcms/v1', '/updateTerm', array(
    'methods' => 'POST',
    'callback' => 'jam_cms_api_update_term_callback',
    'permission_callback' => function () {
      return current_user_can( 'manage_options' );
    }
  ));
}

function jam_cms_api_update_term_callback($data) {
  $parameters = $data->get_params();

  $check = jam_cms_api_base_check($parameters, ['taxonomyID', 'id', 'title', 'slug', 'parentID']);

  if(is_wp_error($check)){
    return $check;
  }

  $updated_term = wp_update_term($parameters['id'], $parameters['taxonomyID'], [
    'name'        => $parameters['title'],
    'parent'      => $parameters['parentID'],
    'slug'        => $parameters['slug'],
    'description' => array_key_exists('description', $parameters) ? $parameters['description'] : '',
  ]);

  if(is_wp_error($updated_term)){
    return $updated_term;
  }

  if(array_key_exists('language', $parameters)){
    pll_set_term_language($updated_term['term_id'], $parameters['language']);
  }

  if($updated_term){
    $term = get_term($updated_term['term_id']);
    $formatted_term = jam_cms_format_term($term);

    return $formatted_term;
  }
}