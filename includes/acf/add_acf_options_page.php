<?php

if( function_exists('acf_add_options_page') ):
	
	acf_add_options_page(array(
		'page_title' 	=> 'Theme Options',
		'menu_title'	=> 'Theme Options',
		'menu_slug' 	=> 'theme-options',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
  
  acf_add_options_sub_page(array(
		'page_title' 	=> 'Redirects',
    'menu_title'	=> 'Redirects',
    'menu_slug' 	=> 'theme_redirects',
		'parent_slug'	=> 'theme-options',
  ));

endif;

?>