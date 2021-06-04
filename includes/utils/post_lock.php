<?php

// The wp_check_post_lock and wp_set_post_lock aren't available via API so we need to recreate theme here

// https://developer.wordpress.org/reference/functions/wp_check_post_lock/
function jam_cms_check_post_lock($post_id){

    $lock = get_post_meta($post_id, '_edit_lock', true );

    if (!$lock) {
        return false;
    }

    $lock = explode( ':', $lock );
    $time = $lock[0];
    $user_id = isset( $lock[1] ) ? $lock[1] : get_post_meta( $post_id, '_edit_last', true );

    if (!get_userdata($user_id)){
        return false;
    }

    $time_window = apply_filters( 'wp_check_post_lock_window', 150 );

    if ($time && $time > time() - $time_window && get_current_user_id() != $user_id ) {
        $user = jam_cms_get_user_by_id($user_id);
        return $user;
    }

    return false;
}

// https://developer.wordpress.org/reference/functions/wp_set_post_lock/
function jam_cms_set_post_lock($post_id) {
    $user_id = get_current_user_id();

    $now  = time();
    $lock = "$now:$user_id";
 
    update_post_meta( $post_id, '_edit_lock', $lock );
}

// https://developer.wordpress.org/reference/functions/wp_ajax_wp_remove_post_lock/
function jam_cms_remove_post_lock($post_id) {

    $lock = get_post_meta($post_id, '_edit_lock', true );
    
    $lock = explode( ':', $lock );

    if(isset( $lock[1] ) && $lock[1] == get_current_user_id()){
        update_post_meta( $post_id, '_edit_lock', false );
    }
}