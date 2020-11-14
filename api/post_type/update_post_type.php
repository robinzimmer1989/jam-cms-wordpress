<?php

add_action( 'rest_api_init', 'gcms_api_update_post_type' ); 
function gcms_api_update_post_type() {
  register_rest_route( 'gcms/v1', '/updateCollection', array(
    'methods' => 'GET',
    'callback' => 'gcms_api_update_post_type_callback'
  ));
}

function gcms_api_update_post_type_callback($data) {
    $site_id = $data->get_param('siteID');
    $post_type_name = $data->get_param('id');
    $title = $data->get_param('title');
    $slug = $data->get_param('slug');
    $template_modules = $data->get_param('template');
    $template_modules = $template_modules ? json_decode($template_modules) : [];

    $site = get_blog_details($site_id);

    if($site){
      switch_to_blog($site->blog_id);

      if($post_type_name != 'page'){
        $post_types = get_option('cptui_post_types');

        if($post_types){
            $post_types[$post_type_name]['label']           = $title;
            $post_types[$post_type_name]['singular_label']  = $title;
            $post_types[$post_type_name]['rewrite_slug']    = $slug;

            update_option('cptui_post_types', $post_types);   
        }

        // Delete all field groups
        $id = gcms_get_acf_field_id('acf-field-group', 'group_template_' . $post_type_name);
        gcms_delete_acf_fields_by_parent_id($id);

        // Check for template. If exists create/update field groups and assign to template
        if(count($template_modules) > 0){

          $i = 0;

          foreach($template_modules as $module){
            $field_group = gcms_add_acf_field_group($module, 'Block: ', '', [
              'rule_0' => ['param' => 'post_type', 'operator' => '==', 'value' => 'page'],
              'rule_1' => ['param' => 'post_type', 'operator' => '!=', 'value' => 'page']
            ]);

            gcms_add_acf_field_group_to_template('group_template_' . $post_type_name, $field_group, $i);
            
            $i++;
          }

        }else{
          // Restore original flexible content template
          gcms_add_acf_template($title, $post_type_name);
        }
        
      }

      return gcms_format_post_type($site_id, $post_types[$post_type_name]);
    }

    return null;
}

?>