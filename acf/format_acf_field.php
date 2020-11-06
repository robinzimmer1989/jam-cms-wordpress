<?php

function gcms_format_acf_field($field){

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
    
  }

  return $args;
}

?>