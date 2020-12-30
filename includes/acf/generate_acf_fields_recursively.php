<?php

function jam_cms_generate_acf_fields_recursively($field, $value, $key){

  $sub_key = "field_{$field->id}_{$key}";

  if($field->type == "group" && property_exists($field, 'fields')){
    $prop = [];
    
    foreach($field->fields as $sub_field){

      $sub_field_id = $sub_field->id;
      $sub_field_value = $value->$sub_field_id;
      
      $prop["field_{$sub_field_id}_{$key}"] = jam_cms_generate_acf_fields_recursively($sub_field, $sub_field_value, $key);
    }

  }else if($field->type == "repeater" && property_exists($field, 'items')){

    $prop = [];

    $j = 0;
    foreach($value as $sub_value) {

      foreach($field->items as $item){

        $item_id = $item->id;
        $item_value = $sub_value->$item_id;

        $prop["row-{$j}"]["field_{$item_id}_{$key}"] = jam_cms_generate_acf_fields_recursively($item, $item_value, $key);
      }

      $j++;
    }

  }else if($field->type == "flexible_content" && property_exists($field, 'items')){

    $prop = [];

    $j = 0;
    foreach($value as $sub_value) {

      $layout_id = $sub_value->id;
      $layout_key = "layout_{$layout_id}_{$key}";

      $layout_index = array_search($layout_id, array_column($field->items, 'id'));

      if(is_numeric($layout_index)){

        $prop["row-{$j}"]["acf_fc_layout"] = $layout_id;

        $layout = $field->items[$layout_index];

        if(property_exists($layout, "fields")){
          foreach($layout->fields as $sub_field){
          
            $sub_field_id = $sub_field->id;
            $sub_field_value = $sub_value->$sub_field_id;

            $prop["row-{$j}"]["field_{$sub_field_id}_{$layout_key}"] = jam_cms_generate_acf_fields_recursively($sub_field, $sub_field_value, $layout_key);
          }
        }

      }

      $j++;
    }

  }else{
    $field->value = $value;
    $prop = jam_cms_format_acf_field_value_for_db($field);
  }

  return $prop;
}

?>