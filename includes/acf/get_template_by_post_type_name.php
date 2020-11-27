<?php

/**
 * jam_cms_get_template_by_post_type_name
 *
 * Get template fields by post type
 *
 * @date	20/11/20
 * @since	0.0.1
 *
 * @param	string $post_type_name The post type name (= unique ID)
 * @return array Field groups of the template
 */

function jam_cms_get_template_by_post_type_name($post_type_name){

  $id = jam_cms_get_acf_field_id('acf-field-group', 'group_template-' . $post_type_name);
  $template_fields = acf_get_fields_by_id($id);

  return $template_fields;
}

?>