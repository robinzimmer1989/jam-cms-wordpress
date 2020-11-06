<?php

if( function_exists('acf_add_options_page') ):
	
	acf_add_options_page(array(
		'page_title' 	=> 'General Settings',
		'menu_title'	=> 'Theme',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Header',
    'menu_title'	=> 'Header',
    'menu_slug' 	=> 'theme-header',
		'parent_slug'	=> 'theme-general-settings',
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Footer',
    'menu_title'	=> 'Footer',
    'menu_slug' 	=> 'theme-footer',
		'parent_slug'	=> 'theme-general-settings',
  ));
  
  acf_add_options_sub_page(array(
		'page_title' 	=> 'Tracking',
    'menu_title'	=> 'Tracking',
    'menu_slug' 	=> 'theme-tracking',
		'parent_slug'	=> 'theme-general-settings',
  ));
  
  acf_add_options_sub_page(array(
		'page_title' 	=> 'Social Media',
		'menu_title'	=> 'Social Media',
		'parent_slug'	=> 'theme-general-settings',
  ));
  
  acf_add_options_sub_page(array(
		'page_title' 	=> 'Cookie Consent',
    'menu_title'	=> 'Cookie Consent',
    'menu_slug' 	=> 'theme-cookie-consent',
		'parent_slug'	=> 'theme-general-settings',
  ));
  
  acf_add_options_sub_page(array(
		'page_title' 	=> 'Redirects',
    'menu_title'	=> 'Redirects',
    'menu_slug' 	=> 'theme-redirects',
		'parent_slug'	=> 'theme-general-settings',
  ));

endif;


?>