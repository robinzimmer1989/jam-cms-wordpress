<?php

function gcms_update_flexible_content_field_values($post_id, $module, $index){

   // Loop through fields and update value and ACF internal group / field reference
   $fields = $module->fields;

   foreach($fields as $field){
     $meta_key =  'flexible_content_' . $index . '_' . $field->id;
     
     if($field->type == 'repeater' && property_exists($field, 'items') && property_exists($field, 'value')){
       gcms_update_sub_fields_recursively($post_id, $module->name, $field, $meta_key);

       // The value for repeater fields must be the amount of items
       $value = count($field->value);

     }else{
       // Value needs to be formatted depending on type before storing into db
       $value = gcms_format_acf_field_value_for_db($field);
     }

     update_post_meta( $post_id, $meta_key, $value );
     update_post_meta( $post_id, '_' . $meta_key, 'field_' . $module->name . '_field_' . $field->id . '_group_' . $module->name);
   }

}

?>