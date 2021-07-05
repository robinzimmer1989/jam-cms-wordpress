<?php

function jam_cms_add_google_maps_api_key($api){

  $settings = get_option("jam_cms_settings");
  $google_maps_api_key = is_array($settings) && array_key_exists("google_maps_api_key", $settings) ? $settings['google_maps_api_key'] : '';

  if($google_maps_api_key){
    $api['key'] = $google_maps_api_key;
  }
	
	return $api;
	
}

add_filter('acf/fields/google_map/api', 'jam_cms_add_google_maps_api_key');