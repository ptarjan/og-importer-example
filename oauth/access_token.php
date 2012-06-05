<?php

require_once('../lib/database.php');
require_once('../lib/memcache.php');

function error($string, $desc) {
  die(
    json_encode(
      array(
        'error' => $string,
        'error_description' => $desc,
      )
    )
  );
}

$user_id = get_loggedin_user();
$m = get_memcache();

assert_correct_secret($_POST['client_id'], $_POST['client_secret']);

$code_key = 'oauth:code:'.$_POST['code'];
$data = $m->get($code_key);

if (!$data) {
  error('invalid_grant', 'Invalid code');
}

$params = json_decode($data, true);

if ($params['client_id'] !== $_POST['client_id']) {
  error('invalid_client', 'Invalid client_id');
}
if ($params['redirect_uri'] !== $_POST['redirect_uri']) {
  error('invalid_request', 'Invalid redirect_uri');
}

// So it can't be re-used
$m->delete($code_key);

$token = (string) mt_rand();
$key = 'oauth:access_token:'.$token;
$m->set(
  $key, 
  json_encode(
    array(
      'user' => $user_id, 
      'app' => $_POST['client_id'],
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
