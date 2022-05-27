<?php

require '../../../config/app.php';

// User
if  (isset($_SESSION['USER'])) {
    $user = R::load('users', $_SESSION['USER']);

    $adminProfile = R::load('admins', DEFAULT_ADMIN_ID);

    if (isset($_GET['account'])) {
        //  if($_GET['account'] == 4) {
        //     // $_SESSION['ERROR'] = 'You can\'t send money from an undue Investment Account';
        //     header('location:  ./account.php?account=4&fail=1');
        // }
        $account = R::load('accounts', $_GET['account']);

        // Transactions related to account
        $transactions = R::findAll('transactions', 'account_id = ?', [$account->id]);
    }

    // Accounts related to user
    $accounts = R::findAll('accounts', 'user_id = ?', [$user->id]);

    // beneficiary
    $beneficiaries = R::findAll('beneficiaries', 'user_id = ?', [$user->id]);

}   else {
    // Redirect user to login
    header('location: layout.php');
}


$errors = [];

if  (isset($_POST['CONTINUE'])) {

    $user_id = htmlspecialchars(strip_tags(trim($_POST['user_id'])));
    $account_id = htmlspecialchars(strip_tags(trim($_POST['account_id'])));
    $amount = htmlspecialchars(strip_tags(trim($_POST['amount'])));
    $transfer_date = htmlspecialchars(strip_tags(trim($_POST['transfer_date'])));
    $memo = htmlspecialchars(strip_tags(trim($_POST['memo'])));
    $selected_form = htmlspecialchars(strip_tags(trim($_POST['selected_form'])));
    $another_bank_transfer = htmlspecialchars(strip_tags(trim($_POST['another_bank'])));

//    $transfer_to_account_id = htmlspecialchars(strip_tags(trim($_POST['transfer_to_account_id'])));
//    $account_number = htmlspecialchars(strip_tags(trim($_POST['account_number'])));

    if  ($selected_form == 'self') {
        $transfer_to_account_id = htmlspecialchars(strip_tags(trim($_POST['transfer_to_account_id'])));
    }

    if  ($selected_form == 'same') {
        $transfer_to_account_id = htmlspecialchars(strip_tags(trim($_POST['account_number'])));
    }

    if  ($selected_form == 'another') {
        $transfer_to_account_id = htmlspecialchars(strip_tags(trim($_POST['beneficiary'])));
    }

    if  (empty($amount) && !is_float($amount)) {
        $amount_error = 'Invalid transfer amount entered';
//        $self_transfer_passed = false;
    }

    // Todo: Set to optional later
    if  (empty($transfer_date)) {
        $transfer_date_error = 'Transfer Date is required';
    }

    if  (strtotime($transfer_date) < strtotime(date('F d Y', time()))) {
        $past_date_error = 'You entered a past date';
    }

    if  (empty($selected_form)) {
        $selected_form_error = 'Select transfer type';
    }

    // -- //

    if  ($selected_form == 'self' || $selected_form == 'same') {

        if  (!isset($selected_form_error) && !isset($amount_error) && !isset($transfer_date_error)  && !isset( $past_date_error)) {

            $accountfrom = R::load('accounts', $account_id);

            if  ($amount > $accountfrom->balance) {
                $transferring_to_same_account_error = "Insufficient funds in your account";

            }   elseif ($account_id === $transfer_to_account_id) {
                $transferring_to_same_account_error = "You can't send money to yourself";

            }   else {
                $_SESSION['TRANSFER_COMPLETED'] = false;
                header('location: transfer.php?transfer=confirm&' . 'user_id=' . $user_id . '&account_id=' . $account_id . '&from=' . $account_id . '&to=' . $transfer_to_account_id . '&amount=' . $amount . '&transfer_date=' . $transfer_date . '&memo=' . $memo);
                exit();

            }

        }

    }

    if  ($selected_form == 'another') {
        if  (!isset($selected_form_error) && !isset($amount_error) && !isset($transfer_date_error)  && !isset( $past_date_error)) {

            $accountfrom = R::load('accounts', $account_id);

            if  ($amount > $accountfrom->balance) {
                $transferring_to_same_account_error = "Insufficient funds in your account";

            }   else {
                $_SESSION['TRANSFER_COMPLETED'] = false;
                header('location: transfer.php?transfer=confirm&transfer_type=' . $another_bank_transfer . '&user_id=' . $user_id . '&account_id=' . $account_id . '&from=' . $account_id . '&to=' . $transfer_to_account_id . '&amount=' . $amount . '&transfer_date=' . $transfer_date . '&memo=' . $memo);
                exit();

            }

        }
    }



}

if  (isset($_POST['SUBMIT_TRANSFER'])) {

    $transfer = R::dispense('transactions');
    $transfer->user_id = $_POST['user_id'];
    $transfer->account_id = $_POST['account_id'];
    $transfer->amount = $_POST['amount'];
    $transfer->from = $_POST['from'];
    $transfer->completed = 0;
    $transfer->number = substr($user_id . md5(time()), 0, 12);
    $transfer->credit = 0;
    $transfer->debit = 1;

    if (isset($_POST['to'])) $transfer->to = $_POST['to'];
    if (isset($_POST['account_number'])) $transfer->to = $_POST['account_number'];
    if (isset($_POST['receiver'])) $transfer->beneficiary = $_POST['receiver'];

    $transfer->transfer_date = $_POST['transfer_date'];
    $transfer->memo = $_POST['memo'];

    // Update accounts
    if (isset($_POST['to'])) $accountto = R::load('accounts', $_POST['to']);

    if  (isset($_POST['to'])) {

        $accountfrom = R::load('accounts', $_POST['from']);
        $accountfrom->balance = $accountfrom->balance - $_POST['amount'];
        $accountfrom->pending_balance = $accountfrom->pending_balance + $_POST['amount'];
        R::store($accountfrom);

//        $accountto->balance = $accountto->balance + $_POST['amount'];
//        R::store($accountto);
    }

    if  (isset($_POST['account_number'])) {

        $external_account = R::findOne('accounts', 'number = ?', [$_POST['account_number']]);

        if(!$external_account) {
            $_SESSION['TRANSFER_COMPLETED'] = true;
            $_SESSION['ERROR'] = "The account number you are sending to doesn't exist";
            header('location: summary.php');
            exit();
        }

        $accountfrom = R::load('accounts', $_POST['from']);
        $accountfrom->balance = $accountfrom->balance - $_POST['amount'];
        $accountfrom->pending_balance = $accountfrom->pending_balance + $_POST['amount'];
        R::store($accountfrom);

//        $external_account->balance = $external_account->balance + $_POST['amount'];
//        R::store($external_account);

    }

    if (isset($_POST['receiver'])) {
        $accountfrom = R::load('accounts', $_POST['from']);
        $accountfrom->balance = $accountfrom->balance - $_POST['amount'];
        $accountfrom->pending_balance = $accountfrom->pending_balance + $_POST['amount'];
        R::store($accountfrom);
    }

    if(R::store($transfer)) {

        if (isset($_POST['to'])) {
            $sent_to = strtoupper($user->name) . ' (...' . substr($accountto->number, -5, -1) . ') account.';

        } elseif(isset($_POST['receiver'])) {
            $getBeneficiary = R::load('beneficiaries', $_POST['receiver']);
            $sent_to = $getBeneficiary->name . ' (' . $getBeneficiary->bank . ')';

        }  else {
            $sent_to = ' ' . $_POST['account_number'];
        }

        $_SESSION['CREATED'] = "You've scheduled a transfer amount of " . interpret_currency($adminProfile->currency) . number_format($_POST['amount'], 2) . ' to ' . $sent_to;
        $_SESSION['TRANSFER_COMPLETED'] = true;
        header('location: summary.php');
        exit();
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

            <div class="bg-white w-full mt-8">
                <div class="flex flex-row justify-between">
                    <div class="flex flex-col top-heading">
                        <span class=" text-4xl text-gray-700">
                            <span>
                                Transfer Money
                            </span>
                        </span>

                    </div>
                    <div class="flex flex-row">
                        <?php  include $base_url . 'app/account/new/layouts/top_left_nav.php'; ?>
                    </div>
                </div>
            </div>

            <?php include $base_url . 'app/account/new/layouts/alerts.php' ?>

            <div class="mt-3 md:flex md:flex-col" style="max-width: 900px">

                <?php if(isset($_GET['transfer']) && $_GET['transfer'] == 'confirm'): ?>

                    <?php $accountfrom = R::load('accounts', $_GET['from']); ?>
                    <?php $accountto = R::load('accounts', $_GET['to']); ?>

                    <p class="text-2xl mb-10"> Please confirm details and submit transfer</p>

                    <div  class="flex flex-row justify-between">
                        <div>
                            Tranfer from <br>
                            <span class="font-semibold">
                                <?php echo strtoupper($user->name) . ' (...' . substr($accountfrom->number, -5, -1) . ') ' . interpret_currency($adminProfile->currency) . number_format($accountfrom->balance, 2); ?>
                            </span>
                        </div>
                        <div>
                            <p>
                                Transfer to <br>
                                <span class="font-semibold">
                                    <?php
                                        if (isset($_GET['transfer_type']) && $_GET['transfer_type'] == 'another_bank') {
                                            $getBeneficiary = R::load('beneficiaries', $_GET['to']);
                                            echo 'You are transferring ' . interpret_currency($adminProfile->currency) . number_format($accountto->balance, 2) . ' to ' . $getBeneficiary->name;

                                        }   elseif  (strlen($_GET['to']) < 3) {
                                            echo strtoupper($user->name) . ' (...' . substr($accountto->number, -5, -1) . ') ' . interpret_currency($adminProfile->currency) . number_format($accountto->balance, 2);

                                        }   else {
                                            echo 'Acct:  ' . $_GET['to'];
                                        }

                                    ?>
                                </span>
                                <br><br>
                                Transfer date <br> <span class="font-semibold"><?php echo $_GET['transfer_date']; ?></span>
                            </p>
                        </div>
                        <div>
                            <p>
                                Amount  <br> <span class="font-semibold"><?php echo interpret_currency($adminProfile->currency) . $_GET['amount']; ?></span> <br><br>
                                Memo <br> <span class="font-semibold"><?php echo (isset($_GET['memo'])) ? $_GET['memo']: ''; ?></span>
                            </p>
                        </div>

                    </div>

                    <div class="mt-10" style="text-align: right">
                        <form action="" method="post">
                            <input type="hidden" name="account_id" value="<?php echo $_GET['account_id']; ?>">
                            <input type="hidden" name="user_id" value="<?php echo $_GET['user_id']; ?>">
                            <input type="hidden" name="from" value="<?php echo $_GET['from']; ?>">

                            <?php if(isset($_GET['transfer_type']) && $_GET['transfer_type'] == 'another_bank'): ?>
                                <input type="hidden" name="receiver" value="<?php echo $_GET['to']; ?>">
                            <?php elseif(strlen($_GET['to']) < 3): ?>
                                <input type="hidden" name="to" value="<?php echo $_GET['to']; ?>">
                            <?php else: ?>
                                <input type="hidden" name="account_number" value="<?php echo $_GET['to']; ?>">
                            <?php endif; ?>

                            <input type="hidden" name="amount" value="<?php echo $_GET['amount']; ?>">
                            <input type="hidden" name="transfer_date" value="<?php echo $_GET['transfer_date']; ?>">
                            <input type="hidden" name="memo" value="<?php echo $_GET['memo']; ?>">

                            <a href="javascript:void(0)" id="cancelTransfer" class="px-3 py-2 bg-gray-200 rounded-sm mr-3 font-semibold focus:outline-none">Cancel</a>
                            <input type="submit" name="SUBMIT_TRANSFER" class="px-3 py-2 bg-blue-600 rounded-sm text-white font-semibold focus:outline-none"
                                   value="Transfer money" <?php echo (isset($_SESSION['TRANSFER_COMPLETED']) && !$_SESSION['TRANSFER_COMPLETED']) ? : 'disabled'; ?>>
                        </form>
                    </div>

                    <div class="mt-20">
                        <p class="mb-5">
                            Our business day cutoff time for online transfers between <?php echo ucfirst($adminProfile->website_name); ?> deposit accounts is 11 PM ET. A business day is a non-holiday weekday. The transfer date above reflects the day your transfer will occur.
                        </p>
                        <p>
                            Are you transferring money to avoid a current overdraft? Your transfer may appear in your available balance immediately, but it won't be used to pay any transactions posted before the transfer date. You still may be charged overdraft fees.
                            The terms of the Transfers Agreement: opens dialog apply to these transactions.
                        </p>
                    </div>

                <?php else: ?>

                    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">

                        <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">

                        <div class="mb-3">

                            <?php if(!isset($account)): ?>

                                <p>Transfer from</p>

                                <select class="px-4 py-3 bg-gray-100 w-full focus:outline-none" name="account_id" id="account">
                                    <!--                                <option value="default" selected>SELECT ACCOUNT</option>-->
                                    <?php foreach($accounts as $account): ?>
                                        <?php //if($account->id  != 4): ?>
                                            
                                            <option value="<?php echo $account->id; ?>">
                                                <?php echo strtoupper($user->name) . ' (...' . substr($account->number, -5, -1) . ') ' . interpret_currency($adminProfile->currency) . ' ' . number_format($account->balance, 2); ?>
                                            </option>
                                            
                                            
                                        <?php //endif; ?>
                                    <?php endforeach; ?>
                                </select>

                            <?php else: ?>

                                <div class="px-4 py-3 bg-gray-100 w-full focus:outline-none text-gray-800">
                                    <?php echo strtoupper($user->name) . ' (...' . substr($account->number, -5, -1) . ') ' . interpret_currency($adminProfile->currency) . ' ' . number_format($account->balance, 2); ?>
                                    <input type="hidden" name="account_id" value="<?php echo $account->id; ?>">
                                </div>

                            <?php endif; ?>

                        </div>

                        <div class="my-10">
                            <div class="flex flex-row">
                                <a onclick="displayCategorizedForm(this)" class="my-3 mr-5 bg-gray-300 text-gray-800 hover:font-semibold px-5 py-3 rounded-sm shadow-sm w-56" href="javascript:void(0)" id="transfertToSelf" data-transfer-type="self">Transfer to Myself</a>
                                <a onclick="displayCategorizedForm(this)" class="my-3 mr-5 bg-gray-300 text-gray-800 hover:font-semibold px-5 py-3 rounded-sm shadow-sm w-56" href="javascript:void(0)" id="transfertToSameBank" data-transfer-type="same">Same Bank Transfer</a>
                                <a onclick="displayCategorizedForm(this)" class="my-3 mr-5 bg-gray-300 text-gray-800 hover:font-semibold px-5 py-3 rounded-sm shadow-sm w-56" href="javascript:void(0)" id="transfertToAnotherBank" data-transfer-type="another">Wire Transfer</a>
<!--                                <a onclick="displayCategorizedForm(this)" class="my-3 mr-5 bg-gray-300 text-gray-800 hover:font-semibold px-5 py-3 rounded-sm shadow-sm w-56" href="javascript:void(0)" id="internationTransfer" data-transfer-type="wire">International Transfer</a>-->
                            </div>

                            <p class="text-red-500 font-semibold"><?php echo(isset($selected_form_error)) ? $selected_form_error : ''; ?></p>
                            <p class="text-red-500 font-semibold"><?php echo(isset($transferring_to_same_account_error)) ? $transferring_to_same_account_error : ''; ?></p>
                            <p class="text-red-500 font-semibold"><?php echo(isset($past_date_error)) ? $past_date_error : ''; ?></p>

                        </div>


                        <script>

                            let transferTypes = ['self', 'same', 'another'];

                            function hideTransferForm(element) {
                                let getParentNode = element.parentNode;
                                getParentNode.className = 'hidden';
                            }

                            function displayCategorizedForm(transfer) {
                                let form = transfer.getAttribute('data-transfer-type');
                                // let continueBtn = document.getElementById('continueBtn');

                                // style selected form type
                                // Array.prototype.slice.call(transfer.attributes).forEach(function(item) {
                                //     if(item.name === 'id') {
                                //         document.getElementById(item.value).className += ' ' + 'border-b-8 border-blue-700'
                                //     }
                                //     document.getElementById(item.value).not(this).className += '';
                                // });



                                if (transferTypeExists(form, transferTypes)) {
                                    document.getElementById(form).className = 'block';
                                }

                                // if (form === 'another') {
                                //     continueBtn.setAttribute('disabled', 'disabled');
                                // } else {
                                //     continueBtn.removeAttribute('disabled');
                                // }

                                hideOtherVisibleForms(form, transferTypes);

                                document.getElementById('selectedForm').setAttribute('value', form);

                            }

                            function transferTypeExists(form, transferType) {

                                if (!transferType || (transferType.constructor !== Array && transferType.constructor !== Object)) {
                                    return false;
                                }

                                for (let i = 0; i < transferType.length; i++) {
                                    if (transferType[i] === form) {
                                        return true
                                    }
                                }

                                return form in transferType;
                            }

                            function hideOtherVisibleForms (currentForm, transferTypes) {
                                let newTransferTypes = transferTypes.filter(transferType => transferType !== currentForm)

                                // style selected form type
                                // alert(transferTypes)

                                for (let i = 0; i < newTransferTypes.length; i++) {
                                    document.getElementById(newTransferTypes[i]).className = 'hidden'
                                }
                            }

                        </script>

                        <input id="selectedForm" type="hidden" name="selected_form" >

                        <div class="mb-5">

                            <div id="self" class="hidden">
                                <div class="mb-3">
                                    <p>Transfer to</p>
                                    <select class="px-4 py-3 bg-gray-100 w-full focus:outline-none" name="transfer_to_account_id" id="transfer_to_account_id">
                                        <!--                                    <option value="default" selected>SELECT ACCOUNT</option>-->
                                        <?php foreach($accounts as $account): ?>
                                            <option value="<?php echo $account->id; ?>">
                                                <?php echo strtoupper($user->name) . ' (...' . substr($account->number, -5, -1) . ') ' . interpret_currency($adminProfile->currency) . ' ' . number_format($account->balance, 2); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <a class="text-gray-400 hover:text-red-500 text-2xl p-3 rounded-full" href="javascript:void" onclick="hideTransferForm(this)"><i class="fas fa-times-circle"></i></a>
                            </div>

                            <div id="same" class="hidden">

                                <div class="mb-5">
                                    <label for="">Beneficiary Account Number</label>
                                    <input class="px-4 py-3 bg-gray-100 w-full focus:outline-none text-gray-800" type="text" name="account_number" id="account_number" placeholder="Account number">
                                    <p class="text-red-500 font-semibold"><?php echo(isset($account_number_error)) ? $account_number_error: ''; ?></p>
                                </div>

                                <a class="text-gray-400 hover:text-red-500 text-2xl p-3 rounded-full" href="javascript:void" onclick="hideTransferForm(this)"><i class="fas fa-times-circle"></i></a>
                            </div>

                            <div id="another" class="hidden">
                                <input type="hidden" name="another_bank" value="another_bank">
                                <label for="beneficiary">Select beneficiary <span class="font-semibold text-blue-600">Wire transfer</span></label>

                                <p class="text-sm my-3 text-gray-700">
                                    You must first add a beneficiary before you can make a wire transfer
                                    <a class="text-blue-600 font-semibold" href="summary.php">add beneficiary</a>
                                </p>

                                <select class="px-4 py-3 bg-gray-100 w-full focus:outline-none" name="beneficiary" id="beneficiary">
                                    <?php if(!empty($beneficiaries)): ?>
                                        <?php foreach ($beneficiaries as $beneficiary): ?>
                                            <option value="<?php echo $beneficiary->id; ?>"><?php echo $beneficiary->name . ' (' . substr($beneficiary->account, -5, -1) . ') ' . $beneficiary->bank; ?></option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <?php $disabled = 'disabled'; ?>
                                        <option value="add_beneficiary">Add beneficiary</option>
                                    <?php endif; ?>
                                </select>

                                <a class="text-gray-400 hover:text-red-500 text-2xl p-3 rounded-full" href="javascript:void" onclick="hideTransferForm(this)"><i class="fas fa-times-circle"></i></a>
                            </div>


                        </div>

                        <div class="mb-5">
                            <label for="">Amount</label>
                            <input class="px-4 py-3 bg-gray-100 w-full focus:outline-none text-gray-800" type="text" name="amount" id="amount" placeholder="<?php echo interpret_currency($adminProfile->currency); ?> Amount">
                            <p class="text-red-500 font-semibold"><?php echo(isset($amount_error)) ? $amount_error: ''; ?></p>
                        </div>

                        <div class="mb-5">
                            <label for="">Transfer date</label>
                            <input class="px-4 py-3 bg-gray-100 w-full focus:outline-none text-gray-800" type="datetime-local" name="transfer_date" id="transfer_date" placeholder="Transfer Date">
                            <p class="text-red-500 font-semibold"><?php echo(isset($transfer_date_error)) ? $transfer_date_error : ''; ?></p>
                        </div>

                        <div class="mb-5">
                            <label for="">Memo (optional)</label>
                            <input class="px-4 py-3 bg-gray-100 w-full focus:outline-none text-gray-800" type="text" name="memo" id="memo" placeholder="Memo">
                        </div>

                        <div class="my-5">
                            <button
                                    id="continueBtn"
                                    class="px-4 py-3 bg-blue-700 hover:bg-blue-600 text-white font-semibold w-full focus:outline-none"
                                    type="submit"
                                    name="CONTINUE">
                                Continue Transfer
                            </button>
                        </div>
                    </form>
                <?php endif; ?>

            </div>

                <?php include $base_url . 'app/account/new/layouts/sub_footer.php'; ?>

            </div>

            <?php require $base_url . 'app/account/new/layouts/footer.php'; ?>
