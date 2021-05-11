<?php

function jam_cms_format_term($term){
  $term = (object) $term;

  if(!property_exists($term, 'term_id')){
    return;
  }

  $uri = get_term_link($term->term_id);
  $uri = jam_cms_format_url($uri);

  $formatted_term = (object) [
    'id'          => $term->term_id,
    'title'       => $term->name,
    'description' => $term->description,
    'slug'        => $term->slug,
    'parentID'    => $term->parent,
    'count'       => $term->count,
    'uri'         => $uri
  ];

  return $formatted_term;
}

?>