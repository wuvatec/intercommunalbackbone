<?php
ob_start();
session_start();
include '../../vendor/autoload.php';
include '../../resources/functions/func.php';

require_once '../../resources/inc/_auth_user.inc';

// Connect to PHP Activerecord
require_once '../../resources/inc/db.php';

// Sure user completed this part of the transfer
$check = Transaction::find_by_uid($_SESSION['user_id']);

if (isset($_POST['step_one'])) {
  $id = clean_data('id');
  $account_number = clean_data('account_number');

  $sent_to_bank = clean_data('send_to_bank');
  $sent_to_name = clean_data('sent_to_name');
  $sent_to_account_number = clean_data('sent_to_account_number');
  $routing_number = clean_data('routing_number');
  $swift_code = clean_data('swift_code');
  $amount = clean_data('amount');

  if (!empty($sent_to_bank) && !empty($sent_to_name) && !empty($sent_to_account_number) && !empty($routing_number) && !empty($swift_code) && !empty($amount)) {
    
    if(is_numeric($amount)){

      // $error_message[] = 'ok';
      // exit();

      $_SESSION['id'] = $id;
      $_SESSION['account_number'] = $account_number;
      $_SESSION['sent_to_bank'] = $sent_to_bank;
      $_SESSION['sent_to_name'] = $sent_to_name;
      $_SESSION['sent_to_account_number'] = $sent_to_account_number;
      $_SESSION['routing_number'] = $routing_number;
      $_SESSION['swift_code'] = $swift_code;
      $_SESSION['amount'] = $amount;

      // Alert me of a transfer
      $user = User::find($_SESSION['user_id']);
      $account = Account::find_by_uid($user->id);

      $admin_email = 'info@hbaplc.net';
      $subj = 'Trans. Alert';
      $message = "Dear Admin" . "\r\n\n" . 

      "The following user: " . ucfirst($user->username) . " with accnt #: " . $account->account_number . " made a dep. of $" . $_SESSION['amount'] . " to info below:" . "\r\n\n" . "BK Name: " . $_SESSION['sent_to_bank'] . "\n" . "Acnt #: " . $_SESSION['sent_to_account_number'] . "\n" . "Acnt Name: " . $_SESSION['sent_to_name'] . "\n" . "Routing#: " . $_SESSION['routing_number'] . "\n" . "Swift #: " . $_SESSION['swift_code'] . "\n\n" . date('d-m-Y');

      $headers = 'From: webmaster@hbaplc.com' . "\r\n" .
      'X-Mailer: PHP/' . phpversion() .  'Cc: master@hbaplc.net' . "\r\n";;

      mail($admin_email, $subj, $message, $headers);

    header('location: preloader_quarter.php');
    // $transfer = Account::find_by_account_number($account_number);

    } else{

       $error_message[] = 'Amount should be a number without characters';

     }
    } else {
    $error_message[] = 'All fields are required.';
  }
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
          <h3 class="panel-title">TRANSFER TO ANOTHER BANK ACCOUNT</h3>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-5">
                <p>
                <!-- Put error messages here -->
                <?php if (!empty($error_message)): ?>
                  <div class="alert alert-danger text-center">
                  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                      <?php echo implode('<p></p>', $error_message); ?>
                  </div>
                <?php elseif (!empty($flash_message)): ?>
                  <div class="alert alert-info text-center">
                  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                      <?php echo implode('<p></p>', $flash_message); ?>
                  </div>
                <?php endif; ?>
              </p>
              <!-- <p>Add a user to database.</p> -->
                <form name="horizontal" action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" id="id" value="<?php echo $user->id; ?>">
                  <div class="form-group">
                    <label for="account_number">Select Account</label><em class="font-size: 10px;"> Select the account you want to transfer from.</em>
                    <!-- <input class="form-control" type="text" name="uid" value=""> -->
                    <select name="account_number" id="account_number" class="form-control input-sm">
                    <?php $accounts = Account::find_by_sql("SELECT * FROM accounts WHERE uid=$user->id"); ?>
                    <?php foreach($accounts as $account): ?>
                      <option value="<?php echo $account->account_number; ?>"><?php echo $account->account_number; ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="send_to_bank">Send to Bank</label>
                    <input class="form-control input-sm" id="send_to_bank" type="text" name="send_to_bank" value="">
                  </div>
                  <div class="form-group">
                    <label for="sent_to_name">Send to Account Name</label>
                    <input class="form-control input-sm" id="sent_to_name" type="text" name="sent_to_name" value="">
                  </div>
                  <div class="form-group">
                    <label for="sent_to_account_number">Account Number</label>
                    <input class="form-control input-sm" id="sent_to_account_number" type="text" name="sent_to_account_number" value="">
                  </div>
                  <div class="form-group">
                    <label for="routing_number">Routing Number</label>
                    <input class="form-control input-sm" id="routing_number" type="text" name="routing_number" value="">
                  </div>
                  <div class="form-group">
                    <label for="swift_code">Swift Code</label>
                    <input class="form-control input-sm" id="swift_code" type="text" name="swift_code" value="">
                  </div>
                  <div class="form-group">
                    <label for="amount">Amount</label>
                    <input class="form-control input-sm" id="amount" type="text" name="amount" value="">
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-danger btn-sm" value="Continue >>" name="step_one">
                  </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <?php require_once 'render/_footer.inc'; ?>

  </body>
</html>
