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

    jam_cms_api_base_check($parameters, ['apiKey']);

    if(array_key_exists('fields', $parameters)){

        $fields = json_decode($parameters['fields']);

        if(property_exists($fields, 'postTypes')){
            foreach ($fields->postTypes as $post_type){
                foreach ($post_type as $template){
                    jam_cms_create_template($template);
                    jam_cms_upsert_acf_template($template);
                }
            }
        }

        if(property_exists($fields, 'themeOptions')){

            $theme_options = $fields->themeOptions;

            jam_cms_upsert_acf_template_options($theme_options);
            jam_cms_update_acf_fields_options($theme_options);
        }

        return 'jamCMS: Synced ACF fields successfully to WordPress';
    }    
}

?>