<?php

add_action( 'rest_api_init', 'jam_cms_api_create_term' ); 
function jam_cms_api_create_term() {
    register_rest_route( 'jamcms/v1', '/createTerm', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_create_term_callback',
        'permission_callback' => function () {
            return current_user_can( 'manage_options' );
        }
    ));
}

function jam_cms_api_create_term_callback($data) {
    $parameters = $data->get_params();

    $site_id     = $parameters['siteID'];
    $taxonomy_id = $parameters['taxonomyID'];
    $title       = $parameters['title'];
    $slug        = $parameters['slug'];
    $parent_id   = $parameters['parentID'];
    $description = $parameters['description'];

    jam_cms_api_base_check($site_id, [$taxonomy_id, $title]);

    $new_term = wp_insert_term($title, $taxonomy_id, [
        'description' => $description,
        'parent'      => $parent_id,
        'slug'        => $slug 
    ]);

    if($new_term){
        $term = get_term($new_term['term_id']);
        $formatted_term = jam_cms_format_term($term);

        return $formatted_term;
    }
}

?>