<?php

add_action( 'rest_api_init', 'gcms_api_update_post_type' ); 
function gcms_api_update_post_type() {
  register_rest_route( 'gcms/v1', '/updateCollection', array(
    'methods' => 'GET',
    'callback' => 'gcms_api_update_post_type_callback'
  ));
}

function gcms_api_update_post_type_callback($data) {
    $site_id = $data->get_param('siteID');
    $post_type_name = $data->get_param('id');
    $title = $data->get_param('title');
    $slug = $data->get_param('slug');
    $template = $data->get_param('template');
    $template = $template ? json_decode($template) : [];

    $site = get_blog_details($site_id);

    if($site){
      switch_to_blog($site->blog_id);

      if($post_type_name['id'] == 'page'){
        return false;
      }

      $post_types = get_option('cptui_post_types');

      if($post_types){
          $post_types[$post_type_name]['label'] = $title;
          $post_types[$post_type_name]['singular_label'] = $title;
          $post_types[$post_type_name]['rewrite_slug'] = $slug;

          update_option('cptui_post_types', $post_types);

          return gcms_format_post_type($site_id, $post_types[$post_type_name]);
      }
    }

    return null;
}

?>