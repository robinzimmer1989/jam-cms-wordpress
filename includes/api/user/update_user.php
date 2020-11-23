<?php

add_action( 'rest_api_init', 'gcms_api_update_user' ); 
function gcms_api_update_user() {
    register_rest_route( 'gcms/v1', '/updateUser', array(
        'methods' => 'POST',
        'callback' => 'gcms_api_update_user_callback',
        'permission_callback' => function ($data) {
            $parameters = $data->get_params();

            if(!array_key_exists('id', $parameters)){
                return false;
            }

            return current_user_can('edit_user', $parameters['id']);
        }
    ));
}

function gcms_api_update_user_callback($data) {
    $parameters = $data->get_params();

    $site_id    = $parameters['siteID'];
    $user_id    = $parameters['id'];
    $role       = $parameters['role'];

    gcms_api_base_check($site_id, [$user_id, $role]);

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

    $data = gcms_get_user_by_id($user_id);

    return $data;
}

?>