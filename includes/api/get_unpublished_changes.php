<?php

add_action( 'rest_api_init', 'jam_cms_api_get_unpublished_changes' ); 
function jam_cms_api_get_unpublished_changes() {
  register_rest_route( 'jamcms/v1', '/getUnpublishedChanges', array(
    'methods' => 'POST',
    'callback' => 'jam_cms_api_get_unpublished_changes_callback',
    'permission_callback' => function () {
      return current_user_can( 'edit_posts' );
    }
  ));
}

function jam_cms_api_get_unpublished_changes_callback() {

  $last_build = get_option('jam_cms_last_build');
  $created_at = get_user_option('user_registered', 1);

  $compare_date = strtotime($last_build ? $last_build : $created_at);

  // Get all action monitors.
  $all_changes = get_posts([
    'numberposts' => -1,
    'post_type'   => 'action_monitor',
    'orderby' => 'modified',
  ]);

  $recent_changes = [];

  foreach($all_changes as $change){
    if(strtotime($change->post_modified_gmt) > $compare_date){

      $formatted_content = json_decode($change->post_content, true);

      if($formatted_content['action_type'] != 'DIFF_SCHEMAS'){
        array_push($recent_changes, jam_cms_format_action_monitor($change));
      }
    }
  }

  return $recent_changes;
}