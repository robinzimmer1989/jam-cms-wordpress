<?php

function jam_cms_create_post_type($post_type) {

  if(!is_object($post_type) || !property_exists($post_type, 'id') || !property_exists($post_type, 'title')){
    return;
  }

  $id     = $post_type->id;
  $title  = $post_type->title;

  $cpt_ui = get_option('cptui_post_types');
  $post_types = $cpt_ui ? $cpt_ui : [];

  if($id !== 'page' && $id !== 'post' && !array_key_exists($id, $post_types)){

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
      'capability_type'       => 'post',
      'hierarchical'          => true,
      'rewrite'               => true,
      'rewrite_withfront'     => false,
      'rewrite_slug'          => '/',
      'supports'              => ['title', 'thumbnail'],
      'taxonomies'            => [],
      'query_var'             => true,
      'show_in_graphql'       => true,
      'graphql_single_name'   => "{$id}",
      'graphql_plural_name'   => "{$id}Multiple",
    ];

    $options = property_exists($post_type, 'options') ? (array) $post_type->options : [];

    $post_types[$id] = array_merge($defaults, $options);

    update_option('cptui_post_types', $post_types);
  }
}