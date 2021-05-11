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

    $check = jam_cms_api_base_check($parameters);

    if(is_wp_error($check)){
        return $check;
    }

    if(array_key_exists('id', $parameters)){
        $site_id = $parameters['id'];
    }else{
        $site_id = 'default';
    }

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

    if(array_key_exists('themeOptions', $parameters)){
        $theme_options = $parameters['themeOptions'];
        $theme_options = $theme_options ? json_decode($theme_options) : [];

        jam_cms_upsert_acf_template_options($theme_options);
        jam_cms_update_acf_fields_options($theme_options);
    }
    
    if(current_user_can( 'manage_options' )){

        if(array_key_exists('title', $parameters)){
           update_option('blogname', $parameters['title']);
        }

        if(array_key_exists('siteUrl', $parameters)){
            $formatted_site_url = rtrim($parameters['siteUrl'],'/');
            update_option('site_url', $formatted_site_url);
         }

        if(array_key_exists('googleMapsApi', $parameters)){           
            update_option('jam_cms_google_maps_api_key', $parameters['googleMapsApi']);
         }

        if(array_key_exists('apiKey', $parameters)){
            $api_key = wp_generate_uuid4();
            update_option('deployment_api_key', $api_key);
        }

        if(array_key_exists('deployment', $parameters)){
            $deployment = $parameters['deployment'];
            $deployment = $deployment ? json_decode($deployment) : [];

            $jamstack_deployment_settings = get_option('wp_jamstack_deployments');

            $jamstack_deployment_settings['webhook_url']                = $deployment->buildHook;
            $jamstack_deployment_settings['deployment_badge_url']       = $deployment->badgeImage;
            $jamstack_deployment_settings['deployment_badge_link_url']  = $deployment->badgeLink;

            update_option('wp_jamstack_deployments', $jamstack_deployment_settings);
        }
    }
    
    update_option('jam_cms_undeployed_changes', true);

    $data = jam_cms_get_site_by_id($site_id);

    return $data;
}

?>