<?php

add_action( 'rest_api_init', 'jam_cms_api_delete_language' ); 
function jam_cms_api_delete_language() {
    register_rest_route( 'jamcms/v1', '/deleteLanguage', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_delete_language_callback',
        'permission_callback' => function () {
            return current_user_can( 'manage_options' );
        }
    ));
}

function jam_cms_api_delete_language_callback($data) {
    $parameters = $data->get_params();

    $check = jam_cms_api_base_check($parameters, ['id']);

    if(is_wp_error($check)){
        return $check;
    }

    if(class_exists('PLL_Admin_Model')){

        $options = get_option('polylang');

        $model = new PLL_Admin_Model($options);
        
        $result = $model->delete_language($parameters['id']);

        return $result;
    }

    return false;
}