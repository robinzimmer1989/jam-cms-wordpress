<?php

function gcms_update_template_field_values($post_id, $module, $index){

  $post_type_name = get_post_type($post_id);
  $template_name = 'group_template_' . $post_type_name;

   $fields = $module->fields;

   foreach($fields as $field){
     $meta_key =  'field_' . $index . '_' . $field->id;
     
     if($field->type == 'repeater' && property_exists($field, 'items') && property_exists($field, 'value')){
       gcms_update_sub_fields_recursively($post_id, $module->name, $field, $meta_key);

       // The value for repeater fields must be the amount of items
       $value = count($field->value);

     }else{
       // Value needs to be formatted depending on type before storing into db
       $value = gcms_format_acf_field_value_for_db($field);
     }

     update_post_meta( $post_id, $meta_key, $value );
     update_post_meta( $post_id, '_' . $meta_key, 'field_' . $index . '_' . $module->name . '_' . $template_name . '_field_' . $field->id . '_' . $module->name);
   }

   $group_meta_key = 'field_' . $index;
   update_post_meta( $post_id, $group_meta_key , '');
   update_post_meta( $post_id, '_' . $group_meta_key, $group_meta_key . '_' . $template_name);

}

?>