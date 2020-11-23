<?php

add_action( 'rest_api_init', 'gcms_api_update_post' ); 
function gcms_api_update_post() {
  register_rest_route( 'gcms/v1', '/updatePost', array(
    'methods' => 'POST',
    'callback' => 'gcms_api_update_post_callback',
    'permission_callback' => function () {
      return current_user_can( 'edit_posts' );
    }
  ));
}

function gcms_api_update_post_callback($data) {
    $parameters = $data->get_params();

    $site_id        = $parameters['siteID'];
    $post_id        = $parameters['id'];

    gcms_api_base_check($site_id, [$post_id]);

    $post_data = array(
      'ID' => $post_id
    );

    if(array_key_exists('title', $parameters)){
      $post_data['post_title'] = $parameters['title'];
    }

    if(array_key_exists('slug', $parameters)){
      $post_data['post_name'] = $parameters['slug'];
    }

    if(array_key_exists('status', $parameters)){
      $post_data['post_status'] = $parameters['status'];
    }

    if(array_key_exists('parentID', $parameters)){
      $post_data['post_parent'] = $parameters['parentID'];
    }

    wp_update_post($post_data);

    if(array_key_exists('seoTitle', $parameters)){
      update_post_meta($post_id, '_yoast_wpseo_title', $parameters['seoTitle']);
    }

    if(array_key_exists('seoDescription', $parameters)){
      update_post_meta($post_id, '_yoast_wpseo_metadesc', $parameters['seoDescription']);
    }

    if(array_key_exists('featuredImage', $parameters)){
      $featured_image = $parameters['featuredImage'] ? json_decode($parameters['featuredImage']) : null;

      if($featured_image){
        set_post_thumbnail($post_id, $featured_image->id);
      }
    }

    if(array_key_exists('content', $parameters)){
     
      $modules = $parameters['content'] ? json_decode($parameters['content']) : [];

      $template = gcms_get_template_by_post_id($post_id);

      $flexible_content_blocks = [];

      // Generate and update acf flexible content modules on the fly
      if($modules && count($modules) > 0){
        
        $i = 0;

        // Update acf fields in post
        foreach($modules as $module){

          // Add / Update ACF field group if doesn't exist yet or has changed
          $field_group = gcms_add_acf_field_group($module, 'Block: ', '', [
            'rule_0' => ['param' => 'post_type', 'operator' => '==', 'value' => 'page'],
            'rule_1' => ['param' => 'post_type', 'operator' => '!=', 'value' => 'page']
          ]);

          // Check if flexible content
          if(count($template) > 0 && $template[0]['type'] == 'flexible_content'){
            gcms_add_acf_field_group_to_flexible_content($field_group);
            gcms_update_flexible_content_field_values($post_id, $module, $i);
            array_push($flexible_content_blocks, 'group_' . $module->name);
          }else {
            gcms_update_template_field_values($post_id, $module, $i);
          }

          $i++;
        }
      }
      
      if(count($template) > 0 && $template[0]['type'] == 'flexible_content'){
        update_post_meta( $post_id, 'flex', $flexible_content_blocks);
        update_post_meta( $post_id, '_flex', 'field_flex');
      }
    }

    $data = gcms_get_post_by_id($site_id, $post_id);

    return $data;
}

?>