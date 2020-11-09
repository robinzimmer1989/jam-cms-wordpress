<?php

add_action( 'rest_api_init', 'gcms_api_test' ); 
function gcms_api_test() {
    register_rest_route( 'gcms/v1', '/test', array(
        'methods' => 'GET',
        'callback' => 'gcms_api_test_callback'
    ));
}

function gcms_api_test_callback($data) {

  $site_id = '7b13ed55-dc26-4529-ae81-b2f8406544fb';

  $site = get_blog_details($site_id);
  switch_to_blog($site->blog_id);



  $menu_items = wp_get_nav_menu_items(2);
  // return $menu_items;
  return gcms_build_menu_tree($menu_items);

}

?>