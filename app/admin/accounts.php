<?php
require_once '../../config/app.php';

if(isset($_SESSION['ADMIN_ID'])) {
    $admin_id = $_SESSION['ADMIN_ID'];

    $admin = R::load('admins', $admin_id);

    if(isset($_GET['user'])){
        $user = R::load('users', $_GET['user']);
    }

    if(isset($_GET['delete'])) {
        $account = R::load('accounts', $_GET['delete']);

        if(R::trash($account)) {
            $flash_message = 'Account successfully deleted';
        }
    }

    // List all accounts that belongs to a user
    $accounts = R::findAll('accounts', 'user_id = ?', [$user->id]);

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
        <i class="fas fa-user-lock"></i> Accounts for <?= $user->name; ?>
        <span class="pull-right">
                <a href="new_account.php" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> ADD ACCOUNT</a>
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

                            <?php if(count($accounts)): ?>
                                <table class="min-w-full">
                                    <thead>
                                    <tr>
                                        <th class="px-6 py-5 border-b border-gray-200 bg-gray-50 text-left text-xl leading-4 font-medium text-gray-700 uppercase tracking-wider p-4">
                                            Account Details
                                        </th>
                                        <th class="px-6 py-5 border-b border-gray-200 bg-gray-50 text-left text-xl leading-4 font-medium text-gray-700 uppercase tracking-wider p-4">
                                            Account Balance
                                        </th>
                                        <th class="px-6 py-5 border-b border-gray-200 bg-gray-50 text-left text-xl leading-4 font-medium text-gray-700 uppercase tracking-wider p-4">
                                            Status
                                        </th>
                                        <th class="px-6 py-5 border-b border-gray-200 bg-gray-50 text-left text-xl leading-4 font-medium text-gray-700 uppercase tracking-wider p-4">
                                            Account Type
                                        </th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
                                    </tr>
                                    </thead>
                                    <?php foreach ($accounts as $account): ?>
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
                                                            <?= $account->number; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <div class="text-xl leading-5 font-medium text-gray-900">
                                                    $<?= $account->balance; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <?php if($account->is_active): ?>
                                                    <span class="px-2 inline-flex text-xl leading-5 font-semibold rounded-sm bg-green-100 text-green-800 p-2">
                                                    Active
                                                </span>
                                                <?php else: ?>
                                                    <span class="px-2 inline-flex text-xl leading-5 font-semibold rounded-sm bg-red-100 text-red-800 p-2">
                                                    Inactive
                                                </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <div class="text-xl leading-5 font-medium text-gray-900">
                                                    <?= $account->type; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-xl leading-5 font-medium">
                                                <div>
                                               <span style="width: 20px;">
                                                   <a href="transactions.php?account=<?= $account->id; ?>" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150"><i class="fas fa-file-invoice-dollar" style="margin-right: 10px"></i> View Transactions</a>
                                               </span>
                                                    <span>
                                                   <a href="edit_account.php?account=<?= $account->id; ?>" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150"><i class="fas fa-edit" style="margin-right: 10px"></i> Edit Account</a>
                                               </span>
                                                    <span>
                                                   <a href="accounts.php?user=<?= $user->id; ?>&delete=<?= $account->id; ?>" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150"
                                                      onclick="return confirm('Do you want to delete this account?')">
                                                       <i class="fas fa-trash" style="margin-right: 10px"></i>
                                                       Delete Account
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
                                        This user doesn't have any accounts yet <br>
                                        Create an account to get started
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
