<?php

add_action( 'admin_menu', 'jam_cms_add_settings_page' );
function jam_cms_add_settings_page() {
    add_options_page( 'jamCMS', 'jamCMS', 'manage_options', 'jam-cms', 'jam_cms_render_plugin_settings_page' );
}

function jam_cms_render_plugin_settings_page() {
    ?>
    <h1>jamCMS</h1>
    <hr />
    <form action="options.php" method="post">
        <?php 
            settings_fields( 'jam_cms_plugin_options' );
            do_settings_sections( 'jam_cms_plugin' );
        ?>

        <input type="submit" class="button-primary" value="<?php _e('Update') ?>" />
    </form>
    <?php
}

add_action( 'admin_init', 'jam_cms_register_settings' );
function jam_cms_register_settings() {
    register_setting( 'jam_cms_plugin_options', 'jam_cms_plugin_options', 'jam_cms_plugin_options_validate' );
    
    add_settings_section( 'settings', 'Settings', 'jam_cms_plugin_section_text', 'jam_cms_plugin' );
    add_settings_field( 'jam_cms_plugin_setting_frontend_url', 'Frontend URL', 'jam_cms_plugin_setting_frontend_url', 'jam_cms_plugin', 'settings' );
    add_settings_field( 'jam_cms_plugin_setting_google_maps_api_key', 'Google Maps', 'jam_cms_plugin_setting_google_maps_api_key', 'jam_cms_plugin', 'settings' );
    
    add_settings_section( 'syncing', 'Syncing', 'jam_cms_plugin_section_text', 'jam_cms_plugin' );
    add_settings_field( 'jam_cms_plugin_setting_api_key', 'API Key', 'jam_cms_plugin_setting_api_key', 'jam_cms_plugin', 'syncing' );
    add_settings_field( 'jam_cms_plugin_setting_disable_syncing', 'Syncing', 'jam_cms_plugin_setting_disable_syncing', 'jam_cms_plugin', 'syncing' );
}

function jam_cms_plugin_options_validate( $input ) {
    
    $settings = get_option('jam_cms_settings');

    $new_settings = [
        'disable_syncing'       => array_key_exists('disable_syncing', $input) ? $input['disable_syncing'] : 0,
        'frontend_url'          => array_key_exists('frontend_url', $input) ? $input['frontend_url'] : '',
        'google_maps_api_key'   => array_key_exists('google_maps_api_key', $input) ? $input['google_maps_api_key'] : ''
    ];

    $array = array_merge($settings, $new_settings);

    update_option('jam_cms_settings', $array);

    return $newinput;
}

function jam_cms_plugin_section_text() {
    
}

add_action( 'wp_ajax_nopriv_get_data', '' );
add_action( 'wp_ajax_regenerate_api_key', 'jam_cms_regenerate_api_key' );

function jam_cms_regenerate_api_key() {
    if(current_user_can('manage_options')){

        $settings = get_option('jam_cms_settings');

        $api_key = wp_generate_uuid4();

        $settings['admin_api_key'] = $api_key;

        update_option('jam_cms_settings', $settings);

        wp_send_json_success($api_key);
    }

    wp_send_json_error();
}

function jam_cms_plugin_setting_api_key() {

    $settings = get_option("jam_cms_settings");
    $admin_api_key = is_array($settings) && array_key_exists("admin_api_key", $settings) ? $settings['admin_api_key'] : '';

    echo '<input id="jam_cms_api_key" class="regular-text" name="jam_cms_plugin_options[api_key]" type="text" value="' . $admin_api_key . '" disabled="disabled" />';
    echo '<input id="jam_cms_regenerate_api_key" type="button" class="button-secondary" value="Regenerate" />';
    echo '<p class="description">Add this API key as an option to the <code>gatsby-source-jam-cms</code> plugin in gatsby-config.js</p>';
    echo '<script type="text/javascript">
        jQuery("#jam_cms_regenerate_api_key").on("click", function(){
            wp.ajax.post( "regenerate_api_key", {} )
            .done(function(response) {
                if(response){
                    jQuery("#jam_cms_api_key").val(response);
                }else{
                    alert("Oops, something went wrong.");
                }
            });
        });
    </script>';
}

function jam_cms_plugin_setting_disable_syncing() {
    $settings = get_option("jam_cms_settings");
    $checked = is_array($settings) && array_key_exists("disable_syncing", $settings) && $settings['disable_syncing'] == 1 ? 'checked="checked"' : '';
    echo '<label><input ' . $checked . ' name="jam_cms_plugin_options[disable_syncing]" type="checkbox" value="1" /> Disable automatic syncing of post types, templates and ACF fields</label>';
}

function jam_cms_plugin_setting_frontend_url() {
    $settings = get_option("jam_cms_settings");
    $frontend_url = is_array($settings) && array_key_exists("frontend_url", $settings) ? $settings['frontend_url'] : '';
    echo "<input id='jam_cms_plugin_setting_frontend_url' class='regular-text' name='jam_cms_plugin_options[frontend_url]' type='text' value='" . $frontend_url . "' />";
}

function jam_cms_plugin_setting_google_maps_api_key() {
    $settings = get_option("jam_cms_settings");
    $google_maps_api_key = is_array($settings) && array_key_exists("google_maps_api_key", $settings) ? $settings['google_maps_api_key'] : '';
    echo "<input id='jam_cms_plugin_setting_google_maps_api_key' class='regular-text' name='jam_cms_plugin_options[google_maps_api_key]' type='text' value='" . $google_maps_api_key . "' />";
    echo '<p class="description">Add the Google Maps API key here. This is only necessary if you use the Google Maps field.</p>';
}