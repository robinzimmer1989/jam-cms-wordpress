<?php

function gcms_add_acf_field_group_to_template($template_name, $field_group, $index){

  // To add a field group to a template we have to create an incrementing group field 
  // first and the clone the new field group into it. This way we avoid name clashes 
  // when two modules of the same kind are being added.

  $template_id = gcms_get_acf_field_id('acf-field-group', $template_name);

  $group_field_id = gcms_add_acf_field(
    'field_' . $index . '_' . $template_name,
    $field_group->key,
    $field_group->title,
    serialize([
      'type'    => 'group'
    ]),
    $template_id,
  );

  $field_id = gcms_add_acf_field(
    'field_' . $index . '_' . $field_group->key .  '_' . $template_name,
    $field_group->key,
    $field_group->title,
    serialize([
      'type'    => 'clone',
      'clone'   => [
        0 => $field_group->key,
      ]
    ]),
    $group_field_id
  );

}

?>