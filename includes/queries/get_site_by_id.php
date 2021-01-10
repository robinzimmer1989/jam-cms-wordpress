<?php

function jam_cms_get_site_by_id($site_id = ''){

  // Get generic and custom post types
  $post_types = get_post_types([], 'objects');
  $custom_post_types = get_option('cptui_post_types') ? get_option('cptui_post_types') : [];
  $all_post_types = array_merge($post_types, $custom_post_types);

  $items = [];
  foreach ( $all_post_types as $post_type ) {
    // Custom post types are constructed as an array so we have to convert them
    $post_type = (object) $post_type;

    if ($post_type->publicly_queryable && $post_type->name != 'attachment') {
        array_push($items, jam_cms_format_post_type($post_type));
    }
  }

  $deployment_build_hook = '';
  $deployment_badge_image = '';
  $deployment_badge_link = '';

  $jamstack_deployment_settings = get_option('wp_jamstack_deployments');

  if($jamstack_deployment_settings){
    $deployment_build_hook = $jamstack_deployment_settings['webhook_url'];
    $deployment_badge_image = $jamstack_deployment_settings['deployment_badge_url'];
    $deployment_badge_link = $jamstack_deployment_settings['deployment_badge_link_url'];
  }

  $api_key = '';

  if(current_user_can( 'manage_options' )){
    $api_key = get_option('deployment_api_key');
  }

  $last_build = get_option('jam_cms_last_build');
  $undeployed_changes = get_option('jam_cms_undeployed_changes');

  $data = array(
    'id'                    => $site_id ? $site_id : 'default',
    'title'                 => get_bloginfo('name'),
    'deployment'            => [
      'lastBuild'           => $last_build,
      'undeployedChanges'   => boolval($undeployed_changes),
      'buildHook'           => $deployment_build_hook,
      'badgeImage'          => $deployment_badge_image,
      'badgeLink'           => $deployment_badge_link,
    ],
    'apiKey'                => $api_key ? $api_key : '',
    'globalOptions'              => jam_cms_get_option_group_fields(),
    'frontPage'             => intval(get_option( 'page_on_front' )),
    'postTypes' => [
      'items'               => $items
    ],
    'forms' => [
      'items'               => []
    ],
    'mediaItems' => [
      'items'               => [],
      'page'                => null
    ],
    'users' => [
      'items'               => [],
      'page'                => null
    ],
  );

  $missing_plugins = jam_cms_check_for_missing_plugins();

  if(count($missing_plugins) > 0){
    $data['errors'] = [
      0 => [
        'title'       => 'Plugins missing',
        'description' => 'Not all required plugins are installed. Please install the following plugins: ' . implode(', ', $missing_plugins)
      ]
    ];
  }

  return $data;
}

?>