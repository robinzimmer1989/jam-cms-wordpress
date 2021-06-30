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
    add_settings_section( 'api_settings', 'Settings', 'jam_cms_plugin_section_text', 'jam_cms_plugin' );
    add_settings_field( 'jam_cms_plugin_setting_api_key', 'API Key', 'jam_cms_plugin_setting_api_key', 'jam_cms_plugin', 'api_settings' );
    add_settings_field( 'jam_cms_plugin_setting_disable_syncing', 'Syncing', 'jam_cms_plugin_setting_disable_syncing', 'jam_cms_plugin', 'api_settings' );
}

function jam_cms_plugin_options_validate( $input ) {
    
    $newinput['api_key'] = trim( $input['api_key'] );

    if (!preg_match( '/^[a-z0-9]{32}$/i', $newinput['api_key'] ) ) {
        $newinput['api_key'] = '';
    }

    $options = get_option('jam_cms_settings');

    if(!$options){
        $options = [];
    }

    $new_settings = [
        'disable_syncing'   => array_key_exists('disable_syncing', $input) ? $input['disable_syncing'] : 0,
    ];

    $array = array_merge($options, $new_settings);

    update_option('jam_cms_settings', $array);

    return $newinput;
}

function jam_cms_plugin_section_text() {
    echo '<p>Add this API key as an option to the <code>gatsby-source-jam-cms</code> plugin in gatsby-config.js</p>';
}

function jam_cms_plugin_setting_api_key() {
    // TODO: Add 'Regenerate' button
    echo "<input id='jam_cms_plugin_setting_api_key' class='regular-text' name='jam_cms_plugin_options[api_key]' type='text' value='" . esc_attr( get_option('deployment_api_key') ) . "' />";
}

function jam_cms_plugin_setting_disable_syncing() {
    $settings = get_option("jam_cms_settings");
    $checked = is_array($settings) && array_key_exists("disable_syncing", $settings) && $settings['disable_syncing'] == 1 ? 'checked="checked"' : '';
    echo '<label><input ' . $checked . ' name="jam_cms_plugin_options[disable_syncing]" type="checkbox" value="1" /> Disable automatic syncing of post types, templates and ACF fields</label>';
}