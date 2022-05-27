<?php
ob_start();
session_start();
include '../../vendor/autoload.php';
include '../../resources/functions/func.php';

require_once '../../resources/inc/_auth_user.inc';

// Connect to PHP Activerecord
require_once '../../resources/inc/db.php';

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once '../../resources/inc/_meta.inc'; ?>
    <title>Transaction</title>

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
          <h3 class="panel-title">Transactions</h3>
        </div>
        <div class="panel-body">
        <!-- <p>
          <a href="add_support_message.php" class="btn btn-primary btn-sm" style="margin-top: 10px; margin-bottom: 10px;">Send Support Message</a>
        </p> -->
          <?php $transactions = Transaction::find_by_sql("SELECT * FROM transactions WHERE uid = $user->id ORDER BY date DESC"); ?>
          <?php if ($transactions != null ): ?>
            <div>
              <table class="table table-striped">
                <thead>
                  <td>#</td>
                  <td>UID</td>
                  <td>TN</td>
                  <td>Bank Name</td>
                  <td>Account Name</td>
                  <td>Account Number</td>
                  <td>Status</td>
                  <td>Amount</td>
                  <td>Date</td>
                </thead>
                <?php foreach($transactions as $transaction): ?> 
                  <tr>
                    <td><?php echo $transaction->id; ?></td>
                    <td><?php echo $transaction->uid; ?></td>
                    <td><?php echo $transaction->t_number; ?></td>
                    <td><?php echo $transaction->bank_name; ?> </td>
                    <td><?php echo $transaction->account_name; ?> </td>
                    <td><?php echo $transaction->account_number; ?> </td>
                    <td>
                      <?php if ($transaction->completed == 1): ?>
                        <span class="label label-success">Completed</span>
                      <?php else: ?>
                        <span class="label label-danger">Pending</span>
                      <?php endif; ?>
                    </td>
                    <td><?php echo '$'. number_format($transaction->amount, 2, '.',','); ?></td>
                    <td><?php echo date('m/d/Y', $transaction->date); ?> </td>
                    
                  </tr>
                <?php endforeach; ?>
              </table>
            </div>
          <?php else: ?>
            <div>
               No user has made a transaction from their account. Unfortunately only users can make a transfer.
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    
    <?php require_once 'render/_footer.inc'; ?>
  </body>
</html>
