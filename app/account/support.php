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
    <title>Support</title>

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
          <h3 class="panel-title">Support</h3>
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
        <p>
          <a href="add_support_message.php" class="btn btn-primary btn-sm" style="margin-top: 10px; margin-bottom: 10px;">Send Support Message</a>
        </p>
          <?php $supports = Support::find_by_sql("SELECT * FROM supports WHERE uid=10"); ?>
          <?php //var_dump($supports); die(); ?>
          <?php if ($supports != null ): ?>
            <div>
              <table class="table table-striped">
                <thead>
                  <td>Message</td>
                  <td>Priority</td>
                  <td>Date</td>
                  <td>Action</td>
                </thead>
                <?php foreach($supports as $support): ?> 
                  <tr>
                    <td><?php echo $support->message; ?> </td>
                    <td>
                      <?php if ($support->priority == 1): ?>
                        <span class="label label-danger">High</span>
                      <?php elseif($support->priority == 2): ?>
                        <span class="label label-warning">Medium</span>
                      <?php else: ?>
                        <span class="label label-success">Low</span>
                      <?php endif; ?>
                    </td>
                    <td><?php echo date('m/d/Y', $support->date); ?> </td>
                    <td>
                      <a href="send_reply.php?uid=<?php echo $support->uid; ?>" class="btn btn-success btn-sm" title="Send Reply"><i class="fa fa-paper-plane"></i></a>
                      <a href="delete_reply.php?uid=<?php echo $support->id; ?>" class="btn btn-danger btn-sm" title="Delete Message"><i class="fa fa-trash"></i></a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </table>
            </div>
          <?php else: ?>
            <div>
               You have not support message here.  <a href="add_support_message.php" class="btn btn-info btn-sm">Client here to send a support message to your account manager.</a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    
    <?php require_once 'render/_footer.inc'; ?>
  </body>
</html>
