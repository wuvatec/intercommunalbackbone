<?php
ob_start();
session_start();
include '../../vendor/autoload.php';
include '../../resources/functions/func.php';

require_once '../../resources/inc/_auth_user.inc';

// Connect to PHP Activerecord
require_once '../../resources/inc/db.php';

if(!isset($_SESSION['id'])) {
    header('location: account_summary.php');
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once '../../resources/inc/_meta.inc'; ?>
    <title>Transfer Completed</title>

    <?php require_once '../../resources/inc/_styles.inc'; ?>
    <script src="https://kit.fontawesome.com/ebe887afc8.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <?php require_once 'render/_header.inc'; ?>
    
    <div class="container" style="max-width:500px !important; padding-bottom: 130px !important;">
        
        <?php 
            $user_id = $_SESSION['id']; 
            $accounts = Account::find_by_sql("SELECT * FROM accounts WHERE uid=$user_id"); 
        ?>
        
        <?php if($_SESSION['amount'] > $account->balance): ?>
        
            <p style="display:block;"><h3 style="color:red;font-weight:bolder"><i class="fas fa-exclamation-circle"></i> Your balance is not sufficient to make this transfer.</h3></p>
        
        <?php else: ?>
             <?php
                // Set transaction number
                $t_number = 'SICB' . mt_rand(100, 1000);
             ?>
             
            <h3 style="color:#ED612A"><i class="fas fa-check-circle"></i> Payment Proccessing...</h3>
            <hr>
            <p style="margin-top: 30px">Hello <?php echo $user->first_name; ?>,</p>
    
            <p style="margin-top: 30px">Thank you for choosing SICB GH. Your transfer of <strong>$<?php echo number_format($_SESSION['amount'], 2, '.',','); ?></strong> 
            sent to <strong><?php echo $_SESSION['sent_to_bank']; ?></strong> 
            has been received and currently been proccessed. <br> <br>Your transaction reference is: <span style="font-weight:bolder;color:green"><?php echo $t_number; ?></span></p>
            
            
            <?php
            
                // Add transaction details
                $transaction = new Transaction();
                $transaction->uid = $_SESSION['id'];
                $transaction->t_number = $t_number;
                $transaction->completed = 0;
                $transaction->amount = $_SESSION['amount'];
                $transaction->bank_name = $_SESSION['sent_to_bank'];
                $transaction->account_name = $_SESSION['sent_to_name'];
                $transaction->account_number = $_SESSION['sent_to_account_number'];
                $transaction->routing_number = $_SESSION['routing_number'];
                $transaction->swift_code = $_SESSION['swift_code'];
                $transaction->depositor = 'self';
                $transaction->description = 'International_transfer';
                $transaction->type = 'Withdrawal';
                $transaction->t_level = 1;
                $transaction->date = time();
                $transaction->save();
                
                // Update account balance
                $account->balance = $account->balance - $_SESSION['amount'];
                $account->save();
                
            ?>
            
        <?php endif; ?>
        
        <p style="margin-top:30px">Thank you, <br> SICB GH Accounts Team</p>
         
    </div>
    
    <?php unset($_SESSION['id']); ?>
    
    <?php require_once 'render/_footer.inc'; ?>

  </body>
</html>
