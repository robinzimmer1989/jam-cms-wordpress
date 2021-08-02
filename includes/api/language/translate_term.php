<?php

add_action( 'rest_api_init', 'jam_cms_api_translate_term' ); 
function jam_cms_api_translate_term() {
  register_rest_route( 'jamcms/v1', '/translateTerm', array(
    'methods' => 'POST',
    'callback' => 'jam_cms_api_translate_term_callback',
    'permission_callback' => function () {
      return current_user_can( 'edit_posts' );
    }
  ));
}

function jam_cms_api_translate_term_callback($data) {
  $parameters   = $data->get_params();

  $check = jam_cms_api_base_check($parameters, ['id', 'language']);

  if(is_wp_error($check)){
    return $check;
  }

  if(!class_exists('Polylang')){
    return null;
  }

  $term_id  = $parameters['id'];
  $language = $parameters['language'];

  // Duplicate term
  $new_term = jam_cms_duplicate_term($term_id);

  if(is_wp_error($new_term)){
    return $new_term;
  }

  // Set language of duplicated term
  pll_set_term_language($new_term['term_id'], $language);

  $original_term = get_term($term_id);

  // Now that the term has a different language assigned, we can fix the slug which was converted from i.e. "category" to "category-2"
  $original_slug = $original_term->slug;

  wp_update_term($new_term['term_id'], $new_term['taxonomy'], ['slug' => $original_slug]);

  // Get all translations of original term
  $translations = pll_get_term_translations($term_id);

  // Update translations with language-id key value pair
  $translations[$language] = $new_term['term_id'];

  // Save translations
  pll_save_term_translations($translations);

  // Get fresh term object so translations are accurate
  $term = get_term($new_term['term_id']);
  $formatted_term = jam_cms_format_term($term);

  return $formatted_term;
}