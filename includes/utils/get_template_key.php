<?php

function jam_cms_get_template_key($post_id, $prefix = true){

  $post_type = get_post_type($post_id);

  // Get template file. WP returns empty string default template is active.
  $template = get_page_template_slug($post_id);
  
  if($template){
    // Manually created templates are stored in the format 'template-name.php'.
    // So we need to extract the actual title here.
    $template = str_replace('template-', '', $template);
    $template = str_replace('.php', '', $template);
  }else{
    $template = "default";
  }

  return $prefix ? "{$post_type}-{$template}" : $template;
}

?>