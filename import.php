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

// Use 'heroku config' to view current env variables
// Use 'heroku config:add NAME=value' to set
$publish_count = getenv('OG_IMPORT_COUNT') !== FALSE
  ? intval(getenv('OG_IMPORT_COUNT'))
  : 15;

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
  return curl_exec(fb_graph_get_curl($path, $params));
}

function fb_graph_get_curl($path, $params) {
  $params['access_token'] = $_POST['fb_access_token'];
  $params['no_feed_story'] = '1';
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/'.$path);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
  return $ch;
}
 
fb_graph('me/browser_notifications', array(
  'name' => 'og_action_importer_count',
  'value' => $publish_count,
));

// Do all the curls at the same time
$mh = curl_multi_init();

// Publish all the user's data to FB
for ($i = 0; $i < $publish_count; $i++) {
  $params = array(
    'start_time' => time() - rand(0, 60 * 60 * 24 * 365),
    'website' => 'http://example.com/',
    'int' => rand(100, 1000),
    'float' => rand(1, 1000) / 1000,
    'string' => md5(rand()),
    'boolean' => (bool) rand(0, 1),
    'datetime' => rand(1, time()),
  );
  $ch = fb_graph_get_curl('me/default_example:do_something_to', $params);
  curl_multi_add_handle($mh, $ch);
}

// execute the handles
$running = null;
do {
  curl_multi_exec($mh, $running);
} while ($running > 0);

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
