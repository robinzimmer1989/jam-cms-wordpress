<?php

function gcms_get_sites_by_user_id($user_id){

  $sites = get_blogs_of_user($user_id);

  $data = array();

  foreach($sites as $site){
    $site_id = $site->userblog_id;

    $settings = get_blog_option($site_id, 'gcms_custom_plugin_options');

    if($settings['site_id']){
      array_push($data, array(
        'id' => $settings['site_id'],
        'title' => $site->blogname,
        'netlifyID' =>  $settings['netlify_id'],
        'netlifyUrl' => $settings['netlify_url']
      ));
    }
  }

  return $data;

}

?>