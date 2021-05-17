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

    global $wpdb;

    $parameters = $data->get_params();

    $check = jam_cms_api_base_check($parameters, ['title', 'postTypeID']);

    if(is_wp_error($check)){
        return $check;
    }

    $site_id    = $parameters['siteID'];
    $title      = $parameters['title'];
    $post_type  = $parameters['postTypeID'];
    $parent_id  = $parameters['parentID'];

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

    // We're updating the db directly with the unique slug (vs wp_update_post) to avoid an automatic post revision.
    $wpdb->update( $wpdb->posts, ['post_name' => $unique_slug], ['ID' => $post_id]);
    
    // We need to clear the cache here, otherwise the get_post_by_id function will receive an empty post_name field.
    clean_post_cache( $post_id );

    $data = jam_cms_get_post_by_id($post_id);

    return $data;
}