<?php 

add_filter('preview_post_link', 'jam_cms_custom_preview_page_link');
function jam_cms_custom_preview_page_link($link) {

	$post_id = get_the_ID();

  // Generate encrypted preview key
  $preview_key = jam_cms_generate_preview_key($post_id);

  $preview_link = jam_cms_generate_preview_link($preview_key);
	
	return $preview_link;
}