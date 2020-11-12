<?php

// This function loops through repeater fields and adds fields recursively (for nested repeaters)
function gcms_generate_sub_fields_recursively($items, $field_key){
  $sub_fields = [];

  foreach($items as $sub_field){
    $sub_field_key = 'field_' . $sub_field->id . '_' . $field_key;

    $base_args = [
      'key'   => $sub_field_key,
      'name'  => $sub_field->id,
      'label' => property_exists($sub_field, 'label') ? $sub_field->label : $sub_field->id,
    ];

    if(property_exists($sub_field, 'items')){
      $sub_sub_fields = generate_sub_fields_recursively($sub_field->items, $sub_field_key);
      $base_args['sub_fields'] = $sub_sub_fields;
    }

    $type_args = gcms_format_acf_field_type_for_db($sub_field);

    if($type_args){
      $args = array_merge($base_args, $type_args);
      array_push($sub_fields, $args);
    }
  }

  return $sub_fields;
}

?>