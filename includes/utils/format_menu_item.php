<?php

function jam_cms_format_menu_item($menu_item){

  $new_menu_item = (object) [
    'key'         => $menu_item->ID,
    'postID'      => $menu_item->object == 'custom' ? null : (int) $menu_item->object_id,
    'postTypeID'  => $menu_item->object == 'custom' ? null : $menu_item->object,
    'title'       => $menu_item->title,
    'url'         => jam_cms_format_url($menu_item->url),
    'children'    => $menu_item->children ? $menu_item->children : [],
  ];

  return $new_menu_item;

}