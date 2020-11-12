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

    if(property_exists($field, 'instructions')){
      $args['instructions'] = $field->instructions;
    }
  
    if(property_exists($field, 'required')){
      $args['required'] = $field->required;
    }
  
    if(property_exists($field, 'defaultValue')){
      $args['default_value'] = $field->defaultValue;
    }
  
    if(property_exists($field, 'placeholder')){
      $args['placeholder'] = $field->placeholder;
    }
  
    if(property_exists($field, 'prepend')){
      $args['prepend'] = $field->prepend;
    }
  
    if(property_exists($field, 'append')){
      $args['append'] = $field->append;
    }
  
    if(property_exists($field, 'min')){
      $args['min'] = $field->min;
    }
  
    if(property_exists($field, 'max')){
      $args['max'] = $field->max;
    }

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