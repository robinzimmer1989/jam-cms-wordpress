<?php

// https://wpengine.com/resources/enable-svg-wordpress/

add_filter( 'wp_check_filetype_and_ext', 'jam_cms_check_file_type' , 10, 4 );
function jam_cms_check_file_type($data, $file, $filename, $mimes) {

  global $wp_version;

  if ( $wp_version !== '4.7.1' ) {
     return $data;
  }

  $filetype = wp_check_filetype( $filename, $mimes );

  return [
      'ext'             => $filetype['ext'],
      'type'            => $filetype['type'],
      'proper_filename' => $data['proper_filename']
  ];
}

add_filter( 'upload_mimes', 'jam_cms_cc_mime_types' );
function jam_cms_cc_mime_types( $mimes ){
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}

add_action( 'admin_head', 'jam_cms_fix_svg' );
function jam_cms_fix_svg() {
  echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}