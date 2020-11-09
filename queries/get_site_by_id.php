<?php

function gcms_get_site_by_id($site_id){
 
  $site = get_blog_details($site_id);

  if($site){
    switch_to_blog($site->blog_id);

    // Get generic and custom post types
    $post_types = get_post_types([], 'objects');
    $custom_post_types = get_option('cptui_post_types') ? get_option('cptui_post_types') : [];
    $all_post_types = array_merge($post_types, $custom_post_types);

    $items = [];
    foreach ( $all_post_types as $post_type ) {
      // Custom post types are constructed as an array so we have to convert them
      $post_type = (object) $post_type;

      if ($post_type->publicly_queryable && $post_type->name != 'attachment') {
          array_push($items, gcms_format_post_type($site_id, $post_type));
      }
    }

    $header_fields = gcms_get_option_group_fields('header');
    $footer_fields = gcms_get_option_group_fields('footer');

    $netlify_build_hook = '';
    $netlify_badge_image = '';
    $netlify_badge_link = '';

    $jamstack_deployment_settings = get_option('wp_jamstack_deployments');

    if($jamstack_deployment_settings){
      $netlify_build_hook = $jamstack_deployment_settings['webhook_url'];
      $netlify_badge_image = $jamstack_deployment_settings['deployment_badge_url'];
      $netlify_badge_link = $jamstack_deployment_settings['deployment_badge_link_url'];
    }

    $data = array(
      'id'                => $site_id,
      'title'             => $site->blogname,
      'netlifyBuildHook'  => $netlify_build_hook,
      'netlifyBadgeImage' => $netlify_badge_image,
      'netlifyBadgeLink'  => $netlify_badge_link,
      'multisite'         => is_multisite(),
      'settings'          => [
        'header' => [
          'name'          => 'header',
          'fields'        => $header_fields
        ],
        'footer' => [
        'name'            => 'footer',
          'fields'        => $footer_fields
        ]
      ],
      'frontPage'         => intval(get_option( 'page_on_front' )),
      'postTypes' => [
                  'items' => $items
      ],
      'forms' => [
                  'items' => []
      ]
    );

    return $data;
  }

  return null;

}

?>