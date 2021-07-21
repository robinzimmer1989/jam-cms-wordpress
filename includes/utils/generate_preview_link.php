<?php

function jam_cms_generate_preview_link($preview_key){

  $post_id = $preview_key['post_id'];

  // Get page slug
  $permalink = get_permalink($post_id);
  $home_url = get_home_url();
  $slug = str_replace($home_url, '', $permalink);

  $settings = get_option("jam_cms_settings");

  $frontend_url = is_array($settings) && array_key_exists("frontend_url", $settings) ? $settings['frontend_url'] : '';

  // Remove trailing slash if exists
  $frontend_url = rtrim($frontend_url, '/');

  // Full URL
  $url = "{$frontend_url}{$slug}";

  // Drafted posts for example use the page_id as a GET parameter. In this case we wanna remove it.
  $url_without_query_string = strtok($url, '?');

	$link = $url_without_query_string . "?preview={$preview_key['id']}";

  return $link;
}