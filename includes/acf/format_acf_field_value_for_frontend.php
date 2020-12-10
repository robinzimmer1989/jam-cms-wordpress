<?php

/**
 * jam_cms_format_acf_field_value_for_frontend
 *
 * Format value before returning to frontend site
 *
 * @date	20/11/20
 * @since	0.0.1
 *
 * @param	object $field The ACF field
 * @param	any $value The ACF field value
 * @return any $value The formatted value
 */

function jam_cms_format_acf_field_value_for_frontend($field, $value){
  $field = (object) $field;

  if(!property_exists($field, 'type')){
    return null;
  }

  $type = $field->type;

  if($type == 'menu'){
    
    // Change null value to empty array
    if(!$value){
      return [];
    }

    $value = jam_cms_get_menu_by_id($value);

  }elseif($type == 'link'){
    // Change null value to empty array
    if(!$value){
      return [
        'url'     => '',
        'title'   => '',
        'target'  => '',
      ];
    }

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
        $value[$i][$key] = jam_cms_format_acf_field_value_for_frontend($sub_field, $repeater_item_value);
        $j++;
      }

      $i++;
    }

  }elseif($type == 'flexible_content'){
    $value = jam_cms_get_flexible_content_sub_blocks($field, $value);

  }elseif($type == 'application'){

    unset($value['ID']);
    unset($value['sizes']);
    unset($value['link']);
    unset($value['author']);
    unset($value['description']);
    unset($value['caption']);
    unset($value['name']);
    unset($value['status']);
    unset($value['uploaded_to']);
    unset($value['date']);
    unset($value['modified']);
    unset($value['menu_order']);
    unset($value['mime_type']);

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
        'base64'      => 'data:image/jpg;base64,'. base64_encode(file_get_contents($value['sizes']['tiny'])),
        'sizes'       => '(max-width: '. $value['width'] .'px) 100vw, '. $value['width'] .'px',
        'src'         => $value['url'],
        'srcSet'      => implode(',',$src_set)
      ]
    ];

    // Rename alt attribute
    $value['altText'] = $value['alt'];

    // Remove unnecessary data
    unset($value['ID']);
    unset($value['sizes']);
    unset($value['link']);
    unset($value['author']);
    unset($value['description']);
    unset($value['caption']);
    unset($value['name']);
    unset($value['status']);
    unset($value['uploaded_to']);
    unset($value['date']);
    unset($value['modified']);
    unset($value['menu_order']);
    unset($value['mime_type']);
    unset($value['icon']);
    unset($value['image_meta']);
    unset($value['alt']);
  }

  return $value;
}

?>