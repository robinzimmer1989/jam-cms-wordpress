<?php

add_action( 'rest_api_init', 'gcms_api_get_post' ); 
function gcms_api_get_post() {
    register_rest_route( 'gcms/v1', '/getPost', array(
        'methods' => 'POST',
        'callback' => 'gcms_api_get_post_callback',
        'permission_callback' => function () {
            return current_user_can( 'read' );
        }
    ));
}

function gcms_api_get_post_callback($data) {
    $parameters = $data->get_params();

    $site_id    = $parameters['siteID'];
    $post_id    = $parameters['postID'];

    gcms_api_base_check($site_id, [$post_id]);
        
    $post = gcms_get_post_by_id($site_id, $post_id);

    return $post;
}

?>