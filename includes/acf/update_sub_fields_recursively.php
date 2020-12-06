<?php

/**
 * jam_cms_update_sub_fields_recursively
 *
 * Update ACF fields recursively (for nested repeaters)
 *
 * @date	20/11/20
 * @since	0.0.1
 *
 * @param	int $post_id
 * @param	string $module_name The name of the field group
 * @param	object $field The ACF field
 * @param	string $field_key The key of the ACF field
 * @return void
 */

function jam_cms_update_sub_fields_recursively($post_id, $module_name, $field, $field_key){
  
  // Repeater fields have an items object with field definitions but no values
  // The values are stored by key-value pairs inside the value property

  $i = 0;

  foreach($field->value as $value){
    foreach($field->items as $sub_field){
    
      $sub_field_key = $field_key . '_' . $i . '_' . $sub_field->id;

      if(property_exists($sub_field, 'items')){
        jam_cms_update_sub_fields_recursively($post_id, $module_name, $sub_field, $sub_field_key);
      }
      
      // Add value to sub field (which is technically just an field definition item)
      $sub_field_id = $sub_field->id;
      if(property_exists($value, $sub_field_id)){
        $sub_field->value = $value->$sub_field_id;
      }

      // Value needs to be formatted depending on type before storing into db
      $sub_field_value = jam_cms_format_acf_field_value_for_db($sub_field);

      update_post_meta( $post_id, $sub_field_key, $sub_field_value );
      update_post_meta( $post_id, '_' . $sub_field_key, 'field_' . $sub_field->id . '_field_' . $field->id . '_group_' . $module_name);
    }

    $i++;
  }
}

?>