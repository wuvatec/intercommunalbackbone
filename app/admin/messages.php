<?php
require_once '../../config/app.php';

if(isset($_SESSION['ADMIN_ID'])) {
    $admin_id = $_SESSION['ADMIN_ID'];

    $admin = R::load('admins', $admin_id);
    
    $messages = R::findAll('messages', 'ORDER BY id DESC');
    
} else {
    // Redirect user to login
    header('location: login.php');
}


?>


<?php require $base_url . 'app/includes/head.php'; ?>

<?php require $base_url . 'app/includes/nav.php'; ?>


<!-- <div class="container-minimized heading">
  <h1>Support</h1>
</div>/container-minimized
<div class="container-minimized">
  s
</div> -->

<div class="container">
    <h1 style="margin: 30px 0">
        <i class="fas fa-comment-alt"></i> Messages
        <span class="pull-right">
                <a href="send_message.php" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Send Message</a>
            </span>
    </h1>
    <hr>
    <div class="panel panel-default" style="border: none">
        <div class="panel-body">
            <p>
                <?php if (isset($_SESSION['FLASH_USER_CREATED'])): ?>
            <div class="alert alert-success text-center">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <?php echo $_SESSION['FLASH_USER_CREATED']; ?>
                <?php unset($_SESSION['FLASH_USER_CREATED']); ?>
            </div>
            <?php endif; ?>
            <?php if (isset($flash_message)): ?>
                <div class="alert alert-success text-center">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <?php echo $flash_message; ?>
                </div>
            <?php endif; ?>
            </p>

            <div class="mt-20 text-5xl">
                <div class="flex flex-col">
                    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                        <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">

                            <?php if(count($messages)): ?>
                                <table class="min-w-full">
                                    <thead>
                                    <tr>
                                        <th class="px-6 py-5 border-b border-gray-200 bg-gray-50 text-left text-xl leading-4 font-medium text-gray-700 uppercase tracking-wider p-4">
                                            Ticket #
                                        </th>
                                        <th class="px-6 py-5 border-b border-gray-200 bg-gray-50 text-left text-xl leading-4 font-medium text-gray-700 uppercase tracking-wider p-4">
                                            Enquiry
                                        </th>
                                        <th class="px-6 py-5 border-b border-gray-200 bg-gray-50 text-left text-xl leading-4 font-medium text-gray-700 uppercase tracking-wider p-4">
                                            User
                                        </th>
                                        <th class="px-6 py-5 border-b border-gray-200 bg-gray-50 text-left text-xl leading-4 font-medium text-gray-700 uppercase tracking-wider p-4">
                                            Date
                                        </th>
                                    </tr>
                                    </thead>
                                    
                                    
                                    <?php foreach ($messages as $message): ?>
                                        <tbody>
                                        <tr>
                                            <td class="px-6 py-4 border-b border-gray-200">
                                                <div class="text-xl font-medium text-gray-900">
                                                    <?php echo $message->ticket_number; ?>
                                                </div>
                                            </td>
                                           <td class="px-6 py-4 border-b border-gray-200">
                                                <div class="text-xl font-medium text-gray-900">
                                                    <?php echo $message->enquiry; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 border-b border-gray-200">
                                                <div class="text-xl font-medium text-gray-900">
                                                    <?php $get_user = R::findOne('users', 'id = ?', [$message->user_id]); ?>
                                                    <?php echo $get_user->name; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 border-b border-gray-200">
                                                <div class="text-xl font-medium text-gray-900">
                                                    <?php echo $message->created_at; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    <?php endforeach; ?>


                                </table>

                            <?php else: ?>

                                <div class="text-center mt-32 text-gray-400 text-gray-600">
                                        <span class="text-5xl">
                                            ¯\_(ツ)_/¯
                                        </span>
                                    <p class="mb-32 mt-4 text-2xl">
                                        You don't have any messages yet
                                    </p>
                                </div>

                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <?php include $base_url . 'app/includes/footer.php'; ?>
