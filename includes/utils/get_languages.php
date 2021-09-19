<?php

function jam_cms_get_languages(){

  $formatted_languages = [];

  if(function_exists('pll_languages_list')){
    $languages = pll_languages_list(['fields' => []]);

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
    'defaultLanguage'   => pll_default_language()
  ];

  return $formatted_taxonomy;
}