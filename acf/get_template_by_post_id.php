<?php

function gcms_get_template_by_post_id($post_id){

  $post_type = get_post_type($post_id);

  $id = gcms_get_acf_field_id('acf-field-group', 'group_template-' . $post_type);
  $template_fields = acf_get_fields_by_id($id);

  return $template_fields;
}

?>