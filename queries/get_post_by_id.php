<?php

function gcms_get_post_by_id($site_id, $post_id){

  $post = get_post($post_id);

  if($post){
    $formatted_post = gcms_format_post($site_id, $post);

    // Get flexible content fields and format
    $content = get_fields($post_id);

    if($content && array_key_exists('flex', $content)){
      $modules = gcms_get_flexible_content_blocks($content['flex']);
    }else {
      $modules = gcms_get_template_field_groups_by_post_type_name($post->post_type, $content);
    }

    $formatted_post['content'] = $modules;

    return $formatted_post;
  }

  return 'Post not found';

}

?>