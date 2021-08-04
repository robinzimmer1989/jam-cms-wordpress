<?php

function jam_cms_check_for_missing_plugins(){
  $active_plugins = [];

  $site_plugins = get_option('active_plugins');

  // Site plugins are stored in the format: [0] => classic-editor/classic-editor.php
  foreach($site_plugins as $value){
    array_push($active_plugins, $value);
  }

  if(is_multisite()){
    $network_plugins = get_site_option('active_sitewide_plugins');

    // Network plugins are stored in the format: [classic-editor/classic-editor.php] => 1627265648
    foreach($network_plugins as $key => $value){
      array_push($active_plugins, $key);
    }
  }

  $missing_plugins = [];

  if(!jam_cms_array_search_partial($active_plugins, '/wp-graphql.php')){
    array_push($missing_plugins, 'WP GraphQL');
  }

  if(!jam_cms_array_search_partial($active_plugins, '/wp-gatsby.php')){
    array_push($missing_plugins, 'WP Gatsby');
  }

  if(!jam_cms_array_search_partial($active_plugins, '/acf.php')){
    array_push($missing_plugins, 'Advanced Custom Fields PRO');
  }

  if(!jam_cms_array_search_partial($active_plugins, '/wp-graphql-acf.php')){
    array_push($missing_plugins, 'WPGraphQL for Advanced Custom Fields');
  }

  if(!jam_cms_array_search_partial($active_plugins, '/custom-post-type-ui.php')){
    array_push($missing_plugins, 'Custom Post Type UI');
  }

  if(!jam_cms_array_search_partial($active_plugins, '/wp-graphql-jwt-authentication.php')){
    array_push($missing_plugins, 'WPGraphQL JWT Authentication');
  }

  if(!jam_cms_array_search_partial($active_plugins, '/wp-seo.php')){
    array_push($missing_plugins, 'Yoast SEO');
  }

  if(!jam_cms_array_search_partial($active_plugins, '/wp-graphql-yoast-seo.php')){
    array_push($missing_plugins, 'Add WPGraphQL SEO');
  }

  return $missing_plugins;
}