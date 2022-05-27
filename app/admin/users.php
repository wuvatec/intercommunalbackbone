<?php
require_once '../../config/app.php';

if(isset($_SESSION['ADMIN_ID'])) {
    $admin_id = $_SESSION['ADMIN_ID'];

    $user = R::load('admins', $admin_id);

    $users = R::findAll('users', 'ORDER BY 1 DESC');

} else {
    // Redirect user to login
    header('location: login.php');
}

if(isset($_GET['delete'])) {
    $user = R::load('users', $_GET['delete']);

    if(R::trash($user)) {
        $flash_message = 'User successfully deleted';
    }
}


?>

<?php require $base_url . 'app/includes/head.php'; ?>

<?php require $base_url . 'app/includes/nav.php'; ?>


    <div class="container">
        <h1 style="margin: 30px 0">
            <i class="fas fa-user-lock"></i> Users
            <span class="pull-right">
                <a href="new_user.php" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> ADD USER</a>
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

                                <?php if(count($users)): ?>
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
                                                <div>
                                               <span style="width: 20px;">
                                                   <a href="accounts.php?user=<?= $user->id; ?>" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150"><i class="fas fa-file-invoice-dollar" style="margin-right: 10px"></i> Accounts</a>
                                               </span>
                                                    <span>
                                                   <a href="edit_user.php?user=<?= $user->id; ?>" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150"><i class="fas fa-user-edit" style="margin-right: 10px"></i> Edit User</a>
                                               </span>
                                                    <span>
                                                   <a href="users.php?delete=<?= $user->id; ?>" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150"
                                                      onclick="return confirm('Do you want to delete this?')">
                                                       <i class="fas fa-user-minus" style="margin-right: 10px"></i>
                                                       Delete User
                                                   </a>
                                               </span>
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
                                            You don't have any users yet <br>
                                            Add a user to get started
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
