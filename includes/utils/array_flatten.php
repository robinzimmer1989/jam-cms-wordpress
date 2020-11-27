<?php

function jam_cms_array_flatten($element, $index = 1, $parent_id = 0){
  $flat_array = array();

  foreach ($element as $key => $node) {
    // We need to add the index here which represents the WP menu order property
    $node->index = $index;

    if (property_exists($node, 'children')) {
      $flat_array = array_merge($flat_array, jam_cms_array_flatten($node->children, $index + 1, $node->key));

      $index = $index + count($flat_array);

      unset($node->children);
    }

    $index++;

    $node->parent_id = $parent_id;
    
    $flat_array[] = $node;
  }

  return $flat_array;
}

?>