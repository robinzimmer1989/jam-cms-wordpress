<?php

function gcms_get_acf_group_id($field_group_name){
  global $wpdb;

  return $wpdb->get_var("
      SELECT ID
      FROM $wpdb->posts
      WHERE post_type='acf-field-group' AND post_excerpt='$field_group_name';
  ");
}

function gcms_get_field_key_by_name($field_group_id, $name){
  $fields = acf_get_fields($field_group_id);

  foreach ( $fields as $field ) {
    if($field['name'] == $name){
      return $field['key'];
    }
  }
  
  return false;
}

function gcms_add_acf_field_group($module){

  $name = $module->name;

  // This must be consistent with automatic flexible content 'modules' function
  $module_title = str_replace('Module: ', '', $name);
  $module_name = strtolower(preg_replace('/[^\w-]+/','-', $module_title));

  $field_group_id = gcms_get_acf_group_id($name);

  if(!$field_group_id){
    $field_group_args = array(
      'post_title'     => 'Module: ' . $name,
      'post_excerpt'   => sanitize_title( $name ),
      'post_name'      => 'group_' . $module_name,
      'post_date'      => date( 'Y-m-d H:i:s' ),
      'comment_status' => 'closed',
      'post_status'    => 'publish',
      'post_type'      => 'acf-field-group',
    );
  
    $field_group_id  = wp_insert_post( $field_group_args );
  }

  $fields = $module->fields;

  foreach($fields as $field){

    $field_key = gcms_get_field_key_by_name($field_group_id, $field->id);

    if(!$field_key){

      $args = [
        'key' => $field->id,
        'label' =>  $field->label,
        'name' => $field->id,
        'parent' => $field_group_id
      ];

      // Map through different fields types and convert JS to ACF schema

      if($field->type == 'image' || $field->type == 'text'){
        $args['type'] = $field->type;

        acf_update_field($args);
      }

      if($field->type == 'select'){
        $args['type'] = 'select';

        $choices = [];
        foreach($field->options as $option){
          $choices[$option->value] = $option->name;
        }
        $args['choices'] = $choices;

        acf_update_field($args);
      }

    }
  }

  return $field_group_id;
}

?>