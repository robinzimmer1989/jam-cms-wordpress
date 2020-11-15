<?php

function gcms_format_post($site_id, $post) {

  $thumbnail_id = get_post_thumbnail_id($post->ID);
  $formatted_media_item = null;
  
  if($thumbnail_id){
    $media_item = acf_get_attachment($thumbnail_id);
    $formatted_media_item = gcms_format_acf_field_value_for_frontend(['type' => 'image'], $media_item);
  }

  $formatted_post = [
    'id'              => $post->ID,
    'siteID'          => $site_id,
    'title'           => $post->post_title,
    'slug'            => $post->post_name,
    'postTypeID'      => $post->post_type,
    'parentID'        => $post->post_parent,
    'status'          => $post->post_status,
    'featuredImage'   => $formatted_media_item,
    'content'         => [],
    'seoTitle'        => get_post_meta($post->ID, '_yoast_wpseo_title'),
    'seoDescription'  => get_post_meta($post->ID, '_yoast_wpseo_metadesc'),
    'createdAt'       => $post->post_date,
  ];

  return $formatted_post;
  
}

?>