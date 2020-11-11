<?php

function gcms_generate_parent_slug($posts, $parent_id, $slug = ''){
  // Find index of parent
  $post_index = array_search($parent_id, array_column($posts, 'ID'));
  $post = $posts[$post_index];

  // Check if parent and parent post exists and run function recursively
  if ($parent_id && $post) {
    $parent_slug = $post->post_name . '/' . $slug;
    $slug = gcms_generate_parent_slug($posts, $post->post_parent, $parent_slug);
  }

  return $slug;
}

function gcms_generate_slug($post_types, $posts, $post, $front_page){

  // Check if post is front page
  if($post->ID == $front_page){
    return '/';
  }

  // Check if post type has a rewrite
  $base_slug = '/';
  if(isset($post_types->rewrite)){
    $base_slug = '/' . $post_types->rewrite_slug;
  }

  // Check for parent slugs
  $parent_slug = '';
  if($post->post_parent){
    $parent_slug = gcms_generate_parent_slug($posts, $post->post_parent);
  }

  // Connect all slug parts
  $slug = $base_slug . $parent_slug . $post->post_name;

  return $slug;
}

?>