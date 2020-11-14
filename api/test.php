<?php

add_action( 'rest_api_init', 'gcms_api_test' ); 
function gcms_api_test() {
    register_rest_route( 'gcms/v1', '/test', array(
        'methods' => 'GET',
        'callback' => 'gcms_api_test_callback'
    ));
}

function gcms_api_test_callback($data) {

  $site_id = '7b13ed55-dc26-4529-ae81-b2f8406544fb';

  $site = get_blog_details($site_id);
  switch_to_blog($site->blog_id);


  $post_id = 671;
  $post_type = get_post_type($post_id);

  $id = gcms_get_acf_field_id('acf-field-group', 'group_template_' . $post_type);
  $template_fields = acf_get_fields_by_id($id);

  // Check for flexible content
  if(count($template_fields) > 0 && $template_fields[0]['type'] == 'flexible_content'){
    // return true;
  }

  return $template_fields;
}

?>