<?php

function jam_cms_format_post_type($site_id, $post_type) {
  // Custom post types are constructed as an array so we have to convert them
  $post_type = (object) $post_type;

  $posts = get_posts(array(
    'numberposts' => -1,
    'post_type' => $post_type->name,
    'post_status' => ['publish', 'draft', 'trash']
  ));

  $formatted_posts = [];
  foreach($posts as $post){
    array_push($formatted_posts, jam_cms_format_post($site_id, $post));
  }

  $formatted_post_type = [
    'siteID' => $site_id,
    'id' => $post_type->name,
    'slug' => property_exists($post_type, 'rewrite_slug') ? $post_type->rewrite_slug : '',
    'title' => $post_type->label,
    'template' => jam_cms_get_template_field_groups_by_post_type_name($post_type->name),
    'posts' => [
      'items' => $formatted_posts
    ],
  ];

  return $formatted_post_type;
  
}

?>