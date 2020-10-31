<?php

add_action( 'rest_api_init', 'gcms_actions_getSites' ); 
function gcms_actions_getSites() {
    register_rest_route( 'wp/v2', '/getSites', array(
        'methods' => 'GET',
        'callback' => 'gcms_actions_getSites_callback'
    ));
}

function gcms_actions_getSites_callback($data) {
    $userID = $data->get_param('userID');

    $user = get_user_by('slug', $userID);

    if($user){
      $data = gcms_resolver_getSitesByUserID($user->ID);
      return $data;
    }

    return null;
}

?>