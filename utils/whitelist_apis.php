<?php

add_filter( 'jwt_auth_whitelist', function ( $endpoints ) {
  return array(
      '/wp-json/gcms/v1/buildSite',
  );
});

?>