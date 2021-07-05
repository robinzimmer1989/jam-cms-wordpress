<?php

/**
 * jam_cms_get_group_items_recursively
 *
 * Get and format sub fields of ACF group
 *
 * @date	20/11/20
 * @since	0.0.1
 *
 * @param	object $field The ACF field
 * @return array The formatted sub fields
 */

function jam_cms_get_group_items_recursively($field){

  $sub_fields = $field->sub_fields;

  $items = [];
  foreach($sub_fields as $sub_field){
    $sub_field = (object) $sub_field;

    $base_args = [
      'id'    => $sub_field->name,
      'type'  => $sub_field->type,
      'label' => htmlspecialchars($sub_field->label)
    ];
    
    $type_args = jam_cms_format_acf_field_type_for_frontend($sub_field, $sub_field->key);
              
    $args = array_merge($base_args, $type_args);

    array_push($items, $args);
  }

  return $items;
}