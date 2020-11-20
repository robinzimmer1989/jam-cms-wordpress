<?php

/**
 * gcms_add_acf_field_group_to_template
 *
 * Adds ACF field group to specified template.
 * We can't add the field group directly to the template, because that would create name clashes (i.e. multiple 'image' fields)
 * Therefore, we have to create a field group first and then clone the target field group into it.
 * The field group key of the wrapper will be a combination of an index and the template name to make it unique.
 *
 * @date	20/11/20
 * @since	0.0.1
 *
 * @param	string $template_name The field group key of the template
 * @param	object $field_group The field_group to add to the template
 * @param	int $index The index to determin at which position to add the field group within the template
 * @return	void
 */

function gcms_add_acf_field_group_to_template($template_name, $field_group, $index){

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