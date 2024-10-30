<?php
function minim_compare_itinerary_start_date( $a, $b ) {
  $date = 'start_date';
  $time = 'start_time';

  // Decide order of items that are in the same day
  if ( $a[$date] == $b[$date] ) {

    // Sort by order field if it is set
    if( $a['order'] || $b['order'] ) {
      
      return ( $a['order'] < $b['order'] ) ? -1 : 1;
    }

    // Sort by start time if start date is the same
    if ( $a[$time] == $b[$time] ) {
      return 0;

    } else {
      return ( $a[$time] < $b[$time] ) ? -1 : 1;
    }
  }

  return ( $a['start_date'] < $b['start_date'] ) ? -1 : 1;
}

// Each itinerary contains assets, bookable_items and non_bookable_items
function minim_get_itinerary_items( $itinerary ) {
  // Abort if there are no items in the itinerary
  if(!isset($itinerary['items'])) {
    return;
  }
  
  $items = [];

  // Build array of bookable and non-bookable items
  foreach( $itinerary['items'] as $i ) {
    $b_items = $i['bookable_items'];
    $nb_items = $i['non_bookable_items'];

    // Check for multiple non bookable items
    foreach($nb_items as $nb) {
    // Add non_bookable items
      if( is_array( $nb ) && !empty( $nb ) ) {
        $item = $nb;

        // Change 'date' to 'start_date' to match bookable items data structure
        if( !array_key_exists( 'start_date', $item ) && isset( $item['date'] ) ) {
          $item['start_date'] = $item['date'];
          unset( $item['date'] );
          array_push( $items, $item );
        }
      }
    }
    // Multiple bookable items per supplier may exist
    foreach($b_items as $b) {
      // Add bookable items
      if( is_array( $b ) && !empty( $b ) ) {
        $is_primary = isset( $b['is_primary'] ) && $b['is_primary'] === true;
        if( $b['type'] !== 'fee' && $is_primary ) {
          array_push( $items, $b );
        }
      }
    }
  }

  // Sort items by start date / time
  usort( $items, 'minim_compare_itinerary_start_date' );

  return $items;
}

function minim_get_itinerary_item_image( $item ) {
  if( is_array( $item['assets'] ) && !empty( $item['assets'] ) ) {
    $assets = $item['assets'][0];
    $image = $assets['image_urls'];
    $item_name = isset( $item['name'] ) ? esc_html( $item['name'] ) : '';

    if( isset( $image ) && !empty( $image ) ) {
      if( isset( $image['large'] ) ) {
        $thumb = esc_html( $image['large'] );
        $src = str_replace( 'large', '/'.MINIM_IMAGE_LARGE.'/assets/', $thumb );

        return'
        <div class="minim-itinerary-item-image">
          <img src="'.$src.'" alt="'.$item_name.'">
        </div>';
      }
    }
  }

  return '';
}

function minim_get_item_addons( $item ) {
  $item_addons = isset( $item['addons'] ) ? $item['addons'] : '';
  $addons = '';

  if( !empty( $item_addons ) ) {
    foreach( $item_addons as $addon ) {
      if( isset( $addon['name'] ) && $addon['name'] !== '' ) {
        $addons .= '<div class="minim-itinerary-item-addon">'.$addon['name'].'</div>';
      }
    }
  }

  return $addons !== '' ? '<div class="minim-itinerary-item-addons">'.$addons.'</div>' : '';
}

// Calculate how many days or night a multi-day item spans
function minim_get_day_difference( $date1, $date2 ) {
  $dif = strtotime( $date2 ) - strtotime( $date1 );

  return round( $dif / ( 60 * 60 * 24 ) );
}

function is_activity_item ($itemType) {
  $types = ['activity', 'bicycle', 'golf', 'guide', 'meal', 'tour'];
  return in_array($itemType, $types);
}

function is_transport_item( $itemType ) {
  $types = ['bicycle', 'coach charter', 'ferry', 'flight', 'motorhome', 'rental car', 'train', 'transfer'];
  return in_array($itemType, $types);
}

// Format title based on duration of item
function minim_get_item_title( $item ) {
  $title = '';
  $start = $item['start_date'];
  $itemType = $item['type'];

  // Only Transport and Activity item types should use the classic_override
  $allowOverride =
    isset($item['classic_override']) &&
    (is_transport_item($itemType) || is_activity_item($itemType)) ? true : false;

  if( isset( $item['name'] ) ) {
    $item_title = isset( $item['name'] ) ? esc_html( $item['name'] ) : '';

    if( $allowOverride ) {
      $item_title = esc_html( $item['classic_override'] );
    }
    $title = '<h3 class="minim-itinerary-item-title">'.$item_title;

    if( isset( $item['end_date'] ) && $start !== $item['end_date'] ) {
      $numDays = minim_get_day_difference( $start, $item['end_date'] );

      if( $itemType === 'accommodation' ) {
        $title .= ( $numDays > 1 ) ? ' ('.$numDays.'&nbsp;nights)' : ' ('.$numDays.'&nbsp;night)';
      } else {
        $numDays++;
        $title .= ( $numDays > 1 ) ? ' ('.$numDays.'&nbsp;days)' : ' ('.$numDays.'&nbsp;day)';
      }
    }
    $title .= '</h3>';
  }

  return $title;
}

function minim_format_itinerary_items( $items ) {
  $html = '';
  $day = 0;

  if(!$items) {
    return;
  }

  // Assume that the first item in an itinerary appears on the first day
  $itineraryStartDate = $items[0]['start_date'];

  // Loop through items building the html for the itinerary
  foreach ( $items as $item ) {
    $item_description = isset( $item['description'] ) ? $item['description'] : '';
    $item_name = isset( $item['name'] ) ? esc_html( $item['name'] ) : '';
    $item_start_date = isset( $item['start_date'] ) ? esc_html( $item['start_date'] ) : '';
    $item_type = isset( $item['type'] ) ? esc_html( $item['type'] ) : '';

    // Use square image if info size is quite long
    $infoLength = strlen( $item_name ) * 3 + strlen( $item_description );
    $squareImg = ( $infoLength > 500 ) ? true : false;
    $image = minim_get_itinerary_item_image( $item, $squareImg );
    $itemClass = 'minim-itinerary-item--'.str_replace( ' ', '-', $item_type );

    // Show day number if it's different from the last
    $daysPassed = minim_get_day_difference( $itineraryStartDate, $item_start_date ) + 1;
    
    if( $daysPassed !== $day ){
      $day = $daysPassed;
      $html .= '<h4 class="minim-itinerary-day-number">Day '.$day.'</h4>';
    }

    // Get minim display style
    $minimOptions = minim_get_options();
    $displayStyle = $minimOptions['display_style'] ?? null;
    if ($displayStyle == '1col') $colSize = ' minim-itinerary--mini'; else $colSize = '';

    // Only output the item types we want to show
    if( !in_array( $item_type, MINIM_ITEM_TYPES_TO_HIDE ) && !$item['hidden'] ) {
      $html .= '<div class="minim-itinerary-item '.$itemClass.'">';
      $html .= $image;
      $html .= '<div class="minim-itinerary-item-info">';
      $html .= minim_get_item_title( $item );
      $html .= ( $item_description !== '' ) ? '<div class="minim-itinerary-item-description">'.str_replace("\n", "</p>\n<p>", '<p>'.$item_description.'</p>').'</div>' : '';
      $html .= minim_get_item_addons( $item );
      $html .= '</div>'; // end .minim-itinerary-item-info
      $html .= '</div>'; // end .minim-itinerary-item
    }
  }

  return '<div class="minim-itinerary'.$colSize.'">'.$html.'</div>';
}

// Append Minim itinerary to the_content
function minim_append_itinerary( $content ) {
  // Prevent itinerary from appearing in post lists
  if( !is_single() && !is_page() ) {
    return $content;
  }

  $post_ID = get_the_ID();
  $itinerary_ID = get_post_meta( $post_ID, 'itinerary_id', true );
  $itinerary_display = get_post_meta( $post_ID, 'itinerary_display', true );
  $itinerary_key = get_post_meta( $post_ID, 'itinerary_key', true );
  $minim_API = minim_get_options();
  $itineraryContent = '';

  if( $itinerary_ID && $minim_API['minim_key'] !== '' ){
    // Use Classic Key set on the itinerary level if it exists
    if($itinerary_key && $itinerary_key !== '') {
      $itinerary = minim_get_itinerary_data( $itinerary_ID, $itinerary_key );
    } else {
      $itinerary = minim_get_itinerary_data( $itinerary_ID );
    }
    
    if ( isset( $itinerary['items'] ) ){

      // If the itinerary has at least one item
      if( sizeof( $itinerary['items'] ) > 0 ) {
        $itinerary_array = minim_get_itinerary_items( $itinerary );
        $itineraryContent = minim_format_itinerary_items( $itinerary_array );
      } else {

      // If the itinerary exists but has no items
        if( current_user_can( 'edit_post', $post_ID ) ) {
          $itineraryContent = '<p class="notice">'. __( 'This itinerary does not seem to contain any viewable items. Please update the itinerary in Tourwriter to view the items here.', 'minim-by-tourwriter' ).'</p>';
        }
      }
    } else {
      
      // If the itinerary can't be found
      if( current_user_can( 'edit_post', $post_ID ) ) {
        $error = minim_render_error();

        if( is_wp_error( $error ) ) {
          $itineraryContent = '<p class="error notice">'.$error->get_error_message().'</p>';
        }
      }
    }
  }

  $fullcontent = $content;

  if( $itinerary_display && $itinerary_display !== 'hide' ) {
    if( $itinerary_display === 'above' ) {
      $fullcontent = $itineraryContent . $content;
    } else {
      $fullcontent = $content . $itineraryContent;
    }
  }

  return $fullcontent;
}

function minim_render_error() {
  return new WP_Error( 'itinerary', __( 'This content cannot be displayed. Please ensure that you have entered the correct Itinerary ID.', 'minim-by-tourwriter' ) );
}

function show_minim_itinerary( $atts ) {
  // normalize attribute keys, lowercase
  $atts = array_change_key_case((array)$atts, CASE_LOWER);

  // Return empty string if there is no itinerary ID set
  if(!isset($atts['id'])) {
    return '';
  }

  $id = $atts['id'];

  // Use the classic key from the shortcode if it exists
  if(isset($atts['classic-key'])) {
    $key = $atts['classic-key'];
  } else {
    $key = MINIM_API_KEY;
  }

  // Don't return any content if we can't find the classic key
  if($key === '') {
    return '';
  }

  // Get the itinerary content using the itinerary ID and classic key
  $itinerary = minim_get_itinerary_data($id, $key);
  $items = minim_get_itinerary_items($itinerary);
  $content = minim_format_itinerary_items($items);

  return $content;
}

function delete_minim_cache( $minim_id = null ) {
  $cache_dir = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/cache/minim-cache';

  if ( isset($minim_id) ) {
    $cache_file = $cache_dir . '/'. $minim_id . '.json';
  } else {
    $files = glob($cache_dir.'/*'); // get all file names
    foreach($files as $file){ // iterate files
      if(is_file($file)) {
        unlink($file); // delete file
      }
    }
  }
}
