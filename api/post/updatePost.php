<?php

add_action( 'rest_api_init', 'gcms_actions_updatePost' ); 
function gcms_actions_updatePost() {
  register_rest_route( 'wp/v2', '/updatePost', array(
    'methods' => 'GET',
    'callback' => 'gcms_actions_updatePost_callback'
  ));
}

function gcms_actions_updatePost_callback($data) {
    $site_id = $data->get_param('siteID');
    $post_id = $data->get_param('id');
    $title = $data->get_param('title');
    $slug = $data->get_param('slug');
    $status = $data->get_param('status');
    $parentID = $data->get_param('parentID');
    $featuredImage = $data->get_param('featuredImage');
  
    $content = $data->get_param('content');
    $modules = $content ? json_decode($content) : [];

    $seoTitle = $data->get_param('seoTitle');
    $seoDescription = $data->get_param('seoDescription');

    $site = get_blog_details($site_id);

    if($site){
      switch_to_blog($site->blog_id);

      $postData = array(
        'ID' => $post_id,
        'post_title' => $title,
        'post_name' => $slug,
        'post_status' => $status,
        'post_parent' => $parentID || 0
      );

      wp_update_post($postData);

      if($seoTitle){
        update_post_meta($post_id, '_yoast_wpseo_title', $seoTitle);
      }

      if($seoDescription){
        update_post_meta($post_id, '_yoast_wpseo_metadesc', $seoDescription);
      }

      // Generate and update acf flexible content modules on the fly
      foreach($modules as $module){
        gcms_add_acf_field_group($module);
      }

      $fc_rows = [];

      // Update acf fields in post
      foreach($modules as $module){
        $name = $module->name;
        $fields = $module->fields;

        // This must be consistent with automatic flexible content 'modules' function
        $module_slug = strtolower(preg_replace('/[^\w-]+/','-', $name));

        $fc_row = ['acf_fc_layout' => $module_slug];
        foreach($fields as $field){
          $fc_row[$field->id] = $field->value;
        }

        array_push($fc_rows, $fc_row);
      }

      update_field('modules', $fc_rows, $post_id);

      $data = gcms_resolver_getPostByID($site_id, $post_id);
      return $data;
    }

    return 'Something went wrong';
}

?>