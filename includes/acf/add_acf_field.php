<?php

/**
 * gcms_add_acf_field
 *
 * Create ACF field
 *
 * @date	20/11/20
 * @since	0.0.1
 *
 * @param	string $key The ACF field key
 * @param	string $name The ACF field name
 * @param	string $label The ACF field label
 * @param	string $post_content The ACF field options
 * @param	int $parent_id The ACF field group ID
 * @return int post_id
 */

function gcms_add_acf_field(
  $key,
  $name,
  $label,
  $post_content = '',
  $parent_id = 0
){

  $args = array(
    'post_title'      => $label,
    'post_name'       => $key,
    'post_excerpt'    => $name,
    'post_status'     => 'publish',
    'post_type'       => 'acf-field',
    'post_content'    => $post_content,
    'post_parent'     => $parent_id
  );

  $post_id = wp_insert_post($args);

  return $post_id;
}

?>