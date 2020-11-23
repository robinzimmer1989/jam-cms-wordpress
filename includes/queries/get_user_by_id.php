<?php

function gcms_get_user_by_id($user_id){

  $user = get_user_by('ID', $user_id);

  if($user){
    $formatted_user = gcms_format_user($user);

    return $formatted_user;
  }

  return new WP_Error( 'user_not_found', __('User not found'), array( 'status' => 400 ) );
}

?>