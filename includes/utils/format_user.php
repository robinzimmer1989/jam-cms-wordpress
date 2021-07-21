<?php

function jam_cms_format_user($user) {

  $formatted_user = [];

  $formatted_user['id']             = $user->ID;
  $formatted_user['email']          = $user->data->user_email;
  $formatted_user['capabilities']   = $user->allcaps;
  $formatted_user['roles']           = $user->roles;

  return $formatted_user;
  
}