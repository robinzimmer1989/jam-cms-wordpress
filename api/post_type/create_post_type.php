<?php

add_action( 'rest_api_init', 'gcms_api_create_post_type' ); 
function gcms_api_create_post_type() {
    register_rest_route( 'gcms/v1', '/createCollection', array(
        'methods' => 'GET',
        'callback' => 'gcms_api_create_post_type_callback'
    ));
}

function gcms_api_create_post_type_callback($data) {
    $site_id = $data->get_param('siteID');
    $title = $data->get_param('title');
    $slug = $data->get_param('slug');

    $site = get_blog_details($site_id);

    if($site && $title){
        switch_to_blog($site->blog_id);

        $cpt_ui = get_option('cptui_post_types');
        $post_types = $cpt_ui ? $cpt_ui : [];

        if($title !== 'Pages' && $slug !== 'page' && !array_key_exists($slug, $post_types)){

            $name = gcms_generate_id();

            $post_types[$name] = [
                'name'                  => $name,
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

            // Create template and assign flexible content as default
            gcms_add_acf_template($title, $name);

            return gcms_format_post_type($site_id, $post_types[$name]);
        }
    }

    return null;
}

?>