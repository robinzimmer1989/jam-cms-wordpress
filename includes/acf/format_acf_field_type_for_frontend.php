<?php

/**
 * jam_cms_format_acf_field_type_for_frontend
 *
 * Format field args before returning them to frontend site
 *
 * @date	20/11/20
 * @since	0.0.1
 *
 * @param	object $field The ACF field
 * @return array $args The formatted arguments of the field
 */

function jam_cms_format_acf_field_type_for_frontend($field){
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

  if(property_exists($field, 'step')){
    $args['step'] = $field->step;
  }

   if($field->type == 'menu'){
    $args['fields'] = jam_cms_get_menu_fields_recursively($field);
  }

  if($field->type == 'group'){
    $args['fields'] = jam_cms_get_group_items_recursively($field);
  }
  
  if($field->type == 'repeater'){
    $args['items'] = jam_cms_get_repeater_items_recursively($field);
  }

  if($field->type == 'flexible_content'){
    $args['items'] = jam_cms_get_flexible_content_items_recursively($field);
  }

  if(property_exists($field, 'choices')){
    $options = [];
    foreach($field->choices as $key => $value){
      array_push($options, [
        'name' => $value,
        'value' => $key
      ]);
    }
    $args['options'] = $options;
  }

  return $args;
}