<?php

add_action( 'rest_api_init', 'gcms_api_update_site' ); 
function gcms_api_update_site() {
    register_rest_route( 'gcms/v1', '/updateSite', array(
        'methods' => 'POST',
        'callback' => 'gcms_api_update_site_callback',
        'permission_callback' => function () {
            return current_user_can( 'edit_posts' );
        }
    ));
}

function gcms_api_update_site_callback($data) {
    $parameters = $data->get_params();

    if(array_key_exists('id', $parameters)){
        $site_id = $parameters['id'];
    }

    gcms_api_base_check($site_id);

    if(array_key_exists('frontPage', $parameters)){
        update_blog_option( $site->blog_id, 'page_on_front', $parameters['frontPage'] );
    }

    if(array_key_exists('settings', $parameters)){
        $settings = $parameters['settings'];
        $settings = $settings ? json_decode($settings) : [];
    }
    
    // Update header
    if(isset($settings) && property_exists($settings, 'header')){
        gcms_add_acf_field_group($settings->header, '', 'header_', [
            'rule_0' => ['param' => 'options_page', 'operator' => '==', 'value' => 'theme_header']
        ]);

        foreach($settings->header->fields as $field){
            $meta = 'options_header_' . $field->id;
            update_option($meta, gcms_format_acf_field_value_for_db($field));
            update_option('_' . $meta, 'field_' . $field->id . '_group_header');
        }
    }

    // Update footer
    if(isset($settings) && property_exists($settings, 'footer')){
        gcms_add_acf_field_group($settings->footer, '', 'footer_', [
            'rule_0' => ['param' => 'options_page', 'operator' => '==', 'value' => 'theme_footer']
        ]);

        foreach($settings->footer->fields as $field){
            $meta = 'options_footer_' . $field->id;
            update_option($meta, gcms_format_acf_field_value_for_db($field));
            update_option('_' . $meta, 'field_' . $field->id . '_group_footer');
        }
    }

    
    if(current_user_can( 'manage_options' )){
        
        if(array_key_exists('title', $parameters)){
            update_option('blogname', $parameters['title']);
        }

            // Update Netlify settings
        if(
            array_key_exists('netlifyBuildHook', $parameters) &&
            array_key_exists('netlifyBadgeImage', $parameters) &&
            array_key_exists('netlifyBadgeLink', $parameters)
        ){
            $jamstack_deployment_settings = get_option('wp_jamstack_deployments');

            $jamstack_deployment_settings['webhook_url']                = $parameters['netlifyBuildHook'];
            $jamstack_deployment_settings['deployment_badge_url']       = $parameters['netlifyBadgeImage'];
            $jamstack_deployment_settings['deployment_badge_link_url']  = $parameters['netlifyBadgeLink'];

            update_option('wp_jamstack_deployments', $jamstack_deployment_settings);
        }
    }

    $data = gcms_get_site_by_id($site_id);

    return $data;
}

?>