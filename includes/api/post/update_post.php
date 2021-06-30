<?php

add_action( 'rest_api_init', 'jam_cms_api_update_post' ); 
function jam_cms_api_update_post() {
  register_rest_route( 'jamcms/v1', '/updatePost', array(
    'methods' => 'POST',
    'callback' => 'jam_cms_api_update_post_callback',
    'permission_callback' => function () {
      return current_user_can( 'edit_posts' );
    }
  ));
}

function jam_cms_api_update_post_callback($data) {

  global $wpdb;

  $parameters = $data->get_params();

  $check = jam_cms_api_base_check($parameters, ['id', 'postTypeID']);

  if(is_wp_error($check)){
      return $check;
  }

  $site_id = $parameters['siteID'];
  $post_id = $parameters['id'];

  // Check if post can be updated first
  $lock = get_post_meta($post_id, '_edit_lock', true );
  $lock = explode( ':', $lock );

  if(isset($lock[1]) && $lock[1] != get_current_user_id()){
    $user = get_userdata($lock[1]);

    if($user){
      return new WP_Error( 'post_is_locked', "{$user->user_email} is currently editing" , array( 'status' => 400 ));
    }
  }

  $content = array_key_exists('content', $parameters) ? json_decode($parameters['content']) : (object) [];

  jam_cms_create_revision($post_id, $content); 

  $post_data = array(
    'ID' => $post_id
  );

  if(array_key_exists('title', $parameters)){
    $post_data['post_title'] = $parameters['title'] ? $parameters['title'] : 'No Title';
  }

  if(array_key_exists('slug', $parameters) && array_key_exists('title', $parameters) && array_key_exists('parentID', $parameters)){

    // Disallow empty slug by using sanitized title as an alternative
    if($parameters['slug'] == '' || $parameters['slug'] == '/'){
      $slug = sanitize_title($parameters['title']);
    }else{
      $slug = $parameters['slug'];
    }

    $unique_slug = wp_unique_post_slug( $slug, $post_id, '', get_post_type($post_id), $parameters['parentID'] );
    $post_data['post_name'] = $unique_slug;
  }

  if(array_key_exists('status', $parameters)){
    $post_data['post_status'] = $parameters['status'];
  }

  if(array_key_exists('parentID', $parameters)){
    $post_data['post_parent'] = $parameters['parentID'];
  }

  if(array_key_exists('taxonomies', $parameters)){
    $taxonomies = $parameters['taxonomies'] ? json_decode($parameters['taxonomies']) : null;

    if($taxonomies){
      foreach($taxonomies as $key => $terms){
        wp_set_post_terms($post_id, $terms, $key);
      }
    }
  }

  // We're updating the db directly (vs wp_update_post) to avoid an automatic post revision.
  $wpdb->update( $wpdb->posts, $post_data, ['ID' => $post_id]);

  // We need to clear the cache here, otherwise the get_post_by_id function will receive an empty post_name field.
  clean_post_cache( $post_id );

  if(array_key_exists('seo', $parameters)){
    $seo = $parameters['seo'] ? json_decode($parameters['seo']) : null;

    if($seo){
      if(property_exists($seo, 'title')){
        update_post_meta($post_id, '_yoast_wpseo_title', $seo->title);
      }

      if(property_exists($seo, 'metaDesc')){
        update_post_meta($post_id, '_yoast_wpseo_metadesc', $seo->metaDesc);
      }

      if(property_exists($seo, 'opengraphImage')){
        
        $url = $seo->opengraphImage && property_exists($seo->opengraphImage, 'url') ? $seo->opengraphImage->url : '';
        update_post_meta($post_id, '_yoast_wpseo_opengraph-image', $url);

        $id = $seo->opengraphImage && property_exists($seo->opengraphImage, 'id') ? $seo->opengraphImage->id : '';
        update_post_meta($post_id, '_yoast_wpseo_opengraph-image-id', strval($id));
      }
    }      
  }

  if(array_key_exists('featuredImage', $parameters)){
    $featured_image = $parameters['featuredImage'] ? json_decode($parameters['featuredImage']) : null;

    if($featured_image){
      set_post_thumbnail($post_id, $featured_image->id);
    }
  }

  if(array_key_exists('template', $parameters)){
    
    // initialize template key. 
    // Unfortunatley we need to distinguish different use cases for inbuilt temlates and custom ones.
    $template_key = '';

    if($parameters['template'] == 'archive'){
      // The archive template is associated with the template name (always archive) and the post type ID
      $template_key = "template-archive-{$parameters['postTypeID']}.php";

    }else{

      if($parameters['postTypeID'] == 'page' && $parameters['template'] == 'default'){
        // The default page template is simply stored as 'default'
        $template_key = 'default';

      }else{
        // Manually created templates as well as the inbuilt post template are stores as 'tempate-[name].php'
        $template_key = "template-{$parameters['template']}.php";
      }

    }

    update_post_meta( $post_id, '_wp_page_template', $template_key );
  }

  // Check if syncing is enabled before updating the ACF template
  $settings = get_option("jam_cms_settings");
  $syncing = is_array($settings) && array_key_exists("disable_syncing", $settings) && $settings['disable_syncing'] == 1 ? false : true;

  if($syncing && array_key_exists('templateObject', $parameters)){
    $templateObject = $parameters['templateObject'] ? json_decode($parameters['templateObject']) : null;

    if($templateObject){
      jam_cms_create_template($templateObject);
      jam_cms_upsert_acf_template($templateObject);
    }
  }

  if(array_key_exists('content', $parameters)){
    jam_cms_update_acf_fields($post_id, $content);

  }
  
  if(array_key_exists('status', $parameters) && $parameters['status'] == 'publish'){

    $post_type = get_post_type($post_id);

    // $monitor = new \WPGatsby\ActionMonitor\Monitors\ActionMonitor();
    // $monitor->log_action([
    //    'action_type'          => 'UPDATE',
    //    'title'                => get_the_title($post_id),
    //    'graphql_single_name'  => $post_type,
    //    'graphql_plural_name'  => "{$post_type}Multiple",
    //    'status'               => 'publish',
    //    'node_id'              => $post_id,
    // ]);
  }

  update_option('jam_cms_undeployed_changes', true);

  $data = jam_cms_get_post_by_id($post_id);

  return $data;
}