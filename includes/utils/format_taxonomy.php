<?php

function jam_cms_format_taxonomy($taxonomy){
  $taxonomy = (object) $taxonomy;

  $terms = get_terms($taxonomy->name, array(
    'hide_empty' => false,
  ));

  $formatted_terms = [];
  foreach($terms as $term){
    array_push($formatted_terms, jam_cms_format_term($term));
  }

  $formatted_taxonomy = (object) [
    'id'         => $taxonomy->name,
    'title'      => $taxonomy->label,
    'slug'       => property_exists($taxonomy, 'rewrite_slug') ? $taxonomy->rewrite_slug : '',
    'postTypes'  => $taxonomy->object_types,
    'terms'      => $formatted_terms
  ];

  return $formatted_taxonomy;
}

?>