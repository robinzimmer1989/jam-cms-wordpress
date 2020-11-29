<?php

add_filter( 'jwt_auth_whitelist', function ( $endpoints ) {

  if(is_multisite()){
    $site = get_blog_details();

    return array(
      $site->path . 'wp-json/jamcms/v1/test',
      $site->path . 'wp-json/jamcms/v1/getBuildSite',
    );
  }else{
    return array(
      '/wp-json/jamcms/v1/test',
      '/wp-json/jamcms/v1/getBuildSite',
    );
  }
});

?>