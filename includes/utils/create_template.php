<?php

function jam_cms_create_template($template){
  $theme_templates = get_option('jam-cms-templates');

  if(!$theme_templates){
    $theme_templates = [];
  }
  
  if(!array_key_exists("{$template->id}", $theme_templates) && $template->id != 'default'){
    $label = $template->label ? $template->label : $template->id;
    $theme_templates["{$template->id}"] = $label;  
  }
  
  update_option('jam-cms-templates', $theme_templates);
}

?>