<?php

add_filter( 'jwt_auth_whitelist', function ( $endpoints ) {
  return array(
      '/wp-json/jamcms/v1/test',
      '/wp-json/jamcms/v1/getBuildSite',
  );
});

?>