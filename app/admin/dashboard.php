<?php
require_once '../../config/app.php';

if(isset($_SESSION['ADMIN_ID'])) {
    $admin_id = $_SESSION['ADMIN_ID'];

    $user = R::load('admins', $admin_id);
    $users = R::findAll('users', 'ORDER BY 1 DESC LIMIT 5');
} else {
    // Redirect user to login
    header('location: login.php');
}

?>

<?php require $base_url . 'app/includes/head.php'; ?>

<?php require $base_url . 'app/includes/nav.php'; ?>

<div class="container">
    <div style="margin: 30px 0">
        <i class="fas fa-sign-in-alt"></i> You are logged in as

        <a href="settings.php" class="text-red-500 mr-3">
            <?php echo ucfirst($user->username); ?>
        </a>

        <span class="text-gray-500 add-header-font">
            <a href="<?= 'https://' . $user->domain; ?>" target="_blank">[<?= $user->website_name; ?>]</a>
        </span>
    </div>
    <div class="panel panel-default" style="margin: 50px 0">
        <div class="panel-heading">
            <h3 class="panel-title">Dashboard</h3>
        </div>
        <div class="panel-body">
            <!-- Error message starts here -->
            <p>
                <?php if (isset($_SESSION['success_msg'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success_msg']; ?>
                <?php unset($_SESSION['success_msg']); ?>
            </div>
            <?php endif; ?>
            </p>
            <!-- Error message ends here -->
            <div class="row">
                <div class="col-md-4 capitalize">
                    <ul class="list-group">
                        <!-- <li class="list-group-item active"><a href="dashboard.php"><span class="glyphicon glyphicon-"></span> Dashboard</a></li> -->
                        <li class="list-group-item">
                            <a href="users.php">
                                <i class="fas fa-user-lock"></i> Users / Account
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="notifications.php">
                                <i class="fas fa-poll"></i> Notifications
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="messages.php">
                                <i class="fas fa-comment-alt"></i> Messages
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="logout.php">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- /end colum 6 -->
                <div class="col-md-8">
                    <h4>
                        <span class="fa fa-database" style="color:#1CB929;"></span> Recently Added Users

                        <small class="pull-right">
                            <a href="new_user.php" class="btn btn-primary btn-xs">
                                <i class="fas fa-user-plus"></i> Add a user

                            </a>
                        </small>
                    </h4>
                    <div class="mt-20 text-5xl">
                        <div class="flex flex-col">
                            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                                <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                                    <?php if(sizeof($users) > 0): ?>
                                        <table class="min-w-full">
                                            <thead>
                                            <tr>
                                                <th class="px-6 py-5 border-b border-gray-200 bg-gray-50 text-left text-xl leading-4 font-medium text-gray-700 uppercase tracking-wider p-4">
                                                    Name
                                                </th>
                                                <th class="px-6 py-5 border-b border-gray-200 bg-gray-50 text-left text-xl leading-4 font-medium text-gray-700 uppercase tracking-wider p-4">
                                                    Status
                                                </th>
                                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
                                            </tr>
                                            </thead>
                                            <?php foreach ($users as $user): ?>
                                                <tbody class="bg-white">
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                        <div class="flex items-center">
                                                            <div class="flex-shrink-0 h-10 w-10">
                                                                <img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
                                                            </div>
                                                            <div class="ml-4">
                                                                <div class="text-xl leading-5 font-medium text-gray-900">
                                                                    <?= $user->name; ?>
                                                                </div>
                                                                <div class="text-xl leading-5 text-gray-500 pt-2">
                                                                    <?= $user->email; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                        <?php if($user->is_active): ?>
                                                            <span class="px-2 inline-flex text-xl leading-5 font-semibold rounded-sm bg-green-100 text-green-800 p-2">
                                                    Active
                                                </span>
                                                        <?php else: ?>
                                                            <span class="px-2 inline-flex text-xl leading-5 font-semibold rounded-sm bg-red-100 text-red-800 p-2">
                                                    Inactive
                                                </span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-xl leading-5 font-medium">
                                                        <a href="accounts.php?user=<?= $user->id; ?>" class="text-indigo-600 hover:text-indigo-900"><i class="fas fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            <?php endforeach; ?>
                                        </table>
                                    <?php else: ?>
                                        <span class="p-10 text-2xl">
                                            You haven't yet added a user
                                        </span>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /end column 6 -->
            </div>
            <!-- /row -->
        </div>
    </div>
</div>

<?php include $base_url . 'app/includes/footer.php'; ?>