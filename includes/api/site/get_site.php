<?php

add_action( 'rest_api_init', 'jam_cms_api_get_site' ); 
function jam_cms_api_get_site() {
    register_rest_route( 'jamcms/v1', '/getSite', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_get_site_callback',
        'permission_callback' => function () {
            return current_user_can('edit_posts');
        }
    ));
}

function jam_cms_api_get_site_callback($data) {
    $parameters = $data->get_params();

    $check = jam_cms_api_base_check($parameters);

    if(is_wp_error($check)){
        return $check;
    }

    $data = jam_cms_get_site_by_id();

    return $data;
}