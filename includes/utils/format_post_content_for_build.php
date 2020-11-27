<?php

function jam_cms_format_post_content_for_build($site_id, $modules){
  $formatted_modules = [];
  foreach($modules as $module){
    $fields = [];

    foreach($module['fields'] as $field){

      // Collection posts are added on the fly in development mode, but on build we have to add them manually
      if($field['type'] == 'collection'){
        $post_type_name = $field['value'];

        $posts = get_posts(array(
          'numberposts' => -1,
          'post_type' => $post_type_name,
          'post_status' => ['publish']
        ));
      
        $formatted_posts = [];
        foreach($posts as $post){
          array_push($formatted_posts, jam_cms_format_post($site_id, $post));
        }

        $fields[$field['id']] = $formatted_posts;
      }else {
        $fields[$field['id']] = $field['value'];
      }
    }

    array_push($formatted_modules, [
      'name'    => $module['name'],
      'fields'  => $fields
    ]);
  }

  return $formatted_modules;
}

?>