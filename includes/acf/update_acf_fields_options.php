<?php

function jam_cms_update_acf_fields_options($fields){

  $values = [];

  foreach($fields as $field){
    $sub_key = "field_{$field->id}_group_theme-options";
    $values[$sub_key] = jam_cms_generate_acf_fields_recursively($field, $field->value, $sub_key);
  }

  acf_save_post("options", $values);
}