<?php
require_once '../../config/app.php';

if(isset($_SESSION['ADMIN_ID'])) {
    $admin_id = $_SESSION['ADMIN_ID'];

    $admin = R::load('admins', $admin_id);

    if(isset($_GET['account']) && isset($_SERVER['HTTP_REFERER'])) {
        $account = R::load('accounts', $_GET['account']);
    } else {
        die('<pre>Unauthorized request</pre>');

    }

} else {
    // Redirect user to login
    header('location: login.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $errors = [];

    $number = htmlspecialchars(strip_tags(trim($_POST['number'])));
    $type = htmlspecialchars(strip_tags(trim($_POST['type'])));

    if(isset($_POST['active'])) {
        $active = htmlspecialchars(strip_tags(trim($_POST['active'])));
    }

    if(!is_numeric($number) || strlen($number) > 16) {
        $errors[] = 'Account number must be only digits and less than 16 digits';
    }

    if(empty($errors)) {

        $account = R::load( 'accounts', $account->id);

        if(!account_number_exists($number)) {
            $account->number = $number;
        }

        $account->type = $type;

        (isset($active)) ? $account->is_active = 1 : $account->is_active = 0;

        if(R::store($account)) {
            $_SESSION['FLASH_USER_CREATED'] = 'Account successfully updated';
            header('location: accounts.php?user=' . $account->user_id);
        }

        echo $errors[] = 'Whoops!! An unknown error occured';

    }
}

function account_number_exists($number) {
    $number = R::findOne('accounts', 'number = ?', [ $number ]);
    return ($number)? true : false;
}

?>

<?php require $base_url . 'app/includes/head.php'; ?>

<?php require $base_url . 'app/includes/nav.php'; ?>


    <div class="container">
        <h1 style="margin: 30px 0">
            <i class="fas fa-user-lock"></i> Edit Account: <?= $account->number; ?>
            <span class="pull-right">
<!--                <a href="new_user.php" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> ADD ACCOUNT</a>-->
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
                        <h4 style="margin-bottom: 10px">Edit account</h4>
                        <form name="horizontal" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">

                            <div class="form-group">
                                <label for="username">Account Number</label>
                                <input class="form-control" id="number" type="text" name="number"
                                       value="<?php echo (isset($number))? $number: $account->number; ?>">
                            </div>
                            <!--<div class="form-group">
                                <label for="name">Account Balance</label>
                                <input class="form-control" id="balance" type="text" name="balance"
                                       value="<?php /*echo (isset($balance))? $balance: $account->balance; */?>">
                            </div>-->
                            <div class="form-group">
                                <label for="type">Account Type</label>
                                <select name="type" id="type" class="form-control">
                                    <option value="Saving Account" <?php if($account->type == 'Saving Account') echo 'selected' ?>>Saving Account</option>
                                    <option value="Checking Account" <?php if($account->type == 'Checking Account') echo 'selected' ?>>Checking Account</option>
                                    <option value="Money Market Account"<?php if($account->type == 'Money Market Account') echo 'selected' ?>>Money Market Account</option>
                                    <option value="Certificate of Deposit"<?php if($account->type == 'Certificate of Deposit') echo 'selected' ?>>Certificate of Deposit</option>
                                    <option value="Retirement Account"<?php if($account->type == 'Retirement Account') echo 'selected' ?>>Retirement Account</option>
                                    <option value="Escrow Account"<?php if($account->type == 'Escrow Account') echo 'selected' ?>>Escrow Account</option>
                                    <option value="Non Residence"<?php if($account->type == 'Non Residence') echo 'selected' ?>>Non Residence</option>
                                    <option value="Investment Account"<?php if($account->type == 'Investment Account') echo 'selected' ?>>Investment Account</option>
                                </select>
                            </div>
                            <div class="form-group mt-5">
                                <label for="active">Active</label>
                                <input id="active" type="checkbox" name="active" value="<?php echo $account->is_active; ?>"
                                    <?php echo ($account->is_active) ? 'checked' : ''; ?>>
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


<?php include $base_url . 'app/includes/footer.php'; ?>