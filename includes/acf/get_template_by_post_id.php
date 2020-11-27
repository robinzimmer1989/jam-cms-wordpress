<?php

/**
 * jam_cms_get_template_by_post_id
 *
 * Get template fields by post id
 *
 * @date	20/11/20
 * @since	0.0.1
 *
 * @param	int $post_id The post ID
 * @return array Field groups of the template
 */

function jam_cms_get_template_by_post_id($post_id){

  $post_type = get_post_type($post_id);

  $id = jam_cms_get_acf_field_id('acf-field-group', 'group_template-' . $post_type);
  $template_fields = acf_get_fields_by_id($id);

  return $template_fields;
}

?>