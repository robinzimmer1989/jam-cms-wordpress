<?php

function jam_cms_update_acf_fields($post_id, $content){

  $template_key = jam_cms_get_template_key($post_id);

  $values = [];
  
  foreach($content as $field){
    $sub_key = "field_{$field->id}_group_{$template_key}";
    $values[$sub_key] = jam_cms_generate_acf_fields_recursively($field, $field->value, $sub_key);
  }

  acf_save_post($post_id, $values);
}