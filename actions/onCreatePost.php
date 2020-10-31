<?php

// TODO: Looks like this is not working. I've tried to retrieve post meta field, but it didn't show up.

function gcms_action_onCreatePost($post_id, $post, $update) {
  $uuid = wp_generate_uuid4();
  add_post_meta($post_id, 'uuid', $uuid );
}
add_action( 'wp_insert_post', 'gcms_action_onCreatePost', 10, 3 );

?>