<?php

function jam_cms_get_site_for_build_by_id(){

  // Get globalOptions and only return id-value pairing
  $formatted_options = (object) [];
  $globalOptions = jam_cms_get_option_group_fields();
  if($globalOptions){
    foreach($globalOptions as $option){
      $option_id = $option['id'];
      $formatted_options->$option_id = $option['value'];
    }
  }

  $data = array(
    'globalOptions'  => $formatted_options
  );

  return $data;
}

?>