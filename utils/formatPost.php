<?php

function gcms_formatPost($siteID, $post) {

  $formattedPost = [
    'id' => $post->ID,
    'siteID' => $siteID,
    'title' => $post->post_title,
    'slug' => $post->post_name,
    'postTypeID' => $post->post_type,
    'parentID' => $post->post_parent,
    'status' => $post->post_status,
    'featuredImage' => null,
    'content' => [],
    'createdAt' => $post->post_date,
  ];

  return $formattedPost;
  
}

?>