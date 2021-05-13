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

    if($fields){
      $formatted_post['content'] = jam_cms_format_fields($fields, $post_id);
    }

    // Add SEO
    $seo_title = get_post_meta($post_id, '_yoast_wpseo_title');
    $seo_description = get_post_meta($post_id, '_yoast_wpseo_metadesc');
    
    $seo_og_image_id = get_post_meta($post_id, '_yoast_wpseo_opengraph-image-id');
    $formatted_seo_og_image = null;

    if($seo_og_image_id && count($seo_og_image_id) > 0){
      $seo_og_image = acf_get_attachment($seo_og_image_id[0]);
      $formatted_seo_og_image = jam_cms_format_acf_field_value_for_frontend(['type' => 'image'], $seo_og_image);
    }

    $formatted_post['seo'] = [
      'title'           => $seo_title ? $seo_title[0] : '',
      'metaDesc'        => $seo_description ? $seo_description[0] : '',
      'opengraphImage'  => $formatted_seo_og_image
    ];

    // Add revisions
    $revisions =  wp_get_post_revisions($post_id);
    $formatted_revisions = [];
    foreach($revisions as $revision){
      array_push($formatted_revisions, [
        'id'      => $revision->ID,
        'title'   => $revision->post_date
      ]);
    }
    $formatted_post['revisions'] = $formatted_revisions;
    $formatted_post['revisionsEnabled'] = wp_revisions_enabled($post);

    return $formatted_post;
  }

  return new WP_Error( 'post_not_found', __('Post not found'), array( 'status' => 400 ) );
}

?>