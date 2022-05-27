<?php
require_once '../../config/app.php';

if(isset($_SESSION['ADMIN_ID'])) {
    $admin_id = $_SESSION['ADMIN_ID'];

    $user = R::load('admins', $admin_id);

} else {
    // Redirect user to login
    header('location: login.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $errors = [];

    $username = htmlspecialchars(strip_tags(trim($_POST['username'])));
    $password = htmlspecialchars(strip_tags(trim($_POST['password'])));
    $name = htmlspecialchars(strip_tags(trim($_POST['name'])));
    $email = htmlspecialchars(strip_tags(trim($_POST['email'])));
    $mobile = htmlspecialchars(strip_tags(trim($_POST['mobile'])));

    if (!$username) {
        $errors[] = 'Username is required';
    }
    if (!$password) {
        $errors[] = 'Password is required';
    }
    if (!$name) {
        $errors[] = 'Full name is required';
    }
    if (!$mobile) {
        $errors[] = 'Mobile number is required';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address';
    }

    if (email_exists($email)) {
        $errors[] = 'Email address already taken';
    }

    if (username_exists($username)) {
        $errors[] = 'Username already taken';
    }

    if(empty($errors)) {

        $user = R::dispense( 'users' );

        $user->name = $name;
        $user->username = $username;
        $user->password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
        $user->email = $email;
        $user->mobile = $mobile;
        $user->is_active = 0;
        $user->created_at = new DateTime('now');

        if($id = R::store($user)) {
            $_SESSION['FLASH_USER_CREATED'] = 'User successfully registered';
            header('location: users.php');
        }

        echo $errors[] = 'Whoops!! An unknown error occured';

    }
}

function email_exists($email) {
    $email_exists = R::findOne('users', 'email = ?', [ $email ]);
    return ($email_exists)? true : false;
}

function username_exists($username) {
    $username_exists = R::findOne('users', 'username = ?', [ $username ]);
    return ($username_exists)? true : false;
}

?>


<?php require $base_url . 'app/includes/head.php'; ?>

<?php require $base_url . 'app/includes/nav.php'; ?>


    <div class="container">
        <h1 style="margin: 30px 0">
            <i class="fas fa-user-lock"></i> User
            <span class="pull-right">
                <a href="new_user.php" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> ADD USER</a>
            </span>
        </h1>
      <div class="panel panel-default">

        <div class="panel-body">
          <div class="row">
            <div class="col-md-5">
                <p>
                <!-- Put error messages here -->
                <?php if (!empty($errors)): ?>
                  <div class="alert alert-danger text-center">
                  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                      <?php echo implode('<p></p>', $errors); ?>
                  </div>
                <?php endif; ?>

              </p>
              <h4 style="margin-bottom: 10px">Add a user to database.</h4>
                <form name="horizontal" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                  <div class="form-group">
                    <label for="username">Username</label>
                    <input class="form-control" id="username" type="text" name="username"
                            value="<?php echo (isset($username))? $username: ''; ?>">
                  </div>
                  <div class="form-group">
                    <label for="password">Password</label>
                    <input class="form-control" id="password" type="password" name="password" value="">
                      <small>
                          <a href="javascript:void(0)" id="showPassword">show password</a>
                      </small>

                        <script>
                            var showPassword = document.getElementById('showPassword');
                            var password = document.getElementById('password');

                            showPassword.addEventListener('click', function () {
                                password.type = 'text'
                            });
                        </script>

                  </div>
                  <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" type="text" name="name"
                           value="<?php echo (isset($name))? $name: ''; ?>">
                  </div>
                    <div class="form-group">
                        <label for="email">E-mail Address</label>
                        <input class="form-control" id="email" type="email" name="email"
                               value="<?php echo (isset($email))? $email: ''; ?>">
                    </div>
                  <div class="form-group">
                    <label for="last_name">Mobile</label>
                    <input class="form-control" id="mobile" type="text" name="mobile"
                           value="<?php echo (isset($mobile))? $mobile: ''; ?>">
                  </div>
                  <div class="form-group">
                      <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Add User</button>
                  </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>

<?php include $base_url . 'app/includes/footer.php'; ?>