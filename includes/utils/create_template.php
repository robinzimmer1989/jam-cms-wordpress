<?php

function jam_cms_create_template($template){

  if(!property_exists($template, 'id') || !property_exists($template, 'postTypeID')){
    return;
  }

  // Don't store default page templates in here
  if($template->id == 'default'){
    return;
  }

  $jam_cms_templates = get_option('jam-cms-templates');

  if(!$jam_cms_templates){
    $jam_cms_templates = [
      'page' => []
    ];
  }
  
  $template_name = '';

  if($template->id == 'archive'){
    $template_name = "template-archive-{$template->postTypeID}.php";

    // Capitalize post type
    $post_type_id = ucfirst($template->postTypeID);

    // The template name follows the structure Template_ArchivePost
    $jam_cms_templates['page'][$template_name] = "Archive{$post_type_id}";
  }else{
    $template_name = "template-{$template->id}.php";
    $jam_cms_templates[$template->postTypeID][$template_name] = property_exists($template, 'label') ? $template->label : $template->id;
  }

  update_option('jam-cms-templates', $jam_cms_templates);
}