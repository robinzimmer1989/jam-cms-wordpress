<?php

function jam_cms_format_term($term){
  $term = (object) $term;

  $formatted_term = (object) [
    'id'          => $term->term_id,
    'title'       => $term->name,
    'description' => $term->description,
    'slug'        => $term->slug,
    'parentID'    => $term->parent,
    'count'       => $term->count
  ];

  return $formatted_term;
}

?>