<?php

add_action( 'rest_api_init', 'jam_cms_api_get_preview_link' ); 
function jam_cms_api_get_preview_link() {
  register_rest_route( 'jamcms/v1', '/getPreviewLink', array(
    'methods' => 'POST',
    'callback' => 'jam_cms_api_get_preview_link_callback',
    'permission_callback' => function () {
      return current_user_can( 'edit_posts' );
    }
  ));
}

function jam_cms_api_get_preview_link_callback($data) {
  $parameters = $data->get_params();

  $check = jam_cms_api_base_check($parameters, ['postID', 'expiryDate']);

  if(is_wp_error($check)){
    return $check;
  }

  $post_id = $parameters['postID'];

  $expiryDate = $parameters['expiryDate'];

  // We need to manually create an auto-save post revision so our function works in the same way as regular WordPress previews.
  $overrides = [
    'post_status' => 'inherit',
    'post_name'   => "{$post_id}-autosave-v1",
    'post_parent' => $post_id,
    'post_type'   => 'revision'
  ];

  $revision = jam_cms_duplicate_post($post_id, $overrides, true);

  if($revision){

    // Generate encrypted preview key
    $preview_key = jam_cms_generate_preview_key($revision['id'], "{$expiryDate} hours");

    $preview_link = jam_cms_generate_preview_link($preview_key);

    return $preview_link;
  }
}