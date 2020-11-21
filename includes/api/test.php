<?php

add_action( 'rest_api_init', 'gcms_api_test' ); 
function gcms_api_test() {
    register_rest_route( 'gcms/v1', '/test', array(
        'methods' => 'POST',
        'callback' => 'gcms_api_test_callback'
    ));
}

function gcms_api_test_callback($data) {
  // $parameters = $data->get_params();

  // $site_id = '06ddf2e6-2589-4896-b5a7-b76bc58f2f2d';

  // $site = get_blog_details($site_id);
  // switch_to_blog($site->blog_id);

  // gcms_get_site_for_build_by_id($site_id);
}

?>