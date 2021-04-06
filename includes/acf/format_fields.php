<?php

/**
 * jam_cms_format_fields
 *
 * Format blocks of flexible content field for frontend use
 *
 * @date	20/11/20
 * @since	0.0.1
 *
 * @param	array $modules The unformatted blocks returnd by the get_fields function
 * @return array The formatted blocks
 */

function jam_cms_format_fields($fields, $post_id){

  $template_key = jam_cms_get_template_key($post_id);

  $formatted_fields = [];

  if($fields){
    
    foreach($fields as $key => $value ){

      // Get field information to get type
      $field = (object) get_field_object("field_{$key}_group_{$template_key}");

      if($field && property_exists($field, 'type')){

        $value = jam_cms_format_acf_field_value_for_frontend($field, $value);

        $base_args = [
          'id'              => $key,
          'type'            => $field->type,
          'value'           => $value
        ];

        $type_args = jam_cms_format_acf_field_type_for_frontend($field);
        
        $args = array_merge($base_args, $type_args);

        $formatted_fields[$key] = $args;
      }

    }

  }

  return $formatted_fields;
}

?>