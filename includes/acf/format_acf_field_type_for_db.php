<?php

/**
 * jam_cms_format_acf_field_type_for_db
 *
 * Format field args before saving them to db
 *
 * @date	20/11/20
 * @since	0.0.1
 *
 * @param	object $field The ACF field
 * @param	string $field_key The field key. Only relevant to update sub fields.
 * @return array $args The formatted arguments of the field
 */

function jam_cms_format_acf_field_type_for_db($field, $field_key = ''){

  $args = [];

  // error_log(print_r($field, true));

  if(!property_exists($field, 'type')){
    return null;
  }

  if(
    $field->type == 'image' ||
    $field->type == 'number' ||
    $field->type == 'text' ||
    $field->type == 'wysiwyg' ||
    $field->type == 'select' ||
    $field->type == 'menu' ||
    $field->type == 'repeater' ||
    $field->type == 'collection' ||
    $field->type == 'link' ||
    $field->type == 'flexible_content' ||
    $field->type == 'layout'
    // $field->type == 'url' ||
    // $field->type == 'file' ||
    // $field->type == 'checkbox' ||
    // $field->type == 'postObject' ||
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

    if(property_exists($field, 'step')){
      $args['step'] = $field->step;
    }

    if(property_exists($field, 'options')){
      $choices = [];
      foreach($field->options as $option){
        $choices[$option->value] = $option->name;
      }
      $args['choices'] = $choices;
    }

    if($field->type == 'repeater' && property_exists($field, 'items')){
      $sub_fields = jam_cms_generate_sub_fields_recursively($field->items, $field_key);
      $args['sub_fields'] = $sub_fields;
    }

    if($field->type == 'flexible_content' && property_exists($field, 'items')){

      foreach($field->items as $layout){

        $sub_field_key = 'layout_' . $layout->id . '_' . $field_key;

        $sub_fields = jam_cms_generate_sub_fields_recursively($layout->fields, $sub_field_key);

        $args['layouts']["layout_" . $layout->id] = [
          "key"     => "layout_" . $layout->id . '_' . $field_key,
          "label"   => $layout->label,
          "name"    => $layout->id,
          "display" => "block",
          'sub_fields'  => $sub_fields
        ];
      }
    }

  }else{
    return null;
  }

  return $args;
}

?>