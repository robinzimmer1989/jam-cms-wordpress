<?php

function jam_cms_format_taxonomy($taxonomy){
  $taxonomy = (object) $taxonomy;

  $terms = get_terms($taxonomy->name, array(
    'hide_empty' => false,
  ));

  $formatted_terms = [];
  foreach($terms as $term){
    $formatted_term = jam_cms_format_term($term);

    if($formatted_term){
      array_push($formatted_terms, $formatted_term);
    }
  }

  if(property_exists($taxonomy, 'rewrite_slug')){ // CPTUI
    $slug = $taxonomy->rewrite_slug;

  }elseif(property_exists($taxonomy, 'rewrite')){ // WP Default
    $slug = $taxonomy->rewrite['slug'];

  }else{
    $slug = '/';
  }

  if(property_exists($taxonomy, 'object_types')){ // CPTUI
   $postTypes = $taxonomy->object_types;

  }elseif(property_exists($taxonomy, 'object_type')){ // WP Default
   $postTypes = $taxonomy->object_type;

  }else{
   $postTypes = [];
  }

  $formatted_taxonomy = (object) [
    'id'                => $taxonomy->name,
    'title'             => $taxonomy->label,
    'slug'              => $slug,
    'postTypes'         => $postTypes,
    'terms'             => $formatted_terms,
    'graphqlSingleName' => $taxonomy->graphql_single_name,
    'graphqlPluralName' => $taxonomy->graphql_plural_name,
    'editable'          => $taxonomy->name != 'category' && $taxonomy->name != 'post_tag',
  ];

  return $formatted_taxonomy;
}

?>