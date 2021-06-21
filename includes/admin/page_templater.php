<?php

// https://github.com/certainlyakey/page-templater

class PageTemplater {

	private static $instance;
	protected $templates;

	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new PageTemplater();
		} 

		return self::$instance;
	} 

	private function __construct() {

		$this->templates = array();

		add_filter( 'theme_page_templates', array( $this, 'jam_cms_templates' ));

		// Add a filter to the template include to determine if the page has our template assigned and return it's path
		add_filter('template_include', array( $this, 'view_project_template'));

		// Add a filter to show template in ACF template dropdown field
		add_filter('acf/location/rule_values/page_template', array( $this, 'acf_page_templates_rules_values'));

		$jam_cms_templates = get_option('jam-cms-templates');

		if(!$jam_cms_templates){
			$jam_cms_templates = [
				'page' => []
			];
		}

		$cache_key = 'post_templates-' . md5( get_theme_root() . '/' . get_stylesheet());

		wp_cache_delete( $cache_key , 'themes');

		wp_cache_add( $cache_key, $jam_cms_templates, 'themes', 1800 );
	} 

	// Adds our template to the page dropdown for v4.7+
	public function jam_cms_templates() {

		$jam_cms_templates = get_option('jam-cms-templates');

		if(!$jam_cms_templates){
			$jam_cms_templates = [
				'page' => []
			];
		}

		return $jam_cms_templates['page'];
	}

	// Checks if the template is assigned to the page
	public function view_project_template( $template ) {
		
		global $post;

		if (!$post) {
			return $template;
		}

		// Return default template if we don't have a custom one defined
		if (!isset( $this->templates[get_post_meta($post->ID, '_wp_page_template', true )] ) ) {
			return $template;
		} 

		$file = plugin_dir_path( __FILE__ ). get_post_meta($post->ID, '_wp_page_template', true);

		// Just to be safe, we check if the file exist first
		if ( file_exists( $file ) ) {
			return $file;
		}

		return $template;
	}

	public function acf_page_templates_rules_values( $choices ) {
		$templates = wp_get_theme()->get_page_templates();

		if ( empty( $templates ) ) {
			$templates = array();
		}
		
		$templates = array_merge( $templates, $this->templates );
		foreach( $templates as $key => $value ) {
			$choices[ $key ] = ucfirst($value);
		}
		
		return $choices;
	}

} 

PageTemplater::get_instance();