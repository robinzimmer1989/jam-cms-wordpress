<?php

function jam_cms_get_media_items($limit, $page, $search, $allow){

  $media_items = get_posts(array(
    'post_type'       => 'attachment',
    'post_mime_type'  => is_array($allow) && count($allow) > 0 ? $allow : '',
    'numberposts'     => $limit,
    'offset'          => $page * $limit,
    's'               => $search
  ));

  $formatted_media_items = [];
  foreach($media_items as $media_item){
    array_push($formatted_media_items, jam_cms_format_media_item($media_item));
  }

  return $formatted_media_items;
}