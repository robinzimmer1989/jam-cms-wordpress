<?php

// Add tiny image size for blur-up image
add_image_size( 'tiny', 25, 25, true);

// Enable featured image
add_theme_support( 'post-thumbnails' );

// Decapitate WordPress
add_action("template_redirect", function() {
  wp_redirect('/wp-admin');
});