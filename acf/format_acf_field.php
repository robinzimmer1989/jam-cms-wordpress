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


function gcms_format_acf_field_value_for_frontend($type, $value){

  if(!$value){
    return null;
  }
  
  if($type == 'menu'){
    $value = gcms_get_menu_by_id($value);

  }elseif($type == 'image'){

    $src_set = [];
    if($value['sizes']['thumbnail']){
      array_push($src_set, $value['sizes']['thumbnail'] . ' ' . $value['sizes']['thumbnail-width'] . 'w');
    }

    if($value['sizes']['medium']){
      array_push($src_set, $value['sizes']['medium'] . ' ' . $value['sizes']['medium-width'] . 'w');
    }

    if($value['sizes']['medium_large']){
      array_push($src_set, $value['sizes']['medium_large'] . ' ' . $value['sizes']['medium_large-width'] . 'w');
    }

    if($value['sizes']['large']){
      array_push($src_set, $value['sizes']['large'] . ' ' . $value['sizes']['large-width'] . 'w');
    }

    $value['childImageSharp'] = [
      'fluid' => [
        'aspectRatio' => $value['height'] / $value['width'],
        'base64'      => '',
        'sizes'       => '(max-width: '. $value['width'] .'px) 100vw, '. $value['width'] .'px',
        'src'         => $value['url'],
        'srcSet'      => implode(',',$src_set)
      ]
    ];

    // Remove unnecessary data
    unset($value['ID']);
    unset($value['sizes']);
    unset($value['link']);
    unset($value['author']);
    unset($value['description']);
    unset($value['caption']);
    unset($value['title']);
    unset($value['name']);
    unset($value['status']);
    unset($value['uploaded_to']);
    unset($value['date']);
    unset($value['modified']);
    unset($value['menu_order']);
    unset($value['mime_type']);
    unset($value['icon']);
    unset($value['image_meta']);
  }

  return $value;
}


?>