<?php

function jam_cms_update_menu($menu_slug, $value){

  // Assign value to menu items
  $menu_items = $value;

  // Get global language variable (set in API base check)
  $language = $GLOBALS['language'];

  // Translated menus are stored with an appendix such as ___en, so we manipulate the menu slug in case it's not the default language
  if(
    function_exists('pll_default_language') && 
    $language &&
    is_object($value) &&
    property_exists($value, $language)
  ){

    // Override slug for translated menus
    if($language !== pll_default_language()){
      $menu_slug = "{$menu_slug}___{$language}";
    }

    // Override menu items
    $menu_items = $value->$language;
  }

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

  // Assign menu automatically to location
  $locations = get_theme_mod('nav_menu_locations');
  $locations[$menu_slug] = $menu_id; 
  set_theme_mod('nav_menu_locations', $locations); 

  // Flatten menu items
  $flattened_menu_items = jam_cms_array_flatten($menu_items);
  
  // Sort items by assigned index (= menu_order)
  usort($flattened_menu_items, function ($a, $b) {
    return $a->index <=> $b->index;
  });

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

  // Even though the user might have saved a translated menu, we always wanna return the menu of the default language (if exists)
  if(
    function_exists('pll_default_language') && 
    $language &&
    $language !== pll_default_language() &&
    is_object($value) &&
    property_exists($value, $language)
  ){
    $original_menu_slug = explode('___', $menu_slug)[0];
    $original_menu      = wp_get_nav_menu_object($original_menu_slug);

    if($original_menu){
      return $original_menu->term_id;
    }
  }

  return $menu_id;
}