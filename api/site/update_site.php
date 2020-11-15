<?php

add_action( 'rest_api_init', 'gcms_api_update_site' ); 
function gcms_api_update_site() {
    register_rest_route( 'gcms/v1', '/updateSite', array(
        'methods' => 'POST',
        'callback' => 'gcms_api_update_site_callback'
    ));
}

function gcms_api_update_site_callback($data) {
    $parameters = $data->get_params();

    $site_id = $parameters['id'];
    $title = $parameters['title'];
    $front_page = $parameters['frontPage'];
    $webhook_url = $parameters['netlifyBuildHook'];
    $deployment_badge_url = $parameters['netlifyBadgeImage'];
    $deployment_badge_link_url = $parameters['netlifyBadgeLink'];
    $settings = $parameters['settings'];
    $settings = $settings ? json_decode($settings) : [];
    
    $site = get_blog_details($site_id);

    if($site){
        switch_to_blog($site->blog_id);

        if(isset($title)){
            update_option('blogname', $title);
        }

        if(isset($front_page)){
            update_blog_option( $site->blog_id, 'page_on_front', $front_page );
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

        // Update Netlify settings
        if(isset($webhook_url) || isset($deployment_badge_url) || isset($deployment_badge_link_url)){
            $jamstack_deployment_settings = unserialize(get_option('wp_jamstack_deployments'));

            if(isset($webhook_url)){
                $jamstack_deployment_settings['webhook_url'] = $webhook_url;
            }

            if(isset($deployment_badge_url)){
                $jamstack_deployment_settings['deployment_badge_url'] = $deployment_badge_url;
            }

            if(isset($deployment_badge_link_url)){
                $jamstack_deployment_settings['deployment_badge_link_url'] = $deployment_badge_link_url;
            }

            update_option('wp_jamstack_deployments', $jamstack_deployment_settings);
        }

        $data = gcms_get_site_by_id($site_id);

        return $data;
    }
}

?>