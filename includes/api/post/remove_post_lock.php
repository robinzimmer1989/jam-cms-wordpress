<?php

add_action( 'rest_api_init', 'jam_cms_api_remove_post_lock' ); 
function jam_cms_api_remove_post_lock() {
    register_rest_route( 'jamcms/v1', '/removePostLock', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_remove_post_lock_callback',
        'permission_callback' => function () {
            return current_user_can( 'edit_posts' );
        }
    ));
}

function jam_cms_api_remove_post_lock_callback($data) {
    $parameters = $data->get_params();

    $check = jam_cms_api_base_check($parameters, ['id']);

    if(is_wp_error($check)){
        return $check;
    }

    $post_id = $parameters['id'];

    jam_cms_remove_post_lock($post_id);

    $post = jam_cms_get_post_by_id($post_id);

    // In case the post gets deleted before removing the post lock, the application throws an error "Post not found".
    // But we don't need to inform the editor about this so we gonna return false instead.
    if(is_wp_error($post)){
        return false;
    }

    return $post;
}