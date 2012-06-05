<?php

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

// Do a bunch of publishing with the oauth_token in the $_GET['signed_request']

die(
  json_encode(
    array(
      'success' => true,
    )
  )
);
