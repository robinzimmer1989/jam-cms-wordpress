<?php

add_action( 'rest_api_init', 'gcms_api_create_site' ); 
function gcms_api_create_site() {
    register_rest_route( 'gcms/v1', '/createSite', array(
        'methods' => 'POST',
        'callback' => 'gcms_api_create_site_callback',
        'permission_callback' => function () {
            return current_user_can( 'create_sites' );
        }
    ));
}

function gcms_api_create_site_callback($data) {
    $parameters = $data->get_params();

    $title      = $parameters['title'];

    if(!is_multisite()){
        return new WP_Error( 'rest_upload_no_data', __( 'No data supplied' ), array( 'status' => 400 ));
    }

    if(!isset($title) || !$title){
        return new WP_Error( 'rest_upload_no_data', __( 'No data supplied' ), array( 'status' => 400 ));
    }

    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;

    $protocols = array('http://', 'http://www.', 'www.');
    $url = str_replace($protocols, '', get_site_url());
    $site_id = wp_generate_uuid4();

    wpmu_create_blog( $url, $site_id, $title, $user_id , array( 'public' => 0 ) );
    
    $site = get_blog_details($site_id);
    switch_to_blog($site->blog_id);

    // Create flexible content element
    gcms_add_acf_flexible_content();

    // Create template and assign flexible content as default
    gcms_add_acf_template('Page', 'page');

    # TODO: Delete sample page and hello world posts
    // $homepage = get_page_by_title( 'Sample Page' );

    // Create deployment api key
    $api_key = wp_generate_uuid4();
    update_option('deployment_api_key', $api_key);

    gcms_api_base_check($site_id);

    $data = gcms_get_site_by_id($site_id);

    return $data;
}

?>