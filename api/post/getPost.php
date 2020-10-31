<?php

add_action( 'rest_api_init', 'gcms_actions_getPost' ); 
function gcms_actions_getPost() {
    register_rest_route( 'wp/v2', '/getPost', array(
        'methods' => 'GET',
        'callback' => 'gcms_actions_getPost_callback'
    ));
}

function gcms_actions_getPost_callback($data) {
    $siteID = $data->get_param('siteID');
    $postID = $data->get_param('postID');

    $site = get_blog_details($siteID);

    if($site && $postID){
        switch_to_blog($site->blog_id);
        
        $post = gcms_resolver_getPostByID($siteID, $postID);

        return $post;
    }

    return 'Something went wrong';
}

?>