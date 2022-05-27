<?php

require_once '../../config/app.php';

$error = [];

// Todo: this would rediret if not authenticated

if(isset($_SESSION['IS_LOGGED_IN']) && $_SESSION['IS_LOGGED_IN']) {
    header('location: dashboard.php');
}

// Todo: set session token for form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = htmlspecialchars(strip_tags(trim($_POST['username'])));
    $password = htmlspecialchars(strip_tags(trim($_POST['password'])));

    if(!$username) {
        $error[] = 'Username field is required';
    }

    if(!$password) {
        $error[] = 'Password field is required';
    }

    if(sizeof($error) < 1) {

        if(login_admin($username, $password)) {
            header('location: dashboard.php');
            exit();
        }

        $error[] = 'Invalid username or password combination';

    }
}

function login_admin($username, $password)
{
    $user = R::findOne('admins', ' username = ?', [ $username]);

    if($user && password_verify($password, $user->password) && $user->is_active) {
        $_SESSION['ADMIN_ID'] = $user->id;
        return true;
    }

    return false;
}


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Admin Login</title>

    <!-- Bootstrap -->
    <link href="../../public/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../public/css/custom.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body ng-app="app">
    <!-- <div class="login-wrapper"> -->

      <div class="message error">
        <?php if($error): ?>
          <div class="alert alert-info text-center">
          <span aria-hidden="true"></span>
              <?php echo implode('<p></p>', $error); ?>
          </div>
        <?php endif; ?>
      </div>

      <div class="form-wrapper" style="padding: 25px">
        <h3>Admin Login</h3>
        <form name="loginForm" class="horizontal-form" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
          <div class="form-group">
            <input type="text" name="username" value="" class="form-control input-sm" placeholder="Username">
          </div>

          <div class="form-group">
            <input type="password" name="password" value="" class="form-control input-sm" placeholder="Password">
          </div>

          <div class="form-group">
            <button type="submit" name="login" class="btn btn-danger input-sm btn-block" ng-if="!loginForm.username.password"><i class="glyphicon glyphicon-log-in"></i> Login</button>
          </div>
        </form>
      </div>
    <!-- </div> -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../../public/js/bootstrap.min.js"></script>
    <!-- <script src="../../public/js/app.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.2/angular.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.4.4/angular-messages.min.js"></script>
    <script>
      var app = angular.module('app', ['ngMessages']);
    </script>
  </body>
</html>
