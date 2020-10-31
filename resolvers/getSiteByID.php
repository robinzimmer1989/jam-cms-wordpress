<?php

function gcms_resolver_getSiteByID($siteID){
 
  $site = get_blog_details($siteID);

  if($site){
    $blogID = $site->blog_id;
    switch_to_blog($blogID);

    $settings = get_blog_option($blogID, 'gcms_custom_plugin_options');

    // Get 'real' post types and add posts
    $allPostTypes = get_post_types([], 'objects');

    $items = [];

    foreach ( $allPostTypes as $postType ) {
      if ($postType->publicly_queryable && $postType->name != 'attachment') {

          $posts = get_posts(array(
            'numberposts' => -1,
            'post_type' => $postType->name,
            'post_status' => ['publish', 'draft', 'trash']
          ));

          $formattedPosts = [];
          foreach($posts as $post){
            array_push($formattedPosts, gcms_formatPost($siteID, $post));
          }

          array_push($items, [
            'id' => $postType->name,
            'slug' => $postType->name,
            'title' => $postType->label,
            'template' => null,
            'posts' => [
              'items' => $formattedPosts
            ]
          ]);
      }
  }

    $data = array(
      'id' => $siteID,
      'title' => $site->blogname,
      'netlifyID' =>  $settings['netlify_id'],
      'netlifyUrl' => $settings['netlify_url'],
      'settings' => null,
      'postTypes' => [
        'items' => $items
      ],
      'forms' => [
        'items' => []
      ]
    );

    return $data;
  }

  return null;

}

?>