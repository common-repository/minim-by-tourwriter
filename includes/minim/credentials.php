<?php 
// Get Minim credentials from plugin settings page
$minimOptions = minim_get_options();

if(is_array($minimOptions) && !empty($minimOptions)) {
  $minimKey = $minimOptions['minim_key'];
} else {
  $minimKey = '';
}

// Set Minim credentials if they haven't already been defined
if (!defined('MINIM_API_KEY')) {
  define('MINIM_API_KEY', $minimKey);
}