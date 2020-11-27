<?php

// Set pages to be publicly queryable. This way we can filter out 'real' post types later.

function jam_cms_fix_page_query() {
  if ( post_type_exists( 'page' ) ) {
      global $wp_post_types;
      $wp_post_types['page']->publicly_queryable = true;
  }
}
add_action( 'init', 'jam_cms_fix_page_query', 1 );

?>