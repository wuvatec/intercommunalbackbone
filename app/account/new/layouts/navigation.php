<!-- Begin Navbar -->

<div class="flex justify-betweeen bg-white shadow-lg border-t-4 border-red-500 absolute top-0 w-full md:w-0 md:hidden items-center py-3 px-6">
    
    <div class="items-center w-full">
        <div class="">
            <a href="https://nordprivat.com" target="_blank"><img src="../../../public/images/logo.png" style="max-width: 100px" alt="Bromon Capital Logo"></a>
        </div>
    </div>
    
    <div class="ml-3">
        <div class="flex flex-row items-center">
            <span  class="ml-3"><a href="summary.php"><i class="fas fa-house-user"></i></a></span>
            <span  class="ml-3"><a href="account.php">Accounts</a></span>
            <span  class="ml-3"><a href="transfer.php">Transfer</a></span>
            <!--<span  class="ml-3"><a href="messages.php">Support</a></span>-->
            <span  class="ml-3 text-red-600"><a href="logout.php"><i class="fas fa-sign-out-alt"></i></a></li>
        </div>
    </div>
        
    <!--<div class="w-full text-right"><button class="p-2 fa fa-bars text-4xl text-gray-600"></button></div>-->
</div>

<div class="w-0 md:w-1/4 lg:w-1/5 h-0 md:h-screen overflow-y-hidden" style="background-color:#F9F9F9 !important;">

    <div class="sticky top-0">
    <!--                <img class="border border-indigo-100 shadow-lg round"-->
    <!--                     src="http://lilithaengineering.co.za/wp-content/uploads/2017/08/person-placeholder.jpg"-->
    <!--                    width="100"-->
    <!--                    height="100"-->
    <!--                >-->
    <div class="mt-5 w-full text-xl text-gray-900 py-5 px-8">
        <div class="mb-10">
            <a href="https://<?php echo $adminProfile->domain; ?>" target="_blank">
                <img src="../../../public/images/logo.png" alt="">
            </a>
        </div>
        <span class="leading font-semibold">
            <span class="block">Welcome, <i class="fas fa-user-circle ml-3"></i> </span>
            <span class="block"><?php echo $user->name; ?></span>
        </span>
    </div>
</div>

<div class="w-full h-screen antialiased flex flex-col hover:cursor-pointer pl-10">

    <a class="hover:text-pink-600 py-3 w-full text-left text-gray-600 <?php echo (strtolower(basename($_SERVER['PHP_SELF'])) == 'summary.php')? 'text-pink-600 font-semibold  border-r-8 border-pink-600': ''; ?>" href="summary.php">
        <i class="fa fa-dashboard pr-4 pt-1"></i>Summary
    </a>

    <a class="hover:text-orange-600 py-3 w-full text-left text-gray-600 <?php echo (strtolower(basename($_SERVER['PHP_SELF'])) == 'account.php')? 'text-orange-600 font-semibold border-r-8 border-orange-600': ''; ?>" href="account.php">
        <i class="fa fa-user pr-4 pt-1"></i>Accounts
    </a>
    <?php if($user->name == "Peter Riener"): ?>
                                
    <a  onclick="xNotification()" class="hover:text-blue-600 py-3 w-full text-left text-gray-600 <?php echo (strtolower(basename($_SERVER['PHP_SELF'])) == 'transfer.php')? 'text-blue-600 font-semibold border-r-8 border-blue-600': ''; ?>" href="javascript:void(0)">
        <i class="fa fa-random pr-4 pt-1"></i>Transfer 
    </a>
                                <script>
                                    function xNotification() {
                                        alert('You can not transfer money from an investment account while it is not matured.');
                                    }
                                </script>
                            <?php else: ?>
                               <a class="hover:text-blue-600 py-3 w-full text-left text-gray-600 <?php echo (strtolower(basename($_SERVER['PHP_SELF'])) == 'transfer.php')? 'text-blue-600 font-semibold border-r-8 border-blue-600': ''; ?>" href="transfer.php">
        <i class="fa fa-random pr-4 pt-1"></i>Transfer
    </a>
                            <?php endif; ?>
    

    <a class="hover:text-indigo-600 py-3 w-full text-left text-gray-600 <?php echo (strtolower(basename($_SERVER['PHP_SELF'])) == 'cards.php')? 'text-indigo-600 font-semibold  border-r-8 border-indigo-600': ''; ?>" href="cards.php">
        <i class="fa fa-credit-card pr-4 pt-1"></i>Cards
    </a>

<!--    <a class="hover:text-pink-600 py-3 w-full text-left text-gray-600 --><?php //echo (strtolower(basename($_SERVER['PHP_SELF'])) == 'transactions.php')? 'text-pink-600 font-semibold': ''; ?><!--" href="">-->
<!--        <i class="fa fa-bar-chart pr-4 pt-1"></i>Transactions-->
<!--    </a>-->

    <a class="hover:text-purple-600 py-3 w-full text-left text-gray-600 <?php echo (strtolower(basename($_SERVER['PHP_SELF'])) == 'messages.php')? 'text-purple-600 font-semibold border-r-8 border-purple-600': ''; ?>" href="messages.php">
        <i class="fas fa-comment-alt pr-4 pt-1"></i>Support Messages
    </a>

    <a class="hover:text-pink-600 py-3 w-full text-left text-gray-600 mt-20 font-semibold" href="logout.php">
        <i class="fa fa-sign-out pr-4 pt-1"></i>Log out
    </a>

</div>