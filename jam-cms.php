<?php
/**
 * @link              http://example.com
 * @since             0.0.1
 * @package           jamCMS
 *
 * @wordpress-plugin
 * Plugin Name:       jamCMS
 * Plugin URI:        https://github.com/robinzimmer1989/jam-cms-wordpress
 * Description:       A CMS for the JAMStack world. Made for developers.
 * Version:           1.6.0
 * Author:            Robin Zimmer
 * Author URI:        https://github.com/robinzimmer1989
 * License:           
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       jam-cms
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit; 

if( ! class_exists('JamCMS') ) :

    class JamCMS {
        
        /** @var string The plugin version number. */
        var $version = '0.0.1';
        
        /**
         * __construct
         *
         * A dummy constructor to ensure JamCMS is only setup once.
         *
         * @date	20/11/20
         * @since	0.0.1
         *
         * @param	void
         * @return	void
         */	
        function __construct() {
            // Do nothing.
        }
        
        /**
         * initialize
         *
         * Sets up the Gatsby CMS plugin.
         *
         * @date	20/11/20
         * @since	0.0.1
         *
         * @param	void
         * @return	void
         */
        function initialize() {
            
            // Define constants.
            $this->define( 'JAM_CMS', true );
            $this->define( 'JAM_CMS_PATH', plugin_dir_path( __FILE__ ) );
            $this->define( 'JAM_CMS_BASENAME', plugin_basename( __FILE__ ) );
            $this->define( 'JAM_CMS_VERSION', $this->version );
            
            // Utilities
            include_once( JAM_CMS_PATH . '/includes/utils/error_log.php');
            include_once( JAM_CMS_PATH . '/includes/utils/format_url.php');
            include_once( JAM_CMS_PATH . '/includes/utils/format_post.php');
            include_once( JAM_CMS_PATH . '/includes/utils/format_post_type.php');
            include_once( JAM_CMS_PATH . '/includes/utils/format_taxonomy.php');
            include_once( JAM_CMS_PATH . '/includes/utils/format_term.php');
            include_once( JAM_CMS_PATH . '/includes/utils/format_media_item.php');
            include_once( JAM_CMS_PATH . '/includes/utils/format_menu_item.php');
            include_once( JAM_CMS_PATH . '/includes/utils/format_user.php');
            include_once( JAM_CMS_PATH . '/includes/utils/format_action_monitor.php');
            include_once( JAM_CMS_PATH . '/includes/utils/generate_id.php');
            include_once( JAM_CMS_PATH . '/includes/utils/array_flatten.php');
            include_once( JAM_CMS_PATH . '/includes/utils/build_menu_tree.php');
            include_once( JAM_CMS_PATH . '/includes/utils/api_base_check.php');
            include_once( JAM_CMS_PATH . '/includes/utils/check_for_missing_plugins.php');
            include_once( JAM_CMS_PATH . '/includes/utils/get_template_key.php');
            include_once( JAM_CMS_PATH . '/includes/utils/create_template.php');
            include_once( JAM_CMS_PATH . '/includes/utils/generate_slug_by_id.php');
            include_once( JAM_CMS_PATH . '/includes/utils/duplicate_post.php');
            include_once( JAM_CMS_PATH . '/includes/utils/create_revision.php');
            include_once( JAM_CMS_PATH . '/includes/utils/create_post_type.php');
            include_once( JAM_CMS_PATH . '/includes/utils/create_taxonomy.php');
            include_once( JAM_CMS_PATH . '/includes/utils/generate_preview_link.php');
            include_once( JAM_CMS_PATH . '/includes/utils/generate_preview_key.php');
            include_once( JAM_CMS_PATH . '/includes/utils/post_lock.php');

            // Admin
            include_once( JAM_CMS_PATH . '/includes/admin/fix_page_query.php');
            include_once( JAM_CMS_PATH . '/includes/admin/whitelist_apis.php');
            include_once( JAM_CMS_PATH . '/includes/admin/settings.php');
            include_once( JAM_CMS_PATH . '/includes/admin/emails.php');
            include_once( JAM_CMS_PATH . '/includes/admin/settings_page.php');
            include_once( JAM_CMS_PATH . '/includes/admin/page_templater.php');
            include_once( JAM_CMS_PATH . '/includes/admin/preview_button.php');
            include_once( JAM_CMS_PATH . '/includes/admin/add_svg_field_to_image_query.php');

            // ACF
            include_once( JAM_CMS_PATH . '/includes/acf/generate_acf_fields_recursively.php');
            include_once( JAM_CMS_PATH . '/includes/acf/update_acf_fields.php');
            include_once( JAM_CMS_PATH . '/includes/acf/update_acf_fields_options.php');
            include_once( JAM_CMS_PATH . '/includes/acf/upsert_acf_template.php');
            include_once( JAM_CMS_PATH . '/includes/acf/upsert_acf_template_options.php');
            include_once( JAM_CMS_PATH . '/includes/acf/format_fields.php');
            include_once( JAM_CMS_PATH . '/includes/acf/get_group_items_recursively.php');
            include_once( JAM_CMS_PATH . '/includes/acf/get_flexible_content_items_recursively.php');
            include_once( JAM_CMS_PATH . '/includes/acf/get_repeater_items_recursively.php');
            include_once( JAM_CMS_PATH . '/includes/acf/add_acf_options_page.php');
            include_once( JAM_CMS_PATH . '/includes/acf/get_acf_field_id.php');
            include_once( JAM_CMS_PATH . '/includes/acf/generate_sub_fields_recursively.php');
            include_once( JAM_CMS_PATH . '/includes/acf/format_acf_field_type_for_frontend.php');
            include_once( JAM_CMS_PATH . '/includes/acf/format_acf_field_type_for_db.php');
            include_once( JAM_CMS_PATH . '/includes/acf/format_acf_field_value_for_frontend.php');
            include_once( JAM_CMS_PATH . '/includes/acf/format_acf_field_value_for_db.php');
            include_once( JAM_CMS_PATH . '/includes/acf/get_option_group_fields.php');
            include_once( JAM_CMS_PATH . '/includes/acf/get_flexible_content_sub_blocks.php');
            include_once( JAM_CMS_PATH . '/includes/acf/add_menu_picker_field.php');
            include_once( JAM_CMS_PATH . '/includes/acf/add_google_maps_api_key.php');
            
            // Queries
            include_once( JAM_CMS_PATH . '/includes/queries/get_site_by_id.php');
            include_once( JAM_CMS_PATH . '/includes/queries/get_site_for_build_by_id.php');
            include_once( JAM_CMS_PATH . '/includes/queries/get_sites_by_user_id.php');
            include_once( JAM_CMS_PATH . '/includes/queries/get_post_by_id.php');
            include_once( JAM_CMS_PATH . '/includes/queries/get_media_items.php');
            include_once( JAM_CMS_PATH . '/includes/queries/get_media_item_by_id.php');
            include_once( JAM_CMS_PATH . '/includes/queries/get_menu_by_id.php');
            include_once( JAM_CMS_PATH . '/includes/queries/get_user_by_id.php');
            include_once( JAM_CMS_PATH . '/includes/queries/get_users.php');

            // Mutations
            include_once( JAM_CMS_PATH . '/includes/mutations/update_menu.php');

            // APIs
            include_once( JAM_CMS_PATH . '/includes/api/test.php');

            include_once( JAM_CMS_PATH . '/includes/api/site/create_site.php');
            include_once( JAM_CMS_PATH . '/includes/api/site/update_site.php');
            include_once( JAM_CMS_PATH . '/includes/api/site/get_site.php');
            include_once( JAM_CMS_PATH . '/includes/api/site/get_site_for_build.php');
            include_once( JAM_CMS_PATH . '/includes/api/site/delete_site.php');
            include_once( JAM_CMS_PATH . '/includes/api/site/get_sites.php');
            include_once( JAM_CMS_PATH . '/includes/api/site/deploy_site.php');

            include_once( JAM_CMS_PATH . '/includes/api/post/create_post.php');
            include_once( JAM_CMS_PATH . '/includes/api/post/update_post.php');
            include_once( JAM_CMS_PATH . '/includes/api/post/get_post.php');
            include_once( JAM_CMS_PATH . '/includes/api/post/delete_post.php');
            include_once( JAM_CMS_PATH . '/includes/api/post/duplicate_post.php');
            include_once( JAM_CMS_PATH . '/includes/api/post/reorder_posts.php');

            include_once( JAM_CMS_PATH . '/includes/api/term/create_term.php');
            include_once( JAM_CMS_PATH . '/includes/api/term/update_term.php');
            include_once( JAM_CMS_PATH . '/includes/api/term/delete_term.php');

            include_once( JAM_CMS_PATH . '/includes/api/media_item/create_media_item.php');
            include_once( JAM_CMS_PATH . '/includes/api/media_item/update_media_item.php');
            include_once( JAM_CMS_PATH . '/includes/api/media_item/delete_media_item.php');
            include_once( JAM_CMS_PATH . '/includes/api/media_item/get_media_items.php');

            include_once( JAM_CMS_PATH . '/includes/api/user/create_user.php');
            include_once( JAM_CMS_PATH . '/includes/api/user/update_user.php');
            include_once( JAM_CMS_PATH . '/includes/api/user/delete_user.php');
            include_once( JAM_CMS_PATH . '/includes/api/user/get_users.php');
            include_once( JAM_CMS_PATH . '/includes/api/user/get_user.php');
            include_once( JAM_CMS_PATH . '/includes/api/user/get_auth_user.php');

            include_once( JAM_CMS_PATH . '/includes/api/preview/get_site_preview.php');
            include_once( JAM_CMS_PATH . '/includes/api/preview/get_post_preview.php');
            include_once( JAM_CMS_PATH . '/includes/api/preview/get_preview_link.php');

            include_once( JAM_CMS_PATH . '/includes/api/post_lock/refresh_post_lock.php');
            include_once( JAM_CMS_PATH . '/includes/api/post_lock/remove_post_lock.php');

            // Misc APIs
            include_once( JAM_CMS_PATH . '/includes/api/sync/sync_fields.php');
            include_once( JAM_CMS_PATH . '/includes/api/get_unpublished_changes.php');
            
            // Add actions.
            add_action( 'init', array($this, 'init'), 100 );
        }
        
        /**
         * init
         *
         * Completes the setup process on "init" of earlier.
         *
         * @date	20/11/20
         * @since	0.0.1
         *
         * @param	void
         * @return	void
         */
        function init() {
            // Bail early if called directly from functions.php or plugin file.
            if( !did_action('plugins_loaded') ) {
                return;
            }
        }
        
        /**
         * define
         *
         * Defines a constant if doesnt already exist.
         *
         * @date	20/11/20
         * @since	0.0.1
         *
         * @param	string $name The constant name.
         * @param	mixed $value The constant value.
         * @return	void
         */
        function define( $name, $value = true ) {
            if( !defined($name) ) {
                define( $name, $value );
            }
        }
    }

    /*
    * jam_cms
    *
    * The main function responsible for returning the one true Jam CMS Instance to functions everywhere.
    *
    * @date	    20/11/20
    * @since	0.0.1
    *
    * @param	void
    * @return	JamCMS
    */
    function jam_cms_initialize_jam_cms() {
        global $jam_cms;
        
        // Instantiate only once.
        if( !isset($jam_cms) ) {
            $jam_cms = new JamCMS();
            $jam_cms->initialize();
        }
        return $jam_cms;
    }

    // Instantiate.
    add_action( 'plugins_loaded', 'jam_cms_initialize_jam_cms' );

     /*
    * jam_cms_activate
    *
    * Add basic ACF field setup and API key on plugin activation
    *
    * @date	    20/11/20
    * @since	0.0.1
    *
    * @param	void
    * @return	JamCMS
    */
    function jam_cms_activate() {

        // Initialize main plugin first so functions are available
        jam_cms_initialize_jam_cms();

        // Create deployment api key if doesn't exist yet
        if(!get_option('deployment_api_key')){        
            $api_key = wp_generate_uuid4();
            update_option('deployment_api_key', $api_key);
        }
    }
    
    register_activation_hook( __FILE__, 'jam_cms_activate' );

endif; // class_exists check