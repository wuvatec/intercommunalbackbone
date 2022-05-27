<?php

require '../../../config/app.php';

// User
if(isset($_SESSION['USER'])) {
    $user = R::load('users', $_SESSION['USER']);

    $adminProfile = R::load('admins', DEFAULT_ADMIN_ID);

    if (isset($_GET['account'])) {
        $account = R::load('accounts', $_GET['account']);

        // Transactions related to account
        $transactions = R::findAll('transactions', 'account_id = ?', [$account->id]);
    }

    // Accounts related to user
    $accounts = R::findAll('accounts', 'user_id = ?', [$user->id]);



} else {
    // Redirect user to login
    header('location: layout.php');
}

?>

<?php require $base_url . 'app/account/new/layouts/head.php'; ?>

<!-- component -->
<div class="w-full flex flex-row flex-wrap">


    <div class="w-full h-screen flex flex-row flex-wrap justify-center">

            <?php include $base_url . 'app/account/new/layouts/navigation.php'; ?>

        </div>

        <!-- End Navbar -->

        <div class="w-full md:w-3/4 lg:w-4/5 p-5 md:px-12 lg:24 h-full overflow-x-scroll antialiased mt-16 md:mt-2">

            <div class="bg-white w-full mt-8">
                <div class="flex flex-row justify-between">
                    <div class="flex flex-col top-heading">
                        <span class=" text-4xl text-gray-700">
                            <span>
                                Credit Cards
                            </span>
                        </span>

                        <div class="flex flex-row border-b border-dashed md:w-full pb-5 block">
                            <span class="text-gray-500 mt-5 mr-10">
                                All cards for  <span class='text-gray-900'><?php echo $user->name; ?></span>
                            </span>

                            <span class="text-gray-500 mt-5">
                                <?php
                                echo (isset($_GET['account']))
                                    ? 'Present balance: ' . "<span class='text-gray-900'>" . interpret_currency($adminProfile->currency) . number_format($account->balance, 2) . "</span>"
                                    : ''
                                ?>
                            </span>

                        </div>

                    </div>
                    <div class="flex flex-row">
                        <?php  include $base_url . 'app/account/new/layouts/top_left_nav.php'; ?>
                    </div>
                </div>
            </div>

            <?php include $base_url . 'app/account/new/layouts/alerts.php' ?>

            <div class="mt-3 md:flex md:flex-col">

                You have no credit cards yet. Contact your account manager

                <?php include $base_url . 'app/account/new/layouts/sub_footer.php'; ?>

            </div>

        <?php require $base_url . 'app/account/new/layouts/footer.php'; ?>
