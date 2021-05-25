<?php

add_action( 'rest_api_init', 'jam_cms_api_get_site_preview' ); 
function jam_cms_api_get_site_preview() {
  register_rest_route( 'jamcms/v1', '/getSitePreview', array(
    'methods' => 'POST',
    'callback' => 'jam_cms_api_get_site_preview_callback',
    'permission_callback' => function () {
      return true;
    }
  ));
}

function jam_cms_api_get_site_preview_callback($data) {
  $parameters = $data->get_params();

  $check = jam_cms_api_base_check($parameters, ['siteID', 'previewID']);

  if(is_wp_error($check)){
    return $check;
  }

  $site_id = $parameters['siteID'];

  $site = jam_cms_get_site_by_id($site_id);

  return $site;
}