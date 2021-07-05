<?php

function jam_cms_get_site_for_build_by_id(){

  // Get themeOptions and only return id-value pairing
  $formatted_options = (object) [];
  $themeOptions = jam_cms_get_option_group_fields();
  if($themeOptions){
    foreach($themeOptions as $option){
      $option_id = $option['id'];
      $formatted_options->$option_id = $option['value'];
    }
  }

  $data = [
    'frontPage'     => intval(get_option( 'page_on_front' )),
    'siteTitle'     => get_bloginfo('name'),
    'themeOptions'  => $formatted_options
  ];

  return $data;
}