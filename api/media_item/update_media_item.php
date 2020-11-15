<?php

add_action( 'rest_api_init', 'gcms_api_update_media_item' ); 
function gcms_api_update_media_item() {
  register_rest_route( 'gcms/v1', '/updateMediaItem', array(
    'methods' => 'POST',
    'callback' => 'gcms_api_update_media_item_callback'
  ));
}

function gcms_api_update_media_item_callback($data) {
    $parameters = $data->get_params();

    $site_id = $parameters['siteID'];
    $attachment_id = $parameters['id'];
    $alt_text = $parameters['altText'];

    $site = get_blog_details($site_id);

    if($site){
      switch_to_blog($site->blog_id);

      $attachment_meta = array(
        'ID'		        => $attachment_id,
        'post_title'	  => '',
        'post_excerpt'	=> '',
        'post_content'	=> '',
      );
  
      update_post_meta( $attachment_id, '_wp_attachment_image_alt', $alt_text );
  
      wp_update_post( $attachment_meta );
    }

    return null;
}

?>