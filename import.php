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
  $params['no_feed_story'] = '1';
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/'.$path);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
  return curl_exec($ch);
}
 
fb_graph('me/browser_notifications', array(
  'name' => 'og_action_importer_count',
  'value' => 15,
));

// Publish all the user's data to FB
for ($i = 0; $i < 15; $i++) {
  $params = array(
    'start_time' => time() - rand(0, 60 * 60 * 24 * 365),
    'website' => 'http://example.com/',
    'int' => rand(100, 1000),
    'float' => rand(1, 1000) / 1000,
    'string' => md5(rand()),
    'boolean' => (bool) rand(0, 1),
    'datetime' => rand(1, time()),
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

fb_graph('me/browser_notifications', array(
  'name' => 'og_action_importer_done',
  'value' => true,
));


header('Content-Type: text/javascript');
echo(
  json_encode(
    array(
      'success' => true,
    )
  )
);
