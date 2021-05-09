<?php

/**
 * jam_cms_generate_sub_fields_recursively
 *
 * Loop through repeater fields and adds fields recursively (for nested repeaters)
 *
 * @date	20/11/20
 * @since	0.0.1
 *
 * @param	array $items The items to format containing id, name and label
 * @param	string $field_key The field key
 * @return array Formatted subfields including type args
 */

function jam_cms_generate_sub_fields_recursively($items, $field_key){
  $sub_fields = [];

  foreach($items as $sub_field){
    $sub_field_key = 'field_' . $sub_field->id . '_' . $field_key;

    $label = property_exists($sub_field, 'label') ? $sub_field->label : $sub_field->id;

    $base_args = [
      'key'   => $sub_field_key,
      'name'  => $sub_field->id,
      'label' => htmlspecialchars($label)
    ];

    if(property_exists($sub_field, 'items') && $sub_field->type != 'flexible_content'){
      $sub_sub_fields = jam_cms_generate_sub_fields_recursively($sub_field->items, $sub_field_key);
      $base_args['sub_fields'] = $sub_sub_fields;
    }

    $type_args = jam_cms_format_acf_field_type_for_db($sub_field, $field_key);

    if($type_args){
      $args = array_merge($base_args, $type_args);
      array_push($sub_fields, $args);
    }
  }

  return $sub_fields;
}

?>