<?php

add_action( 'rest_api_init', 'gcms_actions_createSite' ); 
function gcms_actions_createSite() {
    register_rest_route( 'wp/v2', '/createSite', array(
        'methods' => 'GET',
        'callback' => 'gcms_actions_createSite_callback'
    ));
}

function gcms_actions_createSite_callback($data) {
    $title = $data->get_param('title');
    $userID = $data->get_param('userID');
    $email = $data->get_param('email');

    if($title && $userID && $email){

      # Create a new user
      $password = wp_generate_password( 12, false );
      $wpUserID = wpmu_create_user( $userID, $password, $email );
    
      # Get site url + path
      $protocols = array('http://', 'http://www.', 'www.');
      $url = str_replace($protocols, '', get_site_url());
      $path = wp_generate_uuid4();

      # Create site
      $siteID = wpmu_create_blog( $url, $path, $title, $wpUserID , array( 'public' => 0 ) );

      // If wpUserID is false, it means the email address is already taken
      // Therefore, we have to assign the existing user to the blog manually
      if(!$wpUserID){
        $user = get_user_by('email', $email);

        if($user){
            add_user_to_blog( $siteID, $user->ID, get_site_option( 'default_user_role', 'administrator' ) );
        }
      }

      # TODO: Create site on Netlify

      update_blog_option( $siteID, 'gcms_custom_plugin_options', array(
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