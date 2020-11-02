<?php 
/** 
 * Plugin Name: Gatsby CMS
 * @author Robin Zimmer
*/

add_action( 'plugins_loaded', 'load_gatsby_cms' );

function load_gatsby_cms() {
     // Utils
    require_once __DIR__ . '/utils/format_post.php';
    require_once __DIR__ . '/utils/format_media_item.php';
    require_once __DIR__ . '/utils/add_acf_field_group.php';
    require_once __DIR__ . '/utils/cors.php';
    require_once __DIR__ . '/utils/fix_page_query.php';
    require_once __DIR__ . '/utils/create_post_action.php';
    require_once __DIR__ . '/utils/flexible_content.php';
    require_once __DIR__ . '/utils/whitelist_apis.php';
    
    // Resolver
    require_once __DIR__ . '/resolvers/get_site_by_id.php';
    require_once __DIR__ . '/resolvers/get_sites_by_user_id.php';
    require_once __DIR__ . '/resolvers/get_post_by_id.php';
    require_once __DIR__ . '/resolvers/get_media_items.php';

    // APIs
    require_once __DIR__ . '/api/site/create_site.php';
    require_once __DIR__ . '/api/site/update_site.php';
    require_once __DIR__ . '/api/site/get_site.php';
    require_once __DIR__ . '/api/site/delete_site.php'; // multisite only
    require_once __DIR__ . '/api/site/get_sites.php';   // multisite only

    require_once __DIR__ . '/api/post/create_post.php';
    require_once __DIR__ . '/api/post/update_post.php';
    require_once __DIR__ . '/api/post/get_post.php';
    require_once __DIR__ . '/api/post/delete_post.php';

    require_once __DIR__ . '/api/post_type/create_post_type.php';
    require_once __DIR__ . '/api/post_type/update_post_type.php';
    require_once __DIR__ . '/api/post_type/delete_post_type.php';

    require_once __DIR__ . '/api/media_item/create_media_item.php';
    require_once __DIR__ . '/api/media_item/update_media_item.php';
    require_once __DIR__ . '/api/media_item/delete_media_item.php';
    require_once __DIR__ . '/api/media_item/get_media_items.php';

}

?>