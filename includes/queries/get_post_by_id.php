<?php

function jam_cms_get_post_by_id($site_id, $post_id){

  $post = get_post($post_id);

  if($post){
    $formatted_post = jam_cms_format_post($site_id, $post); 

    $content = get_fields($post_id);
    if($content){
      $formatted_post['content'] = jam_cms_get_flexible_content_blocks($content['flex']);
    }

    $formatted_post['seo'] = [
      'title'       => get_post_meta($post->ID, '_yoast_wpseo_title'),
      'description' => get_post_meta($post->ID, '_yoast_wpseo_metadesc')
    ];

    return $formatted_post;
  }

  return new WP_Error( 'post_not_found', __('Post not found'), array( 'status' => 400 ) );
}

?>