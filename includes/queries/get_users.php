<?php

function jam_cms_get_users($limit = 10, $page = 0){

  $users = get_users([
    'number' => $limit,
    'offset' => $page * $limit
  ]);

  $current_user_id = get_current_user_id();

  $formatted_users = [];
  foreach($users as $user){
    // Don't return super admin and current user profile
    if($user->ID != 1 && $user->ID != $current_user_id){
      array_push($formatted_users, jam_cms_format_user($user));
    }
  }

  return $formatted_users;
}

?>