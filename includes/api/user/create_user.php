<?php

add_action( 'rest_api_init', 'jam_cms_api_create_user' ); 
function jam_cms_api_create_user() {
    register_rest_route( 'gcms/v1', '/createUser', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_create_user_callback',
        'permission_callback' => function () {
            return current_user_can( 'create_users' );
        }
    ));
}

function jam_cms_api_create_user_callback($data) {
    $parameters = $data->get_params();

    $site_id    = $parameters['siteID'];
    $email      = $parameters['email'];
    $role       = $parameters['role'];

    jam_cms_api_base_check($site_id, [$email, $role]);

    $password = wp_generate_password();

    if(is_multisite()) {

        $user = get_user_by('email', $email);

        if($user){
            $user_id = $user->ID;
        }else {
             // We'll use the email as the username as well
            $user_id = wpmu_create_user( $email, $password, $email );
        }

        $site = get_blog_details($site_id);
        add_user_to_blog($site->blog_id, $user_id, $role);

    }else{

        $user_id = wp_insert_user([
            'user_login'    => $email,
            'user_pass'     => $password,
            'user_email'    => $email,
            'role'          => $role
        ]);   
    }

    $data = jam_cms_get_user_by_id($user_id);

    return $data;
}

?>