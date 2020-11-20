<?php

/**
 * gcms_add_acf_field_group
 *
 * Creates ACF field group for module
 *
 * @date	20/11/20
 * @since	0.0.1
 *
 * @param	object $module The frontend module containing fields, name and label (optional)
 * @param	string $group_name_prefix The prefix for the group name (optional). Relevant for header/footer options to avoid name clashes.
 * @param	string $field_name_prefix The prefix for the field name (optional). Relevant for modules for adding 'Block: '.
 * @param	array $location_rule The location rule to decide where to add the field group (options vs. nowhere)
 * @return object $field_group
 */

function gcms_add_acf_field_group(
  $module,
  $group_name_prefix = '',
  $field_name_prefix = '',
  $location_rule
){
  
  // Loop through fields and create ACF subfields
  $fields = [];
  foreach($module->fields as $field){

    if(!is_object($field)){ 
      return;
    }

    $field_key = 'field_' . $field->id . '_group_' . $module->name;
    $field_name = $field_name_prefix . $field->id;
    
    $base_args = [
      'key'   => $field_key,
      'name'  => $field_name,
      'label' => property_exists($field, 'label') ? $field->label : $field->id
    ];

    // Convert JS to ACF type arguments and prevent non supported field types from being added
    $type_args = gcms_format_acf_field_type_for_db($field, $field_key);

    if($type_args){
      $args = array_merge($base_args, $type_args);
      array_push($fields, $args);
    }
  }

  // Upsert module
  $field_group_key = 'group_' . $module->name;
  $field_group_id  = gcms_get_acf_field_id('acf-field-group', $field_group_key);

  if(property_exists($module, 'label')){
    $field_group_label = $group_name_prefix . $module->label;
  }else {
    $field_group_label = $group_name_prefix . $module->name;
  }

  // Create field group
  $field_group = [
    'ID'                    => $field_group_id ? $field_group_id : 0,
    'key'                   => $field_group_key,
    'title'                 => $field_group_label,
    'fields'                => $fields,
    'location'              => array(
      'group_0'             => $location_rule
    ),
    'active'                => true,
    'style'                 => 'seamless',
    'position'              => 'normal',
    'label_placement'       => 'top',
    'instruction_placement' => 'label',
  ];

  $field_group = acf_import_field_group($field_group);

  return (object) $field_group;
}

?>