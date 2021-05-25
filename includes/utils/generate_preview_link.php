<?php

function jam_cms_generate_preview_link($preview_key){

  $post_id = $preview_key['post_id'];

  // Get page slug
  $permalink = get_permalink($post_id);
  $home_url = get_home_url();
  $slug = str_replace($home_url, '', $permalink);

  $frontend_url = get_option('site_url');

  // Remove trailing slash if exists
  $frontend_url = rtrim($frontend_url, '/');

  // Full URL
  $url = "{$frontend_url}{$slug}";

  // Drafted posts for example use the post id with a GET parameter.
  // In this case we have to use an ampersand instead.
  $delimiter = '?';

  if(strpos ($slug, '?')){
    $delimiter = '&';
  }

	$link = $url . "{$delimiter}preview={$preview_key['id']}";

  return $link;
}