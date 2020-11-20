<?php

function gcms_get_media_items($site_id, $limit = 10, $page = 0){

  $media_items = get_posts(array(
    'post_type' => 'attachment',
    'numberposts' => $limit,
    'offset' => $page * $limit
  ));

  $formatted_media_items = [];
  foreach($media_items as $media_item){
    array_push($formatted_media_items, gcms_format_media_item($site_id, $media_item));
  }

  return $formatted_media_items;
}

?>