<?php

/**
 * gcms_delete_acf_fields_by_parent_id
 *
 * Delete all ACF fields of field group
 *
 * @date	20/11/20
 * @since	0.0.1
 *
 * @param	int $parent_id The id of the parent field group
 * @return	void
 */

function gcms_delete_acf_fields_by_parent_id($parent_id){
  global $wpdb;

  $wpdb->get_var("
      DELETE
      FROM $wpdb->posts
      WHERE post_parent='$parent_id';
  ");
}

?>