<?php

add_action( 'rest_api_init', 'jam_cms_api_deploy_site' ); 
function jam_cms_api_deploy_site() {
    register_rest_route( 'jamcms/v1', '/deploySite', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_deploy_site_callback',
        'permission_callback' => function () {
            return current_user_can( 'edit_posts' );
        }
    ));
}

function jam_cms_api_deploy_site_callback($data) {
  $parameters = $data->get_params();
  
  $check = jam_cms_api_base_check($parameters, ['id']);

  if(is_wp_error($check)){
    return $check;
  }

  $site_id = $parameters['id'];

  $jamstack_deployment_settings = get_option('wp_jamstack_deployments');

  if($jamstack_deployment_settings){
    $deployment_build_hook = $jamstack_deployment_settings['webhook_url'];
  
    if(!$deployment_build_hook){
      return new WP_Error( 'no_webhook', __( 'No Webhook found' ), array( 'status' => 400 ) );
    }

    // https://stackoverflow.com/questions/5647461/how-do-i-send-a-post-request-with-php
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query(['key' => 'value'])
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($deployment_build_hook, false, $context);

    // Successful triggers return an empty result
    if ($result != '') {
      return new WP_Error( 'invalid_webhook', __( 'Webhook not valid' ), array( 'status' => 400 ) );
    }

    // Update deployment values
    date_default_timezone_set('UTC');
    $now = date('Y-m-d H:i:s', time());

    update_option('jam_cms_last_build', $now);
    update_option('jam_cms_undeployed_changes', false);

    $data = jam_cms_get_site_by_id($site_id);

    return $data;
  }
}

?>