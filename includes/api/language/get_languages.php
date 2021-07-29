<?php

add_action( 'rest_api_init', 'jam_cms_api_get_languages' ); 
function jam_cms_api_get_languages() {
    register_rest_route( 'jamcms/v1', '/getLanguages', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_get_languages_callback',
        'permission_callback' => function () {
            return current_user_can( 'manage_options' );
        }
    ));
}

function jam_cms_api_get_languages_callback($data) {
    $parameters = $data->get_params();

    $check = jam_cms_api_base_check($parameters);

    if(is_wp_error($check)){
        return $check;
    }

    $languages = [];

    if(class_exists('PLL_Settings')){
      // We need to initialize the link_model object to prevent a PHP warning
      $options = (object) ['options' => null];
      $link_model = (object) ['model' =>  $options];

      $predefined_languages = PLL_Settings::get_predefined_languages();

      foreach ($predefined_languages as $language){
        array_push($languages, [
            'name'      => $language['name'],
            'locale'    => $language['locale'],
            'slug'      => $language['code'],
        ]);
      }
    }

    return $languages;
} 