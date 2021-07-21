<?php

function jam_cms_get_site_by_id($site_id = 'default'){

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

  // Get generic and custom taxonomies
  $taxonomies = get_taxonomies([], 'objects');
  $custom_taxonomies = get_option('cptui_taxonomies') ? get_option('cptui_taxonomies') : [];
  $all_taxonomies = array_merge($taxonomies, $custom_taxonomies);

  $formatted_taxonomies = [];
  foreach($all_taxonomies as $taxonomy){
    $taxonomy = (object) $taxonomy;

    if ($taxonomy->publicly_queryable && $taxonomy->name != 'post_format') {
        array_push($formatted_taxonomies, jam_cms_format_taxonomy($taxonomy));
    }
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

  // Get site created at date based on date of super user (Format: 2021-04-28 00:12:46)
  $created_at = get_user_option('user_registered', 1);
  
  $undeployed_changes = get_option('jam_cms_undeployed_changes');

  $editor_options = get_option('jam_cms_editor_options');

  $settings             = get_option("jam_cms_settings");
  $frontend_url         = is_array($settings) && array_key_exists("frontend_url", $settings) ? $settings['frontend_url'] : '';
  $google_maps_api_key  = is_array($settings) && array_key_exists("google_maps_api_key", $settings) && current_user_can('edit_posts') ? $settings['google_maps_api_key'] : '';
  $admin_api_key        = is_array($settings) && array_key_exists("admin_api_key", $settings) && current_user_can('manage_options') ? $settings['admin_api_key'] : '';

  $data = array(
    'id'                    => $site_id,
    'title'                 => get_bloginfo('name'),
    'siteUrl'               => $frontend_url,
    'createdAt'             => $created_at,
    'googleMapsApi'         => $google_maps_api_key,
    'frontPage'             => intval(get_option('page_on_front')),
    'apiKey'                => $admin_api_key,
    'editorOptions'         => $editor_options ? $editor_options : (object) [],
    'userRoles'             => jam_cms_get_user_roles(),
    'deployment' => [
      'lastBuild'           => $last_build,
      'undeployedChanges'   => boolval($undeployed_changes),
      'buildHook'           => $deployment_build_hook,
      'badgeImage'          => $deployment_badge_image,
      'badgeLink'           => $deployment_badge_link,      
    ],
    'themeOptions'          => jam_cms_get_option_group_fields(),
    'postTypes'   => [
      'items'               => $formatted_post_types
    ],
    'taxonomies'  => [
      'items'               => $formatted_taxonomies,
    ],
  );

  $missing_plugins = jam_cms_check_for_missing_plugins();

  if(count($missing_plugins) > 0){
    $data['errors'] = [
      0 => [
        'id'          => 'missing_plugins',
        'title'       => 'Plugins missing',
        'description' => 'Not all required plugins are installed. Please install the following plugins: ' . implode(', ', $missing_plugins)
      ]
    ];
  }

  return $data;
}