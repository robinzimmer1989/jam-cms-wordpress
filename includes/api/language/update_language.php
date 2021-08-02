<?php

add_action( 'rest_api_init', 'jam_cms_api_update_language' ); 
function jam_cms_api_update_language() {
    register_rest_route( 'jamcms/v1', '/updateLanguage', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_update_language_callback',
        'permission_callback' => function () {
            return current_user_can( 'manage_options' );
        }
    ));
}

function jam_cms_api_update_language_callback($data) {
    $parameters = $data->get_params();

    $check = jam_cms_api_base_check($parameters, ['id', 'slug', 'name', 'locale']);

    if(is_wp_error($check)){
        return $check;
    }

    if(class_exists('PLL_Admin_Model') && class_exists('PLL_Settings')){

        $predefined_languages = PLL_Settings::get_predefined_languages();

        $flag = '';
        
        if(array_key_exists($parameters['locale'], $predefined_languages)){
            $flag = $predefined_languages[$parameters['locale']]['flag'];
        }

        $args = [
            'lang_id'   => $parameters['id'],
            'slug'      => $parameters['slug'],
            'name'      => $parameters['name'],
            'locale'    => $parameters['locale'],
            'rtl'       => 0,
            'flag'      => $flag
        ];

        // When adding a language and running the get_languages functions later on, post types and label property values are empty.
        // That's why we need to fetch them here and then override those values in the next step.
        $current_languages = jam_cms_get_languages();
        
        $options = get_option('polylang');

        $model = new PLL_Admin_Model($options);

        $result = $model->update_language($args);

        if($result){
            $languages = jam_cms_get_languages();

            // Override empty values to fix bug
            $languages->title = $current_languages->title;
            $languages->postTypes = $current_languages->postTypes;

            return $languages;
        }
    }

    return null;
}