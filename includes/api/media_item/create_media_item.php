<?php

add_action( 'rest_api_init', 'jam_cms_api_create_media_item' ); 
function jam_cms_api_create_media_item() {
    register_rest_route( 'jamcms/v1', '/createMediaItem', array(
        'methods' => 'POST',
        'callback' => 'jam_cms_api_create_media_item_callback',
        'permission_callback' => function () {
            return current_user_can( 'upload_files' );
        }
    ));
}

function jam_cms_api_create_media_item_callback($parameters) {
    $site_id    = $parameters->get_param('siteID');
    $files      = $parameters->get_file_params();
    $headers    = $parameters->get_headers();

    jam_cms_api_base_check($site_id);

    if ( empty( $files ) ) {
        return new WP_Error( 'rest_upload_no_data', __( 'No data supplied' ), array( 'status' => 400 ) );
    }

    // Verify hash, if given
    if ( ! empty( $headers['content_md5'] ) ) {
        $content_md5 = array_shift( $headers['content_md5'] );
        $expected    = trim( $content_md5 );
        $actual      = md5_file( $files['file']['tmp_name'] );
        if ( $expected !== $actual ) {
            return new WP_Error( 'rest_upload_hash_mismatch', __( 'Content hash did not match expected' ), array( 'status' => 412 ) );
        }
    }
    // Pass off to WP to handle the actual upload
    $overrides = array(
        'test_form'   => false,
    );
    // Bypasses is_uploaded_file() when running unit tests
    if ( defined( 'DIR_TESTDATA' ) && DIR_TESTDATA ) {
        $overrides['action'] = 'wp_handle_mock_upload';
    }
    /** Include admin functions to get access to wp_handle_upload() */
    require_once ABSPATH . 'wp-admin/includes/admin.php';
    $file = wp_handle_upload( $files['file'], $overrides );

    if ( isset( $file['error'] ) ) {
        return new WP_Error( 'rest_upload_unknown_error', $file['error'], array( 'status' => 500 ) );
    }

    $filename = $file['file'];

    $attachment_id = wp_insert_attachment(
        array(
            'guid' => $file['url'],
            'post_mime_type' => $file['type'],
            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
            'post_content'   => '',
            'post_status'    => 'inherit'
        ),
        $filename,
        0
    );

    $attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
    wp_update_attachment_metadata( $attachment_id, $attachment_data );

    if($attachment_id){
        $media_item = get_post($attachment_id);

        return jam_cms_format_media_item($site_id, $media_item);
    }

    return $attachment_id;
}

?>