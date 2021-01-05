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
    $post_type  = $parameters['postTypeID'];
    $parent_id  = $parameters['parentID'];

    jam_cms_api_base_check($site_id, [$title, $post_type]);

    $post_id = wp_insert_post([
      'post_title'  => $title,
      'post_name'   => '',
      'post_status' => 'draft',
      'post_type'   => $post_type,
      'post_parent' => $parent_id
    ]);

    // Generate unique postname. We need the post id to do that, so we have to split the process into two steps.
    $slug = sanitize_title_with_dashes($title);
    $unique_slug = wp_unique_post_slug( $slug, $post_id, '', $post_type, $parent_id );

    wp_update_post([
      'ID'          => $post_id,
      'post_name'   => $unique_slug
    ]);

    $data = jam_cms_get_post_by_id($post_id);

    return $data;
}

?>