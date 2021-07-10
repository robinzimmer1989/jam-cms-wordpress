<?php

function jam_cms_get_site_for_build_by_id(){

  // Get themeOptions and only return id-value pairing
  $formatted_options = (object) [];
  $themeOptions = jam_cms_get_option_group_fields();
  if($themeOptions){
    foreach($themeOptions as $option){
      $option_id = $option['id'];
      $formatted_options->$option_id = $option['value'];
    }
  }

  // Get generic and custom post types
  $post_types = get_post_types([], 'objects');
  $custom_post_types = get_option('cptui_post_types') ? get_option('cptui_post_types') : [];
  $all_post_types = array_merge($post_types, $custom_post_types);

  $formatted_post_types = [];
  foreach ($all_post_types as $post_type){
    // Custom post types are constructed as an array so we have to convert them
    $post_type = (object) $post_type;

    if ($post_type->publicly_queryable && $post_type->name != 'attachment') {

      $formatted_post_types[$post_type->name] = [];

      $posts = get_posts([
        'numberposts' => -1,
        'post_type'   => $post_type->name,
        'post_status' => ['private'],
        'orderby'     => 'menu_order'
      ]);

      // Format posts
      $formatted_posts = [];
      foreach($posts as $post){

        // Private posts are returning encrypted urls and setting the filter to 'sample' fixes that (see https://developer.wordpress.org/reference/functions/get_sample_permalink/)
        $post->filter = 'sample';
        $permalink = get_permalink( $post );
        $uri = jam_cms_format_url($permalink);

        // Get template
        $template = jam_cms_get_template_key($post->ID, false);

        array_push($formatted_post_types[$post_type->name], [
          'id'          => null, // The Gatsby ID doesn't exist
          'databaseId'  => $post->ID,
          'status'      => $post->post_status,
          'uri'         => $uri,
          'template'    => (object) [
            'templateName' => $template
          ]
        ]);
      }
    }
  }

  $data = [
    'frontPage'       => intval(get_option( 'page_on_front' )),
    'siteTitle'       => get_bloginfo('name'),
    'themeOptions'    => $formatted_options,
    'protectedPosts'  => $formatted_post_types
  ];

  return $data;
}