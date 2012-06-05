<?php

require_once('lib/memcache.php');

if (!$_SERVER['HTTPS']) {
  header('WWW-Authenticate: Bearer, error=invalid_request');
  die(
    json_encode(
      array(
        'error' => 'invalid_request',
        'error_description' => 'You must use https://',
      )
    )
  );
}

$key = 'oauth:access_token:'.$_GET['access_token'];
$m = get_memcache();
$data = $m->get($key);

if (!$data) {
  header('WWW-Authenticate: Bearer, error=invalid_token');
  die(
    json_encode(
      array(
        'error' => 'invalid_token',
      )
    )
  );
}

// Publish all the user's data to FB
for ($i = 0; $i < 10; $i++) {
  $query = array(
    'access_token' => $_POST['fb_access_token'],
    'website' => 'http://example.com/',
  );

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/me/default_example:do_something_to');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
  $response = curl_exec($ch);
  $data = json_decode($data, true);
  
  if (isset($data['id'])) {
    // Log something good
  } else {
    // Log some error
    error_log($data['error']);
  }
}

die(
  json_encode(
    array(
      'success' => true,
    )
  )
);
