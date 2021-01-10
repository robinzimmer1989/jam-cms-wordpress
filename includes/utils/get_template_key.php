<?php

function jam_cms_get_template_key($post_id){

  $post_type = get_post_type($post_id);

  // Get template file. WP return empty string if default template.
  $template = get_page_template_slug($post_id);
  
  if(!$template){
    $template = "default";
  }

  return "{$post_type}-{$template}";
}

?>