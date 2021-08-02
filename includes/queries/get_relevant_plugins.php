<?php

function jam_cms_get_relevant_plugins(){

  // Get all active plugins and return relevant information to source-plugin
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

  $relevant_plugins = [];

  if(jam_cms_array_search_partial($active_plugins, '/wp-seo.php') && jam_cms_array_search_partial($active_plugins, '/wp-graphql-yoast-seo.php')){
    array_push($relevant_plugins, 'yoast');
  }

  if(jam_cms_array_search_partial($active_plugins, '/polylang.php') && jam_cms_array_search_partial($active_plugins, '/wp-graphql-polylang.php')){
    array_push($relevant_plugins, 'polylang');
  }

  if(jam_cms_array_search_partial($active_plugins, '/post-types-order.php')){
    array_push($relevant_plugins, 'postTypeOrder');
  }

  if(jam_cms_array_search_partial($active_plugins, '/woocommerce.php')){
    array_push($relevant_plugins, 'woocommerce');
  }

  return $relevant_plugins;
}