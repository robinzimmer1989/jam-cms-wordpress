<?php

function jam_cms_format_media_item($media_item) {
  $media_item = acf_get_attachment($media_item);

  $formatted_media_item = jam_cms_format_acf_field_value_for_frontend(['type' => $media_item['type']], $media_item);

  return $formatted_media_item;
}