<?php

add_action( 'rest_api_init', 'gcms_api_get_post' ); 
function gcms_api_get_post() {
    register_rest_route( 'gcms/v1', '/getPost', array(
        'methods' => 'GET',
        'callback' => 'gcms_api_get_post_callback'
    ));
}

function gcms_api_get_post_callback($data) {
    $site_id = $data->get_param('siteID');
    $post_id = $data->get_param('postID');

    $site = get_blog_details($site_id);

    if($site && $post_id){
        switch_to_blog($site->blog_id);
        
        $post = gcms_get_post_by_id($site_id, $post_id);

        return $post;
    }

    return null;
}

?>