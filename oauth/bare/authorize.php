<?php

$url = $_GET['redirect_uri'];
$has_query = strpos($url, '?');
$code = 'code=1234';

if ($has_query) {
  $url = str_replace('?', '?'.$code.'&', $url);
} else {
  $url = $url.'?'.$code;
}

header('Location: '.$url);
