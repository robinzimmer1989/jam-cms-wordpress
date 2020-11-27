<?php

add_action( 'rest_api_init', 'jam_cms_api_get_sites' ); 
function jam_cms_api_get_sites() {
    register_rest_route( 'jamcms/v1', '/getSites', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_get_sites_callback',
        'permission_callback' => function () {
            return current_user_can( 'edit_posts' );
        }
    ));
}

function jam_cms_api_get_sites_callback($data) {

    if(is_multisite()){
        $user_id = get_current_user_id();
        $data = jam_cms_get_sites_by_user_id($user_id);
    }else{

        $data = [
            'site' => jam_cms_get_site_by_id()
        ];
    }

    return $data;
}

?>