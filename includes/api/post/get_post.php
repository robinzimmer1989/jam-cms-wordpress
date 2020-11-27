<?php

add_action( 'rest_api_init', 'jam_cms_api_get_post' ); 
function jam_cms_api_get_post() {
    register_rest_route( 'gcms/v1', '/getPost', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_get_post_callback',
        'permission_callback' => function () {
            return current_user_can( 'read' );
        }
    ));
}

function jam_cms_api_get_post_callback($data) {
    $parameters = $data->get_params();

    $site_id    = $parameters['siteID'];
    $post_id    = $parameters['postID'];

    jam_cms_api_base_check($site_id, [$post_id]);
        
    $post = jam_cms_get_post_by_id($site_id, $post_id);

    return $post;
}

?>