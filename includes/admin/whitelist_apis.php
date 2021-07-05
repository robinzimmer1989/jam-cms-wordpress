<?php

add_filter( 'jwt_auth_whitelist', function ( $endpoints ) { 

  if(is_multisite()){
    $site = get_blog_details();

    $array = array_merge($endpoints, [
      $site->path . 'wp-json/jamcms/v1/test',
      $site->path . 'wp-json/jamcms/v1/getBuildSite',
    ]);
  }else{
    $array = array_merge($endpoints, [
      '/wp-json/jamcms/v1/test',
      '/wp-json/jamcms/v1/getBuildSite',
    ]);
  }

  return $array;
});