<?php
require_once '../../config/app.php';

if(isset($_SESSION['ADMIN_ID'])) {
    $admin_id = $_SESSION['ADMIN_ID'];

    $admin = R::load('admins', $admin_id);

    $users = R::findAll('users', 'ORDER BY 1 DESC');

} else {
    // Redirect user to login
    header('location: login.php');
}

if(isset($_POST['CHANGE_PASSWORD'])) {
    $password = htmlspecialchars(strip_tags(trim($_POST['password'])));

    if(strlen($password) < 6) {
        $error = 'Your password must be more than 6 characters';
    }

    if(!isset($error)){
        $admin->password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

        if(R::store($admin)) {
            $success  = 'Your password has been changed';
        }
    }
}

if(isset($_POST['SET_CURRENCY'])) {
    $currency = htmlspecialchars(strip_tags(trim($_POST['currency'])));

    $admin->currency = $currency;

    if(R::store($admin)) {
        $success  = 'Your currency has been set to ' . $currency;
    }
}

if(isset($_POST['SET_REGION'])) {
    $region = htmlspecialchars(strip_tags(trim($_POST['region'])));

    $admin->region = $region;

    if(R::store($admin)) {
        $success  = 'You region has been set to ' . $region;
    }
}
?>


<?php require $base_url . 'app/includes/head.php'; ?>

<?php require $base_url . 'app/includes/nav.php'; ?>


<div class="container">
    <h1 style="margin: 30px 0">
        <i class="fas fa-user-lock"></i> Account Settings
        <span class="pull-right">
<!--                <a href="new_user.php" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> ADD USER</a>-->
            </span>
    </h1>
    <hr>
    <div class="panel panel-default" style="border: none">
        <div class="panel-body">
            <p>
                <?php if (isset($success)): ?>
            <div class="alert alert-success text-center">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <?php echo $success; ?>
            </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger text-center">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            </p>

            <div class="mt-20">
                <h3 class="text-4xl add-header-font">Change Password</h3>

                <div class="mt-10" id="changePasswordForm">
                    <div class="modal-contentd">
                        <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                            <div class="form-group">
                                <input class="form-control" type="password" name="password" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <input type="submit" name="CHANGE_PASSWORD" class="btn btn-primary btn-block" value="Change Password">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="mt-20">
                <h3 class="text-4xl add-header-font">Set Currency</h3>
                <p>Current Currency: <span class="add-header-font" style="color: orangered"><?= $admin->currency; ?></span></p>
                <p>Set the currency to show with all transactions</p>

                <div class="mt-10">
                    <div class="modal-contentd">
                        <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                        <div class="form-group">
                            <select name="currency" id="currency" class="form-control">
                                <option value="USD" <?php if($admin->currency == 'USD') echo 'selected' ?>> US Dollars</option>
                                <option value="EUR" <?php if($admin->currency == 'EUR') echo 'selected' ?>> European Euro</option>
                                <option value="JPY" <?php if($admin->currency == 'JPY') echo 'selected' ?>>Japanese Yen</option>
                                <option value="GBP" <?php if($admin->currency == 'GBP') echo 'selected' ?>> British Pound</option>
                                <option value="CHF" <?php if($admin->currency == 'CHF') echo 'selected' ?>> Swiss Franc</option>
                                <option value="CAD" <?php if($admin->currency == 'CAD') echo 'selected' ?>> Canadian Dollars</option>
                                <option value="AUD/NZD" <?php if($admin->currency == 'AUD/NZD') echo 'selected' ?>> Australian/New Zealand Dollar</option>
                                <option value="ZAR" <?php if($admin->currency == 'ZAR') echo 'selected' ?>> South African Rand</option>
                                <option value="CNY" <?php if($admin->currency == 'CNY') echo 'selected' ?>> Chinese Yuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="SET_CURRENCY" class="btn btn-primary btn-block" value="Set Currency">
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="mt-20">
                <h3 class="text-4xl add-header-font">Bank Region</h3>
                <p>Current Region: <span class="add-header-font" style="color: orangered"><?= $admin->region; ?></span></p>
                <p>Setting regions dictates how transfer forms are shown. Americans use routing numbers, while Europeans dont.
                    <br> You should test how they work, make a change here and try doing a transfer from any account.</p>

                <div class="mt-10">
                    <div class="modal-contentd">
                        <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                        <div class="form-group">
                            <select name="region" id="region" class="form-control">
                                <option value="North_American_Region" <?php if($admin->region == 'North_America_Region') echo 'selected' ?>>North American Region</option>
                                <option value="European_Region" <?php if($admin->region == 'European_Region') echo 'selected' ?>>European Region</option>
                                <option value="Asian_Region" <?php if($admin->region == 'Asian_Region') echo 'selected' ?>>Asian Region</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="SET_REGION" class="btn btn-primary btn-block" value="Set Region">
                        </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include $base_url . 'app/includes/footer.php'; ?>
