<?php
require_once '../../config/app.php';

if(isset($_SESSION['ADMIN_ID'])) {
    $admin_id = $_SESSION['ADMIN_ID'];

    $user = R::load('admins', $admin_id);

    $users = R::findAll('users');
    
    if(isset($_GET['accountID'])) {
        $account_id = $_GET['accountID'];
        
    }

} else {
    // Redirect user to login
    header('location: login.php');
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $errors = [];

    $amount = htmlspecialchars(strip_tags(trim($_POST['amount'])));
    $transfer_type = htmlspecialchars(strip_tags(trim($_POST['transfer_type'])));
    $transfer_status = htmlspecialchars(strip_tags(trim($_POST['transfer_status'])));
    $user_id = htmlspecialchars(strip_tags(trim($_POST['user_id'])));
    $accnt_id = htmlspecialchars(strip_tags(trim($_POST['accnt_id'])));
    $transfer_date = htmlspecialchars(strip_tags(trim($_POST['transfer_date'])));

    // if(!is_numeric($number)) {
    //     $errors[] = 'Account number must be only digits';
    // }

    if (!is_numeric($amount)) {
        $errors[] = 'Account balance must be digits';
    }

    // if (account_number_exists($number)) {
    //     $errors[] = 'Account number has already been added';
    // }

    if(empty($errors)) {

         $transaction = R::dispense('transactions');
                
            $transaction->user_id = $user_id;
            $transaction->account_id = $accnt_id;
            $transaction->number = substr($user_id . md5(time()), 0, 12);
            $transaction->amount = $amount;
            
            if($_POST['transfer_type'] == 'Credit') {
                // if($_POST['transfer_status'] == "Completed") {
                    
                     $transact = R::findOne('accounts', 'id = ?', [$accnt_id]);
                        $transact->balance = $transact->balance + $amount;
                        R::store($transact);
                    
                    $transaction->credit = 1;
                    $transaction->debit = 0;
                    $transaction->completed = 1;
                    $transaction->description = 'INSTACCOUNT_CREDIT';
                // } else {
                //     $transaction->credit = 1;
                //     $transaction->debit = 0;
                //     $transaction->completed = 0;
                //     $transaction->description = 'INSTACCOUNT_CREDIT';
                // }
                
            } else {
            //   if($_POST['transfer_status'] == "Completed") {
            
                    $transact = R::findOne('accounts', 'id = ?', [$accnt_id]);
                    $transact->balance = $transact->balance - $amount;
                    R::store($transact);
                    
                    $transaction->credit = 0;
                    $transaction->debit = 1;
                    $transaction->completed = 1;
                    $transaction->description = 'INSTACCOUNT_DEBIT';
                // } else {
                //     $transaction->credit = 0;
                //     $transaction->debit = 1;
                //     $transaction->completed = 0;
                //     $transaction->description = 'INSTACCOUNT_DEBIT';
                // }
            }
            
            
            $transaction->transfer_date = $transfer_date;
            
        
        if($transaction = R::store($transaction)) {
            // Add initial transaction if amount is empty
            $_SESSION['FLASH_USER_CREATED'] = 'Transaction successfully added';
            header('location: accounts.php?user=' . $user_id);
               
        }

        echo $errors[] = 'An error occurred!';

    }
}

?>

<?php require $base_url . 'app/includes/head.php'; ?>

<?php require $base_url . 'app/includes/nav.php'; ?>


    <div class="container">
        <h1 style="margin: 30px 0">
            <i class="fas fa-user-lock"></i> New Transaction
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
                        <h4 style="margin-bottom: 10px">Add a transaction to database. <small class="text-red-500">You can add many transactions to an account</small></h4>
                        
                        <form name="horizontal" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                            
                            <input type="hidden" name="accnt_id" value="<?php echo $account_id; ?>">


                            <div class="form-group">
                                <label for="name">Amount</label>
                                <input class="form-control" id="amount" type="text" name="amount" value="<?php echo (isset($amount))? $amount: ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="type">Transfer Date</label>
                                <input class="form-control" id="transfer_date" type="datetime-local" name="transfer_date">
                            </div>
                            <div class="form-group">
                                <label for="type">Select User</label>
                                <select name="user_id" id="user" class="form-control">
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?= $user->id; ?>"><?= $user->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="type">Transfer Type</label>
                                <select name="transfer_type" id="$transfer_type" class="form-control">
                                    <option value="Credit">Credit</option>
                                    <option value="Debit">Debit</option>
                                </select>
                            </div>
                            <!--<div class="form-group">
                                <label for="type">Transfer Status</label>
                                <select name="transfer_status" id="$transfer_status" class="form-control">
                                    <option value="Completed">Completed</option>
                                    <option value="Pending">Pending</option>
                                </select>
                            </div>-->
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Add Transaction</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include $base_url . 'app/includes/footer.php'; ?>