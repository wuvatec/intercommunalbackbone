<?php
require_once '../../config/app.php';

if(isset($_SESSION['ADMIN_ID'])) {
    $admin_id = $_SESSION['ADMIN_ID'];

    $user = R::load('admins', $admin_id);

    $users = R::findAll('users');

} else {
    // Redirect user to login
    header('location: login.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $errors = [];

    $number = htmlspecialchars(strip_tags(trim($_POST['number'])));
    $balance = htmlspecialchars(strip_tags(trim($_POST['balance'])));
    $type = htmlspecialchars(strip_tags(trim($_POST['type'])));
    $user_id = htmlspecialchars(strip_tags(trim($_POST['user'])));

    if(!is_numeric($number)) {
        $errors[] = 'Account number must be only digits';
    }

    if (!is_numeric($balance)) {
        $errors[] = 'Account balance must be digits';
    }

    if (account_number_exists($number)) {
        $errors[] = 'Account number has already been added';
    }

    if(empty($errors)) {

        $account = R::dispense( 'accounts' );

        $account->number = $number;
        $account->user_id = $user_id;
        $account->balance = $balance;
        $account->pending_balance = 0;
        $account->is_active = 0;
        $account->type = $type;
        $account->created_at = new DateTime('now');

        if($id = R::store($account)) {
            $_SESSION['FLASH_USER_CREATED'] = 'Account successfully added';
            header('location: accounts.php?user=' . $user_id);
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
            <i class="fas fa-user-lock"></i> Account
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
                        <h4 style="margin-bottom: 10px">Add a user to database. <small class="text-red-500">You can add many accounts to a user</small></h4>
                        <form name="horizontal" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">

                            <div class="form-group">
                                <label for="username">Account Number</label>
                                <input class="form-control" id="number" type="text" name="number"
                                       value="<?php echo (isset($number))? $number: ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="name">Account Balance</label>
                                <input class="form-control" id="balance" type="text" name="balance"
                                       value="<?php echo (isset($balance))? $balance: ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="type">Select User</label>
                                <select name="user" id="user" class="form-control">
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?= $user->id; ?>"><?= $user->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="type">Account Type</label>
                                <select name="type" id="type" class="form-control">
                                    <option value="Saving Account">Saving Account</option>
                                    <option value="Checking Account">Checking Account</option>
                                    <option value="Money Market Account">Money Market Account</option>
                                    <option value="Certificate of Deposit">Certificate of Deposit</option>
                                    <option value="Retirement Account">Retirement Account</option>
                                    <option value="Escrow Account">Escrow Account</option>
                                    <option value="Non Residence">Non Residence</option>
                                    <option value="Investment Account">Investment Account</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Add Account</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include $base_url . 'app/includes/footer.php'; ?>