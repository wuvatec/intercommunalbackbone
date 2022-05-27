<?php
ob_start();
session_start();
include '../../vendor/autoload.php';
include '../../resources/functions/func.php';

require_once '../../resources/inc/_auth_user.inc';

// Connect to PHP Activerecord
require_once '../../resources/inc/db.php';


// Not secured using http_referer, i would still go ahead to use it as this app is personal
// Redirect user to support where user was intended to come from, when use tries to access
if (!isset($_SERVER['HTTP_REFERER'])) {
  header('location: support.php');
}

if (isset($_GET['uid'])){
  $reply = Support::find($_GET['uid']);
  $reply->delete();

  $_SESSION['flash_message'] = 'Message successfully deleted.';

  header('location: support.php');
}