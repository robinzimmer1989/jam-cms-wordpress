<?php

add_action( 'rest_api_init', 'jam_cms_api_create_post' ); 
function jam_cms_api_create_post() {
  register_rest_route( 'jamcms/v1', '/createPost', array(
      'methods' => 'POST',
      'callback' => 'jam_cms_api_create_post_callback',
      'permission_callback' => function () {
        return current_user_can('publish_posts');
      }
  ));
}

function jam_cms_api_create_post_callback($data) {
    $parameters = $data->get_params();

    $site_id    = $parameters['siteID'];
    $title      = $parameters['title'];
    $slug       = $parameters['slug'];
    $post_type  = $parameters['postTypeID'];
    $parent_id  = $parameters['parentID'];

    jam_cms_api_base_check($site_id, [$title, $slug, $post_type]);

    $post_data = array(
      'post_title'  => $title,
      'post_name'   => $slug,
      'post_status' => 'draft',
      'post_type'   => $post_type,
      'post_parent' => $parent_id
    );

    $post_id = wp_insert_post($post_data);

    $data = jam_cms_get_post_by_id($post_id);

    return $data;
}

?>