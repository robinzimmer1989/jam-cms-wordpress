<?php

function gcms_get_post_by_id($site_id, $post_id){

  $post = get_post($post_id);

  if($post){
    $formatted_post = gcms_format_post($site_id, $post);

    // Get flexible content fields and format
    $content = get_fields($post_id);
    $formatted_modules = [];

    if($content && $content['modules']){
      foreach($content['modules'] as $module){
        $fields = [];

        foreach($module as $key => $value ){
          if($key != 'acf_fc_layout'){
            array_push($fields, array(
              'id' => $key,
              'value' => $value
            ));
          }
        }

        array_push($formatted_modules, [
          'fields' => $fields,
          'name' => $module['acf_fc_layout']
        ]);
      }
    }

    $formatted_post['content'] = $formatted_modules;

    return $formatted_post;
  }

  return 'Post not found';

}

?>