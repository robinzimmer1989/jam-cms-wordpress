<?php

function gcms_get_post_by_id($site_id, $post_id){

  $post = get_post($post_id);

  if($post){
    $formatted_post = gcms_format_post($site_id, $post);

    // Get flexible content fields and format
    $content = get_fields($post_id);
    $formatted_modules = [];

    if($content && $content['flexible_content']){
      foreach($content['flexible_content'] as $module){
        $fields = [];

        foreach($module as $key => $value ){
          if($key != 'acf_fc_layout'){
            // The acf field structure looks like i.e. 'group_banner_field_headline'.
            // So we have to transform it back to the React shape which is just 'headline'
            $array = explode('_', $key);
            $id = end($array);

            array_push($fields, array(
              'id' => $id,
              'value' => $value
            ));
          }
        }

        // The acf field group structure looks like i.e. 'group_banner'.
        // So we have to transform it back to the React shape which is just 'banner'
        $array = explode('_', $module['acf_fc_layout']);
        $name = end($array);

        array_push($formatted_modules, [
          'fields' => $fields,
          'name' => $name
        ]);
      }
    }

    $formatted_post['content'] = $formatted_modules;

    return $formatted_post;
  }

  return 'Post not found';

}

?>