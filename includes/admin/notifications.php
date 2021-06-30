<?php

add_action('admin_notices', 'jam_cms_admin_notifications');
function jam_cms_admin_notifications(){
  global $pagenow;

  $missing_plugins = jam_cms_check_for_missing_plugins();

  if(count($missing_plugins) > 0){
    echo '<div class="notice notice-error">
      <p>Not all required plugins are installed. Please install the following plugins: ' . implode(', ', $missing_plugins) . '</p>
    </div>';
  }

  // Check if WpGraphQL JWT secret is defined (via filter or global variable)
  if(!array_search('WPGraphQL JWT Authentication', $missing_plugins) && !defined('GRAPHQL_JWT_AUTH_SECRET_KEY') && !has_filter('graphql_jwt_auth_secret_key')){
    echo '<div class="notice notice-error">
      <p>The plugin WPGraphQL JWT Authentication is installed, but no secret key is defined. Please follow the plugin instructions and add a secret key.</p>
    </div>';
  }
}
