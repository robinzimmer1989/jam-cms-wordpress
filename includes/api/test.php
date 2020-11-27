<?php

add_action( 'rest_api_init', 'jam_cms_api_test' ); 
function jam_cms_api_test() {
    register_rest_route( 'jamcms/v1', '/test', array(
        'methods' => 'GET',
        'callback' => 'jam_cms_api_test_callback'
    ));
}


function jam_cms_api_test_callback($data) {
  // $parameters = $data->get_params();

  // $site_id = '06ddf2e6-2589-4896-b5a7-b76bc58f2f2d';

  // $site = get_blog_details($site_id);
  // switch_to_blog($site->blog_id);
  
  // $menu_items = get_option('active_plugins');


}

?>