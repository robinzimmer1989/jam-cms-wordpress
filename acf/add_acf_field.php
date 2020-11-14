<?php

function gcms_add_acf_field(
  $key,
  $name,
  $label,
  $post_content = '',
  $parent_id
){

  $args = array(
    'post_title'      => $label,
    'post_name'       => $key,
    'post_excerpt'    => $name,
    'post_status'     => 'publish',
    'post_type'       => 'acf-field',
    'post_content'    => $post_content,
    'post_parent'     => $parent_id  ? $parent_id : 0
  );

  $post_id = wp_insert_post($args);

  return $post_id;
}

?>