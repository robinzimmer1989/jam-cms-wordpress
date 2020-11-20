<?php

if( function_exists('acf_add_options_page') ):
	
	acf_add_options_page(array(
		'page_title' 	=> 'General Settings',
		'menu_title'	=> 'Theme',
		'menu_slug' 	=> 'theme_general_settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Header Settings',
    'menu_title'	=> 'Header',
    'menu_slug' 	=> 'theme_header',
		'parent_slug'	=> 'theme_general_settings',
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Footer',
    'menu_title'	=> 'Footer',
    'menu_slug' 	=> 'theme_footer',
		'parent_slug'	=> 'theme_general_settings',
  ));
  
  acf_add_options_sub_page(array(
		'page_title' 	=> 'Tracking',
    'menu_title'	=> 'Tracking',
    'menu_slug' 	=> 'theme_tracking',
		'parent_slug'	=> 'theme_general_settings',
  ));
  
  acf_add_options_sub_page(array(
		'page_title' 	=> 'Social Media',
		'menu_title'	=> 'Social Media',
		'parent_slug'	=> 'theme_general_settings',
  ));
  
  acf_add_options_sub_page(array(
		'page_title' 	=> 'Cookie Consent',
    'menu_title'	=> 'Cookie Consent',
    'menu_slug' 	=> 'theme_cookie_consent',
		'parent_slug'	=> 'theme_general_settings',
  ));
  
  acf_add_options_sub_page(array(
		'page_title' 	=> 'Redirects',
    'menu_title'	=> 'Redirects',
    'menu_slug' 	=> 'theme_redirects',
		'parent_slug'	=> 'theme_general_settings',
  ));

endif;


?>