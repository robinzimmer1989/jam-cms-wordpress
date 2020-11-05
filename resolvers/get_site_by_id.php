<?php

function gcms_get_site_by_id($site_id){
 
  $site = get_blog_details($site_id);

  if($site){
    switch_to_blog($site->blog_id);

    $settings = get_blog_option($site->blog_id, 'gcms_custom_plugin_options');

    // Get generic and custom post types
    $post_types = get_post_types([], 'objects');
    $custom_post_types = get_option('cptui_post_types') ? get_option('cptui_post_types') : [];
    $all_post_types = array_merge($post_types, $custom_post_types);

    $items = [];
    foreach ( $all_post_types as $post_type ) {
      // Custom post types are constructed as an array so we have to convert them
      $post_type = (object) $post_type;

      if ($post_type->publicly_queryable && $post_type->name != 'attachment') {
          array_push($items, gcms_format_post_type($site_id, $post_type));
      }
    }

    $header_fields = get_field('theme-header', 'option');
    $formatted_header_fields = [];
    foreach($header_fields as $key => $value){
      array_push($formatted_header_fields, [
        'id' => $key,
        'value' => $value
      ]);
    }

    $footer_fields = get_field('theme-footer', 'option');
    $formatted_footer_fields = [];
    foreach($footer_fields as $key => $value){
      array_push($formatted_footer_fields, [
        'id' => $key,
        'value' => $value
      ]);
    }

    $jamstack_deployment_settings = get_option('wp_jamstack_deployments');

    $data = array(
      'id' => $site_id,
      'title' => $site->blogname,
      'netlifyBuildHook' =>  $jamstack_deployment_settings['webhook_url'],
      'netlifyBadgeImage' => $jamstack_deployment_settings['deployment_badge_url'],
      'netlifyBadgeLink' => $jamstack_deployment_settings['deployment_badge_link_url'],
      'settings' => [
        'header' => [
          'name' => 'header',
          'fields' => $formatted_header_fields
        ],
        'footer' => [
          'name' => 'footer',
          'fields' => $formatted_footer_fields
        ]
      ],
      'frontPage' => intval(get_option( 'page_on_front' )),
      'postTypes' => [
        'items' => $items
      ],
      'forms' => [
        'items' => []
      ]
    );

    return $data;
  }

  return null;

}

?>