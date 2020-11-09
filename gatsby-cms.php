<?php 
/** 
 * Plugin Name: Gatsby CMS
 * @author Robin Zimmer
*/

add_action( 'plugins_loaded', 'load_gatsby_cms' );

function load_gatsby_cms() {
     // Utils
    require_once __DIR__ . '/utils/format_post.php';
    require_once __DIR__ . '/utils/format_post_type.php';
    require_once __DIR__ . '/utils/format_media_item.php';
    require_once __DIR__ . '/utils/format_menu_item.php';
    require_once __DIR__ . '/utils/cors.php';
    require_once __DIR__ . '/utils/fix_page_query.php';
    require_once __DIR__ . '/utils/create_post_action.php';
    require_once __DIR__ . '/utils/whitelist_apis.php';
    require_once __DIR__ . '/utils/generate_id.php';
    require_once __DIR__ . '/utils/remove_default_post_type.php';
    require_once __DIR__ . '/utils/array_flatten.php';
    require_once __DIR__ . '/utils/build_menu_tree.php';

    // ACF
    require_once __DIR__ . '/acf/get_acf_field_id.php';
    require_once __DIR__ . '/acf/add_acf_field_group.php';
    require_once __DIR__ . '/acf/add_acf_options_page.php';
    require_once __DIR__ . '/acf/format_acf_field.php'; 
    require_once __DIR__ . '/acf/create_default_acf_fields.php';
    require_once __DIR__ . '/acf/get_option_group_fields.php';
    require_once __DIR__ . '/acf/add_menu_picker_field.php';
    
    // Queries
    require_once __DIR__ . '/queries/get_site_by_id.php';
    require_once __DIR__ . '/queries/get_site_for_build_by_id.php';
    require_once __DIR__ . '/queries/get_sites_by_user_id.php';
    require_once __DIR__ . '/queries/get_post_by_id.php';
    require_once __DIR__ . '/queries/get_media_items.php';
    require_once __DIR__ . '/queries/get_post_type_by_name.php';
    require_once __DIR__ . '/queries/get_menu_by_id.php';

    // Mutations
    require_once __DIR__ . '/mutations/update_menu.php';

    // APIs
    require_once __DIR__ . '/api/test.php';

    require_once __DIR__ . '/api/site/create_site.php';
    require_once __DIR__ . '/api/site/update_site.php';
    require_once __DIR__ . '/api/site/get_site.php';
    require_once __DIR__ . '/api/site/get_site_for_build.php';
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