<?php

add_action( 'rest_api_init', 'jam_cms_api_get_post_preview' ); 
function jam_cms_api_get_post_preview() {
  register_rest_route( 'jamcms/v1', '/getPostPreview', array(
    'methods' => 'POST',
    'callback' => 'jam_cms_api_get_post_preview_callback',
    'permission_callback' => function () {
      return true;
    }
  ));
}

function jam_cms_api_get_post_preview_callback($data) {
  $parameters = $data->get_params();

  $post_id = jam_cms_api_base_check($parameters, ['siteID', 'previewID']);

  if(is_wp_error($post_id)){
    return $post_id;
  }

  // A preview is created as an auto-save revision in WP.
  $revisions = wp_get_post_revisions($post_id);
  
  if(count($revisions) > 0){
    // That's why we wanna get the id of the last revision
    $post_id = array_shift($revisions);
  }

  $post = jam_cms_get_post_by_id($post_id);

  return $post;
}