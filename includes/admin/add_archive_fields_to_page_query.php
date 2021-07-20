<?php

add_action( 'graphql_register_types', function() {
  register_graphql_field( 'Page', 'archive', [
    'type' => 'Boolean',
    'resolve' => function($root) {
      $archive = get_post_meta($root->ID, 'jam_cms_archive', true);
      return $archive === 'true' ? true : false;
    },
  ]);

  register_graphql_field( 'Page', 'archivePostType', [
    'type' => 'String',
    'resolve' => function($root) {
      $archive_post_type = get_post_meta($root->ID, 'jam_cms_archive_post_type', true);
      return (string) $archive_post_type;
    },
  ]);

  register_graphql_field( 'Page', 'archivePostsPerPage', [
    'type' => 'Int',
    'resolve' => function($root) {
      $archive_posts_per_page = (int) get_post_meta($root->ID, 'jam_cms_archive_posts_per_page', true);
      $default_posts_per_page = (int) get_option('posts_per_page');

      return $archive_posts_per_page ? $archive_posts_per_page : $default_posts_per_page;
    },
  ]);
});
