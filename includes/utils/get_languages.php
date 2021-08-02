<?php

function jam_cms_get_languages(){

  $default_language = pll_default_language();

  $formatted_languages = [];

  // We need to check for a default language, otherwise pll_the_languages might throw an error.
  if($default_language){
    $languages = pll_the_languages([
      'show_flags'    => 1,
      'raw'           => 1,
      'hide_if_empty' => 0
    ]);

    foreach($languages as $language){
      array_push($formatted_languages, jam_cms_format_language($language));
    }
  }
  
  $language = get_taxonomy('language');

  $formatted_post_types = [];
  $formatted_taxonomies = [];

  if(class_exists('PLL_Model')){

    $options = get_option('polylang');
    $model = new PLL_Model($options);

    $post_types = $model->get_translated_post_types();

    foreach($post_types as $key => $value){
      // Skip default post types
      if($value !== "wp_block" && $value !== "attachment"){
        array_push($formatted_post_types, $value);
      }
    }

    $taxonomies = $model->get_translated_taxonomies();

    foreach($taxonomies as $key => $value){
      array_push($formatted_taxonomies, $value);
    }
  }

  $formatted_taxonomy = (object) [
    'title'             => $language->label,
    'postTypes'         => $formatted_post_types,
    'taxonomies'        => $formatted_taxonomies,
    'languages'         => $formatted_languages,
    'defaultLanguage'   => $default_language
  ];

  return $formatted_taxonomy;
}