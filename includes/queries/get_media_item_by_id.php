<?php

function jam_cms_get_media_item_by_id($media_item_id){

  $media_item = get_post($media_item_id);
  $formatted_media_item = jam_cms_format_media_item($media_item);

  return $formatted_media_item;
}