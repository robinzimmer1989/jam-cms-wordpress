<?php

// Automatically add locations for each menu
add_action( 'init', 'jam_cms_register_menu_locations' );
function jam_cms_register_menu_locations() {

    $array = [];

    $menus = wp_get_nav_menus();

    foreach($menus as $menu){
        $array[$menu->slug] = ucfirst($menu->name);
    }

    register_nav_menus($array);
}