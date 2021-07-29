<?php

add_action( 'rest_api_init', 'jam_cms_api_update_language_settings' ); 
function jam_cms_api_update_language_settings() {
    register_rest_route( 'jamcms/v1', '/updateLanguageSettings', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_update_language_settings_callback',
        'permission_callback' => function () {
            return current_user_can( 'manage_options' );
        }
    ));
}

function jam_cms_api_update_language_settings_callback($data) {
    $parameters = $data->get_params();

    $check = jam_cms_api_base_check($parameters, ['postTypes', 'defaultLanguage']);

    if(is_wp_error($check)){
        return $check;
    }

    $options = get_option('polylang');

    if(!$options){
        $options = [];
    }

    $options['default_lang'] = $parameters['defaultLanguage'];
    $options['post_types']   = json_decode($parameters['postTypes']);

    $has_updated = update_option('polylang', $options);

    return $has_updated;
}