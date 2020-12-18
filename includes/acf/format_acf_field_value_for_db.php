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

  if($field->type == 'image' && $field->value){
    $value = $field->value->id;

  }elseif($field->type == 'file' && $field->value){
    $value = $field->value->id;
  
  }elseif($field->type == 'menu'){
    $menu_slug = $field->id;
    $menu_items = $field->value;

    $menu_id = jam_cms_update_menu($menu_slug, $menu_items);
    $value = $menu_id;
  
  }elseif($field->type == 'link'){
    $value = (array) $field->value;

  }else {
    $value = $field->value;
  }

  return $value;
}

?>