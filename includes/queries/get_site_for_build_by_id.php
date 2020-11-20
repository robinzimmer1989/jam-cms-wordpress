<?php

function gcms_format_post_content_for_build($modules){
  $formatted_modules = [];
  foreach($modules as $module){
    $fields = [];

    foreach($module['fields'] as $field){
      $fields[$field['id']] = $field['value'];
    }

    array_push($formatted_modules, [
      'name'    => $module['name'],
      'fields'  => $fields
    ]);
  }

  return $formatted_modules;
}

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
      $formatted_post = gcms_get_post_by_id($site_id, $post->ID);
      $formatted_post['slug'] = gcms_generate_slug($all_post_types, $posts, $post, $front_page);
      $formatted_post['content'] = gcms_format_post_content_for_build($formatted_post['content']);

      // Remove the for builds unnecessary data
      unset($formatted_post['siteID']);
      unset($formatted_post['postTypeID']);
      unset($formatted_post['parentID']);
      unset($formatted_post['status']);
      // unset($formatted_post['featuredImage']);
      unset($formatted_post['createdAt']);

      array_push($formatted_posts, $formatted_post);
    }

    $header_fields = gcms_get_option_group_fields('header');

    $formatted_header_fields = [];
    foreach($header_fields as $field){
      $formatted_header_fields[$field['id']] = $field['value'];
    }

    $footer_fields = gcms_get_option_group_fields('footer');

    $formatted_footer_fields = [];
    foreach($footer_fields as $field){
      $formatted_footer_fields[$field['id']] = $field['value'];
    }

    $data = array(
      'posts' => $formatted_posts,
      'header' => $formatted_header_fields,
      'footer' => $formatted_footer_fields
    );

    return $data;
  }

  return null;

}

?>