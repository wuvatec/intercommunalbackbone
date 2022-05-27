<?php

require_once '../../config/app.php';

if(isset($_SESSION['USER_ID'])) {
    $user = R::load('admins', $_SESSION['USER_ID']);
} else {
    // Redirect user to login
    header('location: layout.php');
}

?>
<!DOCTYPE html>
<html lang="en">
  <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?php require_once '../../resources/inc/_meta.inc'; ?>
    <title>Account Summary</title>

    <?php require_once '../../resources/inc/_styles.inc'; ?>
    
    <script src="https://kit.fontawesome.com/ebe887afc8.js" crossorigin="anonymous"></script>

  </head>
  
  </head>
  <body>
    <?php require_once 'render/_header.inc'; ?>

    <div class="container">
      <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Account Summary as of &#8594; <?php echo date('d/m/Y h:m:s'); ?></h3>
      </div>
      <div class="panel-body">
        <div class="row">
      <div class="col-md-4">
        <h4><span class="fa fa-user"></span> Account Details</h4>
        <?php if($user->id != 8): ?>
            <p><img src="<?php echo $account->img_path; ?>" width="300" /></p>
        <?php endif; ?>
	    <p><?php echo 'Account Name: ' . $account->account_name; ?></p>
	    <p><?php echo 'Email on File: ' . $user->email_address; ?></p>
	    <p><?php echo 'Mobile on File: ' . $user->mobile_number; ?></p>
	    <!-- <p><?php //echo $account->account_name; ?></p> -->
      </div>  <!-- /end colum 4 --> 
      <div class="col-md-4">
      	<h4><span class="fa fa-bank"></span> Account Summary</h4>
      	<ul class="list-group">
          <!-- <li class="list-group-item active"><a href="dashboard.php"><span class="glyphicon glyphicon-"></span> Dashboard</a></li> -->
          <li class="list-group-item"><span class="glyphicon glyphicon-"></span><span class="label label-info"> Account Name:</span> <?php echo $account->account_name; ?></li>
          <li class="list-group-item"><span class="glyphicon glyphicon-"></span> <span class="label label-info">Account Number:</span> <?php echo $account->account_number; ?></li>
          <li class="list-group-item"><span class="glyphicon glyphicon-"></span> <span class="label label-info">Account Type:</span> <?php echo $account->account_type; ?></li>
          <li class="list-group-item"><span class="glyphicon glyphicon-"></span> <span class="label label-info">Current Balance:</span> <?php echo '$' . number_format($account->balance, 2, '.',','); ?></li>
          <li class="list-group-item"><span class="glyphicon glyphicon-"></span> <span class="label label-info">Avalaible Balance:</span> <?php echo '$' . number_format($account->balance, 2, '.',','); ?></li><li class="list-group-item"><span class="glyphicon glyphicon-"></span> <span class="label label-info">Account Created On:</span> <?php echo $account->dated; ?></li>
          
        </ul>
        <p>
          <?php $deposit = Deposit::find_by_user_id($user->id); ?>
          <?php if ($deposit != null): ?>
            <a href="deposit.php" class="btn btn-success">Check Your Deposited Items with Us.</a>
          <?php else: ?>
            <!--<span>
              <a href="#" class="btn btn-danger">You haven't deposited anything for safekeeping.</a>
            </span>-->
          <?php endif; ?>
        </p>
      </div> <!-- /end column 4 -->
      <div class="col-md-4">
        <h4><span class="fa fa-database"></span> Recent Transactions</h4>
        <p></p>
        <?php $transactions = Transaction::find_by_sql("SELECT * FROM transactions WHERE uid='$user->id' ORDER BY date DESC"); ?>
          <?php if ($transactions != null ): ?>
            <div>
              <table class="table table-striped">
                <thead>
                  <td>TN</td>
                  <!-- <td>Bank</td> -->
                  <td>Amt</td>
                  <td>Status</td>
                  <td>Date</td>
                  <!-- <td></td> -->
                </thead>
                <?php foreach($transactions as $transaction): ?> 
                  <tr>
                    <td><?php echo $transaction->t_number; ?> </td>
                    <td><?php echo '$'.number_format($transaction->amount, 2, '.',','); ?></td>
                    <td>
                      <?php if($transaction->completed == 1): ?>
                        <span style="color: #00B545"><small>Completed</small></span>
                      <?php else: ?>
                        <span style="color: #f00"><small>Pending</small></span>
                      <?php endif; ?>
                    </td>
                    <td><?php echo date('m/d/Y', $transaction->date); ?> </td>
                    <!-- <td>
                      <?php //if($transaction->completed == 1): ?>
                        <a href="#"></a>
                      <?php //else: ?>
                        <a href="transfer.php?transfer=continue_transfer"><i class="fa fa-reply"></i></a>
                      <?php // endif; ?>
                    </td> -->
                    
                  </tr>
                <?php endforeach; ?>
              </table>
              <!-- <p>Click on the blue arrow <i style="color: #23527C" class="fa fa-reply"></i> on the right of listed transfers to complete transaction. Or make a new transfer below. By using the Quick Transfer link below.</p> -->
            </div>
          <?php else: ?>
            <div>
               You haven't made any transfers yet.
            </div>
          <?php endif; ?>
          <br>
          <br>
          <p>
          	<a class="btn btn-info btn-block" href="transfer.php?transfer=new_transfer">QUICK TRANSFER</a>
          </p>
      </div><!-- /end column 4 -->
    </div><!-- /row -->
      </div>
    </div>
    </div>
    <?php require_once 'render/_footer.inc'; ?>
  </body>
</html>
