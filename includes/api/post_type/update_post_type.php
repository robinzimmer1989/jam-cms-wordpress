<?php

add_action( 'rest_api_init', 'jam_cms_api_update_post_type' ); 
function jam_cms_api_update_post_type() {
  register_rest_route( 'gcms/v1', '/updateCollection', array(
    'methods' => 'POST',
    'callback' => 'jam_cms_api_update_post_type_callback',
    'permission_callback' => function () {
      return current_user_can( 'manage_options' );
    }
  ));
}

function jam_cms_api_update_post_type_callback($data) {
    $parameters       = $data->get_params();

    $site_id          = $parameters['siteID'];
    $post_type_name   = $parameters['id'];
    $title            = $parameters['title'];
    $slug             = $parameters['slug'];
    $template_modules = $parameters['template'];
    $template_modules = $template_modules ? json_decode($template_modules) : [];

    jam_cms_api_base_check($site_id, [$post_id]);

    if($post_type_name != 'page'){
      $post_types = get_option('cptui_post_types');

      if($post_types){
          $post_types[$post_type_name]['label']           = $title;
          $post_types[$post_type_name]['singular_label']  = $title;
          $post_types[$post_type_name]['rewrite_slug']    = $slug;

          update_option('cptui_post_types', $post_types);   
      }

      // Delete all field groups
      $id = jam_cms_get_acf_field_id('acf-field-group', 'group_template-' . $post_type_name);
      jam_cms_delete_acf_fields_by_parent_id($id);

      // Check for template. If exists create/update field groups and assign to template
      if(count($template_modules) > 0){

        $i = 0;

        foreach($template_modules as $module){
          $field_group = jam_cms_add_acf_field_group($module, 'Block: ', '', [
            'rule_0' => ['param' => 'post_type', 'operator' => '==', 'value' => 'page'],
            'rule_1' => ['param' => 'post_type', 'operator' => '!=', 'value' => 'page']
          ]);

          jam_cms_add_acf_field_group_to_template('group_template-' . $post_type_name, $field_group, $i);
          
          $i++;
        }

      }else{
        // Restore original flexible content template
        jam_cms_add_acf_template($title, $post_type_name);
      }
      
    }

    $post_type = jam_cms_format_post_type($site_id, $post_types[$post_type_name]);

    return $post_type;
}

?>