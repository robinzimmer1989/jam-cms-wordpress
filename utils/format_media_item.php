<?php

function gcms_format_media_item($site_id, $media_item) {

  $meta_data = wp_get_attachment_metadata($media_item->ID);

  $formatted_media_item = [
    'id' => $media_item->ID,
    'siteID' => $site_id,
    'title' => $media_item->post_title,
    'src' => wp_get_attachment_image_src($media_item->ID, 'large')[0],
    'altText' => get_post_meta($media_item->ID, '_wp_attachment_image_alt', true),
    'width' => $meta_data['width'],
    'height' => $meta_data['height'],
  ];

  return $formatted_media_item;
  
}

?>