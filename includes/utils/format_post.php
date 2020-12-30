<?php

function jam_cms_format_post($site_id, $post) {

  $thumbnail_id = get_post_thumbnail_id($post->ID);
  $formatted_media_item = null;
  
  if($thumbnail_id){
    $media_item = acf_get_attachment($thumbnail_id);
    $formatted_media_item = jam_cms_format_acf_field_value_for_frontend(['type' => 'image'], $media_item);
  }

  $slug = $post->post_name;

  // Remove "_trashed" affix added by WordPress
  if($post->post_status == 'trash'){
    $slug = str_replace('__trashed', '', $slug);
  }

  // Get template file. WP return empty string if default template.
  $template = get_page_template_slug($post->ID);
  if(!$template){
    $template = "default";
  }

  $formatted_post = [
    'id'              => $post->ID,
    'siteID'          => $site_id,
    'title'           => $post->post_title,
    'slug'            => $slug,
    'postTypeID'      => $post->post_type,
    'parentID'        => $post->post_parent,
    'status'          => $post->post_status,
    'featuredImage'   => $formatted_media_item,
    'template'        => $template,
    'content'         => (object) [],
    'seo'             => [],
    'createdAt'       => $post->post_date,
  ];

  return $formatted_post;
  
}

?>