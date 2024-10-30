<?php

// ---------------------------------------------------------------------------
// Define the plugin options.
// ---------------------------------------------------------------------------
 
/**
 * The approach we are taking here is to have all of the options for the plugin
 * contained in one array. We'll store that array as a single WordPress
 * option. This simplifies a lot of things, and cuts down on the boilerplate.
 */
 
/**
 * We will need to define the default options array, since it won't be set the
 * first time it is needed.
 *
 * Here we'll add a couple of simple fields - an integer, and a list of strings
 * that will be stored as an indexed array.
 */
function minim_default_options() {
  return array(
    'minim_key' => '',
    'display_style' => []
  );
}

/**
 * Since we have a default options array, it is best to provide a helper
 * function to obtain the options for the plugin, in order to wrap the necessary
 * get_option() call.
 */
function minim_get_options() {
  // $options = get_option('minim_options', minim_default_options());
  // delete_option('minim_options');

  return get_option('minim_options', minim_default_options());
}

/**
 * All options set in the WordPress administrative interface must be on the
 * whitelist. So we'll register our compound options array as
 * 'minim_options'.
 *
 * The important thing here is to provide the sanitize_callback function, as
 * that function is where all of the error check and error message setting has
 * to happen.
 *
 * If you have unusual requirements, such as storing options in custom database
 * tables, then the sanitize_callback function is where that will have to happen
 * as well.
 */
function minim_admin_init() {
  register_setting(
    'minim_options_group',
    'minim_options',
    array(
      'type' => 'array',
      'default' => minim_default_options(),
      'sanitize_callback' => 'minim_sanitize_options'
    )
  );
}


/**
 * The function for sanitizing options entered by the user.
 *
 * This actually has to do more than just that. It also must set error messages
 * and juggle whether or not to reject and replace specific values.
 *
 * It isn't an ideal situation, as some approaches to form UI are not possible
 * to implement via this interface. Whatever is returned from this method will
 * be set as the options value regardless of everything else, so about the best
 * that can be done is to adjust values or reject all changes. You can't, for
 * example, choose not to save the results, provide errors, and still show the
 * user-entered invalid values in the form when the page reloads.
 *
 * In the example here, we take the approach of discarding all entered data if
 * any of it is bad, and issuing appropriate error messages. When the page
 * reloads, the user will see the prior values.
 */
function minim_sanitize_options($input) {
  $output = minim_get_options();
  $error = false;

  $keyInput = trim($input['minim_key']);
  $displayStyle = trim($input['display_style']);
  
  // Check that there's a value in the field
  if (!isset($keyInput) || empty($keyInput)) {
    add_settings_error(
      'minim_options',
      'minim_key',
      __('Please enter your Tourwriter API Key', 'minim-by-tourwriter')
    );
  }
 
  // Only update the existing data in the absence of errors.
  if (!count(get_settings_errors('minim_options'))) {
    $output['minim_key'] = $keyInput;
    $output['display_style'] = $displayStyle;
  }
  return $output;
}

/**
 * Add a link to the settings page into the settings submenu.
 */
function minim_options_page() {
  add_menu_page(
    __('Tourwriter Itinerary Settings', 'minim-by-tourwriter'), // Page Title
    'Tourwriter', // Menu Title 
    'manage_options',
    'minim-by-tourwriter', // Slug
    'minim_options_page_html', // Callable Function
    ''
  );
}
 
/**
 * Add a settings link to the plugin page
 */
function minim_plugin_action_links($links) {
  $links = array_merge( array(
		'<a href="' . esc_url( admin_url( '/admin.php?page='.'minim-by-tourwriter' ) ) . '">' . __( 'Settings', 'minim-by-tourwriter' ) . '</a>'
	), $links );
	return $links;
}