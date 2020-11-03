<?php

add_action( 'rest_api_init', 'gcms_api_update_post' ); 
function gcms_api_update_post() {
  register_rest_route( 'gcms/v1', '/updatePost', array(
    'methods' => 'GET',
    'callback' => 'gcms_api_update_post_callback'
  ));
}

function gcms_api_update_post_callback($data) {
    $site_id = $data->get_param('siteID');
    $post_id = $data->get_param('id');
    $title = $data->get_param('title');
    $slug = $data->get_param('slug');
    $status = $data->get_param('status');
    $parent_id = $data->get_param('parentID');
    $featured_image = $data->get_param('featuredImage');
    $content = $data->get_param('content');
    $modules = $content ? json_decode($content) : [];
    $seo_title = $data->get_param('seoTitle');
    $seo_description = $data->get_param('seoDescription');

    $site = get_blog_details($site_id);

    if($site){
      switch_to_blog($site->blog_id);

      $post_data = array(
        'ID' => $post_id
      );

      if($title){
        $post_data['post_title'] = $title;
      }

      if($slug){
        $post_data['post_name'] = $slug;
      }

      if($status){
        $post_data['post_status'] = $status;
      }

      if($parent_id){
        $post_data['post_parent'] = $parent_id;
      }

      wp_update_post($post_data);

      if($seo_title){
        update_post_meta($post_id, '_yoast_wpseo_title', $seo_title);
      }

      if($seo_description){
        update_post_meta($post_id, '_yoast_wpseo_metadesc', $seo_description);
      }

      // Generate and update acf flexible content modules on the fly
      if($modules && count($modules) > 0){

        $fc_rows = [];

        // Update acf fields in post
        foreach($modules as $module){
          gcms_add_acf_field_group($module);

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
      }

      $data = gcms_get_post_by_id($site_id, $post_id);
      return $data;
    }

    return null;
}

?>