<?php
ob_start();
session_start();
include '../../vendor/autoload.php';
include '../../resources/functions/func.php';

require_once '../../resources/inc/_auth_user.inc';

// Connect to PHP Activerecord
require_once '../../resources/inc/db.php';

// Not secured using http_referer, i would still go ahead to use it as this app is personal
// Redirect user to support where user was intended to come from, when use tries to access
if (!isset($_SERVER['HTTP_REFERER'])) {
  header('location: transfer.php');
}

if (isset($_GET['transaction']) && isset($_GET['amount'])) {
  $transaction = $_GET['transaction'];
  $amount = $_GET['amount'];
} else {
  header('location: account_summary.php');
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once '../../resources/inc/_meta.inc'; ?>
    <title>Transfer</title>

    <?php require_once '../../resources/inc/_styles.inc'; ?>
  </head>
  <body>
    <?php require_once 'render/_header.inc'; ?>
    <!-- <div class="container-minimized heading">
      <h1>Support</h1>
    </div>/container-minimized
    <div class="container-minimized">
      s
    </div> -->

    <div class="container">
      <div class="panel panel-default">
        <div class="panel-heading">
        <?php $code = Code::find_by_id(4); ?>
          <h3 class="panel-title">Thank You</h3>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-5">
                
              <p>
              	<?php 
              		$transaction = Transaction::find_by_t_number($transaction); 
              		$transaction->completed = 1;
              		$transaction->save();

              		$final = Account::find_by_uid($user->id);
                  $final_balance = $user->final_balance - $amount;
              		$final->balance = $final_balance;
              		$final->save();
              	?>

                Thank you for your transaction. Your transfer amount of $<?php echo number_format($amount, ',','.'); ?> was successful. Your Transaction Number is <strong><?php echo $_SESSION['t_number']; ?></strong>. Please note is would take three business working days for funds to reflect in recepients Account. Transfers to local banks are sent instantly. You Current balance is now <strong><?php echo $final_balance; ?></strong>
              </p>
              
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <?php require_once 'render/_footer.inc'; ?>

  </body>
</html>
