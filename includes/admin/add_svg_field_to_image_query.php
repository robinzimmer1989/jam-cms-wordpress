<?php

add_action( 'graphql_register_types', function() {
  register_graphql_field( 'MediaItem', 'svg', [
    'type' => 'String',
    'resolve' => function($root) {
      $post_id = $root->ID;

      $mime_type = get_post_mime_type($post_id);

      if ($mime_type == 'image/svg+xml') {
        $attachment_url = wp_get_attachment_url($post_id);
        return file_get_contents($attachment_url);
      } else {
        return null;
      }
    },
  ]);
});
