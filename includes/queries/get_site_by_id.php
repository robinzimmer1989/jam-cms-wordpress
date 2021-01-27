<?php

function jam_cms_get_site_by_id($site_id = ''){

  // Get generic and custom post types
  $post_types = get_post_types([], 'objects');
  $custom_post_types = get_option('cptui_post_types') ? get_option('cptui_post_types') : [];
  $all_post_types = array_merge($post_types, $custom_post_types);

  $formatted_post_types = [];
  foreach ($all_post_types as $post_type){
    // Custom post types are constructed as an array so we have to convert them
    $post_type = (object) $post_type;

    if ($post_type->publicly_queryable && $post_type->name != 'attachment') {
        array_push($formatted_post_types, jam_cms_format_post_type($post_type));
    }
  }

  // Get custom taxonomies
  $taxonomies = get_option('cptui_taxonomies') ? get_option('cptui_taxonomies') : [];

  $formatted_taxonomies = [];
  foreach($taxonomies as $taxonomy){
    array_push($formatted_taxonomies, jam_cms_format_taxonomy($taxonomy));
  }

  // Get deployment info
  $deployment_build_hook = '';
  $deployment_badge_image = '';
  $deployment_badge_link = '';

  $jamstack_deployment_settings = get_option('wp_jamstack_deployments');

  if($jamstack_deployment_settings){
    $deployment_build_hook = $jamstack_deployment_settings['webhook_url'];
    $deployment_badge_image = $jamstack_deployment_settings['deployment_badge_url'];
    $deployment_badge_link = $jamstack_deployment_settings['deployment_badge_link_url'];
  }

  $last_build = get_option('jam_cms_last_build');
  $undeployed_changes = get_option('jam_cms_undeployed_changes');

  $deployment_api_key = '';
  if(current_user_can('manage_options')){
    $deployment_api_key = get_option('deployment_api_key');
  }

  $google_maps_api_key= '';
  if(current_user_can('edit_posts')){
    $google_maps_api_key = get_option('jam_cms_google_maps_api_key');
  }

  $site_url = get_option('site_url');

  $data = array(
    'id'                    => $site_id ? $site_id : 'default',
    'title'                 => get_bloginfo('name'),
    'siteUrl'               => $site_url ? $site_url : '',
    'googleMapsApi'         => $google_maps_api_key ? $google_maps_api_key : '',
    'frontPage'             => intval(get_option( 'page_on_front' )),
    'apiKey'                => $deployment_api_key ? $deployment_api_key : '',
    'deployment'            => [
      'lastBuild'           => $last_build,
      'undeployedChanges'   => boolval($undeployed_changes),
      'buildHook'           => $deployment_build_hook,
      'badgeImage'          => $deployment_badge_image,
      'badgeLink'           => $deployment_badge_link,      
    ],
    'globalOptions'         => jam_cms_get_option_group_fields(),
    'postTypes' => [
      'items'               => $formatted_post_types
    ],
    'taxonomies' => [
      'items'               => $formatted_taxonomies,
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