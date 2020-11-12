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
    $featured_image = $featured_image ? json_decode($featured_image) : null;

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

      if(isset($title)){
        $post_data['post_title'] = $title;
      }

      if(isset($slug)){
        $post_data['post_name'] = $slug;
      }

      if(isset($status)){
        $post_data['post_status'] = $status;
      }

      if(isset($parent_id)){
        $post_data['post_parent'] = $parent_id;
      }

      wp_update_post($post_data);

      if(isset($seo_title)){
        update_post_meta($post_id, '_yoast_wpseo_title', $seo_title);
      }

      if(isset($seo_description)){
        update_post_meta($post_id, '_yoast_wpseo_metadesc', $seo_description);
      }

      if(isset($featured_image)){
        set_post_thumbnail($post_id, $featured_image->id);
      }

      // Generate and update acf flexible content modules on the fly
      if($modules && count($modules) > 0){
        // Updating ACF flexible content field via native function didn't work,
        // so we take care of the update process manually.

        // Update flexible content fields with collection of blocks
        $flexible_content_blocks = [];
        foreach($modules as $module){
          array_push($flexible_content_blocks, 'group_' . $module->name);
        }
        update_post_meta( $post_id, 'flexible_content', $flexible_content_blocks );

        // Add field key reference for flexible content. This is defined in 'add_flexible_content.php'
        update_post_meta( $post_id, '_flexible_content', "field_5fa4b6444156f" );

        $i = 0;

        // Update acf fields in post
        foreach($modules as $module){

          // Add / Update ACF field group if doesn't exist yet or has changed
          gcms_add_acf_field_group($module);

          // Loop through fields and update value and ACF internal group / field reference
          $fields = $module->fields;
          foreach($fields as $field){
            $meta_key =  'flexible_content_' . $i . '_' . $field->id;
            
            if($field->type == 'repeater' && property_exists($field, 'items') && property_exists($field, 'value')){
              gcms_update_sub_fields_recursively($post_id, $module->name, $field, $meta_key);

              // The value for repeater fields must be the amount of items
              $value = count($field->value);

            }else{
              // Value needs to be formatted depending on type before storing into db
              $value = gcms_format_acf_field_value_for_db($field);
            }

            update_post_meta( $post_id, $meta_key, $value );
            update_post_meta( $post_id, '_' . $meta_key, 'field_' . $module->name . '_field_' . $field->id . '_group_' . $module->name);
          }

          $i++;
        }
      }

      $data = gcms_get_post_by_id($site_id, $post_id);
      return $data;
    }

    return null;
}

?>