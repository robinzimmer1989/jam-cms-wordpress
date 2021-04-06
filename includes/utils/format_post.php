<?php

function jam_cms_format_post($post) {

  $thumbnail_id = get_post_thumbnail_id($post->ID);
  $formatted_media_item = null;
  
  if($thumbnail_id){
    $media_item = acf_get_attachment($thumbnail_id);
    $formatted_media_item = jam_cms_format_acf_field_value_for_frontend(['type' => 'image'], $media_item);
  }

  $slug = $post->post_name;
  
  // Remove "_trashed" affix added by WordPress
  if($post->post_status == 'trash'){
    $slug = str_replace('__trashed', '', $slug);
  }

  // Get template
  $template = jam_cms_get_template_key($post->ID, false);

  // Get taxonomies
  $taxonomies = get_object_taxonomies($post->post_type);
  
  $formatted_taxonomies = [];
  foreach($taxonomies as $taxonomy_name){

    $taxonomy = get_taxonomy($taxonomy_name);

    if ($taxonomy->publicly_queryable && $taxonomy->name != 'post_format') {
      $terms = wp_get_post_terms($post->ID, $taxonomy_name);

      $term_ids = [];
      foreach($terms as $term){
        array_push($term_ids, $term->term_id);
      }

      $formatted_taxonomies[$taxonomy_name] = $term_ids;
    }
  }

  $formatted_post = [
    'id'              => $post->ID,
    'title'           => $post->post_title,
    'slug'            => $slug,
    'postTypeID'      => $post->post_type,
    'parentID'        => $post->post_parent,
    'status'          => $post->post_status,
    'featuredImage'   => $formatted_media_item,
    'template'        => $template,
    'content'         => (object) [],
    'seo'             => [],
    'taxonomies'      => $formatted_taxonomies,
    'order'           => $post->menu_order,
    'createdAt'       => $post->post_date,
  ];

  return $formatted_post;
  
}

?>