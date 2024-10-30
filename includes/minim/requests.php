<?php
/*
* $url (string) endpoint to fetch data from
* $headers (array) headers to send with request
*/
function minim_fetch_data( $url, $headers ) {
  $response = wp_remote_get( $url, ['body' => $headers] );

  if ( is_wp_error( $response ) ) {
    $error_message = $response->get_error_message();
    return "Something went wrong with the GET request: $error_message";
  } else {
    return $response['body'];
  }
}

function minim_get_access_token($key) {
  $url = MINIM_ENDPOINT.'authentication';
  $data = [
    'classic_key' => $key,
    'strategy' => 'classic'
  ];

  $response = wp_remote_post( $url, ['body' => $data] );

  if ( is_wp_error( $response ) ) {
    $error_message = $response->get_error_message();
    if( WP_DEBUG ) {
      echo "Something went wrong with the POST request: $error_message";
    }
    return '';
  } else {
    return json_decode( $response['body'], true );
  }
}

function minim_get_request_headers($key) {
  $tokenResult = minim_get_access_token($key);

  if(!is_array($tokenResult)) {
    if( WP_DEBUG ) {
      echo 'ERROR: Unable to get access token:<br>';
      var_dump( $tokenResult );
    }
    return false;
  }

  if( !array_key_exists( 'accessToken', $tokenResult ) ){
    if( WP_DEBUG ) {
      echo 'ERROR: A response was given when requesting the access token but the token was not found:<br>';
      var_dump( $tokenResult );
    }
    return false;
  }

  return [
    'Authorization' => $tokenResult["accessToken"],
    'Accept' => 'application/json',
    'Content-Type' => 'application/json',
    'X-Tourwriter-Source' => 'wordpressplugin'
  ];
}

/*
* $id (string) Minim itinerary ID
* $key (string) Minim Classic Key, defaults to global key if not set on the itinerary
*/
function minim_get_itinerary_data( $id, $key = MINIM_API_KEY ) {
  $cache_dir = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/cache/minim-cache';
  $cache_file = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/cache/minim-cache/'. $id . '.json';
  $json_data = '';

  if (!file_exists($cache_dir)) {
    mkdir($cache_dir, 0777, true);
  }
  
  if (file_exists($cache_file) && (filemtime($cache_file) > (time() - 60 * 60 * 24 ))) {
    // Cache file is less than five minutes old. 
    // Don't bother refreshing, just use the file as-is.
    $json_data = file_get_contents($cache_file);
 } else {
    // Our cache is out-of-date, so load the data from our remote server,
    // and also save it over our cache for next time.
    $url = MINIM_ENDPOINT.'itineraries/'.$id.MINIM_REQUEST_PARAMS;

    $headers = minim_get_request_headers($key);
    if( !$headers ) return false;
  
    $response = wp_remote_get( $url, ['headers' => $headers] );
  
    if ( is_wp_error( $response ) ) {
      if( WP_DEBUG ) {
        $error_message = $response->get_error_message();
        echo "Something went wrong with the GET request: $error_message";
        var_dump( $response );
      }
      return false;
    } else {
      $json_data = $response['body'];
    }
      file_put_contents($cache_file, $json_data, LOCK_EX);
 }

  return json_decode( $json_data, true );
}