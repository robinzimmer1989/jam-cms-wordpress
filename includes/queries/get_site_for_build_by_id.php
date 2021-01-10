<?php

function jam_cms_get_site_for_build_by_id(){

  // Get generic and custom post types
  $post_types = get_post_types([], 'objects');
  $custom_post_types = get_option('cptui_post_types') ? get_option('cptui_post_types') : [];
  $all_post_types = array_merge($post_types, $custom_post_types);
  
  $public_post_types = [];
  foreach ( $all_post_types as $post_type ) { 
    $obj = (object) $post_type;
    if ($obj->publicly_queryable && $obj->name != 'attachment') {
        array_push($public_post_types, $obj->name);
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
    $formatted_post             = jam_cms_get_post_by_id($post->ID, 'build');
    $formatted_post['slug']     = jam_cms_generate_slug_by_id($post->ID);

    // Remove the for builds unnecessary data
    unset($formatted_post['siteID']);
    unset($formatted_post['parentID']);
    unset($formatted_post['status']);
    unset($formatted_post['revisions']);

    array_push($formatted_posts, $formatted_post);
  }

  // Get globalOptions and only return id-value pairing
  $formatted_options = (object) [];
  $globalOptions = jam_cms_get_option_group_fields();
  if($globalOptions){
    foreach($globalOptions as $option){
      $option_id = $option['id'];
      $formatted_options->$option_id = $option['value'];
    }
  }

  $data = array(
    'posts'     => $formatted_posts,
    'globalOptions'  => $formatted_options
  );

  return $data;
}

?>