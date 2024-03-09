<?php

/**
 * Load admin scripts
 * WP docs: https://developer.wordpress.org/reference/functions/wp_enqueue_script/
 *
 * @return void
 */
function giphy_api_enqueue_admin_scripts() {
    $screen = get_current_screen();

    wp_register_script('uw-giphy-admin-js', plugin_dir_url(__FILE__) . 'dist/scripts/main.js', ['jquery'], GIPHY_API_PLUGIN_VERSION, true);
    wp_enqueue_script('uw-giphy-admin-js');

    wp_register_style('uw-giphy-admin-style', plugin_dir_url(__FILE__) . 'dist/styles/styles.css', array(), GIPHY_API_PLUGIN_VERSION, 'all');
    wp_enqueue_style('uw-giphy-admin-style');

    if ($screen->id === 'post' || $screen->id === 'page') {
        wp_register_script('uw-giphy-search-js', plugin_dir_url(__FILE__) . 'dist/scripts/giphy.js', ['jquery'], GIPHY_API_PLUGIN_VERSION, true);
        wp_enqueue_script('uw-giphy-search-js');

        wp_register_style('uw-giphy-results-style', plugin_dir_url(__FILE__) . 'dist/styles/giphy-results.css', array(), time(), 'all');
        wp_enqueue_style('uw-giphy-results-style');
    }

}

add_action('admin_enqueue_scripts', 'giphy_api_enqueue_admin_scripts');