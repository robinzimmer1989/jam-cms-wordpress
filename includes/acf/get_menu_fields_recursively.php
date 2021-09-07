<?php

/**
 * jam_cms_get_menu_fields_recursively
 *
 * Get and format fields of menu items
 *
 * @date	20/11/20
 * @since	1.11.1
 *
 * @param	object $field The ACF field
 * @return array The formatted fields
 */

function jam_cms_get_menu_fields_recursively($field){

  // Get repeater sub fields
  $id = jam_cms_get_acf_field_id('acf-field-group', "group_menu-{$field->name}");

  // We need to check for the id here, because if none is found it'll get all field groups
  if(!$id){
    return [];
  }

  $sub_fields = acf_get_fields_by_id($id);

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