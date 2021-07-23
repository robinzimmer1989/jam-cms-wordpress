<?php

function jam_cms_format_post_type($post_type) {
  // Custom post types are constructed as an array so we have to convert them
  $post_type = (object) $post_type;

  // Get all posts
  $posts = get_posts([
    'numberposts' => -1,
    'post_type'   => $post_type->name,
    'post_status' => ['publish', 'draft', 'private', 'trash'],
    'orderby'     => 'menu_order'
  ]);

  // Format posts
  $formatted_posts = [];
  foreach($posts as $post){
    array_push($formatted_posts, jam_cms_format_post($post));
  }

  $formatted_post_type = [
    'id'          => $post_type->name,
    'slug'        => property_exists($post_type, 'rewrite_slug') ? $post_type->rewrite_slug : '',
    'title'       => $post_type->label,
    'posts'       => [
      'items'       => $formatted_posts,
    ],
  ];

  return $formatted_post_type;
  
}