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

    if(!$taxonomy->publicly_queryable){
      continue;
    }

    if($taxonomy->name == 'post_format'){
      continue;
    }

    // Exclude language taxonomy but only if Polylang is active
    if(class_exists('Polylang') && $taxonomy->name == 'language'){
      continue;
    }

    array_push($formatted_taxonomies, jam_cms_format_taxonomy($taxonomy));
  }

  // Get site created at date based on date of super user (Format: 2021-04-28 00:12:46)
  $created_at = get_user_option('user_registered', 1);

  $settings = get_option("jam_cms_settings");
  
  $frontend_url = is_array($settings) && array_key_exists("frontend_url", $settings) ? $settings['frontend_url'] : '';

  $site = array(
    'id'                    => $site_id,
    'title'                 => get_bloginfo('name'),
    'siteUrl'               => $frontend_url,
    'createdAt'             => $created_at,
    'frontPage'             => intval(get_option('page_on_front')),
    'themeOptions'          => jam_cms_get_option_group_fields(),
    'postTypes'   => [
      'items'               => $formatted_post_types
    ],
    'taxonomies'  => [
      'items'               => $formatted_taxonomies,
    ],
  );

  if(current_user_can('manage_options')){
    $site['apiKey'] = is_array($settings) && array_key_exists("admin_api_key", $settings) ? $settings['admin_api_key'] : '';
  }

  if(current_user_can('edit_posts')){
    // API keys
    $site['googleMapsApi'] = is_array($settings) && array_key_exists("google_maps_api_key", $settings) ? $settings['google_maps_api_key'] : '';

    // Editor options
    $editor_options = get_option('jam_cms_editor_options');
    $site['editorOptions'] = $editor_options ? $editor_options : (object) [];

    // Get deployment info
    $last_build         = get_option('jam_cms_last_build');
    $undeployed_changes = get_option('jam_cms_undeployed_changes');

    $deployment = [
      'lastBuild'           => $last_build,
      'undeployedChanges'   => boolval($undeployed_changes),
      'buildHook'           => '',
      'badgeImage'          => '',
      'badgeLink'           => '',      
    ];

    $jamstack_deployment_settings = get_option('wp_jamstack_deployments');

    if($jamstack_deployment_settings){
      $deployment['buildHook']  = $jamstack_deployment_settings['webhook_url'];
      $deployment['badgeImage'] = $jamstack_deployment_settings['deployment_badge_url'];
      $deployment['badgeLink']  = $jamstack_deployment_settings['deployment_badge_link_url'];
    }

    $site['deployment'] = $deployment;
  }

  if(current_user_can('list_users')){
    $site['userRoles'] = jam_cms_get_user_roles();
  }

  $missing_plugins = jam_cms_check_for_missing_plugins();

  if(count($missing_plugins) > 0){
    $site['errors'] = [
      0 => [
        'id'          => 'missing_plugins',
        'title'       => 'Plugins missing',
        'description' => 'Not all required plugins are installed. Please install the following plugins: ' . implode(', ', $missing_plugins)
      ]
    ];
  }

  // Add languages if supported
  if(class_exists('Polylang')){
    $site['languages'] = jam_cms_get_languages();
  }

  return $site;
}