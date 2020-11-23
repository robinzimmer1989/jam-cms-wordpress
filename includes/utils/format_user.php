<?php

function gcms_format_user($user) {

  $formatted_user = [];

  $formatted_user['id']             = $user->ID;
  $formatted_user['email']          = $user->data->user_email;
  $formatted_user['capabilities']   = $user->allcaps;
  $formatted_user['role']           = count($user->roles) > 0 ? $user->roles[0] : '';

  return $formatted_user;
  
}

?>