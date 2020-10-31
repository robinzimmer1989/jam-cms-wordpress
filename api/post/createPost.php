<?php

add_action( 'rest_api_init', 'gcms_actions_createPost' ); 
function gcms_actions_createPost() {
    register_rest_route( 'wp/v2', '/createPost', array(
        'methods' => 'GET',
        'callback' => 'gcms_actions_createPost_callback'
    ));
}

function gcms_actions_createPost_callback($data) {
    $siteID = $data->get_param('siteID');
    $title = $data->get_param('title');
    $slug = $data->get_param('slug');
    $postType = $data->get_param('postTypeID');
    $parentID = $data->get_param('parentID');

    $site = get_blog_details($siteID);

    if($site && $title && $slug && $postType){
      
      $blogID = $site->blog_id;
      switch_to_blog($blogID);

      $postData = array(
        'post_title' => $title,
        'post_name' => $slug,
        'post_status' => 'draft',
        'post_type' => $postType,
        'post_parent' => $parentID || 0
      );

      $postID = wp_insert_post($postData);

      $data = gcms_resolver_getPostByID($siteID, $postID);
      return $data;
    }

    return 'Something went wrong';
}

?>