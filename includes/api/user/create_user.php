<?php

add_action( 'rest_api_init', 'jam_cms_api_create_user' ); 
function jam_cms_api_create_user() {
    register_rest_route( 'jamcms/v1', '/createUser', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_create_user_callback',
        'permission_callback' => function () {
            return current_user_can( 'create_users' );
        }
    ));
}

function jam_cms_api_create_user_callback($data) {
    $parameters = $data->get_params();

    $check = jam_cms_api_base_check($parameters, ['email', 'role']);

    if(is_wp_error($check)){
        return $check;
    }

    $site_id    = $parameters['siteID'];
    $email      = $parameters['email'];
    $role       = $parameters['role'];
    $send_email = array_key_exists('sendEmail', $parameters) ? $parameters['sendEmail'] : false;

    // Check if user already exists
    $user = get_user_by('email', $email);

    $password = wp_generate_password();

    if(is_multisite()) {

        if($user){
            $user_id = $user->ID;
        }else{
            // We'll use the email for the username as well
            $user_id = wpmu_create_user( $email, $password, $email );
        }

        // Use null to get current blog
        if($site_id == 'default'){
            $site_id = null;
        }

        $site = get_blog_details($site_id);

        add_user_to_blog($site->blog_id, $user_id, $role);

    }else{

        if($user){
            return new WP_Error( 'user_already_exists', __( 'User already exists' ), array( 'status' => 400 ));
        }

        $user_id = wp_insert_user([
            'user_login'    => $email,
            'user_pass'     => $password,
            'user_email'    => $email,
            'role'          => $role
        ]);   
    }

    if($send_email){
        wp_new_user_notification($user_id, null, 'user');
    }

    $data = jam_cms_get_user_by_id($user_id);

    return $data;
}