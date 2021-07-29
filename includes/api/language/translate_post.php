<?php

add_action( 'rest_api_init', 'jam_cms_api_translate_post' ); 
function jam_cms_api_translate_post() {
  register_rest_route( 'jamcms/v1', '/translatePost', array(
    'methods' => 'POST',
    'callback' => 'jam_cms_api_translate_post_callback',
    'permission_callback' => function () {
      return current_user_can( 'edit_posts' );
    }
  ));
}

function jam_cms_api_translate_post_callback($data) {
  $parameters   = $data->get_params();

  $check = jam_cms_api_base_check($parameters, ['id', 'language']);

  if(is_wp_error($check)){
    return $check;
  }

  if(!class_exists('Polylang')){
    return null;
  }

  $post_id  = $parameters['id'];
  $language = $parameters['language'];

  // Duplicate post
  $translated_post = jam_cms_duplicate_post($post_id);

  if($translated_post){

    // Set language of duplicated post
    pll_set_post_language($translated_post['id'], $language);

    // Now that the post has a different language assigned, we can fix the slug which was converted from i.e. "about" to "about-2"
    $original_post_name = get_post_field( 'post_name', $post_id );

    // We're updating the db directly (vs wp_update_post) to avoid an automatic post revision.
    global $wpdb;
    $wpdb->update( $wpdb->posts, ['post_name' => $original_post_name], ['ID' => $translated_post['id']]);

    // We need to clear the cache here, otherwise the get_post_by_id function will receive an empty post_name field.
    clean_post_cache($translated_post['id']);

    // Get all translations of original post
    $translations = pll_get_post_translations($post_id);

    // Update translations with language-id key value pair
    $translations[$language] = $translated_post['id'];

    // Save translations
    pll_save_post_translations($translations);

    // Get fresh post object so translations are accurate
    $post = jam_cms_get_post_by_id($translated_post['id']);

    return $post;
  }

  return null;
}