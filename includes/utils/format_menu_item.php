<?php

function jam_cms_format_menu_item($menu_item){

  if($menu_item->object == 'custom'){
    $post_id = null;
    $post_type_id = null;
    $url = jam_cms_format_url($menu_item->url);

  }else {
    $post_id = $menu_item->object_id;
    $post_type_id = $menu_item->object;

    $post = get_post($post_id);
    // Private posts are returning encrypted urls and setting the filter to 'sample' fixes that (see https://developer.wordpress.org/reference/functions/get_sample_permalink/)
    $post->filter = 'sample';
    $permalink = get_permalink( $post );
    $url = jam_cms_format_url($permalink);
  }

  $new_menu_item = (object) [
    'key'         => $menu_item->ID,
    'postID'      => $post_id,
    'postTypeID'  => $post_type_id,
    'title'       => $menu_item->title,
    'url'         => $url,
    'children'    => $menu_item->children ? $menu_item->children : [],
  ];

  return $new_menu_item;

}