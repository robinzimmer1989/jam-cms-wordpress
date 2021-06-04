<?php

function jam_cms_create_revision($post_id, $content){

  $post = get_post($post_id);

  // Check if revisions are enabled first
  if(wp_revisions_enabled($post)){

    $overrides = [
      'post_status' => 'inherit',
      'post_name'   => "{$post_id}-revision-v1",
      'post_parent' => $post_id,
      'post_type'   => 'revision'
    ];

    // Get current content of post
    $fields = get_fields($post_id);
    $current_content = (object) jam_cms_format_fields($fields, $post_id);

    // Compare curent and new post content and only create revision when they are different
    if(json_encode($content, true) != json_encode($current_content, true)){
      // Create revision
      $revision = jam_cms_duplicate_post($post_id, $overrides, true);
     
      // Get number of allowed revisions
      $amount = wp_revisions_to_keep($post);

      // all revisions and (possibly) one autosave
      $revisions = wp_get_post_revisions( $post_id, array( 'order' => 'ASC' ) );

      // Get revisions to be deleted
      $delete = count($revisions) - $amount;

      if ($delete > 0){
        $revisions = array_slice( $revisions, 0, $delete);
    
        for ( $i = 0; isset($revisions[$i]); $i++ ) {
          if ( false !== strpos( $revisions[$i]->post_name, 'autosave' ) ) {
            continue;
          }
          
          wp_delete_post_revision($revisions[$i]->ID);
        }
      }
      
      return $revision;
    }
  }
}