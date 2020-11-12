<?php

// Repeater fields have an items object with field definitions but no values
// The values are stored by key-value pairs inside the value property

function gcms_update_sub_fields_recursively($post_id, $module_name, $field, $field_key){
  
  $i = 0;

  foreach($field->value as $value){
    foreach($field->items as $sub_field){
    
      $sub_field_key = $field_key . '_' . $i . '_' . $sub_field->id;

      if(property_exists($sub_field, 'items')){
        gcms_update_sub_fields_recursively($post_id, $module_name, $sub_field, $sub_field_key);
      }
      
      // Add value to sub field (which is technically just an field definition item)
      $sub_field_id = $sub_field->id;
      $sub_field->value = $value->$sub_field_id;

      // Value needs to be formatted depending on type before storing into db
      $sub_field_value = gcms_format_acf_field_value_for_db($sub_field);

      update_post_meta( $post_id, $sub_field_key, $sub_field_value );
      update_post_meta( $post_id, '_' . $sub_field_key, 'field_' . $sub_field->id . '_field_' . $field->id . '_group_' . $module_name);
    }

    $i++;
  }
}

?>