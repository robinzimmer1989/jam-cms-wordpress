<?php

add_action( 'rest_api_init', 'jam_cms_api_update_post' ); 
function jam_cms_api_update_post() {
  register_rest_route( 'jamcms/v1', '/updatePost', array(
    'methods' => 'POST',
    'callback' => 'jam_cms_api_update_post_callback',
    'permission_callback' => function () {
      return current_user_can( 'edit_posts' );
    }
  ));
}

function jam_cms_api_update_post_callback($data) {
    $parameters = $data->get_params();

    jam_cms_api_base_check($parameters, ['id']);

    $site_id    = $parameters['siteID'];
    $post_id    = $parameters['id'];

    $post_data = array(
      'ID' => $post_id
    );

    if(array_key_exists('title', $parameters)){
      $post_data['post_title'] = $parameters['title'];
    }

    if(array_key_exists('slug', $parameters) && array_key_exists('parentID', $parameters)){
      $unique_slug = wp_unique_post_slug( $parameters['slug'], $post_id, '', get_post_type($post_id), $parameters['parentID'] );
      $post_data['post_name'] = $unique_slug;
    }

    if(array_key_exists('status', $parameters)){
      $post_data['post_status'] = $parameters['status'];
    }

    if(array_key_exists('parentID', $parameters)){
      $post_data['post_parent'] = $parameters['parentID'];
    }

    if(array_key_exists('taxonomies', $parameters)){
      $taxonomies = $parameters['taxonomies'] ? json_decode($parameters['taxonomies']) : null;

      if($taxonomies){
        foreach($taxonomies as $key => $terms){
          wp_set_post_terms($post_id, $terms, $key);
        }
      }
    }

    wp_update_post($post_data);

    if(array_key_exists('seo', $parameters)){
      $seo = $parameters['seo'] ? json_decode($parameters['seo']) : null;

      if($seo){
        if(property_exists($seo, 'title')){
          update_post_meta($post_id, '_yoast_wpseo_title', $seo->title);
        }

        if(property_exists($seo, 'description')){
          update_post_meta($post_id, '_yoast_wpseo_metadesc', $seo->description);
        }

        if(property_exists($seo, 'ogImage')){
         
          $url = $seo->ogImage && property_exists($seo->ogImage, 'url') ? $seo->ogImage->url : '';
          update_post_meta($post_id, '_yoast_wpseo_opengraph-image', $url);

          $id = $seo->ogImage && property_exists($seo->ogImage, 'id') ? $seo->ogImage->id : '';
          update_post_meta($post_id, '_yoast_wpseo_opengraph-image-id', strval($id));
        }
      }      
    }

    if(array_key_exists('featuredImage', $parameters)){
      $featured_image = $parameters['featuredImage'] ? json_decode($parameters['featuredImage']) : null;

      if($featured_image){
        set_post_thumbnail($post_id, $featured_image->id);
      }
    }

    if(array_key_exists('template', $parameters)){
      // Manually created templates are stores as 'tempate-name.php' but jamCMS is reading 'name' only, so we need to format it differently when storing it in the db.
      update_post_meta( $post_id, '_wp_page_template', "template-{$parameters['template']}.php" );
    }

    if(array_key_exists('templateObject', $parameters)){
      $templateObject = $parameters['templateObject'] ? json_decode($parameters['templateObject']) : null;

      if($templateObject){
        jam_cms_create_template($templateObject);
        jam_cms_upsert_acf_template($templateObject, $post_id);
      }
    }

    if(array_key_exists('content', $parameters)){
      $content = $parameters['content'] ? json_decode($parameters['content']) : null;

      if($content){
        jam_cms_update_acf_fields($post_id, $content);
      }
    }

    update_option('jam_cms_undeployed_changes', true);

    $data = jam_cms_get_post_by_id($post_id);

    return $data;
}

?>