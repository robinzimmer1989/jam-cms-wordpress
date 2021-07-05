<?php

// TODO: Add option for this in backend

add_filter('register_post_type_args', 'jam_cms_remove_default_post_type', 0, 2);
function jam_cms_remove_default_post_type($args, $postType) {
  if ($postType === 'post') {
      $args['public']                = false;
      $args['show_ui']               = false;
      $args['show_in_menu']          = false;
      $args['show_in_admin_bar']     = false;
      $args['show_in_nav_menus']     = false;
      $args['can_export']            = false;
      $args['has_archive']           = false;
      $args['exclude_from_search']   = true;
      $args['publicly_queryable']    = false;
      $args['show_in_rest']          = false;
      $args['show_in_graphql']       = false;
  }

  return $args;
}