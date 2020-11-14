<?php

function gcms_get_flexible_content_blocks($modules){

  $formatted_modules = [];

  foreach($modules as $module){
    $fields = [];

    foreach($module as $key => $value ){
      if($key != 'acf_fc_layout'){

        // Get field information to get type
        $field = (object) get_field_object('field_' . $key . '_' . $module['acf_fc_layout']);

        if($field){

          $base_args = [
            'id'    => $key,
            'type'  => $field->type,
            'value' => gcms_format_acf_field_value_for_frontend($field->type, $value)
          ];

          $type_args = gcms_format_acf_field_type_for_frontend($field);
          
          $args = array_merge($base_args, $type_args);

          array_push($fields, $args);
        }
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

  return $formatted_modules;
}

?>