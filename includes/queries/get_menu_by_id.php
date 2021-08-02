<?php

function jam_cms_get_menu_tree($menu_id){
  $menu_items = wp_get_nav_menu_items($menu_id);

  if(!$menu_items){
    return [];
  }

  $menu_tree = jam_cms_build_menu_tree($menu_items);

  return $menu_tree;
}

function jam_cms_get_menu_by_id($menu_id){

  if(!$menu_id){
    return [];
  }

  // For sites that are using Polylang, we gonna return an object of key value pairs (language => menu) instead of an array
  if(function_exists('pll_default_language') && function_exists('pll_the_languages')){

    $default_language = pll_default_language();

     // We need to check for a default language, otherwise pll_the_languages might throw an error.
    if($default_language){

      $languages = pll_the_languages([
        'raw'           => 1,
        'hide_if_empty' => 0
      ]);

      // Get original menu in order to get the slug
      $menu = wp_get_nav_menu_object($menu_id);

      $menus = [];

      foreach($languages as $language){
        // The menu slug is either the default one of the menu or the translated version in i.e. this format ___en
        $menu_slug = $language['slug'] == $default_language ? $menu->slug : "{$menu->slug}___{$language['slug']}";

        $menus[$language['slug']] = jam_cms_get_menu_tree($menu_slug);
      }

      return (object) $menus;
    }
  }

  return jam_cms_get_menu_tree($menu_id);
}