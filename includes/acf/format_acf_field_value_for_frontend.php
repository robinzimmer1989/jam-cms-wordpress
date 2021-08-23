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
    
  }elseif($type == 'checkbox'){

    // Change null value to empty array
    if(!$value){
      return [];
    }
    
  }elseif($type == 'google_map'){

    if($value){

      // Transform ACF into WpGraphQL schema
      return [
        'latitude'      => $value['lat'],
        'longitude'     => $value['lng'],
        'streetAddress' => $value['address']
      ];

    }else{

      return [
        'latitude'      => null,
        'longitude'     => null,
        'streetAddress' => null,
      ];
    }
    
  }elseif($type == 'number'){

    if(!$value){
      return null;
    }

    return (int) $value;

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

  }elseif($type == 'group'){

    // Loop through group sub fields and transform values recursively
    foreach($field->sub_fields as $group_item){
      $key = $group_item['name'];
      $value[$key] = jam_cms_format_acf_field_value_for_frontend($group_item, $value[$key]);
    }

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

  }elseif($type == 'gallery'){

    // Change null value to empty array
    if(!$value){
      return [];
    }

    $formatted_gallery = [];

    foreach($value as $item){
      $formatted_item = jam_cms_format_acf_field_value_for_frontend(['type' => 'image'], $item);
      array_push($formatted_gallery, $formatted_item);
    }

    return $formatted_gallery;

  }elseif($type == 'image' || $type == 'file'){

    if(!$value){
      return null;
    }

    if($value['mime_type'] == 'image/svg+xml'){
      $value['svg'] = file_get_contents($value['url']);

    }elseif(array_key_exists('sizes', $value)){

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

      array_push($src_set, $value['url'] . ' ' . $value['width'] . 'w');

      $value['localFile']['childImageSharp'] = [
        // gatsby-image
        'fluid' => [
          'aspectRatio' => $value['height'] / $value['width'],
          'base64'      => '',
          'sizes'       => "(max-width: {$value['width']}px) 100vw, {$value['width']}px",
          'src'         => $value['url'],
          'srcSet'      => implode(',',$src_set)
        ],
        // gatsby-plugin-image
        'gatsbyImageData' => [
          'height'        => $value['height'],
          'width'         => $value['width'],
          'layout'        => 'constrained',
          'placeholder'   => [
            'fallback'    => ''
          ],
          'images'        => [
            'fallback'    => [
              'sizes'     => "(min-width: {$value['width']}px) {$value['width']}px, 100vw",
              'src'       => $value['url'],
              'srcSet'    => implode(',',$src_set),
            ],
            'sources'     => [
              0           => [
                'sizes'   => "(min-width: {$value['width']}px) {$value['width']}px, 100vw",
                'srcSet'  => implode(',',$src_set),
                'type'    => "image"
              ]
            ]
          ]  
        ]
      ];

      // Set svg value to null for non-svg images
      $value['svg'] = null;
    }

    $value['sourceUrl'] = $value['url'];

    // Rename alt attribute
    $value['altText'] = $value['alt'];

    // Rename name to slug
    $value['slug'] = $value['name'];

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
    unset($value['image_meta']);
    unset($value['alt']);
  }

  return $value;
}