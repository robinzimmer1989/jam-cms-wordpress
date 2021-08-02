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

    $check = jam_cms_api_base_check($parameters, ['taxonomyID', 'title', 'slug', 'parentID']);

    if(is_wp_error($check)){
        return $check;
    }

    $new_term = wp_insert_term($parameters['title'], $parameters['taxonomyID'], [
        'parent'      => $parameters['parentID'],
        'slug'        => $parameters['slug'],
        'description' => array_key_exists('description', $parameters) ? $parameters['description'] : ''
    ]);

    if(is_wp_error($new_term)){
        return $new_term;
    }

    if(array_key_exists('language', $parameters)){
        pll_set_term_language($new_term['term_id'], $parameters['language']);
    }

    $term = get_term($new_term['term_id']);
    $formatted_term = jam_cms_format_term($term);

    return $formatted_term;
}