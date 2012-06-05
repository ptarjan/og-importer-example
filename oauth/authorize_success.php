<?php

require_once('../lib/database.php');
require_once('../lib/memcache.php');
require_once('../lib/url_utils.php');

$user_id = get_loggedin_user();
$m = get_memcache();

$code = mt_rand();
$key = 'oauth:code:'.$code;
$m->set(
  $key, 
  json_encode(
    array(
      'user_id' => $user_id,
      'client_id' => $_POST['client_id'],
      'redirect_uri' => $_POST['redirect_uri'],
    )
  ),
  /* expires in 15 mins */ 15 * 60
);

$redirect_uri = addParamsToURL(
  $_POST['redirect_uri'], 
  array(
    'code' => $code,
    'state' => $_POST['state']
  )
);

header('Location: '.$redirect_uri);
