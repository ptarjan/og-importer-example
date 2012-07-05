<?php

require_once('lib/memcache.php');

if (!$_SERVER['HTTPS'] && $_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https') {
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

$key = 'oauth:access_token:'.$_POST['access_token'];
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

function fb_graph($path, $params) {
  $params['access_token'] = $_POST['fb_access_token'];
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/'.$path);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
  return curl_exec($ch);
}
 
fb_graph('me/brower_notifications', array(
  'name' => 'og_action_importer_count',
  'value' => 10
));

// Publish all the user's data to FB
for ($i = 0; $i < 10; $i++) {
  $params = array(
    'start_time' => 1234567890 + rand(-5000000, 5000000),
    'website' => 'http://example.com/',
  );
  $response = fb_graph('me/default_example:do_something_to', $params);
  $data = json_decode($response, true);
  
  if (isset($data['id'])) {
    // Log something good
    error_log($response);
  } else {
    // Log some error
    error_log($response);
  }
}

fb_graph('me/brower_notifications', array(
  'name' => 'done',
  'value' => true
));


die(
  json_encode(
    array(
      'success' => true,
    )
  )
);
