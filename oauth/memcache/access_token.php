<?php

require_once('database.php');
require_once('memcache.php');

$user_id = get_loggedin_user();
$m = get_memcache();

assert_correct_secret($_GET['client_id'], $_GET['client_secret']);

$key = 'oauth:code:'.$user_id.':'.$_POST['client_id'].':'.$_POST['redirect_uri'];
$code = $m->get($code_key);

if ($code != $_GET['code']) {
  die(
    json_encode(
      array(
        'error' => 'invalid_request',
      )
    )
  );
}

// So it can't be re-used
$m->delete($code_key);

$token = mt_rand();
$key = 'oauth:access_token:'.$token;
$m->set(
  $key, 
  json_encode(
    array(
      'user' => $user_id, 
      'app' => $_GET['client_id'],
    )
  )
);
  
die(
  json_encode(
    array(
      'access_token' => $token,
    )
  )
);
