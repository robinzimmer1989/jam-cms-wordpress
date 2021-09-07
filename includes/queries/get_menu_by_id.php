<?php

function jam_cms_get_menu_tree($menu_id){
  $menu_items = wp_get_nav_menu_items($menu_id);

  if(!$menu_items){
    return [];
  }

  // Translated menus are stored with a language code extention (i.e. headermenu___de). To get the ACF field id we need to trim this extention.
  $original_menu_id = explode('___', $menu_id)[0];

  $fields = acf_get_fields("group_menu-{$original_menu_id}");

  $menu_tree = jam_cms_build_menu_tree($fields, $menu_items);

  return $menu_tree;
}

function jam_cms_get_menu_by_id($menu_id){

  if(!$menu_id){
    return [];
  }

  // For sites that are using Polylang, we gonna return an object of key value pairs (language => menu) instead of an array
  if(class_exists('Polylang')){

      $languages = pll_languages_list(['fields' => []]);

      $default_language = pll_default_language();

      $menus = [];

      // Get original menu in order to get the slug
      $menu = wp_get_nav_menu_object($menu_id);

      if($menu){
        foreach($languages as $language){
          // The menu slug is either the default one of the menu or the translated version in i.e. this format ___en
          $menu_slug = $language->slug == $default_language ? $menu->slug : "{$menu->slug}___{$language->slug}";

          $menus[$language->slug] = jam_cms_get_menu_tree($menu_slug);
        }
      }

      return (object) $menus;
  }

  return jam_cms_get_menu_tree($menu_id);
}