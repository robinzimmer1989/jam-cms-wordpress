<?php

add_action( 'rest_api_init', 'jam_cms_api_create_post_type' ); 
function jam_cms_api_create_post_type() {
    register_rest_route( 'jamcms/v1', '/createCollection', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_create_post_type_callback',
        'permission_callback' => function () {
            return current_user_can( 'manage_options' );
        }
    ));
}

function jam_cms_api_create_post_type_callback($data) {
    $parameters = $data->get_params();

    $site_id    = $parameters['siteID'];
    $id         = $parameters['id'];
    $title      = $parameters['title'];
    $slug       = $parameters['slug'];

    jam_cms_api_base_check($site_id, [$title, $id]);

    $cpt_ui = get_option('cptui_post_types');
    $post_types = $cpt_ui ? $cpt_ui : [];

    if($title !== 'Pages' && $id !== 'page' && !array_key_exists($id, $post_types)){

        $post_types[$id] = [
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
            'capability_type'       => 'post',
            'hierarchical'          => true,
            'rewrite'               => true,
            'rewrite_withfront'     => false,
            'rewrite_slug'          => $slug,
            'supports'              => ['title', 'thumbnail'],
            'taxonomies'            => [],
            'query_var'             => true
        ];

        update_option('cptui_post_types', $post_types);

        $post_type = jam_cms_format_post_type($post_types[$id]);

        return $post_type;
    }
}

?>