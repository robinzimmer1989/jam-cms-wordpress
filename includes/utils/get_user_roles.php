<?php

function jam_cms_get_user_roles(){
  // https://wordpress.stackexchange.com/questions/1665/getting-a-list-of-currently-available-roles-on-a-wordpress-site
  global $wp_roles;

  $roles = apply_filters('editable_roles', $wp_roles->roles);

  $formatted_roles = [];

  foreach($roles as $key => $role){
    array_push($formatted_roles, [
      'id'    => $key,
      'name'  => $role['name']
    ]);
  }

  return $formatted_roles;
}