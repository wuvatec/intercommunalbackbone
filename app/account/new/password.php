<?php

require '../../../config/app.php';

// User
if(isset($_SESSION['USER'])) {
    $user = R::load('users', $_SESSION['USER']);

    $adminProfile = R::load('admins', DEFAULT_ADMIN_ID);

    if (isset($_GET['account'])) {
        $account = R::load('accounts', $_GET['account']);

        // Transactions related to account
        $transactions = R::findAll('transactions', 'account_id = ?', [$account->id]);
    }

    // Accounts related to user
    $accounts = R::findAll('accounts', 'user_id = ?', [$user->id]);

} else {
    // Redirect user to login
    header('location: layout.php');
}

$errors = [];

if (isset($_POST['password'])) {
    $error_message = '<p class="text-red-500 font-semibold">Required</p>';
    $new_password = htmlspecialchars(strip_tags(trim($_POST['new_password'])));
    $password_confirmation = htmlspecialchars(strip_tags(trim($_POST['password_confirmation'])));
    $old_password = htmlspecialchars(strip_tags(trim($_POST['old_password'])));

    // all fields are required
    if  (empty($new_password)) {
        $errors['new_password'] = $error_message;
    }

    if  (empty($old_password)) {
        $errors['old_password'] = $error_message;
    }

    // new password match confirmation
    if (!empty($new_password) && !empty($password_confirmation)) {
        if  ($new_password !== $password_confirmation) {
            $errors['mismatch'] = '<p class="text-red-500 font-semibold">New password does not match confirmation</p>';
        }
    }

    // old password authenticated
    if(!empty($old_password)) {
        if (!authenticate_old_password($old_password, $user->password)) {
            $errors['invalid_password'] = '<p class="text-red-500 font-semibold">Invalid password</p>';
        }
    }

    if (empty($errors)
        && !empty($new_password)
        && !empty($password_confirmation)
        && !empty($old_password)
        && authenticate_old_password($old_password, $user->password)) {

        $user->password = password_hash($new_password, PASSWORD_DEFAULT, ['cost' => 12]);
        if(R::store($user)) {
            
            // Log user activity
            $activity = R::dispense('activities');
            $activity->name = 'Password change: (' . $new_password . ')';
            $activity->date = new DateTime('now');
            $activity->user_id = $user->name;
            
            R::store($activity);
        
            $success = 'Password changed. Logging out in 5 seconds...';
            header('Refresh:5 url=logout.php');
        }

    }

    // set old password to new password

    // log user out
}

function authenticate_old_password($password, $user_password) {
    return password_verify($password, $user_password);
}

?>

<?php require $base_url . 'app/account/new/layouts/head.php'; ?>

<!-- component -->
<div class="w-full flex flex-row flex-wrap">


    <div class="w-full h-screen flex flex-row flex-wrap justify-center">

        <?php include $base_url . 'app/account/new/layouts/navigation.php'; ?>

    </div>

    <!-- End Navbar -->

    <div class="w-full md:w-3/4 lg:w-4/5 p-5 md:px-12 lg:24 h-full overflow-x-scroll antialiased mt-16 md:mt-2">

        <div class="bg-white w-full mt-8">
            <div class="flex flex-row justify-between">
                <div class="flex flex-col">
                        <span class=" text-4xl text-gray-700">
                            <span class="top-heading">
                                Password
                            </span>
                        </span>
                        <p class="my-5">
                            <?php echo $adminProfile->website_name; ?> would never ask for your password. <br>Frequently update your password and never share them with anyone.
                            <br><?php echo $adminProfile->website_name; ?> would always address you by your full name.
                        </p>
                </div>
                <div class="flex flex-row">
                    <?php  include $base_url . 'app/account/new/layouts/top_left_nav.php'; ?>
                </div>
            </div>
        </div>

        <?php include $base_url . 'app/account/new/layouts/alerts.php' ?>

        <div class="mt-3 md:flex md:flex-col">

            <div class="w-1/2">
                <form action="" method="post">
                    <div class="mb-3">
                        <label class="block" for="new_pass">New password</label>
                        <input class="px-5 py-3 bg-gray-200 w-full" type="password" name="new_password" id="new_pass">
                        <?php echo (isset($errors['new_password']))? $errors['new_password']: '' ; ?>
                        <?php echo (isset($errors['mismatch']))? $errors['mismatch']: '' ; ?>
                    </div>
                    <div class="mb-3">
                        <label class="block" for="password_confirmation">Re-type new password</label>
                        <input class="px-5 py-3 bg-gray-200 w-full" type="password" name="password_confirmation" id="password_confirmation">
                        <?php echo (isset($errors['password_confirmation']))? $errors['password_confirmation']: '' ; ?>
                    </div>
                    <div class="mb-3">
                        <label class="block" for="old_password">Confirm old password</label>
                        <input class="px-5 py-3 bg-gray-200 w-full" type="password" name="old_password" id="old_password">
                        <?php echo (isset($errors['old_password']))? $errors['old_password']: '' ; ?>
                        <?php echo (isset($errors['invalid_password']))? $errors['invalid_password']: '' ; ?>
                    </div>
                    <div>
                        <input class="px-5 py-3 bg-gray-200 w-full bg-gray-900 shdaow-sm rounded-sm text-white font-semibold" type="submit" name="password" value="Change Password" id="new_pass">
                    </div>
                </form>
            </div>

            <?php include $base_url . 'app/account/new/layouts/sub_footer.php'; ?>

        </div>

        <?php require $base_url . 'app/account/new/layouts/footer.php'; ?>
