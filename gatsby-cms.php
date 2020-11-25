<?php
/**
 * @link              http://example.com
 * @since             0.0.1
 * @package           Gatsby CMS
 *
 * @wordpress-plugin
 * Plugin Name:       Gatsby CMS
 * Plugin URI:        https://github.com/robinzimmer1989/gatsby-cms-wordpress
 * Description:       
 * Version:           0.0.1
 * Author:            Robin Zimmer
 * Author URI:        https://github.com/robinzimmer1989
 * License:           
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       gatsby-cms
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit; 

if( ! class_exists('GatsbyCMS') ) :

    class GatsbyCMS {
        
        /** @var string The plugin version number. */
        var $version = '0.0.1';
        
        /**
         * __construct
         *
         * A dummy constructor to ensure Gatsby CMS is only setup once.
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
            $this->define( 'GATSBY_CMS', true );
            $this->define( 'GATSBY_CMS_PATH', plugin_dir_path( __FILE__ ) );
            $this->define( 'GATSBY_CMS_BASENAME', plugin_basename( __FILE__ ) );
            $this->define( 'GATSBY_CMS_VERSION', $this->version );
            
             // Utilities
            include_once( GATSBY_CMS_PATH . '/includes/utils/format_post.php');
            include_once( GATSBY_CMS_PATH . '/includes/utils/format_post_type.php');
            include_once( GATSBY_CMS_PATH . '/includes/utils/format_media_item.php');
            include_once( GATSBY_CMS_PATH . '/includes/utils/format_menu_item.php');
            include_once( GATSBY_CMS_PATH . '/includes/utils/format_user.php');
            include_once( GATSBY_CMS_PATH . '/includes/utils/format_post_content_for_build.php');
            include_once( GATSBY_CMS_PATH . '/includes/utils/generate_id.php');
            include_once( GATSBY_CMS_PATH . '/includes/utils/array_flatten.php');
            include_once( GATSBY_CMS_PATH . '/includes/utils/build_menu_tree.php');
            include_once( GATSBY_CMS_PATH . '/includes/utils/generate_slug.php');
            include_once( GATSBY_CMS_PATH . '/includes/utils/api_base_check.php');
            include_once( GATSBY_CMS_PATH . '/includes/utils/check_for_missing_plugins.php');

            // Admin
            include_once( GATSBY_CMS_PATH . '/includes/admin/fix_page_query.php');
            include_once( GATSBY_CMS_PATH . '/includes/admin/whitelist_apis.php');
            include_once( GATSBY_CMS_PATH . '/includes/admin/remove_default_post_type.php');
            include_once( GATSBY_CMS_PATH . '/includes/admin/add_image_size.php');

            // ACF
            include_once( GATSBY_CMS_PATH . '/includes/acf/get_acf_field_id.php');
            include_once( GATSBY_CMS_PATH . '/includes/acf/add_acf_field.php');
            include_once( GATSBY_CMS_PATH . '/includes/acf/add_acf_field_group.php');
            include_once( GATSBY_CMS_PATH . '/includes/acf/add_acf_options_page.php');
            include_once( GATSBY_CMS_PATH . '/includes/acf/add_acf_template.php');
            include_once( GATSBY_CMS_PATH . '/includes/acf/add_acf_flexible_content.php');
            include_once( GATSBY_CMS_PATH . '/includes/acf/add_acf_field_group_to_template.php');
            include_once( GATSBY_CMS_PATH . '/includes/acf/generate_sub_fields_recursively.php');
            include_once( GATSBY_CMS_PATH . '/includes/acf/format_acf_field_type_for_frontend.php');
            include_once( GATSBY_CMS_PATH . '/includes/acf/format_acf_field_type_for_db.php');
            include_once( GATSBY_CMS_PATH . '/includes/acf/format_acf_field_value_for_frontend.php');
            include_once( GATSBY_CMS_PATH . '/includes/acf/format_acf_field_value_for_db.php');
            include_once( GATSBY_CMS_PATH . '/includes/acf/get_repeater_items_recursively.php');
            include_once( GATSBY_CMS_PATH . '/includes/acf/get_option_group_fields.php');
            include_once( GATSBY_CMS_PATH . '/includes/acf/get_flexible_content_blocks.php');
            include_once( GATSBY_CMS_PATH . '/includes/acf/update_sub_fields_recursively.php');
            include_once( GATSBY_CMS_PATH . '/includes/acf/add_acf_field_group_to_flexible_content.php');
            include_once( GATSBY_CMS_PATH . '/includes/acf/get_template_by_post_id.php');
            include_once( GATSBY_CMS_PATH . '/includes/acf/get_template_by_post_type_name.php');
            include_once( GATSBY_CMS_PATH . '/includes/acf/get_template_field_groups_by_post_type_name.php');
            include_once( GATSBY_CMS_PATH . '/includes/acf/update_flexible_content_field_values.php');
            include_once( GATSBY_CMS_PATH . '/includes/acf/update_template_field_values.php');
            include_once( GATSBY_CMS_PATH . '/includes/acf/delete_acf_fields_by_parent_id.php');
            include_once( GATSBY_CMS_PATH . '/includes/acf/add_menu_picker_field.php');
            include_once( GATSBY_CMS_PATH . '/includes/acf/add_post_type_picker_field.php');
            
            // Queries
            include_once( GATSBY_CMS_PATH . '/includes/queries/get_site_by_id.php');
            include_once( GATSBY_CMS_PATH . '/includes/queries/get_site_for_build_by_id.php');
            include_once( GATSBY_CMS_PATH . '/includes/queries/get_sites_by_user_id.php');
            include_once( GATSBY_CMS_PATH . '/includes/queries/get_post_by_id.php');
            include_once( GATSBY_CMS_PATH . '/includes/queries/get_media_items.php');
            include_once( GATSBY_CMS_PATH . '/includes/queries/get_post_type_by_name.php');
            include_once( GATSBY_CMS_PATH . '/includes/queries/get_menu_by_id.php');
            include_once( GATSBY_CMS_PATH . '/includes/queries/get_user_by_id.php');
            include_once( GATSBY_CMS_PATH . '/includes/queries/get_users.php');

            // Mutations
            include_once( GATSBY_CMS_PATH . '/includes/mutations/update_menu.php');

            // APIs
            include_once( GATSBY_CMS_PATH . '/includes/api/test.php');

            include_once( GATSBY_CMS_PATH . '/includes/api/site/create_site.php');
            include_once( GATSBY_CMS_PATH . '/includes/api/site/update_site.php');
            include_once( GATSBY_CMS_PATH . '/includes/api/site/get_site.php');
            include_once( GATSBY_CMS_PATH . '/includes/api/site/get_site_for_build.php');
            include_once( GATSBY_CMS_PATH . '/includes/api/site/delete_site.php');
            include_once( GATSBY_CMS_PATH . '/includes/api/site/get_sites.php');

            include_once( GATSBY_CMS_PATH . '/includes/api/post/create_post.php');
            include_once( GATSBY_CMS_PATH . '/includes/api/post/update_post.php');
            include_once( GATSBY_CMS_PATH . '/includes/api/post/get_post.php');
            include_once( GATSBY_CMS_PATH . '/includes/api/post/delete_post.php');

            include_once( GATSBY_CMS_PATH . '/includes/api/post_type/create_post_type.php');
            include_once( GATSBY_CMS_PATH . '/includes/api/post_type/update_post_type.php');
            include_once( GATSBY_CMS_PATH . '/includes/api/post_type/delete_post_type.php');

            include_once( GATSBY_CMS_PATH . '/includes/api/media_item/create_media_item.php');
            include_once( GATSBY_CMS_PATH . '/includes/api/media_item/update_media_item.php');
            include_once( GATSBY_CMS_PATH . '/includes/api/media_item/delete_media_item.php');
            include_once( GATSBY_CMS_PATH . '/includes/api/media_item/get_media_items.php');

            include_once( GATSBY_CMS_PATH . '/includes/api/user/create_user.php');
            include_once( GATSBY_CMS_PATH . '/includes/api/user/update_user.php');
            include_once( GATSBY_CMS_PATH . '/includes/api/user/delete_user.php');
            include_once( GATSBY_CMS_PATH . '/includes/api/user/get_users.php');
            include_once( GATSBY_CMS_PATH . '/includes/api/user/get_user.php');
            include_once( GATSBY_CMS_PATH . '/includes/api/user/get_auth_user.php');
            
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
    * gatsby_cms
    *
    * The main function responsible for returning the one true Gatsby CMS Instance to functions everywhere.
    *
    * @date	    20/11/20
    * @since	0.0.1
    *
    * @param	void
    * @return	GatsbyCMS
    */
    function gcms_initialize_gatsby_cms() {
        global $gatsby_cms;
        
        // Instantiate only once.
        if( !isset($gatsby_cms) ) {
            $gatsby_cms = new GatsbyCMS();
            $gatsby_cms->initialize();
        }
        return $gatsby_cms;
    }

    // Instantiate.
    add_action( 'plugins_loaded', 'gcms_initialize_gatsby_cms' );

     /*
    * gatsby_cms_activate
    *
    * Add basic ACF field setup and API key on plugin activation
    *
    * @date	    20/11/20
    * @since	0.0.1
    *
    * @param	void
    * @return	GatsbyCMS
    */
    function gatsby_cms_activate() {

        // Initialize main plugin first so functions are available
        gcms_initialize_gatsby_cms();

       // Create flexible content element
        gcms_add_acf_flexible_content();

        // Create template and assign flexible content as default
        gcms_add_acf_template('Page', 'page');

        // Create deployment api key if doesn't exist yet
        if(!get_option('deployment_api_key')){        
            $api_key = wp_generate_uuid4();
            update_option('deployment_api_key', $api_key);
        }
    }
    
    register_activation_hook( __FILE__, 'gatsby_cms_activate' );

endif; // class_exists check


?>