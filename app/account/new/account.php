<?php

require '../../../config/app.php';



// User
if(isset($_SESSION['USER'])) {


    $user = R::load('users', $_SESSION['USER']);

    if(strtolower(basename($_SERVER['PHP_SELF'])) === 'account.php') {
        $transactions = R::findAll('transactions', 'user_id = ? ORDER BY transfer_date DESC', [$user->id]);
    }

    $adminProfile = R::load('admins', DEFAULT_ADMIN_ID);

    if (isset($_GET['account'])) {
       
        $account = R::load('accounts', $_GET['account']);

        // Transactions related to account
        $transactions = R::findAll('transactions', 'account_id = ? ORDER BY transfer_date DESC', [$account->id]);
    }

    // Accounts related to user
    $accounts = R::findAll('accounts', 'user_id = ?', [$user->id]);

} else {
    // Redirect user to login
    header('location: layout.php');
}

function sum_pending_transaction_amount($transactions) {
    $sum = 0;
    foreach ($transactions as $transaction) $sum += $transaction->amount;
    return $sum;
}

?>

<?php require $base_url . 'app/account/new/layouts/head.php'; ?>

<!-- component -->
<div class="w-full flex flex-row flex-wrap">


    <div class="w-full h-screen flex flex-row flex-wrap justify-center ">

            <?php include $base_url . 'app/account/new/layouts/navigation.php'; ?>

        </div>

        <!-- End Navbar -->

        <div class="w-full md:w-3/4 lg:w-4/5 p-5 md:px-12 lg:24 h-full overflow-x-scroll antialiased mt-16 md:mt-2">

            <div class="bg-white w-full mt-8">
                <div class="flex flex-row justify-between">
                    <div class="flex flex-col top-heading">
                        <span class=" text-4xl text-gray-700">
                            <span>
                                <?php echo (isset($_GET['account']))? $account->type . " <small class='text-gray-500 text-sm'>" . $account->number . "</small>": 'Accounts'; ?>
                            </span>
                        </span>

                        <div class="flex flex-row border-b border-dashed md:w-full pb-5 block">
                            <span class="text-gray-500 mt-5 mr-10">
                                <?php
                                echo (isset($_GET['account']))
                                    ? 'Available balance: ' . "<span class='text-gray-900'>" . interpret_currency($adminProfile->currency) . number_format($account->balance, 2) . "</span>"
                                    : 'All accounts for ' ."<span class='text-gray-900'>" . $user->name . "</span>";
                                ?>
                            </span>

                            <span class="text-gray-500 mt-5">
                                <?php
                                echo (isset($_GET['account']))
                                    ? 'Present balance: ' . "<span class='text-gray-900'>" . interpret_currency($adminProfile->currency) . number_format($account->balance + $account->pending_balance, 2) . "</span>"
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

            <?php if(isset($_GET['fail']) && $_GET['fail'] == 1): ?>

                <div class="w-full mb-5 bg-red-100 text-red-700 px-3 py-5 font-semibold">
                    <?php
                        echo "<i class=\"fas fa-times-circle\"></i> " . "You can't transfer from an undue Investment";
                        unset($_SESSION['ERROR']);
                    ?>
                </div>

            <?php endif; ?>

            <div class="mt-3 md:flex md:flex-col">


                <?php if (!isset($_GET['account'])): ?>
                <div class="bg-white mt-3">
                    <div class="md:flex md:flex-row justify-between">

                        <div class="md:w-1/2">
                            <h3 class="mb-3 sub-heading">Accounts</h3>
                            <div class="flex flex-col p-5 rounded-md mr-5" style="background-color: #F9F9F9">

                                <?php if (!empty($accounts)): ?>
                                    <?php foreach ($accounts as $account): ?>
                                        <div class="flex flex-row justify-between mb-5">
                                            <div class="flex flex-col">
                                                <span class="sub-heading"><?php echo $account->type; ?></span>
                                                <small class="text-gray-600"><?php echo $account->number; ?></small>
                                            </div>
                                            <div class="sub-heading">
                                                <?php echo interpret_currency($adminProfile->currency); ?> <?php echo number_format($account->balance, 2); ?>
                                            </div>
                                            <div class="sub-heading text-right">
                                                <a href="account.php?account=<?php echo $account->id; ?>" class="text-orange-600 hover:text-orange-500"><small>View Details</small></a>
                                            </div>
                                        </div>

                                    <?php endforeach; ?>
                                <?php else: ?>

                                    You have no accounts. Contact your account manager

                                <?php endif; ?>

                            </div>

                        </div>
                    </div>

                    <?php include $base_url . 'app/account/new/layouts/transactions.php'; ?>

                </div>

                <?php else: ?>

                <!-- Account details page -->
                <div class="mt-10">
                    <div class="flex flex-row">
<!--                        <div class="w-1/2">-->
<!--                            <a class="bg-orange-600 rounded-sm shadow px-4 text-white py-3 font-semibold" href="">Statement</a>-->
<!--                        </div>-->
                        <div class="w-60">
                            <a
                                    class="bg-orange-600 rounded-sm shadow px-4 text-white py-3 font-semibold"
                                    href="transfer.php?account=<?php echo $account->id; ?>">Transfer Money</a>
                        </div>
                    </div>

                    <div class="mt-32">
                        <?php include $base_url  . 'app/account/new/layouts/transactions.php'; ?>
                    </div>
                </div>

                <?php endif; ?>


            <?php include $base_url . 'app/account/new/layouts/sub_footer.php'; ?>




        </div>

        <?php require $base_url . 'app/account/new/layouts/footer.php'; ?>
