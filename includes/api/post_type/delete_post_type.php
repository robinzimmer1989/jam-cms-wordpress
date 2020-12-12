<?php

add_action( 'rest_api_init', 'jam_cms_api_delete_post_type' ); 
function jam_cms_api_delete_post_type() {
  register_rest_route( 'jamcms/v1', '/deleteCollection', array(
    'methods' => 'POST',
    'callback' => 'jam_cms_api_delete_post_type_callback',
    'permission_callback' => function () {
      return current_user_can( 'manage_options' );
    }
  ));
}

function jam_cms_api_delete_post_type_callback($data) {
    $parameters   = $data->get_params();

    $site_id      = $parameters['siteID'];
    $post_type_id = $parameters['id'];

    jam_cms_api_base_check($site_id, [$post_type_id]);

    $post_types = get_option('cptui_post_types');

    if($post_types){

      unset($post_types[$post_type_id]);

      update_option('cptui_post_types', $post_types);

      // Delete ACF field group
      acf_delete_field_group("group_template-{$post_type_id}");

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