<?php

add_action( 'rest_api_init', 'jam_cms_api_create_taxonomy' ); 
function jam_cms_api_create_taxonomy() {
    register_rest_route( 'jamcms/v1', '/createTaxonomy', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_create_taxonomy_callback',
        'permission_callback' => function () {
            return current_user_can( 'manage_options' );
        }
    ));
}

function jam_cms_api_create_taxonomy_callback($data) {
    $parameters = $data->get_params();

    $site_id    = $parameters['siteID'];
    $id         = $parameters['id'];
    $title      = $parameters['title'];
    $slug       = $parameters['slug'];
    $postTypes  = $parameters['postTypes'];

    jam_cms_api_base_check($site_id, [$title, $id]);

    $taxonomies = get_option('cptui_taxonomies');
    $taxonomies = $taxonomies ? $taxonomies : [];

    if(!array_key_exists($id, $taxonomies)){

        $taxonomies[$id] = [
            'name'                  => $id,
            'label'                 => $title,
            'singular_label'        => $title,
            'labels'                => [],
            'description'           => '',
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_nav_menus'     => true,
            'has_archive'           => false,
            'show_in_menu'          => true,
            'delete_with_user'      => false,
            'show_in_rest'          => false,
            'rest_base'             => '',
            'rest_controller_class' => '',
            'exclude_from_search'   => false,
            'hierarchical'          => true,
            'rewrite'               => true,
            'rewrite_withfront'     => false,
            'rewrite_slug'          => $slug ? $slug : '/',
            'query_var'             => true,
            'object_types'          => $postTypes ? json_decode($postTypes) : []
        ];

        update_option('cptui_taxonomies', $taxonomies);

        $taxonomy = jam_cms_format_taxonomy($taxonomies[$id]);

        return $taxonomy;
    }
}

?>