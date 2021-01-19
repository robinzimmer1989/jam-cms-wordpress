<?php

add_action( 'rest_api_init', 'jam_cms_api_delete_taxonomy' ); 
function jam_cms_api_delete_taxonomy() {
  register_rest_route( 'jamcms/v1', '/deleteTaxonomy', array(
    'methods' => 'POST',
    'callback' => 'jam_cms_api_delete_taxonomy_callback',
    'permission_callback' => function () {
      return current_user_can( 'manage_options' );
    }
  ));
}

function jam_cms_api_delete_taxonomy_callback($data) {
    $parameters   = $data->get_params();

    $site_id      = $parameters['siteID'];
    $taxonomy_id  = $parameters['id'];

    jam_cms_api_base_check($site_id, [$taxonomy_id]);

    $taxonomies = get_option('cptui_taxonomies');
    $taxonomies = $taxonomies ? $taxonomies : [];

    $taxonomy = $taxonomies[$taxonomy_id];

    if($taxonomy){
      unset($taxonomies[$taxonomy_id]);
      update_option('cptui_taxonomies', $taxonomies);

      $taxonomy = jam_cms_format_taxonomy($taxonomy);
      return $taxonomy;
    }
}

?>