<?php
require_once '../../config/app.php';

if(isset($_SESSION['ADMIN_ID'])) {
    $admin_id = $_SESSION['ADMIN_ID'];

    $admin = R::load('admins', $admin_id);

    // Load user if request referred from users.
    // User should be unauthorized without referral url
    if(isset($_GET['user']) && isset($_SERVER['HTTP_REFERER'])) {
        $user = R::load('users', $_GET['user']);
    } else {
        die('<pre>Unauthorized request</pre>');

    }


} else {
    // Redirect user to login
    header('location: login.php');
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $errors = [];

    $user_id = htmlspecialchars(strip_tags(trim($_POST['user_id'])));
    $username = htmlspecialchars(strip_tags(trim($_POST['username'])));
    $password = htmlspecialchars(strip_tags(trim($_POST['password'])));
    $name = htmlspecialchars(strip_tags(trim($_POST['name'])));
    $email = htmlspecialchars(strip_tags(trim($_POST['email'])));
    $mobile = htmlspecialchars(strip_tags(trim($_POST['mobile'])));

    if(isset($_POST['active'])) {
        $active = htmlspecialchars(strip_tags(trim($_POST['active'])));
    }

    var_dump($_POST);

    if (!$username) {
        $errors[] = 'Username is required';
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

    if(empty($errors)) {

        $user = R::load( 'users', $user_id);

        $user->name = $name;

        if(!username_exists($username)) {
            $user->username = $username;
        }

        if(!email_exists($email)) {
            $user->email = $email;
        }

        if($password) {
            $user->password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
        }

        (isset($active)) ? $user->is_active = 1 : $user->is_active = 0;

        $user->mobile = $mobile;
//        $user->created_at = new DateTime('mow');

        if($id = R::store($user)) {
            $_SESSION['FLASH_USER_CREATED'] = 'User has successfully been updated';
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
            <i class="fas fa-user-lock"></i> User: <?= $user->name; ?>
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
                        <h4 style="margin-bottom: 10px">Edit user information provided</h4>
                        <form name="horizontal" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                            <input type="hidden" name="user_id" value="<?= $user->id; ?>">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input class="form-control" id="username" type="text" name="username"
                                       value="<?php echo (isset($username))? $username: $user->username; ?>">
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
                                       value="<?php echo (isset($name))? $name: $user->name; ?>">
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail Address</label>
                                <input class="form-control" id="email" type="email" name="email"
                                       value="<?php echo (isset($email))? $email: $user->email; ?>">
                            </div>
                            <div class="form-group">
                                <label for="last_name">Mobile</label>
                                <input class="form-control" id="mobile" type="text" name="mobile"
                                       value="<?php echo (isset($mobile))? $mobile: $user->mobile; ?>">
                            </div>
                            <div class="form-group mt-5">
                                <label for="active">Active</label>
                                <input id="active" type="checkbox" name="active" value="<?php echo $user->is_active; ?>"
                                    <?php echo ($user->is_active) ? 'checked' : ''; ?>>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require $base_url . 'app/includes/footer.php' ?>