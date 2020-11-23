<?php

function gcms_get_users($limit = 10, $page = 0){

  $users = get_users([
    'number' => $limit,
    'offset' => $page * $limit
  ]);

  $formatted_users = [];
  foreach($users as $user){
    // Don't return super admin
    if($user->ID != 1){
      array_push($formatted_users, gcms_format_user($user));
    }
  }

  return $formatted_users;
}

?>