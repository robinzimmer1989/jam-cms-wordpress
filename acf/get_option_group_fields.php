<?php

function gcms_get_option_group_fields($option_name){
  $id = gcms_get_acf_field_id('acf-field-group', 'group_' . $option_name);
  $fields = acf_get_fields_by_id($id);

  $formatted_fields = [];

  if($fields){  
    foreach($fields as $field){

      $value = get_field($field['key'], 'option');

      array_push($formatted_fields, [
        'id'      => str_replace($option_name . '_', '', $field['name']),
        'type'    => $field['type'],
        'value'   => gcms_format_acf_field_value_for_frontend($field['type'], $value)
      ]);
    }
  }

  return $formatted_fields;
}

?>