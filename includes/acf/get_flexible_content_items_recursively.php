<?php

function jam_cms_get_flexible_content_items_recursively($field){

  $items = [];

  $i = 0;
  foreach($field->layouts as $key => $layout){
    $layout = (object) $layout;

    $fields = [];
    foreach($layout->sub_fields as $sub_field){
      $sub_field = (object) $sub_field;      

      $base_args = [
        'id'    => $sub_field->name,
        'type'  => $sub_field->type,
        'label' => htmlspecialchars($sub_field->label)
      ];
      
      $type_args = jam_cms_format_acf_field_type_for_frontend($sub_field, $sub_field->key);
                
      $sub_field_args = array_merge($base_args, $type_args);

      array_push($fields, $sub_field_args);
    }

    $item = [
      'id'      => $layout->name,
      'label'   => htmlspecialchars($layout->label),
      'fields'  => $fields
    ];

    $items[$i] = $item;

    $i++;
  }

  return $items;
}

?>