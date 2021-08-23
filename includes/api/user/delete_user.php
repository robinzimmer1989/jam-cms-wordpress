<?php

add_action( 'rest_api_init', 'jam_cms_api_delete_user' ); 
function jam_cms_api_delete_user() {
    register_rest_route( 'jamcms/v1', '/deleteUser', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_delete_user_callback',
        'permission_callback' => function () {
            return current_user_can( 'remove_users' );
        }
    ));
}

function jam_cms_api_delete_user_callback($data) {
    $parameters = $data->get_params();

    $check = jam_cms_api_base_check($parameters, ['id']);

    if(is_wp_error($check)){
        return $check;
    }

    $user_id    = $parameters['id'];

    // We need to generate the user object before deletion
    $data = jam_cms_get_user_by_id($user_id);

    if(is_multisite()){
        $blog_id = get_current_blog_id();
        remove_user_from_blog($user_id, $blog_id);

    }else {
        // We need to load the delete user function separately
        // https://stackoverflow.com/questions/37080849/call-to-undefined-function-wp-delete-user
        require_once(ABSPATH.'wp-admin/includes/user.php');
        
        wp_delete_user($user_id);
    }

    return $data;
}