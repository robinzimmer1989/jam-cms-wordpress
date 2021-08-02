<?php

function jam_cms_format_term($term){
  $term = (object) $term;

  if(!property_exists($term, 'term_id')){
    return;
  }

  $uri = get_term_link($term->term_id);

  $formatted_uri = jam_cms_format_url($uri);

  $formatted_term = (object) [
    'taxonomyID'  => $term->taxonomy,
    'id'          => $term->term_id,
    'title'       => $term->name,
    'description' => $term->description,
    'slug'        => $term->slug,
    'parentID'    => $term->parent,
    'count'       => $term->count,
    'uri'         => $formatted_uri,
  ];

  // Add language information to post
  if(class_exists('Polylang')){

    $supports_translations = pll_is_translated_taxonomy($term->taxonomy);

    if($supports_translations){
      $term_language = pll_get_term_language($term->term_id);

      $translations = [];
      $languages = pll_languages_list(['fields' => []]);

      foreach ($languages as $language){

        // Skip own translation
        if($language->slug == $term_language){
          continue;
        }

        $translation = pll_get_term($term->term_id, $language->slug);

        if($translation){
          $translations[$language->slug] = $translation;
        }
      }

      $formatted_term->language      = $term_language;
      $formatted_term->translations  = (object) $translations;
    }
  }

  return $formatted_term;
}