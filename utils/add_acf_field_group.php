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

      if($field->type == 'image'){
        $args['type'] = $field->type;

      }elseif($field->type == 'number'){
        $args['type'] = $field->type;

      }elseif($field->type == 'text'){
        $args['type'] = $field->type;

      }elseif($field->type == 'wysiwyg'){
        $args['type'] = $field->type;

      }elseif($field->type == 'select'){
        $args['type'] = 'select';

        $choices = [];
        foreach($field->options as $option){
          $choices[$option->value] = $option->name;
        }
        $args['choices'] = $choices;

      }elseif($field->type == 'image'){
        

      }elseif($field->type == 'email'){
        

      }elseif($field->type == 'url'){
        

      }elseif($field->type == 'file'){
        

      }elseif($field->type == 'oEmbed'){
        

      }elseif($field->type == 'checkbox'){
        

      }elseif($field->type == 'radio'){
        

      }elseif($field->type == 'postObject'){
        

      }elseif($field->type == 'link'){
        

      }elseif($field->type == 'map'){
        

      }elseif($field->type == 'colorPicker'){
        

      }elseif($field->type == 'repeater'){
        

      }elseif($field->type == 'accordion'){
        

      }else {
        $args['type'] = null;
      }

      if($args['type']){
        acf_update_field($args);
      }
    }
  }

  return $field_group_id;
}

?>