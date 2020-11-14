<?php

add_action( 'rest_api_init', 'gcms_api_create_site' ); 
function gcms_api_create_site() {
    register_rest_route( 'gcms/v1', '/createSite', array(
        'methods' => 'GET',
        'callback' => 'gcms_api_create_site_callback'
    ));
}

function gcms_api_create_site_callback($data) {
    $title = $data->get_param('title');

    if($title && current_user_can('administrator')){

        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;

        $protocols = array('http://', 'http://www.', 'www.');
        $url = str_replace($protocols, '', get_site_url());
        $path = wp_generate_uuid4();

        $site_id = wpmu_create_blog( $url, $path, $title, $user_id , array( 'public' => 0 ) );
        
        $site = get_blog_details($site_id);
        switch_to_blog($site->blog_id);

        // Create flexible content element
        gcms_add_acf_flexible_content();

        // Create template and assign flexible content as default
        gcms_add_acf_template('Page', 'page');

        # TODO: Delete sample page and hello world posts
        // $homepage = get_page_by_title( 'Sample Page' );

        $data = gcms_get_site_by_id($site_id);
        return $data;
    }

    return null;
}

?>