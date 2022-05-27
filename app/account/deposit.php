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
    <title>Deposits</title>

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
        <div class="panel-heading" style="background-color: #157254; color: #FFF">
          <h3 class="panel-title">Your Deposited Items</h3>
        </div>
        <div class="panel-body">

        <p>
          <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-success text-center">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <?php echo $_SESSION['flash_message']; ?>
                <?php unset($_SESSION['flash_message']); ?>
            </div>
          <?php endif; ?>
        </p>
       <!--  <p>
          <a href="add_support_message.php" class="btn btn-primary btn-sm" style="margin-top: 10px; margin-bottom: 10px;">Send Support Message</a>
        </p> -->
        <?php echo $sid = $user->id; ?>
          <?php $deposits = Deposit::find_by_sql("SELECT * FROM deposits WHERE user_id='$sid'"); ?>
          <!-- <?php $user = User::all(); ?> -->
          <?php if ($deposits !== null ): ?>
            <div>
              <table class="table table-striped">
                <thead>
                  <td>#</td>
                  <td>Depositor</td>
                  <td>Security Code</td>
                  <td>Nationality</td>
                  <td>Item Dep.</td>
                  <td>Quantity</td>
                  <td>Weight</td>
                  <td>Date Dep.</td>
                  <td>Purpose</td>
                  <!-- <td>Action</td> -->
                </thead>
                <?php foreach($deposits as $deposit): ?> 
                  <tr>
                    <td><?php echo $deposit->id; ?></td>
                    <td><?php echo $deposit->depositor_name; ?></td>
                    <td><?php echo $deposit->security_code; ?> </td>
                    <td><?php echo $deposit->nationality; ?> </td>
                    <td><?php echo $deposit->item_deposited; ?> </td>
                    <td><?php echo $deposit->quantity . ' ' . $deposit->deposit_type . '(s)'; ?> </td>
                    <td><?php echo $deposit->weight . ' KG'; ?> </td>
                    <!-- <td></td> -->
                    <td><?php echo $deposit->deposit_date; ?> </td>
                    <td><?php echo $deposit->purpose; ?> </td>
                    
                  </tr>
                <?php endforeach; ?>
              </table>
            </div>
          <?php else: ?>
            <div>
               You have not added a user or no user have been registered.  <a href="new_user.php" class="btn btn-info btn-sm">Click here to add a new user</a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    
    <?php require_once 'render/_footer.inc'; ?>
  </body>
</html>
