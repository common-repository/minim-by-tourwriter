<?php
require_once plugin_dir_path(__FILE__) . './options.php';
/**
 * This function emits the HTML for the options page.
 */
function minim_options_page_html() {
  // Don't proceed if the user lacks permissions.
  if (!current_user_can('manage_options')) {
    return;
  }
  $options = minim_get_options();
  ?>
  <div class="wrap">
    <h1><?= esc_html(get_admin_page_title()); ?></h1>
    <form action="options.php" method="post">
      <?php settings_fields('minim_options_group'); ?>
      <h2> 
        <?php esc_html_e('Authentication', 'minim-by-tourwriter'); ?>
      </h2>
      <p>
        <?php esc_html_e('Enter your Tourwriter API Key in the box below then click the "Check API Key" button to connect your Tourwriter account.', 'minim-by-tourwriter'); ?>
      </p>
      <p>
        <?php esc_html_e('Once verified, click the "Save Settings" button and you can begin embedding itineraries into your posts and pages!', 'minim-by-tourwriter'); ?>
      </p>
      <table class="form-table">
        <tr>
          <th scope="row">
            <label for="minim_options[minim_key]">
              <?php esc_html_e('Tourwriter API Key', 'minim-by-tourwriter'); ?>:
            </label>
          </th>
          <td>
            <input
              class="regular-text"
              id="minim-key"
              name="minim_options[minim_key]"
              type="text"
              value="<?php echo esc_attr($options['minim_key']); ?>"
            />
             <p>
             <?php esc_html_e('Navigate to your Profile in Tourwriter to retrieve your unique API key.', 'minim-by-tourwriter'); ?>
            </p>
            <div id="minim-info" class="minim-info">
              <div class="minim-info-icon"></div>
              <div id="minim-success-message" class="minim-message minim-message--success">
              <?php esc_html_e('Success! API key verified.', 'minim-by-tourwriter'); ?>
              </div>
              <div id="minim-error-message" class="minim-message minim-message--error">
              <?php esc_html_e('Your Tourwriter API Key appears to be incorrect', 'minim-by-tourwriter'); ?>
              </div>
            </div>

            <button id="minim-validate-button" class="minim-validate-button">Check API Key</button>
          </td>
        </tr>
        <tr>
          <th>
            Display itinerary items as:
          </th>
          <td>
          <select name="minim_options[display_style]" id="display_style">
            <?php
              $col1 = '';
              $col2 = '';
              if (($options['display_style'] ?? '') == '1col') $col1 = 'selected';
              if (($options['display_style'] ?? '') == '2col') $col2 = 'selected';              
            ?>
            <option value="1col" <?=$col1; ?>>1 column with images above text<br>
            <option value="2col" <?=$col2; ?>>2 columns with images next to text
          </select>
          </td>
        </tr>
      </table>
      <?php submit_button(__('Save Settings', 'minim-by-tourwriter'));?>
 
    </form>
    <hr>
    <div>
      <h3>Shortcode</h3>
      <p>The shortcode makes it possible to display a Tourwriter itinerary on any of your website pages or posts.</p>
      <p>The shortcode accepts 1 parameter:</p>
      <p><strong>id</strong> : this is the ID of the itinerary which can be found in Tourwriter. Can't find your itinerary ID? Learn more <a href="https://itinerarybuilder.tourwriter.com/help-articles/integrations/wordpress-plugin/?ref=plugin" target="_blank">here</a>.</p>
      <p>Copy and paste this shortcode onto any post or page on your website - add your desired itinerary ID in between the inverted commas.</p>
      <p><strong>[tourwriter id="Itinerary ID goes here"]</strong></p>
    </div>

    <hr>
    <div>
      <h3>Publish latest itinerary edits</h3>
      <p>To ensure pages load quickly, your embedded Tourwriter itineraries are cached for 24 hours on your website when the itinerary page is first loaded.</p>
      <p>If you have updated an itinerary in Tourwriter and need to display the updated version immediately you can clear the cache by clicking the button below.</p>
	  <form action="" method="post">
      <input type="hidden" name="delete_cache" value="true">
		  <?php submit_button(__('Delete Cache', 'minim-by-tourwriter'));?>
	  </form>
    </div>
    <?php
      if ((isset($_POST['delete_cache'])) && ($_POST['delete_cache'] == "true")) {
        delete_minim_cache();
      }
    ?>
  </div>
  <?php
}