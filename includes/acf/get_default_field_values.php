<?php

function jam_cms_get_default_field_value_recursively($field){
  $value = null;

  if($field->type == 'group'){

    $value = [];

    foreach($field->sub_fields as $sub_field){
      $sub_field = (object) $sub_field;
      $value[$sub_field->name] = jam_cms_get_default_field_value_recursively($sub_field);
    }

  }else if($field->type == 'flexible_content' || $field->type == 'repeater'){
    $value = []; 
  }

  return $value;
}


function jam_cms_get_default_field_values($post_id){

  $template_key = jam_cms_get_template_key($post_id);
  $field_group_id = jam_cms_get_acf_field_id('acf-field-group', "group_{$template_key}");
  $fields = acf_get_fields($field_group_id);

  $array = [];

  foreach ($fields as $field) {
    $field = (object) $field;
    $array[$field->name] = jam_cms_get_default_field_value_recursively($field);
	}

  return (object) $array;
}