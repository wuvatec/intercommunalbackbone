<?php
require '../../../config/app.php';

// Page refused direct access
if(!isset($_SERVER['HTTP_REFERER'])) {
    die('This page does not exist');
}

// User already logged in redirect
if(isset($_SESSION['USER'])) {
    header('location: summary.php');
}

// process login form from external website
if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $message['failed'] = 'Invalid username or password combination';
    $message['required'] = 'All fields are required';
    
    $username = htmlspecialchars(strip_tags(trim($_POST['username'])));
    $password = htmlspecialchars(strip_tags(trim($_POST['password'])));

    if(!empty($username) && !empty($password)) {
        if(login_user($username, $password)) {
            $hide_errors = 'hidden';
            header('refresh:4; url=summary.php');
//            exit();
        }


        $error = $message['failed'];
    }

    if(!isset($error)) {
        $error = $message['required'];
    }
}


function login_user($username, $password)
{
    $user = R::findOne('users', ' username = ?', [ $username]);

    if($user && password_verify($password, $user->password) && $user->is_active) {
        $_SESSION['USER'] = $user->id;
        
        // Log user activity
        $activity = R::dispense('activities');
        $activity->name = 'Account login';
        $activity->date = new DateTime('now');
        $activity->user_id = $user->id;
        
        R::store($activity);

        if (empty($user->new_login)) {
            $user->new_login = new DateTime('now');
        } else {
            $user->last_login = $user->new_login;
            $user->new_login = new DateTime('now');
        }

//        (empty($user->new_login)? $user->new_login = new DateTime('now') : $user->last_login = $user->new_login);

        R::store($user);

        return true;
    }

    return false;

}

?>


<?php require $base_url . 'app/account/new/layouts/head.php'; ?>

<div class="container mx-auto text-center mt-10 <?php echo (isset($hide_errors))? $hide_errors: 'hidden'; ?>" id="errorPage">

    <?php if(isset($error)): ?>

        <div class="mb-5 text-red-600">
            <i class="fas fa-times-circle"></i> <?php echo $error; unset($error); ?>
        </div>

    <?php endif; ?>

    <span class="text-blue-800 px-10 tracking-wider leading-normal uppercase py-2 rounded-sm shadow-sm bg-blue-100 ">
        <?php echo "<a href='https://intercommunalkredit.com/default.html'>back</a>"  ?>
    </span>

</div>

<div class="container mx-auto text-center mt-10 text-indigo-700" id="loading">
    <span class="text-xl">Authenticating... <span><span class="text-5xl font-semibold"></span>

</div>


    <script>

        window.onload = function() {
            let errorPage = document.getElementById('errorPage');
            let loading = document.getElementById('loading');

            setTimeout(function() {
                errorPage.classList.remove('hidden');
                loading.className = 'hidden';
            }, 5000);

        };

    </script>

<?php require $base_url . 'app/account/new/layouts/footer.php'; ?>