<?php

function jam_cms_get_menu_by_id($menu_id, $format = true){
  $menu_items = wp_get_nav_menu_items($menu_id);
  return jam_cms_build_menu_tree($menu_items);
}

?>