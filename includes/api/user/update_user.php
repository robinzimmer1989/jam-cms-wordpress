<?php

add_action( 'rest_api_init', 'jam_cms_api_update_user' ); 
function jam_cms_api_update_user() {
    register_rest_route( 'jamcms/v1', '/updateUser', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_update_user_callback',
        'permission_callback' => function ($data) {
            $parameters = $data->get_params();

            if(!array_key_exists('id', $parameters)){
                return false;
            }

            return current_user_can('edit_user', $parameters['id']);
        }
    ));
}

function jam_cms_api_update_user_callback($data) {
    $parameters = $data->get_params();

    $check = jam_cms_api_base_check($parameters, ['id', 'role']);

    if(is_wp_error($check)){
        return $check;
    }

    $site_id    = $parameters['siteID'];
    $user_id    = $parameters['id'];
    $role       = $parameters['role'];

    // Make sure super admin can't be updated
    if($user_id == 1){
        return new WP_Error( 'no_update_super_admin', __( 'Can\'t update super admin' ), array( 'status' => 403 ));
    }

    if(is_multisite()){
        $site = get_blog_details($site_id);
        add_user_to_blog($site->blog_id, $user_id, $role);

    }else{

        $user = new WP_User($user_id);
        $user_data = get_userdata($user_id);
        $user_roles = $user_data->roles;
        
        foreach($user_roles as $user_role) {
            $user->remove_role($user_role);
        }

        $user->add_role($role);
    }

    $data = jam_cms_get_user_by_id($user_id);

    return $data;
}