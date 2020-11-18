<?php

function gcms_get_template_by_post_type_name($post_type_name){

  $id = gcms_get_acf_field_id('acf-field-group', 'group_template-' . $post_type_name);
  $template_fields = acf_get_fields_by_id($id);

  return $template_fields;
}

?>