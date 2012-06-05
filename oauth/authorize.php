<?php

require_once('../lib/database.php');
require_once('../lib/memcache.php');
require_once('../lib/url_utils.php');

if ($_GET['response_type'] !== 'code') {
  throw new Exception('Only code response type supported');
}

assert_redirect_allowed($_GET['client_id'], $_GET['redirect_uri']);
$app_name = get_app_name($_GET['client_id']);

$cancel_uri = addParamsToURL(
  $_GET['redirect_uri'], 
  array(
    'error' => 'access_denied',
    'state' => $_GET['state'],
  )
);

if ($_GET['display'] == 'none') {
  // This would normally issue a very limited access token that can 
  // only call "import" and nothing else
  $_POST = $_GET;
  include_once('authorize_success.php');
  die();
}

?>
Send all your data to <?php print $app_name ?>?

<form action="authorize_success" method="POST">
  <input type="submit" value="Yes">
  
  <input type="hidden" name="client_id" value="<?php print htmlentities($_GET['client_id']) ?>">
  <input type="hidden" name="state" value="<?php print htmlentities($_GET['state']) ?>">
  <input type="hidden" name="redirect_uri" value="<?php print htmlentities($_GET['redirect_uri']) ?>">

  <a href="<?php print $cancel_uri ?>">
    No
  </a>
</form>
