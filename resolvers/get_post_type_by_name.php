<?php

function gcms_get_post_type_by_name($site_id, $name){

  $post_types = get_post_types([], 'objects');
  $custom_post_types = get_option('cptui_post_types') ? get_option('cptui_post_types') : [];
  $all_post_types = array_merge($post_types, $custom_post_types);

  $post_type = $all_post_types['name'];

  if($post_type){
    return gcms_format_post_type($site_id, $post_type);
  }

  return false;
}

?>