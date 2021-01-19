<?php

add_action( 'rest_api_init', 'jam_cms_api_update_taxonomy' ); 
function jam_cms_api_update_taxonomy() {
  register_rest_route( 'jamcms/v1', '/updateTaxonomy', array(
    'methods' => 'POST',
    'callback' => 'jam_cms_api_update_taxonomy_callback',
    'permission_callback' => function () {
      return current_user_can( 'manage_options' );
    }
  ));
}

function jam_cms_api_update_taxonomy_callback($data) {
    $parameters       = $data->get_params();

    $site_id          = $parameters['siteID'];
    $id               = $parameters['id'];
    $title            = $parameters['title'];
    $slug             = $parameters['slug'];
    $postTypes        = $parameters['postTypes'];

    jam_cms_api_base_check($site_id, [$id, $title, $slug]);

    $taxonomies = get_option('cptui_taxonomies');
    $taxonomies = $taxonomies ? $taxonomies : [];

    if($taxonomies[$id]){

        $taxonomies[$id]['label']           = $title;
        $taxonomies[$id]['singular_label']  = $title;
        $taxonomies[$id]['rewrite_slug']    = $slug ? $slug : '/';
        $taxonomies[$id]['object_types']    = $postTypes ? json_decode($postTypes) : [];

        update_option('cptui_taxonomies', $taxonomies);   
    }
    
    $taxonomy = jam_cms_format_taxonomy($taxonomies[$id]);

    return $taxonomy;
}

?>