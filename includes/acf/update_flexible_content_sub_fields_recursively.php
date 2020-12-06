<?php

function jam_cms_update_flexible_content_sub_fields_recursively($post_id, $module_name, $field, $field_key){

  $i = 0;

  foreach($field->value as $layout){
    $j = 0;
    foreach($layout->fields as $sub_field){

      $sub_field_key = $field_key . '_' . $i . '_' . $sub_field->id;

      if(property_exists($sub_field, 'items')){
        jam_cms_update_sub_fields_recursively($post_id, $module_name, $sub_field, $sub_field_key);
      }
      
      $sub_field->value = $layout->fields[$j]->value;

      // Value needs to be formatted depending on type before storing into db
      $sub_field_value = jam_cms_format_acf_field_value_for_db($sub_field);

      update_post_meta( $post_id, $sub_field_key, $sub_field_value );
      update_post_meta( $post_id, '_' . $sub_field_key, 'field_' . $sub_field->id . '_field_' . $field->id . '_group_' . $module_name);

      $j++;
    }
    $i++;
  }
}

?>