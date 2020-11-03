<?php

add_action( 'rest_api_init', 'gcms_api_update_site' ); 
function gcms_api_update_site() {
    register_rest_route( 'gcms/v1', '/updateSite', array(
        'methods' => 'GET',
        'callback' => 'gcms_api_update_site_callback'
    ));
}

function gcms_api_update_site_callback($data) {
    $site_id = $data->get_param('id');
    $settings = $data->get_param('settings');
    $settings = $settings ? json_decode($settings) : [];
    
    $site = get_blog_details($site_id);

    if($site){
        switch_to_blog($site->blog_id);
        
        // Update header
        gcms_add_acf_field_group($settings->header, 'theme-header');

        $header_fields = [];
        foreach($settings->header->fields as $field){
            if($field->type == 'image'){
                $header_fields[$field->id] = $field->value->id;
            }else {
                $header_fields[$field->id] = $field->value;
            }
        }

        $data = update_field('theme-header', $header_fields, 'option');

        return $data;
    }
}

?>