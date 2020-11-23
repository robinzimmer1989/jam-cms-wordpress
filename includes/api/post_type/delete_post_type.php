<?php

add_action( 'rest_api_init', 'gcms_api_delete_post_type' ); 
function gcms_api_delete_post_type() {
  register_rest_route( 'gcms/v1', '/deleteCollection', array(
    'methods' => 'POST',
    'callback' => 'gcms_api_delete_post_type_callback',
    'permission_callback' => function () {
      return current_user_can( 'manage_options' );
    }
  ));
}

function gcms_api_delete_post_type_callback($data) {
    $parameters   = $data->get_params();

    $site_id      = $parameters['siteID'];
    $post_type_id = $parameters['id'];

    gcms_api_base_check($site_id, [$post_id]);

    $post_types = get_option('cptui_post_types');

    if($post_types){

      unset($post_types[$post_type_id]);

      update_option('cptui_post_types', $post_types);

      $post_type = [
        'siteID' => $site_id,
        'id' => $post_type_id,
        'slug' => '',
        'title' => '',
        'template' => [],
        'posts' => [
          'items' => []
        ],
      ];

      return $post_type;
    }
}

?>