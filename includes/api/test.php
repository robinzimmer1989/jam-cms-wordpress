<?php

add_action( 'rest_api_init', 'jam_cms_api_test' ); 
function jam_cms_api_test() {
    register_rest_route( 'jamcms/v1', '/test', array(
        'methods' => 'GET',
        'callback' => 'jam_cms_api_test_callback',
        'permission_callback' => function () {
          return true;
      }
    ));
}


function jam_cms_api_test_callback($data) {
  return null;
}