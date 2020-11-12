<?php

function gcms_format_acf_field_type_for_db($field, $field_key = ''){

  $args = [];

  if(
    $field->type == 'image' ||
    $field->type == 'number' ||
    $field->type == 'text' ||
    $field->type == 'wysiwyg' ||
    $field->type == 'select' ||
    $field->type == 'menu' ||
    $field->type == 'repeater'
    // $field->type == 'email' ||
    // $field->type == 'url' ||
    // $field->type == 'file' ||
    // $field->type == 'checkbox' ||
    // $field->type == 'radio' ||
    // $field->type == 'postObject' ||
    // $field->type == 'link' ||
    // $field->type == 'map' ||
    // $field->type == 'colorPicker'
  ){
    $args['type'] = $field->type;

    if(property_exists($field, 'options')){
      $choices = [];
      foreach($field->options as $option){
        $choices[$option->value] = $option->name;
      }
      $args['choices'] = $choices;
    }

    if(property_exists($field, 'items')){
      $sub_fields = gcms_generate_sub_fields_recursively($field->items, $field_key);
      $args['sub_fields'] = $sub_fields;
    }

  }else{
    return null;
  }

  return $args;
}

?>