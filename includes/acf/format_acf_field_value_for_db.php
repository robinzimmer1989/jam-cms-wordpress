<?php

/**
 * jam_cms_format_acf_field_value_for_db
 *
 * Format value before saving them to db
 *
 * @date	20/11/20
 * @since	0.0.1
 *
 * @param	object $field The ACF field
 * @return any $value The formatted value
 */

function jam_cms_format_acf_field_value_for_db($field){

  if(!property_exists($field, 'value')){
    return null;
  }

  if($field->type == 'image' || $field->type == 'file'){
    if($field->value){
      $value = $field->value->id;
    }else{
      $value = '';
    }
  }elseif($field->type == 'gallery'){
    $gallery = [];
    
    foreach($field->value as $value){
      array_push($gallery, $value->id);
    }

    $value = $gallery;
  
  }elseif($field->type == 'google_map'){

    // Transform WPGraphQL to ACF schema
    $value = [
      'lat'     => $field->value->latitude,
      'lng'     => $field->value->longitude,
      'address' => $field->value->streetAddress
    ];
  
  }elseif($field->type == 'menu'){
    $value = jam_cms_update_menu($field);
  
  }elseif($field->type == 'link'){
    $value = (array) $field->value;

  }else {
    $value = $field->value;
  }

  return $value;
}