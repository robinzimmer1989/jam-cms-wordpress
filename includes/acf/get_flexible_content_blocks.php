<?php

/**
 * jam_cms_get_flexible_content_blocks
 *
 * Format blocks of flexible content field for frontend use
 *
 * @date	20/11/20
 * @since	0.0.1
 *
 * @param	array $modules The unformatted blocks returnd by the get_fields function
 * @return array The formatted blocks
 */

function jam_cms_get_flexible_content_blocks($modules){

  if(!$modules){
    return [];
  }

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
            'value' => jam_cms_format_acf_field_value_for_frontend($field, $value)
          ];

          $type_args = jam_cms_format_acf_field_type_for_frontend($field);
          
          $args = array_merge($base_args, $type_args);

          $fields[$key] = $args;
        }
      }
    }

    // The module name is something like 'group_hero' so we want to remove the 'group_' part in the next step
    $array = explode('_', $module['acf_fc_layout']);
    $id = end($array);

    $field_group_id = jam_cms_get_acf_field_id('acf-field-group', $module['acf_fc_layout']);

    array_push($formatted_modules, [
      'id' => $id,
      'label' => str_replace('Block: ', '', get_the_title($field_group_id)),
      'fields' => $fields
    ]);
  }

  return $formatted_modules;
}

?>