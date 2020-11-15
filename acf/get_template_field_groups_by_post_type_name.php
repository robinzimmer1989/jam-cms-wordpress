<?php

function gcms_get_template_field_groups_by_post_type_name($post_type_name, $content = null){

  $template_field_groups = gcms_get_template_by_post_type_name($post_type_name);

  $template = [];

  if(count($template_field_groups) > 0 && $template_field_groups[0]['type'] != 'flexible_content'){
    
    foreach($template_field_groups as $field_group){
      
      $name = str_replace('group_', '', $field_group['name']);
      $label = str_replace('Block: ', '', $field_group['label']);

      $fields = [];

      if(array_key_exists('sub_fields', $field_group)){
        foreach($field_group['sub_fields'] as $field){

          // We gonna overrite the key field of the clone with the original field key in order to attach the correct data
          // Later in the get_repeater_items_recursively function we need the original key instead.
          $field['key'] = $field['__key'];

          // Transform to object
          $field = (object) $field;

          // Assign value if content variable is passed through.
          if($content){
            $value = $content[$field_group['name']][$field->name];
            $value =  gcms_format_acf_field_value_for_frontend($field, $value);
          }else{
            $value = null;
          }

          $base_args = [
            'id'    => $field->name,
            'type'  => $field->type,
            'value' => $value
          ];

          $type_args = gcms_format_acf_field_type_for_frontend($field);
          
          $args = array_merge($base_args, $type_args);

          array_push($fields, $args);

        }

        array_push($template, [
          'name'    => $name,
          'label'   => $label,
          'fields'  => $fields
        ]);

      }
    }
  }

  return $template;
}

?>