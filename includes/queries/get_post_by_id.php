<?php

function jam_cms_get_post_by_id($site_id, $post_id){

  $post = get_post($post_id);

  if($post){
    $formatted_post = jam_cms_format_post($site_id, $post);

    // Get flexible content fields and format
    $content = get_fields($post_id);

    $template = jam_cms_get_template_by_post_id($post_id);

    // Check if assigned template is flexible content or not
    if($template && count($template) > 0 && $template[0]['name'] == 'flex'){
      $formatted_post['content'] = jam_cms_get_flexible_content_blocks($content['flex']);
    }else {
      $formatted_post['content'] = jam_cms_get_template_field_groups_by_post_type_name($post->post_type, $content);
    }

    return $formatted_post;
  }

  return new WP_Error( 'post_not_found', __('Post not found'), array( 'status' => 400 ) );
}

?>