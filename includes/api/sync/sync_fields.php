<?php

add_action( 'rest_api_init', 'jam_cms_api_sync_fields' ); 
function jam_cms_api_sync_fields() {
    register_rest_route( 'jamcms/v1', '/syncFields', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_sync_fields_callback',
        'permission_callback' => function () {
            return true;
        }
    ));
}

function jam_cms_api_sync_fields_callback($data) {
    $parameters = $data->get_params();

    $check = jam_cms_api_base_check($parameters, ['apiKey']);

    if(is_wp_error($check)){
        return $check;
    }

    // Check if syncing is enabled before updating the ACF template
    $settings = get_option("jam_cms_settings");
    $syncing = is_array($settings) && array_key_exists("disable_syncing", $settings) && $settings['disable_syncing'] == 1 ? false : true;

    if($syncing && array_key_exists('fields', $parameters)){

        $fields = json_decode($parameters['fields']);

        // Delete all ACF field groups
        global $wpdb;

        $result = $wpdb->query( 
            $wpdb->prepare("
                DELETE posts
                FROM {$wpdb->prefix}posts posts
                WHERE posts.post_type = %s OR posts.post_type = %s
                ",
                "acf-field-group",
                "acf-field"
            ) 
        );

        // Delete all custom post types
        update_option('cptui_post_types', []);   

        // Delete all WordPress templates
        update_option('jam-cms-templates', []);

        if(property_exists($fields, 'postTypes')){
            foreach ($fields->postTypes as $post_type){

                jam_cms_create_post_type($post_type);

                foreach ($post_type->templates as $template){
                    jam_cms_create_template($template);
                    jam_cms_upsert_acf_template($template);
                }
            }
        }

        // Delete all custom taxonomies
        update_option('cptui_taxonomies', []);   

        if(property_exists($fields, 'taxonomies')){
            foreach ($fields->taxonomies as $taxonomy){
                jam_cms_create_taxonomy($taxonomy);
            }
        }

        if(property_exists($fields, 'themeOptions')){
            jam_cms_upsert_acf_template_options($fields->themeOptions);
        }

        
    }    

    
    // When the user creates a new post type while running gatsby develop, jamCMS will display a sync button in the jamCMS backend. 
    if(is_user_logged_in()){
        if($syncing){
            return jam_cms_get_site_by_id();
        }else{
            return new WP_Error( 'syncing_disabled', __( 'Syncing has been disabled in the WordPress backend' ), array( 'status' => 400 ));
        }
    }else{
        if($syncing){
            return 'jamCMS: Synced ACF fields successfully to WordPress';
        }else{
            return 'jamCMS: Syncing has been disabled in the WordPress backend';
        }
    }
}