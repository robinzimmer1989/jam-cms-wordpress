<?php

function gcms_get_site_by_id($site_id){
 
  $site = get_blog_details($site_id);

  if($site){
    switch_to_blog($site->blog_id);

    $settings = get_blog_option($site->blog_id, 'gcms_custom_plugin_options');

    // Get 'real' post types and add posts
    $all_post_types = get_post_types([], 'objects');

    $items = [];
    foreach ( $all_post_types as $post_type ) {
      if ($post_type->publicly_queryable && $post_type->name != 'attachment') {

          $posts = get_posts(array(
            'numberposts' => -1,
            'post_type' => $post_type->name,
            'post_status' => ['publish', 'draft', 'trash']
          ));

          $formatted_posts = [];
          foreach($posts as $post){
            array_push($formatted_posts, gcms_format_post($site_id, $post));
          }

          array_push($items, [
            'id' => $post_type->name,
            'slug' => $post_type->name,
            'title' => $post_type->label,
            'template' => null,
            'posts' => [
              'items' => $formatted_posts
            ],
          ]);
      }
    }

    $header_fields = get_field('theme-header', 'option');
    $formatted_header_fields = [];
    foreach($header_fields as $key => $value){
      array_push($formatted_header_fields, [
        'id' => $key,
        'value' => $value
      ]);
    }

    $footer = get_field('theme-footer', 'option');

    $data = array(
      'id' => $site_id,
      'title' => $site->blogname,
      'netlifyID' =>  $settings['netlify_id'],
      'netlifyUrl' => $settings['netlify_url'],
      'settings' => [
        'header' => [
          'name' => 'header',
          'fields' => $formatted_header_fields
        ]
      ],
      'postTypes' => [
        'items' => $items
      ],
      'forms' => [
        'items' => []
      ]
    );

    return $data;
  }

  return null;

}

?>