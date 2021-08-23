<?php

add_action( 'rest_api_init', 'jam_cms_api_get_post' ); 
function jam_cms_api_get_post() {
    register_rest_route( 'jamcms/v1', '/getPost', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_get_post_callback',
        'permission_callback' => function () {
            return current_user_can('read');
        }
    ));
}

function jam_cms_api_get_post_callback($data) {
    $parameters = $data->get_params();

    $check = jam_cms_api_base_check($parameters, ['id']);

    if(is_wp_error($check)){
        return $check;
    }

    $post_id = $parameters['id'];

    if(current_user_can('edit_posts')){
        // Check if post is locked
        $is_locked = jam_cms_check_post_lock($post_id);

        if(!$is_locked){
            // Lock post so other users can't edit it at the same time
            jam_cms_set_post_lock($post_id);
        }
    }

    $post = jam_cms_get_post_by_id($post_id);

    return $post;
}