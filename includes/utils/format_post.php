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

  // We don't need all SEO information for the site but we wanna display a tag if the post is blocked by search engines.
  $seo_noindex = get_post_meta($post->ID, '_yoast_wpseo_meta-robots-noindex', true);
  $seo = [
    'metaRobotsNoindex' => $seo_noindex && $seo_noindex == 1 ? "noindex" : "index"
  ];

  // Archive logic
  $archive = get_post_meta($post->ID, 'jam_cms_archive', true);
  $archive_post_type = get_post_meta($post->ID, 'jam_cms_archive_post_type', true);
  $archive_posts_per_page = get_option('posts_per_page');

  if($archive && $archive_post_type){
    $archive_posts_per_page = get_post_meta($post->ID, 'jam_cms_archive_posts_per_page', true);
  }

  $formatted_post = [
    'id'                  => $post->ID,
    'title'               => $post->post_title,
    'slug'                => $slug,
    'postTypeID'          => $post->post_type,
    'parentID'            => $post->post_parent,
    'status'              => $post->post_status,
    'featuredImage'       => $formatted_media_item,
    'template'            => $template,
    'content'             => (object) [],
    'seo'                 => $seo,
    'taxonomies'          => $formatted_taxonomies,
    'order'               => $post->menu_order,
    'locked'              => jam_cms_check_post_lock($post->ID),
    'createdAt'           => $post->post_date,
    'updatedAt'           => get_the_modified_time('Y-m-d H:m:s', $post),
    'archive'             => $archive === 'true' ? true : false,
    'archivePostType'     => $archive_post_type,
    'archivePostsPerPage' => (int) $archive_posts_per_page,
  ];

  // Add language information to post
  if(class_exists('Polylang')){
    $post_language = pll_get_post_language($post->ID);

    $translations = [];
    $languages = pll_languages_list(['fields' => []]);

    foreach ($languages as $language){

      // Skip own translation
      if($language->slug == $post_language){
        continue;
      }

      $translation = pll_get_post($post->ID, $language->slug);

      if($translation){
        $translations[$language->slug] = $translation;
      }
    }

    $formatted_post['language']     = $post_language;
    $formatted_post['translations'] = (object) $translations;
  }

  return $formatted_post;
}