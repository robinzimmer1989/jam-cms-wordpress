<?php

function gcms_format_acf_field_value_for_db($field){

  if(!property_exists($field, 'value') || !$field->value){
    return null;
  }

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

?>