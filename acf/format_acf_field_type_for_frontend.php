<?php

function gcms_format_acf_field_type_for_frontend($field){
  $field = (object)$field;
  
  $args = [];

  if(property_exists($field, 'instructions')){
    $args['instructions'] = $field->instructions;
  }

  if(property_exists($field, 'required')){
    $args['required'] = $field->required;
  }

  if(property_exists($field, 'default_value')){
    $args['defaultValue'] = $field->default_value;
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

  if($field->type == 'repeater'){
    $args['items'] = gcms_get_repeater_items_recursively($field);
  }

  if(property_exists($field, 'choices')){
    $options = [];
    foreach($field->choices as $key => $value){
      array_push($options, [
        'name' => $key,
        'value' => $value
      ]);
    }
    $args['options'] = $options;
  }

  return $args;
}

?>