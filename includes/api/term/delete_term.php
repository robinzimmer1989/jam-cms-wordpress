<?php

add_action( 'rest_api_init', 'jam_cms_api_delete_term' ); 
function jam_cms_api_delete_term() {
  register_rest_route( 'jamcms/v1', '/deleteTerm', array(
    'methods' => 'POST',
    'callback' => 'jam_cms_api_delete_term_callback',
    'permission_callback' => function () {
      return current_user_can( 'manage_options' );
    }
  ));
}

function jam_cms_api_delete_term_callback($data) {
    $parameters   = $data->get_params();

    $site_id      = $parameters['siteID'];
    $taxonomy_id  = $parameters['taxonomyID'];
    $term_id      = $parameters['id'];

    jam_cms_api_base_check($site_id, [$taxonomy_id, $term_id]);

    // Get term before deleting it
    $term = get_term($term_id);

    $result = wp_delete_term($term_id, $taxonomy_id);

    if($result == 0){
      return new WP_Error( "cannot_delete_term", __( "Can't delete default category" ), array( 'status' => 400 ) );

    }elseif($result){
      $formatted_term = jam_cms_format_term($term);
      return $formatted_term;
      
    }else{
      return new WP_Error( "term_does_not_exist", __( "Term doesn't exist" ), array( 'status' => 400 ) );
    }
}

?>