<?php

add_action( 'rest_api_init', 'gcms_api_test' ); 
function gcms_api_test() {
    register_rest_route( 'gcms/v1', '/test', array(
        'methods' => 'POST',
        'callback' => 'gcms_api_test_callback'
    ));
}

function gcms_api_test_callback($data) {
  $parameters = $data->get_params();

  $site_id = 'd8e07ad3-8bd8-49f5-8864-0bc47b219f69';

  $site = get_blog_details($site_id);
  switch_to_blog($site->blog_id);

  
}

?>