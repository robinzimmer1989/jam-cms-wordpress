<?php

add_action( 'rest_api_init', 'gcms_api_delete_post_type' ); 
function gcms_api_delete_post_type() {
  register_rest_route( 'gcms/v1', '/deleteCollection', array(
    'methods' => 'POST',
    'callback' => 'gcms_api_delete_post_type_callback'
  ));
}

function gcms_api_delete_post_type_callback($data) {
    $parameters = $data->get_params();

    $site_id = $parameters['siteID'];
    $post_type_id = $parameters['id'];

    $site = get_blog_details($site_id);

    if($site){
      switch_to_blog($site->blog_id);

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

    return null;
}

?>