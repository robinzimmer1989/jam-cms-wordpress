<?php   

// https://github.com/certainlyakey/page-templater

function jam_cms_custom_templates($plugin_templates) {

  $theme_templates = get_option('jam-cms-templates');

  if(!$theme_templates){
    $theme_templates = [];
  }

  $custom_templates = array_merge($plugin_templates, $theme_templates);

  return $custom_templates;
}

add_filter( 'pagetemplater_change_custom_templates_list', 'jam_cms_custom_templates' );

?>