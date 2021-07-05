<?php

function jam_cms_create_taxonomy($taxonomy) {
    
    if(!is_object($taxonomy) || !property_exists($taxonomy, 'id') || !property_exists($taxonomy, 'title')){
        return;
    }

    $id     = $taxonomy->id;
    $title  = $taxonomy->title;

    $taxonomies = get_option('cptui_taxonomies');
    $taxonomies = $taxonomies ? $taxonomies : [];

    if($id !== 'category' && $id !== 'post_tag' && !array_key_exists($id, $taxonomies)){

        $defaults = [
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
            'rewrite_slug'          => '/',
            'query_var'             => true,
            'object_types'          => property_exists($taxonomy, 'postTypes') ? $taxonomy->postTypes : [],
            'graphql_single_name'   => $id,
            'graphql_plural_name'   => $id
        ];

        $options = property_exists($taxonomy, 'options') ? (array) $taxonomy->options : [];

        $taxonomies[$id] = array_merge($defaults, $options);

        update_option('cptui_taxonomies', $taxonomies);
    }
}