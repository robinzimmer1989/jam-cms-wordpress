<?php

function jam_cms_get_data_by_query($post_id){
  
  $template_key = jam_cms_get_template_key($post_id);
  $query = get_option("jam_cms_template_{$template_key}_query");

  if(!$query){
    return '';
  }

  $site_url = get_site_url();

  // TODO: Use internal WPGraphQL function
  $response =  wp_remote_get("{$site_url}/graphql?query={$query}");

  if($response['response']['code'] == 200){
    $body = $response['body'];

    $json = json_decode($body);

    if(property_exists($json, 'data')){
      return $json->data;
    }
  }
}

?>