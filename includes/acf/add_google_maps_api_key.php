<?php


function jam_cms_add_google_maps_api_key($api){

  $google_maps_api_key = get_option('jam_cms_google_maps_api_key');

  if($google_maps_api_key){
    $api['key'] = $google_maps_api_key;
  }
	
	return $api;
	
}

add_filter('acf/fields/google_map/api', 'jam_cms_add_google_maps_api_key');

?>