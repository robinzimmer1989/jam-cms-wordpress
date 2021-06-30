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

  if(!in_array('wp-graphql/wp-graphql.php', $active_plugins)){
    array_push($missing_plugins, 'WP GraphQL');
  }

  if(!in_array('wp-gatsby/wp-gatsby.php', $active_plugins)){
    array_push($missing_plugins, 'WP Gatsby');
  }

  if(!in_array('advanced-custom-fields-pro/acf.php', $active_plugins)){
    array_push($missing_plugins, 'Advanced Custom Fields PRO');
  }

  if(!in_array('wp-graphql-acf-0.5.2/wp-graphql-acf.php', $active_plugins)){
    array_push($missing_plugins, 'WPGraphQL for Advanced Custom Fields');
  }

  if(!in_array('custom-post-type-ui/custom-post-type-ui.php', $active_plugins)){
    array_push($missing_plugins, 'Custom Post Type UI');
  }

  if(!in_array('wp-graphql-jwt-authentication-0.4.1/wp-graphql-jwt-authentication.php', $active_plugins)){
    array_push($missing_plugins, 'WPGraphQL JWT Authentication');
  }

  if(!in_array('wordpress-seo/wp-seo.php', $active_plugins)){
    array_push($missing_plugins, 'Yoast SEO');
  }

  if(!in_array('add-wpgraphql-seo/wp-graphql-yoast-seo.php', $active_plugins)){
    array_push($missing_plugins, 'Add WPGraphQL SEO');
  }

  return $missing_plugins;
}