<?php
/*
Plugin Name: Giphy Pop
Plugin URI: https://git.doit.wisc.edu/
Description: A WordPress plugin for adding gifs from Giphy into posts and pages.
Author: UW-Madison
Author URI: https://git.doit.wisc.edu/
Version: 1.0.0
License:
License URI: 
Text Domain: wp-giphy-api
Tags: uwmadison
*/


// Plugin Path
define('UW_GIPHY_API_PATH', plugin_dir_path(__FILE__));
// Plugin URI
define('UW_GIPHY_API_URL', plugin_dir_url(__FILE__));
 // Giphy Pop API Plugin Version
 define('GIPHY_API_PLUGIN_VERSION', '1.0.0');
 // Giphy API Key (Customizable)
 define('GIPHY_API_KEY', '');

// Enqueue Scripts
require_once 'frontend/enqueue-scripts.php';

// Plugin Admin
require_once 'admin/admin-settings.php';

// Giphy Seach Metabox
require_once 'admin/metabox.php';