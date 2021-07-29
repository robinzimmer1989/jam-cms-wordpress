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

    if(class_exists('PLL_Admin_Model')){

        // When adding a language and running the get_languages functions later on, post types and label property values are empty.
        // That's why we need to fetch them here and then override those values in the next step.
        $current_languages = jam_cms_get_languages();
        
        $options = get_option('polylang');

        $model = new PLL_Admin_Model($options);

        $args = [
            'slug'   => $parameters['slug'],
            'name'   => $parameters['name'],
            'locale' => $parameters['locale'],
            'rtl'    => 0,
            'flag'   => $parameters['slug']
        ];

        $result = $model->add_language($args);

        if($result){

            $languages = jam_cms_get_languages($default_language);

            $default_language = pll_default_language();
            
            // If there is no default language, the user just added the first language and in this case we need to add the data manually,
            // because the main function 'pll_the_languages' requires a reload in order to get the fresh data.
            if(!$default_language){
                $languages->defaultLanguage = $parameters['slug'];

                $term = get_term_by('slug', $parameters['slug'], 'language');

                $languages->languages = [[
                    'id'      => $term->term_id,
                    'name'    => $parameters['name'],
                    'slug'    => $parameters['slug'],
                    'locale'  => $parameters['locale'],
                    'flag'    => $parameters['slug'],
                ]];
            }

            // Override empty values to fix bug
            $languages->title = $current_languages->title;
            $languages->postTypes = $current_languages->postTypes;

            return $languages;
        }
    }

    return null;
}