<?php

function gcms_format_media_item($site_id, $media_item) {
  $media_item = acf_get_attachment($media_item);
  $formatted_media_item = gcms_format_acf_field_value_for_frontend(['type' => 'image'], $media_item);

  $formatted_media_item['siteID'] = $site_id;

  return $formatted_media_item;
}

?>