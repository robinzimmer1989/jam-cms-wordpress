<?php

function jam_cms_create_revision($post_id){

  $post = get_post($post_id);

  // Check if revisions are enabled first
  if(wp_revisions_enabled($post)){

    $overrides = [
      'post_status' => 'inherit',
      'post_name'   => "{$post_id}-revision-v1",
      'post_parent' => $post_id,
      'post_type'   => 'revision'
    ];

    jam_cms_duplicate_post($post_id, $overrides, true);

  }
}