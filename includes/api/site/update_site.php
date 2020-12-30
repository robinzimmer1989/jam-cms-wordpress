<?php

add_action( 'rest_api_init', 'jam_cms_api_update_site' ); 
function jam_cms_api_update_site() {
    register_rest_route( 'jamcms/v1', '/updateSite', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_update_site_callback',
        'permission_callback' => function () {
            return current_user_can( 'edit_posts' );
        }
    ));
}

function jam_cms_api_update_site_callback($data) {
    $parameters = $data->get_params();

    if(array_key_exists('id', $parameters)){
        $site_id = $parameters['id'];
    }

    jam_cms_api_base_check($site_id);

    if(array_key_exists('frontPage', $parameters)){
        if(is_multisite()){
            $blog_id = get_current_blog_id();
            update_blog_option($blog_id, 'show_on_front', 'page');
            update_blog_option($blog_id, 'page_on_front', $parameters['frontPage']);
        }else{
            update_option('show_on_front', 'page');
            update_option('page_on_front', $parameters['frontPage']);
        }
    }

    if(array_key_exists('settings', $parameters)){
        $settings = $parameters['settings'];
        $settings = $settings ? json_decode($settings) : [];

        jam_cms_upsert_acf_template_options($settings);
        jam_cms_update_acf_fields_options($settings);
    }
    
    if(current_user_can( 'manage_options' )){

        if(array_key_exists('title', $parameters)){
            update_option('blogname', $parameters['title']);
        }

        if(array_key_exists('apiKey', $parameters)){
            $api_key = wp_generate_uuid4();
            update_option('deployment_api_key', $api_key);
        }

        if(
            array_key_exists('deploymentBuildHook', $parameters) &&
            array_key_exists('deploymentBadgeImage', $parameters) &&
            array_key_exists('deploymentBadgeLink', $parameters)
        ){
            $jamstack_deployment_settings = get_option('wp_jamstack_deployments');

            $jamstack_deployment_settings['webhook_url']                = $parameters['deploymentBuildHook'];
            $jamstack_deployment_settings['deployment_badge_url']       = $parameters['deploymentBadgeImage'];
            $jamstack_deployment_settings['deployment_badge_link_url']  = $parameters['deploymentBadgeLink'];

            update_option('wp_jamstack_deployments', $jamstack_deployment_settings);
        }
    }

    $data = jam_cms_get_site_by_id($site_id);

    return $data;
}

?>