<?php

/**
 * jam_cms_get_option_group_fields
 *
 * Get option fields by option name
 *
 * @date	20/11/20
 * @since	0.0.1
 *
 * @param	string $option_name The name of the option field group such as header or footer
 * @return array Formatted fields of field group
 */

function jam_cms_get_option_group_fields($option_name){
  $id = jam_cms_get_acf_field_id('acf-field-group', 'group_' . $option_name);
  $fields = acf_get_fields_by_id($id);

  $formatted_fields = [];

  if($fields){  
    foreach($fields as $field){

      $value = get_field($field['key'], 'option');

      // We need to remove the module name in the title
      $field_key = str_replace($option_name . '_', '', $field['name']);

      $base_args = [
        'id'      => $field_key,
        'type'    => $field['type'],
        'value'   => jam_cms_format_acf_field_value_for_frontend($field, $value)
      ];

      $type_args = jam_cms_format_acf_field_type_for_frontend($field);
      
      $args = array_merge($base_args, $type_args);

      array_push($formatted_fields, $args);
    }
  }

  return $formatted_fields;
}

?>