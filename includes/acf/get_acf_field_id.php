<?php

/**
 * jam_cms_get_acf_field_id
 *
 * Get database ID of ACF field group or field
 *
 * @date	20/11/20
 * @since	0.0.1
 *
 * @param	string $field_type The type of query (group vs field)
 * @param	string $key The field key
 * @return int ID
 */

function jam_cms_get_acf_field_id($field_type, $key){
  global $wpdb;

  return $wpdb->get_var("
      SELECT ID
      FROM $wpdb->posts
      WHERE post_type='$field_type' AND post_name='$key';
  ");
}