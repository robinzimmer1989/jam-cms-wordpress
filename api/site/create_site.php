<?php

add_action( 'rest_api_init', 'gcms_api_create_site' ); 
function gcms_api_create_site() {
    register_rest_route( 'gcms/v1', '/createSite', array(
        'methods' => 'GET',
        'callback' => 'gcms_api_create_site_callback'
    ));
}

function gcms_api_create_site_callback($data) {
    $title = $data->get_param('title');
    $user_id = $data->get_param('userID');
    $email = $data->get_param('email');

    if($title && $user_id && $email){

      # Create a new user
      $password = wp_generate_password( 12, false );
      $wp_user_id = wpmu_create_user( $user_id, $password, $email );
    
      # Get site url + path
      $protocols = array('http://', 'http://www.', 'www.');
      $url = str_replace($protocols, '', get_site_url());
      $path = wp_generate_uuid4();

      # Create site
      $site_id = wpmu_create_blog( $url, $path, $title, $wp_user_id , array( 'public' => 0 ) );

      // If wpUserID is false, it means the email address is already taken
      // Therefore, we have to assign the existing user to the blog manually
      if(!$wp_user_id){
        $user = get_user_by('email', $email);

        if($user){
            add_user_to_blog( $site_id, $user->ID, get_site_option( 'default_user_role', 'administrator' ) );
        }
      }

      # TODO: Create site on Netlify

      # TODO: Delete sample page and hello world posts
      // $homepage = get_page_by_title( 'Sample Page' );

      update_blog_option( $site_id, 'gcms_custom_plugin_options', array(
        'site_id' => $path,
        'api_key' => wp_generate_uuid4(),
        'netlify_id' => '',
        'netlify_url' => ''
      ));

      return $path;
    }

    return null;
}

?>