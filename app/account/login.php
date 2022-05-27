<?php
ob_start();
session_start();

include '../../vendor/autoload.php';
include '../../resources/functions/func.php';

if (user_authenticated()) {
  header('location: summary.php');
}

// Connect to PHP Activerecord
require_once '../../resources/inc/db.php';

if (isset($_POST['login'])) {

  $username = clean_data('username');
  $password = clean_data('password');
  $_SESSION['GRANT_ACCESS'] =  false;

  if (empty($username) || empty($password)) {
    $errors[] = 'Username and Password required to login.';
  } else {
    // Form input filtered and validated. Process form
    if (login($username, $password)) {
      // Grant access and set last login time
      // user_has_no_account($_SESSION['user_id']);
      $_SESSION['GRANT_ACCESS'] = true;
      $_SESSION['LAST_LOGIN_TIME'] = time();
      // Admin authenticated, redirect to dashboard
      header('location: account_summary.php');
    } else {
      $errors[] = 'Invalid username or password combination.';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once '../../resources/inc/_meta.inc'; ?>
    <title>Login</title>

    <?php require_once '../../resources/inc/_styles.inc'; ?>
  </head>
  <body>

    <div class="container">
      <p>
        <!-- Put error messages here -->
        <?php if (!empty($errors)): ?>
          <div class="alert alert-danger text-center">
          <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
              <?php echo implode('<p></p>', $errors); ?>
          </div>
        <?php endif; ?>
      </p>
    </div>
  </body>
</html>
