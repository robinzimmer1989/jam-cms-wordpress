<?php

function gcms_get_acf_field_id($field_type, $key, $parent_id = 0){
  global $wpdb;

  return $wpdb->get_var("
      SELECT ID
      FROM $wpdb->posts
      WHERE post_type='$field_type' AND post_name='$key';
  ");
}

?>