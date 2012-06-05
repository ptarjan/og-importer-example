<?php

function addParamsToURL($url, $params) {
  $encoded = http_build_query($params);
  $has_query = strpos($url, '?');
  if ($has_query) {
    $url = str_replace('?', '?'.$encoded.'&', $url);
  } else {
    $url = $url.'?'.$encoded;
  }
  return $url;
}
