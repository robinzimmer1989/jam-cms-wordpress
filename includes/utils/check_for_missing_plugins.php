<?php

function jam_cms_check_for_missing_plugins(){
  $active_plugins = [];

  $site_plugins = get_option('active_plugins');

  foreach($site_plugins as $key => $value){
    array_push($active_plugins, $key);
  }

  if(is_multisite()){
    $network_plugins = get_site_option('active_sitewide_plugins');

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