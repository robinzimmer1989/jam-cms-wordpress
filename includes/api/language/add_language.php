<?php

add_action( 'rest_api_init', 'jam_cms_api_add_language' ); 
function jam_cms_api_add_language() {
    register_rest_route( 'jamcms/v1', '/addLanguage', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_add_language_callback',
        'permission_callback' => function () {
            return current_user_can( 'manage_options' );
        }
    ));
}

function jam_cms_api_add_language_callback($data) {
    $parameters = $data->get_params();

    $check = jam_cms_api_base_check($parameters, ['slug', 'name', 'locale'] );

    if(is_wp_error($check)){
        return $check;
    }

    if(class_exists('PLL_Admin_Model') && class_exists('PLL_Settings')){

        $predefined_languages = PLL_Settings::get_predefined_languages();

        $flag = '';
        
        if(array_key_exists($parameters['locale'], $predefined_languages)){
            $flag = $predefined_languages[$parameters['locale']]['flag'];
        }

        $current_languages = jam_cms_get_languages();

        // Check if language code already exists
        foreach($current_languages->languages as $language) {
            if($language['slug'] == $parameters['slug']){
                return new WP_Error( 'translation_slug_already_exists', __( 'The language code already exists' ), array( 'status' => 400 ) );
            }
        }
        
        $options = get_option('polylang');

        $model = new PLL_Admin_Model($options);

        $result = $model->add_language([
            'slug'   => $parameters['slug'],
            'name'   => $parameters['name'],
            'locale' => $parameters['locale'],
            'rtl'    => 0,
            'flag'   => $flag
        ]);

        if($result){
            $languages = jam_cms_get_languages();

            if(!$languages->defaultLanguage){
            
                // Override missing default language and title which are unavailable without a refresh
                $languages->defaultLanguage = $parameters['slug'];
                $languages->title = $current_languages->title;
            }

            return $languages;
        }
    }

    return null;
}