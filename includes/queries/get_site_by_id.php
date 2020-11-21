<?php

function gcms_get_site_by_id($site_id){

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

  $deployment_build_hook = '';
  $deployment_badge_image = '';
  $deployment_badge_link = '';

  $jamstack_deployment_settings = get_option('wp_jamstack_deployments');

  if($jamstack_deployment_settings){
    $deployment_build_hook = $jamstack_deployment_settings['webhook_url'];
    $deployment_badge_image = $jamstack_deployment_settings['deployment_badge_url'];
    $deployment_badge_link = $jamstack_deployment_settings['deployment_badge_link_url'];
  }

  $api_key = get_option('deployment_api_key');

  $data = array(
    'id'                    => $site_id,
    'title'                 => get_bloginfo('name'),
    'deploymentBuildHook'   => $deployment_build_hook,
    'deploymentBadgeImage'  => $deployment_badge_image,
    'deploymentBadgeLink'   => $deployment_badge_link,
    'apiKey'                => $api_key ? $api_key : '',
    'multisite'             => is_multisite(),
    'settings'              => [
      'header' => [
        'name'              => 'header',
        'fields'            => $header_fields
      ],
      'footer' => [
      'name'                => 'footer',
        'fields'            => $footer_fields
      ]
    ],
    'frontPage'             => intval(get_option( 'page_on_front' )),
    'postTypes' => [
      'items'               => $items
    ],
    'forms' => [
      'items'               => []
    ]
  );

  return $data;
}

?>