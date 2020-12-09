<?php

/**
 * jam_cms_get_flexible_content_sub_blocks
 *
 * Format sub blocks of flexible content field for frontend use
 *
 * @date	20/11/20
 * @since	0.0.1
 *
 * @param	array $modules The unformatted blocks returnd by the get_fields function
 * @return array The formatted blocks
 */

function jam_cms_get_flexible_content_sub_blocks($field, $value){

  if(!$value){
    return [];
  }

  $formatted_value = [];

  foreach($value as $layout){
    $sub_fields = [];

    foreach($layout as $key => $value ){
      if($key != 'acf_fc_layout'){

        // Get field information to get type
        $sub_field = (object) get_field_object('field_' . $key . '_layout_' . $layout['acf_fc_layout'] . '_');

        if($sub_field){
          $sub_fields[$key] = jam_cms_format_acf_field_value_for_frontend($sub_field, $value);
        }
      }
    }

    $sub_fields['id'] = $layout['acf_fc_layout'];
    
    array_push($formatted_value, $sub_fields);
  }

  return $formatted_value;
}

?>