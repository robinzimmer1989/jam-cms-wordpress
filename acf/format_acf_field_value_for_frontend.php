<?php

function gcms_format_acf_field_value_for_frontend($field, $value){
  $field = (object) $field;
  $type = $field->type;

  if($type == 'menu'){
    
    if(!$value){
      return null;
    }

    $value = gcms_get_menu_by_id($value);

  }elseif($type == 'repeater'){

    // Change null value to empty array
    if(!$value){
      return [];
    }

    // Loop through repeater items recursively and transform value
    $i = 0;
    foreach($value as $repeater_item){
      $j = 0;
      foreach($repeater_item as $key => $repeater_item_value){
        $sub_field = $field->sub_fields[$j];
        $value[$i][$key] = gcms_format_acf_field_value_for_frontend($sub_field, $repeater_item_value);
        $j++;
      }

      $i++;
    }

  }elseif($type == 'image'){

    if(!$value){
      return null;
    }

    // Format to dummy Gatsby image

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