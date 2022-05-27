<?php

require '../../../config/app.php';

// User
if(isset($_SESSION['USER'])) {
    $user = R::load('users', $_SESSION['USER']);

    $adminProfile = R::load('admins', DEFAULT_ADMIN_ID);

    // Accounts related to user
    $accounts = R::findAll('accounts', 'user_id = ?', [$user->id]);

    // Transactions related to user
    $transactions = R::findAll('transactions', 'user_id = ? ORDER BY transfer_date DESC', [$user->id]);

    // Beneficiaries
    $beneficiaries = R::findAll('beneficiaries', 'user_id = ? ORDER BY created_at DESC limit 4', [$user->id]);

} else {
    // Redirect user to login
    header('location: layout.php');
}

$error = [];

if(isset($_POST['ADD_BENEFICIARY'])) {
    $display_form = true;
    $name = htmlspecialchars(strip_tags(trim($_POST['name'])));
    $bank = htmlspecialchars(strip_tags(trim($_POST['bank'])));
    $account_number = htmlspecialchars(strip_tags(trim($_POST['account'])));
    $routing = htmlspecialchars(strip_tags(trim($_POST['routing'])));
    $user_id = htmlspecialchars(strip_tags(trim($_POST['user_id'])));

    if (empty($name)) {
        $error['name'] = true;
    }

    if (empty($bank)) {
        $error['bank'] = true;
    }

    if (empty($account_number)) {
        $error['account'] = true;
    }

    if (empty($routing)) {
        $error['routing'] = true;
    }

    if (!sizeof($error) > 0) {
        $beneficiary = R::dispense( 'beneficiaries' );
        $beneficiary->name = $name;
        $beneficiary->user_id = $user_id;
        $beneficiary->bank = $bank;
        $beneficiary->account = $account_number;
        $beneficiary->routing = $routing;
        $beneficiary->created_at = new DateTime('now');

        if (R::store($beneficiary)) {
            $success = 'Beneficiary successfully added';
        }

        unset($name, $bank, $account_number, $routing, $display_form);
    }
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

            <div class="bg-white w-full p-5">
                <div class="flex flex-row justify-between">
                    <div class="flex flex-col top-heading">
                        <span class=" text-4xl text-gray-700">
                            <?php echo interpret_currency($adminProfile->currency); ?>
                            <span>
                                <?php echo number_format(sum_account($accounts), 2) ?>
                            </span>
                        </span>
                        <span class="text-gray-500">All account balance in <span class="text-gray-700"><?php echo $adminProfile->currency; ?></span></span>
                    </div>
                    <div class="flex flex-row">
                        <?php include $base_url . 'app/account/new/layouts/top_left_nav.php'; ?>
                    </div>
                </div>
            </div>

            <?php include $base_url . 'app/account/new/layouts/alerts.php' ?>

            <div class="bg-white w-full px-2 py-5">
                <?php include $base_url . 'app/account/new/layouts/account_manager.php'; ?>
            </div>

            <div class="mt-3 md:flex md:flex-col">


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
                                               <a href="account.php?account=<?php echo $account->id; ?>" class="text-pink-600"><small>View details</small></a>
                                           </div>
                                       </div>

                                   <?php endforeach; ?>
                                <?php else: ?>
                                    You have no accounts. Contact your account manager
                               <?php endif; ?>
                           </div>
                       </div>

                       <div class="md:w-1/2 mt-10 md:mt-0">
                           <h3 class="mb-3 sub-heading">Send money to</h3>
                           <div class="p-5 rounded-md mr-5" style="background-color: #F9F9F9">
                               <div class="flex flex-row flex-wrap">

                                   <?php if (!empty($beneficiaries)): ?>
                                        <?php foreach ($beneficiaries as $beneficiary): ?>
                                           <div class="w-1/2 mb-5">
                                               <div class="flex flex-row">
                                                   <div class="flex-shrink-0 h-10 w-10 mr-5">
                                                       <img class="h-10 w-10 rounded-full" src="../../../public/images/user-male.png" alt="" />
                                                   </div>
                                                   <div>
                                                       <div class="sub-heading">
                                                           <?php echo $beneficiary->name; ?>
                                                           <a
                                                               onclick="return confirm('Do you want to delete this beneficiary?')"
                                                               href="delete.php?page=beneficiary&beneficiary=<?php echo $beneficiary->id; ?>"
                                                               class="text-gray-500 hover:text-pink-600">x
                                                           </a>
                                                       </div>
                                                       <div class="">
                                                           <small>
                                                               <p>
                                                                   <?php // echo $beneficiary->bank; ?>
<!--                                                                   <a href="transfer.php?page=transfer_to_beneficiary&beneciary_id=--><?php //echo $beneficiary->id; ?><!--" title="Send money to this user"><i class="far fa-share-square text-gray-500 hover:text-pink-600"></i></a>-->
                                                               </p>
                                                           </small>
                                                       </div>
                                                   </div>
                                               </div>
                                           </div>
                                        <?php endforeach; ?>
                                   <?php else: ?>
                                    <p>
                                        Add a beneficiary to continue
                                    </p>
                                   <?php endif; ?>

                                   <div class="mt-10">
                                       <a href="javascript:void(0)" id="myBtn" class="text-white px-5 py-2 shadow-sm bg-pink-600 rounded-sm hover:bg-pink-700">
                                           <small><i class="fas fa-user-plus"></i></small> Add beneficiary</a>
                                   </div>

<!--                                   Modal -->
                                   <div id="myModal" class="modal" style="border: none; <?php echo (isset($display_form))? 'display: block' : ''; ?> ">

                                       <!-- Modal content -->
                                       <div class="relative modal-content rounded-sm shadow-sm">

                                           <span class="close absolute right-0 -mr-10 text-red-900" >&times;</span>

                                           <div>

                                               <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                                                   <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
                                                   <div class="mb-3">
                                                       <input class="px-4 py-3 bg-gray-100 w-full focus:outline-none" type="text" name="alias" placeholder="Nickname">
                                                   </div>
                                                   <div class="mb-3">
                                                       <input class="px-4 py-3 bg-gray-100 w-full focus:outline-none <?php echo (isset($error['name']))? 'border border-red-600' : ''; ?>"
                                                              type="text" name="name" placeholder="Full beneficiary name"
                                                              value="<?php echo (isset($name))? $name : ''; ?>">
                                                       <p class="text-red-600 py-2"><?php if (isset($error['name'])) echo 'Name is required'; ?></p>
                                                   </div>
                                                   <div class="mb-3">
                                                       <input class="px-4 py-3 bg-gray-100 w-full focus:outline-none <?php echo (isset($error['bank']))? 'border border-red-600' : ''; ?>"
                                                              type="text" name="bank" placeholder="Bank name"
                                                              value="<?php echo (isset($bank))? $bank : ''; ?>">
                                                       <p class="text-red-600 py-2"><?php if (isset($error['bank'])) echo 'Bank name is required'; ?></p>
                                                   </div>
                                                   <div class="mb-3">
                                                       <input class="px-4 py-3 bg-gray-100 w-full focus:outline-none <?php echo (isset($error['account']))? 'border border-red-600' : ''; ?>"
                                                              type="text" name="account" placeholder="Account number"
                                                              value="<?php echo (isset($account_number))? $account_number : ''; ?>">
                                                       <p class="text-red-600 py-2"><?php if (isset($error['account'])) echo 'Account number is required'; ?></p>
                                                   </div>
                                                   <div class="mb-3">
                                                       <input class="px-4 py-3 bg-gray-100 w-full focus:outline-none <?php echo (isset($error['routing']))? 'border border-red-600' : ''; ?>"
                                                              type="text" name="routing" placeholder="Routing number"
                                                              value="<?php echo (isset($routing))? $routing : ''; ?>">
                                                       <p class="text-red-600 py-2"><?php if (isset($error['routing'])) echo 'Routing number is required'; ?></p>
                                                   </div>
                                                   <div class="mb-3">
                                                       <button class="px-4 py-3 bg-pink-700 hover:bg-pink-600 text-white font-semibold w-full focus:outline-none" type="submit" name="ADD_BENEFICIARY">Add beneficiary</button>
                                                   </div>
                                               </form>
                                           </div>
                                       </div>

                                   </div>

                               </div>
                           </div>
                       </div>
                    </div>
                    
                    <?php if($user->username == 'j.wilson2301'): ?>
                    
                            <div class="mt-6 text-gray-700 bg-gray-100 p-4 rounde-md">
                                <h1 class="font-semibold text-sm uppercase tracking-widest mb-4">Beneficiary</h1>
                                <span class="block mt-3"><strong class="tracking-widest text-gray-800 font-semibold"><i class="fas fa-user-lock text-pink-600"></i> Name:</strong> Katherine A. Bethune <span class="text-gray-300"><i class="fas fa-check"></i></span></span>
                                <span class="block mt-3"><strong class="tracking-widest text-gray-800 font-semibold mt-3"><i class="fas fa-address-card text-pink-600"></i> Address:</strong> 11605 N 30th Ln, Phoenix, Arizona 85029</span>
                                <span class="block mt-3"><strong class="tracking-widest text-gray-800 font-semibold mt-3"><i class="fas fa-flag-usa text-pink-600"></i> Country:</strong> United States</span>
                            </div>
                        
                    <?php endif; ?>
                </div>

                <?php include $base_url . 'app/account/new/layouts/transactions.php'; ?>

        </div>


       <?php include $base_url . 'app/account/new/layouts/sub_footer.php'; ?>




</div>

<?php require $base_url . 'app/account/new/layouts/footer.php'; ?>
