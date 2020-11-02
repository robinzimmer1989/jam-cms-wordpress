<?php

function gcms_format_post($site_id, $post) {

  $formatted_post = [
    'id' => $post->ID,
    'siteID' => $site_id,
    'title' => $post->post_title,
    'slug' => $post->post_name,
    'postTypeID' => $post->post_type,
    'parentID' => $post->post_parent,
    'status' => $post->post_status,
    'featuredImage' => null,
    'content' => [],
    'template' => [],
    'createdAt' => $post->post_date,
  ];

  return $formatted_post;
  
}

?>