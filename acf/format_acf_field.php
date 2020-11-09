<?php

function gcms_format_acf_field_value_for_db($field){
  if($field->type == 'image'){
    $value = $field->value->id;

  }elseif($field->type == 'menu'){
    $menu_slug = $field->id;
    $menu_items = $field->value;

    $menu_id = gcms_update_menu($menu_slug, $menu_items);
    
    $value = $menu_id;
  
  }else {
    $value = $field->value;
  }

  return $value;
}

function gcms_format_acf_field_type($field){

  $args = [];

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

  }elseif($field->type == 'menu'){
    $args['type'] = $field->type;

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
    

  }else{
    return null;
  }

  return $args;
}


function gcms_format_acf_field_value_for_frontend($field, $options_page = ''){
  
  if($field['type'] == 'menu'){
    $menu_id = get_field($field['key'], $options_page);
    $value = gcms_get_menu_by_id(2);

  }else {
    $value = get_field($field['key'], $options_page);
  }

  return $value;
}


?>