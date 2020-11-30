<?php

add_action( 'rest_api_init', 'jam_cms_api_update_media_item' ); 
function jam_cms_api_update_media_item() {
  register_rest_route( 'jamcms/v1', '/updateMediaItem', array(
    'methods' => 'POST',
    'callback' => 'jam_cms_api_update_media_item_callback',
    'permission_callback' => function () {
      return current_user_can( 'edit_posts' );
    }
  ));
}

function jam_cms_api_update_media_item_callback($data) {
    $parameters     = $data->get_params();

    $site_id        = $parameters['siteID'];
    $attachment_id  = $parameters['id'];
    $alt_text       = $parameters['altText'];

    jam_cms_api_base_check($site_id, [$attachment_id]);

    $attachment_meta = array(
      'ID'		        => $attachment_id,
      'post_title'	  => '',
      'post_excerpt'	=> '',
      'post_content'	=> '',
    );

    update_post_meta( $attachment_id, '_wp_attachment_image_alt', $alt_text );

    wp_update_post( $attachment_meta );

    $media_item = jam_cms_get_media_item_by_id($site_id, $attachment_id);

    return $media_item;
}

?>