<?php

/**
 * jam_cms_update_template_field_values
 *
 * Update values of module in template
 *
 * @date	20/11/20
 * @since	0.0.1
 *
 * @param	int $post_id
 * @param	object $module The ACF field group
 * @param	int $index The index within the template modules array
 * @return void
 */

function jam_cms_update_template_field_values($post_id, $module, $index){

  $post_type_name = get_post_type($post_id);
  $template_name = 'group_template-' . $post_type_name;

   $fields = $module->fields;

   foreach($fields as $field){
     $meta_key =  'group_' . $module->name . '_' . $field->id;
     
     if($field->type == 'repeater' && property_exists($field, 'items') && property_exists($field, 'value')){
       jam_cms_update_sub_fields_recursively($post_id, $module->name, $field, $meta_key);

       // The value for repeater fields must be the amount of items
       $value = count($field->value);

     }else{
       // Value needs to be formatted depending on type before storing into db
       $value = jam_cms_format_acf_field_value_for_db($field);
     }

     update_post_meta( $post_id, $meta_key, $value );
     update_post_meta( $post_id, '_' . $meta_key, 'field_' . $index . '_group_' . $module->name . '_' .  $template_name . '_field_' . $field->id . '_group_' . $module->name);
   }

   $group_meta_key = 'field_' . $index;
   update_post_meta( $post_id, 'group_' . $module->name , '');
   update_post_meta( $post_id, '_' . 'group_' . $module->name, 'field_' . $index . '_' . $template_name);

}

?>