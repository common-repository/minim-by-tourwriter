<?php
/*
Plugin Name: Tourwriter Itineraries
Plugin URI: https://itinerarybuilder.tourwriter.com/help-articles/integrations/wordpress-plugin
Description: Integrate Tourwriter's software with your Wordpress website to display beautiful itineraries
Author: Tourwriter
Version: 2.2.4
Author URI: https://www.tourwriter.com
Text Domain: minim-by-tourwriter
Domain Path: /languages/
*/

 // Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

// Define constants for plugin directory
if (!defined('MINIM_PLUGIN_DIR')) define('MINIM_PLUGIN_DIR', plugin_dir_path( __FILE__ ));

// Define constants for main plugin file
if (!defined('MINIM_PLUGIN_FILE')) define('MINIM_PLUGIN_FILE', plugin_basename( __FILE__ ));

// Plugin settings page
require_once MINIM_PLUGIN_DIR . 'config.php';

// Admin Panel folders
require_once MINIM_PLUGIN_DIR . 'includes/admin/options-output.php'; // Add fields to settings page
require_once MINIM_PLUGIN_DIR . 'includes/admin/options.php'; // this loads inside the output.

// General functions
require_once MINIM_PLUGIN_DIR . 'includes/minim/functions.php';

// Minim integration - minim folder
require_once MINIM_PLUGIN_DIR . 'includes/minim/requests.php'; // Anything related to using the Minim API
require_once MINIM_PLUGIN_DIR . 'includes/minim/credentials.php'; // defining minim password and email

function minim_load_translation_file() {
  load_plugin_textdomain( 'minim-by-tourwriter', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'minim_load_translation_file' );
