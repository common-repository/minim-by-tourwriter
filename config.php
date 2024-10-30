<?php
// Define all global parameters here
// Default enpoint is provided in case there is no setting in .env
define('MINIM_DEFAULT_ENDPOINT', 'https://online-api.tourwriter.com/');

// The if statements are here incase we decide to change global variables in the .env
if(!defined('MINIM_ENDPOINT')) {
  define('MINIM_ENDPOINT', MINIM_DEFAULT_ENDPOINT);
}

// Define parameters for Minim API
if(!defined('MINIM_REQUEST_PARAMS')) {
  define('MINIM_REQUEST_PARAMS', '?$eager=[heros,items.[bookable_items.[assets,addons.[option.[product.[supplier]]]],non_bookable_items.[assets]]]');
}

// Define item image sizes
if (!defined('MINIM_IMAGE_LARGE')) define('MINIM_IMAGE_LARGE', '1170x658');

// Define how different item types should show
if (!defined('MINIM_ITEM_TYPES_TO_HIDE')) define('MINIM_ITEM_TYPES_TO_HIDE', ['direction']);

// Register admin & wp styles and scripts
add_action('admin_enqueue_scripts', 'minim_enqueue_admin_scripts');
add_action('wp_enqueue_scripts', 'minim_enqueue_wp_scripts');

// Register plugin options
add_action('admin_init', 'minim_admin_init');

// Add a link to the settings page into the settings submenu.
add_action('admin_menu', 'minim_options_page'); 

// Add settings link to Plugins page
add_filter('plugin_action_links_' . MINIM_PLUGIN_FILE, 'minim_plugin_action_links');

// Append or prepend itinerary to content of post or page
add_filter('the_content', 'minim_append_itinerary');

// Shortcode
add_shortcode( 'minim_itinerary', 'show_minim_itinerary' );
add_shortcode( 'minim', 'show_minim_itinerary' );
add_shortcode( 'tourwriter', 'show_minim_itinerary' );

function minim_enqueue_admin_scripts() {
  wp_enqueue_script( 'minim-admin-scripts', plugin_dir_url(__FILE__) . 'dist/js/admin.js');
  wp_enqueue_style( 'minim-admin-styles', plugin_dir_url(__FILE__) . 'dist/css/minim-by-tourwriter-admin.css');
}

function minim_enqueue_wp_scripts() {
	$rand = rand( 1, 99999999999 );
  wp_enqueue_style( 'minim-styles', plugin_dir_url(__FILE__) . 'dist/css/minim-by-tourwriter.css', '', $rand );
}
