<?php

add_action( 'rest_api_init', 'gcms_api_update_post' ); 
function gcms_api_update_post() {
  register_rest_route( 'gcms/v1', '/updatePost', array(
    'methods' => 'GET',
    'callback' => 'gcms_api_update_post_callback'
  ));
}

function gcms_api_update_post_callback($data) {
    $site_id        = $data->get_param('siteID');
    $post_id        = $data->get_param('id');
    $title          = $data->get_param('title');
    $slug           = $data->get_param('slug');
    $status         = $data->get_param('status');
    $parent_id      = $data->get_param('parentID');
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
        update_post_meta( $post_id, 'flexible_content', $flexible_content_blocks);
        update_post_meta( $post_id, '_flexible_content', "field_flexible_content");
      }

      $data = gcms_get_post_by_id($site_id, $post_id);
      return $data;
    }

    return null;
}

?>