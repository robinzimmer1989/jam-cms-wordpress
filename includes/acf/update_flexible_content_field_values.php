<?php

/**
 * jam_cms_update_flexible_content_field_values
 *
 * Update all fields of module of flexible content field
 *
 * @date	20/11/20
 * @since	0.0.1
 *
 * @param	int $post_id The id of the post
 * @param	object $module The module
 * @return void
 */


function jam_cms_update_flexible_content_field_values($post_id, $module, $index){

   // Loop through fields and update value and ACF internal group / field reference
   $fields = $module->fields;

   foreach($fields as $field){
     $meta_key =  'flex_' . $index . '_' . $field->id;
    
     if($field->type == 'repeater' && property_exists($field, 'items') && property_exists($field, 'value')){
       jam_cms_update_sub_fields_recursively($post_id, $module->id, $field, $meta_key);

       // The value for repeater fields must be the amount of items
       $value = count($field->value);

     }else if($field->type == 'flexible_content' && property_exists($field, 'items') && property_exists($field, 'value')){
      $layouts = [];
      
      $j = 0;
      foreach($field->value as $layout){
        array_push($layouts, $layout->id);
        $j++;
      }

       $value = $layouts;

       jam_cms_update_flexible_content_sub_fields_recursively($post_id, $module->id, $field, $meta_key);

     }else{
       // Value needs to be formatted depending on type before storing into db
       $value = jam_cms_format_acf_field_value_for_db($field);
     }

     update_post_meta( $post_id, $meta_key, $value );
     update_post_meta( $post_id, '_' . $meta_key, 'field_group_' . $module->id . '_field_' . $field->id . '_group_' . $module->id);
   }

}

?>