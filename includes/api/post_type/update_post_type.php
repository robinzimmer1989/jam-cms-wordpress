<?php

add_action( 'rest_api_init', 'jam_cms_api_update_post_type' ); 
function jam_cms_api_update_post_type() {
  register_rest_route( 'jamcms/v1', '/updateCollection', array(
    'methods' => 'POST',
    'callback' => 'jam_cms_api_update_post_type_callback',
    'permission_callback' => function () {
      return current_user_can( 'manage_options' );
    }
  ));
}

function jam_cms_api_update_post_type_callback($data) {
    $parameters       = $data->get_params();

    jam_cms_api_base_check($parameters, ['id', 'title', 'slug']);

    $site_id          = $parameters['siteID'];
    $post_type_name   = $parameters['id'];
    $title            = $parameters['title'];
    $slug             = $parameters['slug'];

    if($post_type_name != 'page'){
      $post_types = get_option('cptui_post_types');

      if($post_types){

          $post_types[$post_type_name]['label']                = $title;
          $post_types[$post_type_name]['singular_label']       = $title;
          $post_types[$post_type_name]['rewrite_slug']         = $slug ? $slug : '/';
          $post_types[$post_type_name]['graphql_single_name']  = "{$title}";
          $post_types[$post_type_name]['graphql_plural_name']  = "{$title}Multiple";

          update_option('cptui_post_types', $post_types);   
      }
    }

    $post_type = jam_cms_format_post_type($post_types[$post_type_name]);

    return $post_type;
}

?>