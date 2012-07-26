<?php

// Some hard-coded functions that should be DB lookups

function get_loggedin_user() {
  return 31415926;
}

function get_app_name($id) {
  if ($id == 1234) {
    return 'Facebook';
  }
  throw new Exception('Unknown Client ID: '.$id);
}

function assert_redirect_allowed($id, $redirect_uri) {
  if ($redirect_uri == 'https://www.facebook.com/open_graph/oauth/callback?service=1001') {
    return;
  }
  if ($redirect_uri == 'https://www.ptarjan.sb.facebook.com/open_graph/oauth/callback?service=1001') {
    return;
  }
  if ($redirect_uri == 'https://www.facebook.com/open_graph/oauth/callback?allow_iframe=1&service=1001') {
    return;
  }
  if ($redirect_uri == 'https://www.ptarjan.sb.facebook.com/open_graph/oauth/callback?allow_iframe=1&service=1001') {
    return;
  }
  if ($redirect_uri == 'https://www.luchen.sb.facebook.com/open_graph/oauth/callback?allow_iframe=1&service=1001') {
    return;
  }
  throw new Exception('Invalid Redirect URI: '.$redirect_uri);
}

function assert_correct_secret($id, $secret) {
  if ($secret == '5678') {
    return;
  }
  throw new Exception('Invalid Secret: '.$secret);
}
