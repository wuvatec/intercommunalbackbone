<?php
require_once '../../config/app.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if(isset($_SESSION['ADMIN_ID'])) {
    $admin_id = $_SESSION['ADMIN_ID'];

    $admin = R::load('admins', $admin_id);
    
    $activities = R::findAll('activities', 'ORDER BY id DESC');


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
        <i class="fas fa-comment-alt"></i> Activities
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

                            <?php if(count($activities) > 0): ?>
                                <table class="min-w-full">
                                    <thead>
                                    <tr>
                                        <th class="px-6 py-5 border-b border-gray-200 bg-gray-50 text-left text-xl leading-4 font-medium text-gray-700 uppercase tracking-wider p-4">
                                            Activity
                                        </th>
                                        <th class="px-6 py-5 border-b border-gray-200 bg-gray-50 text-left text-xl leading-4 font-medium text-gray-700 uppercase tracking-wider p-4">
                                            User
                                        </th>
                                        <th class="px-6 py-5 border-b border-gray-200 bg-gray-50 text-left text-xl leading-4 font-medium text-gray-700 uppercase tracking-wider p-4">
                                            Date
                                        </th>
                                    </tr>
                                    </thead>
                                    
                                    
                                    <?php foreach ($activities as $act): ?>
                                        <tbody>
                                        <tr>
                                            <td class="px-6 py-4 border-b border-gray-200">
                                                <div class="text-xl font-medium text-gray-900">
                                                    <?php echo $act->name; ?>
                                                </div>
                                            </td>
                                           <td class="px-6 py-4 border-b border-gray-200">
                                                <div class="text-xl font-medium text-gray-900">
                                                    <?php
                                                        // get user name
                                                        $setUsername = R::load('users', $act->user_id);
                                                    ?>
                                                    
                                                    <?php echo $setUsername->name; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 border-b border-gray-200">
                                                <div class="text-xl font-medium text-gray-900">
                                                    <?php echo $act->date; ?>
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
                                        You don't have any activities yet
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
