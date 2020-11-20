<?php

function gcms_get_sites_by_user_id($user_id){
  $sites = get_blogs_of_user($user_id);

  $data = array();

  foreach($sites as $site){
    $internal_site_id = $site->userblog_id;

    // Get path and remove slashes at beginning and end
    $site_id = str_replace('/', '', $site->path);

    // Remove master site
    if($site_id){

      $jamstack_deployment_settings = get_option('wp_jamstack_deployments');

      if($jamstack_deployment_settings){
        $webhook_url = $jamstack_deployment_settings['webhook_url'];
        $deployment_badge_url = $jamstack_deployment_settings['deployment_badge_url'];
        $deployment_badge_link_url = $jamstack_deployment_settings['deployment_badge_link_url'];
      }

      array_push($data, array(
        'id'                  => $site_id,
        'title'               => $site->blogname,
        'netlifyBuildHook'    => isset($webhook_url) ? $webhook_url : '',
        'netlifyBadgeImage'   => isset($deployment_badge_url) ? $deployment_badge_url : '',
        'netlifyBadgeLink'    => isset($sitesdeployment_badge_link_url) ? $deployment_badge_link_url : '',
        'multisite'           => is_multisite()
      ));
    }
  }

  return $data;

}

?>