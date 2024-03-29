<?php

function jam_cms_get_post_by_id($post_id){

  $post = get_post($post_id);

  if($post){
    
    // Add post content
    $fields = get_fields($post_id);

    // If the post is a revision, we have to override postID and post object with the post parent of the revision.
    // This is because content and title are the only things that are tracked by the WP revisions functions. 
    // For everything else we gonna use the original post.
    $revision = null;

    if($post->post_type == 'revision'){
      $revision = $post;
      $post_id = $post->post_parent;
      $post = get_post($post_id);
    }

    $formatted_post = jam_cms_format_post($post);

    // Override title and modify date if post is revision
    if($revision){
      $formatted_post['title'] = $revision->post_title;
      $formatted_post['updatedAt'] = $revision->post_date;
      $formatted_post['revisionID'] = $revision->ID;
    }

    // If no fields are found (post hasn't been saved yet or no content changes), we'll generate them manually.
    if(!$fields){     
      $fields = jam_cms_get_default_field_values($post_id);
    }

    if($fields){
      $formatted_post['content'] = (object) jam_cms_format_fields($fields, $post_id);
    }

    // Add SEO
    $seo_title        = get_post_meta($post_id, '_yoast_wpseo_title', true);
    $seo_description  = get_post_meta($post_id, '_yoast_wpseo_metadesc', true);
    $seo_noindex      = get_post_meta($post_id, '_yoast_wpseo_meta-robots-noindex', true);
    $seo_og_image_id  = get_post_meta($post_id, '_yoast_wpseo_opengraph-image-id', true);

    $formatted_seo_og_image = null;
    if($seo_og_image_id && count($seo_og_image_id) > 0){
      $seo_og_image           = acf_get_attachment($seo_og_image_id[0]);
      $formatted_seo_og_image = jam_cms_format_acf_field_value_for_frontend(['type' => 'image'], $seo_og_image);
    }

    $formatted_post['seo'] = [
      'title'             => $seo_title ? $seo_title[0] : '',
      'metaDesc'          => $seo_description ? $seo_description[0] : '',
      'opengraphImage'    => $formatted_seo_og_image,
      'metaRobotsNoindex' => $seo_noindex && $seo_noindex == 1 ? "noindex" : "index"
    ];

    // Add revisions
    if(current_user_can('edit_posts')){
      $revisions =  wp_get_post_revisions($post_id);
      $formatted_revisions = [];
      foreach($revisions as $revision){
        // In the create_post API we're updating the post right after inserting. 
        // This causes a unnecessary revision which will be filtered out here.
        if($revision->post_date != $post->post_date){
          array_push($formatted_revisions, [
            'id'      => $revision->ID,
            'title'   => $revision->post_date
          ]);
        }
      }
      $formatted_post['revisions'] = $formatted_revisions;
      $formatted_post['revisionsEnabled'] = wp_revisions_enabled($post);
    }

    return $formatted_post;
  }

  return new WP_Error( 'post_not_found', __('Post not found'), array( 'status' => 400 ) );
}