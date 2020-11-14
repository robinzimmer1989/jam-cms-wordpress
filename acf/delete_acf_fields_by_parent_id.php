<?php

function gcms_delete_acf_fields_by_parent_id($parent_id){
  global $wpdb;

  return $wpdb->get_var("
      DELETE
      FROM $wpdb->posts
      WHERE post_parent='$parent_id';
  ");
}

?>