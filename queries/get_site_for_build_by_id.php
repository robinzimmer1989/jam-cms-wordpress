<?php

function gcms_get_site_for_build_by_id($site_id){
 
  $site = get_blog_details($site_id);

  if($site){
    switch_to_blog($site->blog_id);

    // Get generic and custom post types
    $post_types = get_post_types([], 'objects');
    $custom_post_types = get_option('cptui_post_types') ? get_option('cptui_post_types') : [];
    $all_post_types = array_merge($post_types, $custom_post_types);

    $public_post_types = [];
    foreach ( $all_post_types as $post_type ) { 
      if ($post_type->publicly_queryable && $post_type->name != 'attachment') {
          array_push($public_post_types, $post_type->name);
      }
    }

    $front_page = intval(get_option( 'page_on_front' ));

    $posts = get_posts(array(
      'numberposts' => -1,
      'post_type' => $public_post_types,
      'post_status' => ['publish']
    ));

    $formatted_posts = [];
    foreach($posts as $post){
      $formatted_post = gcms_get_post_by_id($site_id, $post->ID);

      array_push($formatted_posts, $formatted_post);
    }

    $data = array(
      'header' => [
        'name' => 'header',
        'fields' => gcms_get_option_group_fields('header')
      ],
      'footer' => [
        'name' => 'footer',
        'fields' => gcms_get_option_group_fields('footer')
      ],
      'posts' => $formatted_posts
    );

    return $data;
  }

  return null;

}

?>