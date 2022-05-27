<?php

require '../../../config/app.php';

// User
if(isset($_SESSION['USER'])) {
    $user = R::load('users', $_SESSION['USER']);

    $adminProfile = R::load('admins', DEFAULT_ADMIN_ID);

    // Accounts related to user
    $tickets = R::findAll('messages', 'user_id = ? ORDER BY created_at DESC', [$user->id]);

    // Transactions related to user
//    $transactions = R::findAll('transactions', 'user_id = ?', [$user->id]);

    // Beneficiaries
//    $beneficiaries = R::findAll('beneficiaries', 'user_id = ? limit 4', [$user->id]);

    $ticket_number = 'SUPQRY20' . date('Y') . mt_rand(1, 1000);

} else {
    // Redirect user to login
    header('location: layout.php');
}

$error = [];

if(isset($_POST['SEND_MESSAGE'])) {

    $ticket_number = htmlspecialchars(strip_tags(trim($_POST['ticket_number'])));
    $enquiry = htmlspecialchars(strip_tags(trim($_POST['enquiry'])));
    $send_to_account_manager = htmlspecialchars(strip_tags(trim($_POST['send_to_account_manager'])));
    $user_id = htmlspecialchars(strip_tags(trim($_POST['user_id'])));

    if (!$send_to_account_manager) {
        $enquiry_type = htmlspecialchars(strip_tags(trim($_POST['enquiry_type'])));
    }

    if (empty($enquiry)) {
        $error['enquiry'] = true;
    }

    if (!sizeof($error) > 0) {

        $ticket = R::dispense( 'messages' );
        $ticket->ticket_number = $ticket_number;
        $ticket->enquiry = $enquiry;

        if (!$send_to_account_manager) {
            $ticket->enquiry_type = $enquiry_type;
            $ticket->for_account_manager = 1;
        } else {
            $ticket->for_support = 1;
        }

        $ticket->send_to_account_manager = $send_to_account_manager;
        $ticket->user_id = $user_id;
        $ticket->created_at = new DateTime('now');

        if (R::store($ticket)) {
            if($send_to_account_manager) {
                $_SESSION['CREATED'] = 'Message successfully sent to your account manager';
            } else {
                $_SESSION['CREATED'] = 'Ticket successfully added';
            }
            header('location: messages.php');
            exit();
        }
    }
}

?>

<?php require $base_url . 'app/account/new/layouts/head.php'; ?>

<!-- component -->
<div class="w-full flex flex-row flex-wrap" xmlns="http://www.w3.org/1999/html">


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
                                Support Tickets
                            </span>
                        </span>
                        <span class="text-gray-500">All messages for <span class="text-gray-700"><?php echo $user->name; ?></span></span>
                    </div>
                    <div class="flex flex-row">
                        <?php  include $base_url . 'app/account/new/layouts/top_left_nav.php'; ?>
                    </div>
                </div>
            </div>

            <?php include $base_url . 'app/account/new/layouts/alerts.php' ?>

            <div class="mt-3 md:flex md:flex-col">


                <div class="bg-white mt-3">
                    <div class="md:flex md:flex-row justify-between">

                        <div class="md:w-1/2">
                            <h3 class="mb-3 sub-heading">Create a support ticket</h3>
                            <small class="mb-5">This ticket would be logged to our customer service department. <br>
                                I prefer to send a message to my account manager
                                <a
                                    href="messages.php?account_manager=<?php echo strtoupper('989000292__' . str_replace(' ', '_', $user->name)); ?>"
                                    class="ml-3 text-purple-600">
                                    <i class="fas fa-mail-bulk"></i> <small>Send message</small>
                                </a>
                            </small>
                            <div class="mr-10 mt-10">

                                <p class="mb-10">Ticket number: <span class="text-purple-600 font-semibold"><?php echo (isset($_GET['account_manager']))? $_GET['account_manager'] : $ticket_number; ?></span></p>

                                <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                                    <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
                                    <input type="hidden" name="ticket_number" value="<?php echo (isset($_GET['account_manager']))? $_GET['account_manager']: $ticket_number; ?>"">
                                    <input type="hidden" name="send_to_account_manager" value="<?php echo (isset($_GET['account_manager']))? 1: 0; ?>">

                                    <div class="mb-3">
                                        <?php if(!isset($_GET['account_manager'])): ?>
                                            <small>What type of enquiry are you making?</small>
                                            <select class="px-4 py-3 bg-gray-100 w-full focus:outline-none" name="enquiry_type" id="enquiry_type">
                                                <option value="loans">Loans</option>
                                                <option value="general">General Complaints</option>
                                                <option value="account">Account Services</option>
                                                <option value="transfer">Funds Transfer</option>
                                                <option value="new_account">New Account Opening Requirement</option>
                                                <option value="investment">Investment</option>
                                                <option value="product">Product Enquiry</option>
                                                <option value="authenticity">Provide Authenticity of Email</option>
                                            </select>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <small>Your enquiry</small>
                                        <textarea class="px-4 py-3 bg-gray-100 w-full focus:outline-none <?php echo (isset($error['enquiry']))? 'border border-red-600' : ''; ?>" name="enquiry" id=enquiry" cols="30" rows="10"></textarea>
                                        <p class="text-red-600 font-semibold mb-5"><?php echo (isset($error['enquiry']))? 'Ticket message is required' : ''; ?></p>
                                    </div>
                                    <div class="mb-3">
                                        <button
                                            class="px-4 py-3 bg-purple-700 hover:bg-purple-600 text-white font-semibold w-full focus:outline-none"
                                            type="submit"
                                            name="SEND_MESSAGE">
                                            <?php echo (!isset($_GET['account_manager']))? 'Create Ticket' : 'Send Message'; ?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="md:w-full">
                            <div class="mb-5">
                                <h3 class="mb-3 sub-heading inline mr-5">Tickets <span class="text-gray-900"><?php echo count($tickets); ?></span></h3>
                                <h3 class="mb-3 sub-heading text-gray-500 inline">Notifications <span class="text-gray-500">0</span></h3>
                            </div>

                            <div class="flex flex-col p-5 rounded-md mr-5" style="background-color: #F9F9F9">

                                <?php if (!empty($tickets)): ?>
                                    <?php foreach ($tickets as $ticket): ?>
                                        <div class="flex flex-row justify-between mb-5">
                                            <div class="flex flex-col">
                                                <span class="sub-heading">
                                                    <a href="" class="text-purple-600"><?php echo $ticket->ticket_number; ?></a>
                                                </span>
                                                <small class="text-gray-600"><?php echo $ticket->created_at; ?></small>
                                            </div>
                                            <div class="flex flex-col text-right text-gray-600">
                                                <span>
                                                    <a onclick="return confirm('Do you want to delete ticket?')" href="delete.php?page=ticket&ticket=<?php echo $ticket->id; ?>"><i class="fas fa-trash text-gray-500 hover:text-red-600 font-semibold"></i></a>
                                                </span>
                                            </div>
                                        </div>

                                    <?php endforeach; ?>
                                <?php else: ?>
                                    You have no support tickets yet.
                                <?php endif; ?>

                            </div>
                        </div>

                    </div>
                </div>


            </div>


            <?php include $base_url . 'app/account/new/layouts/sub_footer.php'; ?>




        </div>

        <?php require $base_url . 'app/account/new/layouts/footer.php'; ?>
