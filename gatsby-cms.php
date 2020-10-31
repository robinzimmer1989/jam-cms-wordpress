<?php 
/** 
 * Plugin Name: Gatsby CMS
 * @author Robin Zimmer
*/

add_action( 'plugins_loaded', 'load_gatsby_cms' );

function load_gatsby_cms() {
     // Utils
    require_once __DIR__ . '/utils/formatPost.php';
    require_once __DIR__ . '/utils/acfAddFieldGroup.php';
    require_once __DIR__ . '/utils/cors.php';

     // Actions
     require_once __DIR__ . '/actions/fixPageQuery.php';
     require_once __DIR__ . '/actions/onCreatePost.php';
     require_once __DIR__ . '/actions/flexibleContent.php';
    
    // Resolver
    require_once __DIR__ . '/resolvers/getSiteByID.php';
    require_once __DIR__ . '/resolvers/getSitesByUserID.php';
    require_once __DIR__ . '/resolvers/getPostByID.php';

    // APIs
    require_once __DIR__ . '/api/site/createSite.php';
    require_once __DIR__ . '/api/site/getSites.php';
    require_once __DIR__ . '/api/site/getSite.php';
    require_once __DIR__ . '/api/post/createPost.php';
    require_once __DIR__ . '/api/post/updatePost.php';
    require_once __DIR__ . '/api/post/getPost.php';

}

?>