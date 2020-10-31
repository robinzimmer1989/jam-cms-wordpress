<?php

function gcms_resolver_getSitesByUserID($userID){

  $sites = get_blogs_of_user($userID);

  $data = array();

  foreach($sites as $site){
    $siteID = $site->userblog_id;

    $settings = get_blog_option($siteID, 'gcms_custom_plugin_options');

    array_push($data, array(
      'id' => $settings['site_id'],
      'title' => $site->blogname,
      'netlifyID' =>  $settings['netlify_id'],
      'netlifyUrl' => $settings['netlify_url']
    ));
  }

  return $data;

}

?>