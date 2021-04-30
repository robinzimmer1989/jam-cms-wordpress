<?php

add_action( 'rest_api_init', 'jam_cms_api_get_post' ); 
function jam_cms_api_get_post() {
    register_rest_route( 'jamcms/v1', '/getPost', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_get_post_callback',
        'permission_callback' => function () {
            return current_user_can( 'edit_posts' );
        }
    ));
}

function jam_cms_api_get_post_callback($data) {
    $parameters = $data->get_params();

    jam_cms_api_base_check($parameters, ['postID']);

    $site_id    = $parameters['siteID'];
    $post_id    = $parameters['postID'];
        
    $post = jam_cms_get_post_by_id($post_id);

    return $post;
}

?>