<?php
ob_start();
session_start();
include '../../vendor/autoload.php';
include '../../resources/functions/func.php';

require_once '../../resources/inc/_auth_user.inc';

// Connect to PHP Activerecord
require_once '../../resources/inc/db.php';

if (isset($_POST['add_support'])) {
  $uid = urlencode($_GET['uid']);
  $support_message = clean_data('support_message');
  $priority = clean_data('priority');

  if (!empty($support_message)) {
    $support = new Support();
    $support->uid = $uid;
    $support->message = $support_message;
    $support->priority = $priority;
    $support->date = time();
    $support->save();

    $flash_message[] = 'Support message has been replied';

  } else {
    $error_message[] = 'The support message is required';
  }
}

$uid = User::find($_GET['uid']);

// Not secured using http_referer, i would still go ahead to use it as this app is personal
// Redirect user to support where user was intended to come from, when use tries to access
if (!isset($_SERVER['HTTP_REFERER'])) {
	header('location: support.php');
}



?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once '../../resources/inc/_meta.inc'; ?>
    <title>Support</title>

    <?php require_once '../../resources/inc/_styles.inc'; ?>
  </head>
  <body>
    <?php require_once '../../resources/inc/_header.inc'; ?>
    <!-- <div class="container-minimized heading">
      <h1>Support</h1>
    </div>/container-minimized
    <div class="container-minimized">
      s
    </div> -->

    <div class="container">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Send a support message</h3>
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
              <p>Send a reply to Account Manager</p>
                <form name="horizontal" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                  <div class="form-group">
                    <label for="support_message">Message</label>
                    <textarea class="form-control" rows="5" name="support_message"></textarea>
                  </div>
                  <div class="form-group">
                    <label for="priority">Priority</label>
                    <select id="priority" name="priority" class="form-control">
                      <option value="1">High</option>
                      <option value="2">Medium</option>
                      <option value="3">Low</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-primary btn-sm" value="Send Message" name="add_support">
                  </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <?php require_once '../../resources/inc/_footer.inc'; ?>
  </body>
</html>
