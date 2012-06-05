<?php

require_once('database.php');
require_once('memcache.php');
require_once('url_utils.php');

$user_id = get_loggedin_user();
$m = get_memcache();

$code = mt_rand();
$key = 'oauth:code:'.$user_id.':'.$_POST['client_id'].':'.$_POST['redirect_uri'];
$m->add($key, $code, /* expires in 15 mins */ 15 * 60);

$redirect_uri = addParamsToURL(
  $_POST['redirect_uri'], 
  array(
    'code' => $code,
    'state' => $_POST['state']
  )
);

header('Location: '.$redirect_uri);
