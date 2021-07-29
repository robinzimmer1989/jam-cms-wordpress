<?php

add_action( 'rest_api_init', 'jam_cms_api_refresh_post_lock' ); 
function jam_cms_api_refresh_post_lock() {
    register_rest_route( 'jamcms/v1', '/refreshPostLock', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_refresh_post_lock_callback',
        'permission_callback' => function () {
            return current_user_can( 'edit_posts' );
        }
    ));
}

function jam_cms_api_refresh_post_lock_callback($data) {
    $parameters = $data->get_params();

    $check = jam_cms_api_base_check($parameters, ['id']);

    if(is_wp_error($check)){
        return $check;
    }

    $post_id = $parameters['id'];
    
    $is_locked = jam_cms_check_post_lock($post_id);

    if(!$is_locked){
        jam_cms_set_post_lock($post_id);
    }

    $post = jam_cms_get_post_by_id($post_id);

    return $post;
}