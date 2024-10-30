<?php
/**
 * Register meta boxes.
 */
function minim_get_itinerary_fields() {
  return [
    'itinerary_id',
    'itinerary_display',
    'itinerary_key'
  ];
}
/**
 * Register meta boxes.
 */
function minim_register_meta_boxes() {
  add_meta_box(
    'minim_options', // ID
    __( 'Minim by Tourwriter', 'minim-by-tourwriter' ), // Title 
    'minim_render_metabox', // Callback
    ['post', 'page', 'itinerary'], // Screen - where itinerary will be available
    'side' // Context - where on the CMS page to display the metabox
  );
}

/**
* Meta box display callback.
*
* @param WP_Post $post Current post object.
*/
function minim_render_metabox( $post ) {
  $post_id = get_the_ID();
  $itinerary_ID = get_post_meta( $post_id , 'itinerary_id', true );
  $itinerary_display = get_post_meta( $post_id , 'itinerary_display', true );
  $itinerary_key = get_post_meta( $post_id , 'itinerary_key', true );

  // Ensure default display option is selected if no value is present
  if( !$itinerary_display || $itinerary_display === '' ) {
    $itinerary_display = MINIM_ITINERARY_DEFAULT_DISPLAY_OPTION;
  }

  wp_nonce_field( 'minim_meta_box', 'minim_meta_box_nonce' ) ?>

  <style>
    .minim-title { display: block; margin: 12px 0 6px }
    .minim-id { margin: 12px 0 6px }
    .minim-radio { display: block; margin: 3px 0 }
    .minim-radio label { align-items: center; display: flex }
    .minim-radio input[type=radio] { margin: 1px 6px 0 0 }
    .minim-radio span { position: relative; top: 1px }

    @media (min-width: 600px) {
      .minim-id {
        /* Ensure whole ID string can be seen in the input field */
        font-size: .8em !important
      }
    }

    @media screen and (max-width: 782px) {
      .minim-radio span { line-height: 2; top: 3px }
    }
  </style>
  <label for="itinerary_id">
    <strong class="minim-title"><?php _e('Itinerary ID', 'minim-by-tourwriter') ?></strong>
    <?php _e('The ID of the itinerary you would like to show. This is a random string of letters and numbers around 30 characters long and can be found in the URL (address bar) of your itinerary in Minim.', 'minim-by-tourwriter') ?>
    <div>
      <input
        class="minim-id widefat"
        id="itinerary_id"
        name="itinerary_id"
        type="text"
        value="<?php echo esc_attr( $itinerary_ID ); ?>">
    </div>
  </label>
  <strong class="minim-title"><?php _e('Itinerary placement', 'minim-by-tourwriter') ?></strong>
  <label class="minim-radio">
    <input type="radio" name="itinerary_display" value="above" <?php checked( $itinerary_display, 'above' ) ?>>
    <span>Above the content</span>
  </label>
  <label class="minim-radio">
    <input type="radio" name="itinerary_display" value="below" <?php checked( $itinerary_display, 'below' ) ?>>
    <span>Below the content</span>
  </label>
  <label class="minim-radio">
    <input type="radio" name="itinerary_display" value="hide" <?php checked( $itinerary_display, 'hide' ) ?>>
    <span>Hide itinerary</span>
  </label>
  <label for="itinerary_key">
    <strong class="minim-title"><?php _e('Minim Classic Key', 'minim-by-tourwriter') ?></strong>
    <?php _e('If you want to use a different API key to the one specified in the settings you can add that here.') ?>
    <div>
      <input
        class="minim-id widefat"
        id="itinerary_key"
        name="itinerary_key"
        type="text"
        value="<?php echo esc_attr( $itinerary_key ); ?>">
    </div>
  </label>
  <?php
}

/**
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function minim_save_meta_box( $post_id ) {
  // Check if our nonce is set.
  if ( !isset( $_POST['minim_meta_box_nonce'] ) ) {
    return;
  }

  // Verify that the nonce is valid.
  if ( !wp_verify_nonce( $_POST['minim_meta_box_nonce'], 'minim_meta_box' ) ) {
    return;
  }

  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
  }

  // Check the user's permissions.
  if ( !current_user_can( 'edit_post', $post_id ) ) {
    return;
  }

  $fields = minim_get_itinerary_fields();

  // Loop through each field updating post meta
  foreach ( $fields as $field ) {
    if ( array_key_exists( $field, $_POST ) ) {

      // Sanitize input fields
      switch( $field ) {
        case 'itinerary_id':
          $value = sanitize_text_field( $_POST[$field] );
        break;
        case 'itinerary_display':
          $value = sanitize_option( $field, $_POST[$field] );

          // Set to default value if not set to one of the allowed options
          if( !in_array( $value, MINIM_ITINERARY_DISPLAY_OPTIONS ) ) {
            $value = MINIM_ITINERARY_DEFAULT_DISPLAY_OPTION;
          }
        break;
        case 'itinerary_key':
          $value = sanitize_text_field( $_POST[$field] );
        break;
        default:
          $value = '';
      }

      update_post_meta( $post_id, $field, $value );
    }
  }
}

add_action( 'add_meta_boxes', 'minim_register_meta_boxes' );
add_action( 'save_post', 'minim_save_meta_box' );