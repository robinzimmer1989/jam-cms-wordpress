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
            
            // Get field information to get type
            $field = get_field_object('field_' . $key . '_' . $module['acf_fc_layout']);

            array_push($fields, array(
              'id'    => $key,
              'type'  => $field['type'],
              'value' => gcms_format_acf_field_value_for_frontend($field['type'], $value)
            ));
          }
        }

        // The module name is something like 'group_hero' so we want to remove the 'group_' part in the next step
        $array = explode('_', $module['acf_fc_layout']);
        $name = end($array);

        $field_group_id = gcms_get_acf_field_id('acf-field-group', $module['acf_fc_layout']);

        array_push($formatted_modules, [
          'fields' => $fields,
          'name' => $name,
          'label' => str_replace('Block: ', '', get_the_title($field_group_id))
        ]);
      }
    }

    $formatted_post['content'] = $formatted_modules;

    return $formatted_post;
  }

  return 'Post not found';

}

?>