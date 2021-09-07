<?php

function jam_cms_format_menu_item($fields, $menu_item){

  if($menu_item->object == 'custom'){
    $post_id = null;
    $post_type_id = null;
    $url = jam_cms_format_url($menu_item->url);

  }else {
    $post_id = intval($menu_item->object_id);
    $post_type_id = $menu_item->object;

    $post = get_post($post_id);
    // Private posts are returning encrypted urls and setting the filter to 'sample' fixes that (see https://developer.wordpress.org/reference/functions/get_sample_permalink/)
    $post->filter = 'sample';
    $permalink = get_permalink($post);
    $url = jam_cms_format_url($permalink);
  }

  $new_menu_item = [
    'key'         => $menu_item->ID,
    'postID'      => $post_id,
    'postTypeID'  => $post_type_id,
    'title'       => $menu_item->title,
    'url'         => $url,
    'children'    => $menu_item->children ? $menu_item->children : [],
  ];

  // Add ACF field values to menu items
  if($fields){
    foreach($fields as $field){
      $value = get_field($field['name'], $menu_item->ID);
      $new_menu_item['value'][$field['name']] = jam_cms_format_acf_field_value_for_frontend($field, $value);
    }
  }

  return (object) $new_menu_item;
}