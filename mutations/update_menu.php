<?php

function gcms_update_menu($menu_slug, $menu_items){
  $menu = wp_get_nav_menu_object($menu_slug);

  // Create menu if doesn't exist
  if(!$menu){
    $menu_id = wp_update_nav_menu_object(0, ['menu-name' => $menu_slug]);
  }else{
    $menu_id = $menu->term_id;
    
    // Not sure if this could be solved more elegant or not, but intead of comparing and updating
    // individual menu items we simply gonna delete all of them and re-add them in the next step
    $old_menu_items = wp_get_nav_menu_items($menu_id);
    foreach($old_menu_items as $item){
      wp_delete_post($item->ID);
    }
  }

  // Flatten menu items
  $flattened_menu_items = gcms_array_flatten($menu_items);
  
  // Sort items by assigned index (= menu_order)
  usort($flattened_menu_items, fn($a, $b) => strcmp($a->index, $b->index));
  
  // Initialize empty array to store new menu item ids
  $menu_item_ids = [];

  foreach($flattened_menu_items as $item){

    // Grab new menu item id of parent
    $parent_id = $item->parent_id ? $menu_item_ids[$item->parent_id] : 0;

    if(property_exists($item, 'postTypeID') && $item->postTypeID){
      $menu_item_id = wp_update_nav_menu_item( $menu_id, 0, [
        'menu-item-parent-id'   => $parent_id,
        'menu-item-object-id'   => $item->postID,
        'menu-item-object'      => $item->postTypeID,
        'menu-item-position'    => $item->index,
        'menu-item-title'       => $item->title,
        'menu-item-type'        => 'post_type',
        'menu-item-status'      => 'publish',
      ]);
    }else{
      $menu_item_id = wp_update_nav_menu_item( $menu_id, 0, [
        'menu-item-parent-id'   => $parent_id,
        'menu-item-position'    => $item->index,
        'menu-item-title'       => $item->title, 
        'menu-item-type'        => 'custom',
        'menu-item-url'         => $item->url,
        'menu-item-status'      => 'publish',
      ]);
    }

    // Store new menu item id in array
    $menu_item_ids[$item->key] = $menu_item_id;

  }

  return $menu_id;
}


?>