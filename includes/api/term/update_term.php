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

  $site_id     = $parameters['siteID'];
  $taxonomy_id = $parameters['taxonomyID'];
  $id          = $parameters['id'];
  $title       = $parameters['title'];
  $slug        = $parameters['slug'];
  $parent_id   = $parameters['parentID'];
  $description = $parameters['description'];

  jam_cms_api_base_check($site_id, [$taxonomy_id, $title, $id]);

  $updated_term = wp_update_term($id, $taxonomy_id, [
    'name'        => $title,
    'description' => $description,
    'parent'      => $parent_id,
    'slug'        => $slug 
  ]);

  if($updated_term){
    $term = get_term($updated_term['term_id']);
    $formatted_term = jam_cms_format_term($term);

    return $formatted_term;
  }
}

?>