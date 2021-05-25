<?php

function jam_cms_generate_preview_key($post_id, $expiry_time = '+ 2 hours'){

  // The preview keys array contains all currently valid preview keys
  $preview_keys = get_option('jam-cms-preview-keys');

  if(!$preview_keys){
    $preview_keys = [];
  }

  $now = time();

  // Every time we run this function, it automatically cleans up expired preview keys first
  $cleaned_preview_keys = [];
  
  foreach($preview_keys as $value){
    if($value['expiry_date'] > $now){
      array_push($cleaned_preview_keys, $value);
    }
  }

  // Generate new preview key
  $new_key = [
    'id'          => wp_generate_uuid4(),
    'post_id'     => $post_id,
    'expiry_date' => strtotime($expiry_time)
  ];

  array_push($cleaned_preview_keys, $new_key);

  update_option('jam-cms-preview-keys', $cleaned_preview_keys);

  return $new_key;
}